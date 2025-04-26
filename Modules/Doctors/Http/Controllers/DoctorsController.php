<?php

namespace Modules\Doctors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Category;
use App\Models\Governorate;
use App\Models\User;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Notifications\NewDoctorNotification;
use App\Notifications\DoctorUpdatedNotification;
use App\Notifications\DoctorDeletedNotification;

class DoctorsController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['categories', 'user']);

        // تطبيق فلتر البحث
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // تطبيق فلتر التخصص
        if ($request->filled('category_filter')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_filter);
            });
        }

        // تطبيق فلتر الحالة
        if ($request->filled('status_filter')) {
            $status = $request->status_filter === '1' ? true : false;
            $query->where('status', $status);
        }

        $doctors = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('doctors::index', [
            'doctors' => $doctors,
            'categories' => $categories,
            'title' => 'الأطباء'
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $governorates = Governorate::with('cities')->get();
        return view('doctors::create', compact('categories', 'governorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'consultation_fee' => 'required|numeric|min:0',
            'waiting_time' => 'nullable|integer|min:0',
            'categories' => 'required|exists:categories,id',
            'gender' => 'required|in:ذكر,انثي',
            'experience_years' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'address' => 'nullable|string',
            'governorate_id' => 'required|exists:governorates,id',
            'city_id' => 'required|exists:cities,id',
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
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'] ?? null,
            'description' => $validated['description'] ?? null,
            'consultation_fee' => $validated['consultation_fee'],
            'waiting_time' => $validated['waiting_time'],
            'experience_years' => $validated['experience_years'] ?? null,
            'gender' => $validated['gender'],
            'status' => true,
            'address' => $validated['address'] ?? null,
            'governorate_id' => $validated['governorate_id'],
            'city_id' => $validated['city_id'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $doctor->image = Doctor::uploadImage($request->file('image'));
            $doctor->save();
        }

        // Sync categories
        $doctor->categories()->sync($request->categories);

        // Send notification to admins
        User::role('Admin')->each(function($admin) use ($doctor) {
            $admin->notify(new NewDoctorNotification($doctor));
        });

        return redirect()->route('doctors.index')
            ->with('success', 'تم إضافة الطبيب بنجاح');
    }

    public function edit(Doctor $doctor)
    {
        $categories = Category::all();
        $governorates = Governorate::with('cities')->get();
        return view('doctors::edit', compact('doctor', 'categories', 'governorates'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($doctor->user_id ?? 0),
            'phone' => 'required|string|max:20',
            'categories' => 'required|exists:categories,id',
            'bio' => 'nullable|string',
            'description' => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'waiting_time' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gender' => 'required|in:ذكر,انثي',
            'address' => 'nullable|string',
            'governorate_id' => 'required|exists:governorates,id',
            'city_id' => 'required|exists:cities,id',
        ]);

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
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image first
            $doctor->deleteImage();
            // Upload and save new image
            $doctor->image = Doctor::uploadImage($request->file('image'));
            $doctor->save();
        }

        // Handle status field - convert checkbox value to boolean
        $status = $request->has('status');

        // Update doctor record
        $doctor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'] ?? null,
            'description' => $validated['description'] ?? null,
            'gender' => $validated['gender'],
            'status' => $status,
            'address' => $validated['address'] ?? null,
            'consultation_fee' => $validated['consultation_fee'],
            'waiting_time' => $validated['waiting_time'],
            'experience_years' => $validated['experience_years'] ?? null,
            'governorate_id' => $validated['governorate_id'],
            'city_id' => $validated['city_id']
        ]);

        // Sync categories
        $doctor->categories()->sync($request->categories);

        // Send notification to admins
        User::role('Admin')->each(function($admin) use ($doctor) {
            $admin->notify(new DoctorUpdatedNotification($doctor));
        });

        return redirect()->route('doctors.index')
            ->with('success', 'تم تحديث بيانات الطبيب بنجاح');
    }

    public function profiles(Request $request)
    {
        $query = Doctor::query();

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by experience
        if ($request->filled('experience')) {
            [$min, $max] = explode('-', $request->experience);
            if ($max === '+') {
                $query->where('experience_years', '>=', $min);
            } else {
                $query->whereBetween('experience_years', [$min, $max]);
            }
        }

        // Filter by governorate
        if ($request->filled('governorate')) {
            $query->where('governorate_id', $request->governorate);
        }

        // Filter by consultation fee range
        if ($request->filled('fee_range')) {
            [$min, $max] = explode('-', $request->fee_range);
            if ($max === '+') {
                $query->where('consultation_fee', '>=', $min);
            } else {
                $query->whereBetween('consultation_fee', [$min, $max]);
            }
        }

        $doctors = $query->with(['categories', 'user', 'governorate', 'city'])
            ->where('status', true)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();
        $governorates = Governorate::all();

        return view('doctors::profiles', compact('doctors', 'categories', 'governorates'));
    }

    public function destroy(Doctor $doctor)
    {
        $doctorName = $doctor->name;
        $doctor->categories()->detach();

        // Get the user ID before deleting the doctor
        $userId = $doctor->user_id;

        $doctor->delete();

        // Delete the associated user if it exists
        if ($userId) {
            User::where('id', $userId)->delete();
        }

        // Send notification to admins
        User::role('Admin')->each(function($admin) use ($doctorName) {
            $admin->notify(new DoctorDeletedNotification($doctorName));
        });

        return redirect()->route('doctors.index')
            ->with('success', 'تم حذف الطبيب بنجاح');
    }

    /**
     * Display the specified doctor.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['categories', 'user', 'governorate', 'city']);
        $appointments = app(AppointmentRepository::class)->findByDoctorAndDate($doctor->id, now());
        return view('doctors::show', compact('doctor', 'appointments'));
    }
}
