<?php

namespace Modules\Doctors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Modules\User\Entities\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query();

        // البحث حسب التخصص
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // البحث حسب سنوات الخبرة
        if ($request->filled('experience')) {
            [$min, $max] = explode('-', $request->experience);
            if ($max === '+') {
                $query->where('experience_years', '>=', $min);
            } else {
                $query->whereBetween('experience_years', [$min, $max]);
            }
        }

        // البحث حسب سعر الكشف
        if ($request->filled('fee_min')) {
            $query->where('price', '>=', $request->fee_min);
        }
        if ($request->filled('fee_max')) {
            $query->where('price', '<=', $request->fee_max);
        }

        $doctors = $query->with(['categories', 'user'])
                        ->latest()
                        ->paginate(10)
                        ->withQueryString();

        $categories = Category::all();

        return view('admin::doctors.index', compact('doctors', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin::doctors.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'gender' => 'required|in:ذكر,انثي',
            'experience_years' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address1' => 'nullable|string',
            'city' => 'nullable|string'
        ], [
            'categories.required' => 'يجب اختيار تخصص واحد على الأقل',
            'categories.min' => 'يجب اختيار تخصص واحد على الأقل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'price.required' => 'سعر الكشف مطلوب',
            'price.numeric' => 'سعر الكشف يجب أن يكون رقماً',
            'price.min' => 'سعر الكشف يجب أن يكون أكبر من صفر'
        ]);

        // Create user record first
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone'],
            'type' => 'doctor'
        ]);

        // Assign doctor role with web guard
        $doctorRole = Role::findByName('Doctor', 'web');
        $user->assignRole($doctorRole);

        // Create doctor record with user_id and name
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'name' => $validated['name'],  // Adding name field
            'email' => $validated['email'], // Adding email field
            'phone' => $validated['phone'], // Adding phone field
            'bio' => $validated['bio'] ?? null,
            'price' => $validated['price'],
            'experience_years' => $validated['experience_years'] ?? null,
            'gender' => $validated['gender'],
            'address' => $validated['address1'] ?? null,
            'city' => $validated['city'] ?? null,
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('doctors', 'public');
            $doctor->image = $imagePath;
            $doctor->save();
        }

        // Sync categories
        $doctor->categories()->sync($request->categories);

        return redirect()->route('doctors.index')
            ->with('success', 'تم إضافة الطبيب بنجاح');
    }

    public function edit(Doctor $doctor)
    {
        $categories = Category::all();
        return view('admin::doctors.edit', compact('doctor', 'categories'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($doctor->user_id ?? 0),
            'phone' => 'required|string|max:20',
            'categories' => 'required|array|min:1',
            'categories.*' => 'required|exists:categories,id',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gender' => 'required|in:ذكر,انثي',
            'status' => 'nullable|boolean',
            'address1' => 'nullable|string',
            'address2' => 'nullable|string',
            'city' => 'nullable|string',
            'district' => 'nullable|string',
            'postal_code' => 'nullable|string'
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = false;
        }
        // Update user record if it exists
        if ($doctor->user_id) {
            $user = User::find($doctor->user_id);
            if ($user) {
                $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone_number' => $validated['phone']
                ]);
            }
        } else {
            // If no user exists, create one
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('password'), // Default password
                'phone_number' => $validated['phone'],
                'type' => 'doctor'
            ]);

            // Assign doctor role with web guard
            $doctorRole = Role::findByName('Doctor', 'web');
            $user->assignRole($doctorRole);

            // Associate user with doctor
            $doctor->user_id = $user->id;
            $doctor->save();
        }

        // Update doctor record including duplicated fields
        $doctor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'] ?? null,
            'gender' => $validated['gender'],
            'status' => $validated['status'] ?? false,
            'address' => $validated['address1'] ?? null,
            'city' => $validated['city'] ?? null,
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('doctors', 'public');
            $doctor->image = $imagePath;
            $doctor->save();
        }

        $doctor->categories()->sync($request->categories);

        return redirect()->route('doctors.index')
            ->with('success', 'تم تحديث بيانات الطبيب بنجاح');
    }


    public function destroy(Doctor $doctor)
    {
        $doctor->categories()->detach();

        // Get the user ID before deleting the doctor
        $userId = $doctor->user_id;

        $doctor->delete();

        // Delete the associated user if it exists
        if ($userId) {
            User::where('id', $userId)->delete();
        }

        return redirect()->route('doctors.index')
            ->with('success', 'تم حذف الطبيب بنجاح');
    }
}
