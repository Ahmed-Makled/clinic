<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Category;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('categories')->paginate(10);
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
            'email' => 'required|email|unique:doctors',
            'phone' => 'required|string|max:20',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $doctor = Doctor::create($validated);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('doctors', 'public');
            $doctor->image = $imagePath;
            $doctor->save();
        }

        $doctor->categories()->sync($request->category_ids);

        return redirect()->route('admin.doctors.index')
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
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'required|string|max:20',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $doctor->update($validated);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('doctors', 'public');
            $doctor->image = $imagePath;
            $doctor->save();
        }

        $doctor->categories()->sync($request->category_ids);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'تم تحديث بيانات الطبيب بنجاح');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->categories()->detach();
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'تم حذف الطبيب بنجاح');
    }
}
