<?php

namespace Modules\Doctors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Category;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('categories')->get();
        $categories = Category::all();

        return view('doctors::index', [
            'doctors' => $doctors,
            'categories' => $categories,
            'title' => 'الأطباء',
            'classes' => 'bg-white'
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('doctors::create', compact('categories'));
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

        return redirect()->route('doctors.index')
            ->with('success', 'تم إضافة الطبيب بنجاح');
    }

    public function show($id)
    {
        $doctor = Doctor::with('categories')->findOrFail($id);
        return view('doctors::show', compact('doctor'));
    }

    public function edit($id)
    {
        $doctor = Doctor::with('categories')->findOrFail($id);
        $categories = Category::all();
        return view('doctors::edit', compact('doctor', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        
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

        return redirect()->route('doctors.index')
            ->with('success', 'تم تحديث بيانات الطبيب بنجاح');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->categories()->detach();
        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'تم حذف الطبيب بنجاح');
    }
}
