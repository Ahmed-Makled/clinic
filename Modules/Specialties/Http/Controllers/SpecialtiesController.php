<?php

namespace Modules\Specialties\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Specialties\Entities\Category;
use Illuminate\Http\Request;

class SpecialtiesController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('doctors');

        // تطبيق فلتر البحث
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // تطبيق فلتر الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $specialties = $query->latest()->paginate(10);

        return view('specialties::admin.index', compact('specialties'));
    }

    public function create()
    {
        $title = 'إضافة تخصص جديد';
        return view('specialties::admin.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean'
        ], [
            'name.required' => 'اسم التخصص مطلوب',
            'name.unique' => 'اسم التخصص موجود بالفعل',
            'name.max' => 'اسم التخصص لا يمكن أن يتجاوز 255 حرفاً',
            'description.max' => 'الوصف لا يمكن أن يتجاوز 1000 حرفاً'
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $request->has('status') ? true : false,
        ]);

        return redirect()->route('specialties.index')
            ->with('success', 'تم إضافة التخصص بنجاح');
    }

    public function edit(Category $specialty)
    {
        return view('specialties::admin.edit', compact('specialty'));
    }

    public function update(Request $request, Category $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $specialty->id,
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean'
        ], [
            'name.required' => 'اسم التخصص مطلوب',
            'name.unique' => 'اسم التخصص موجود بالفعل',
            'name.max' => 'اسم التخصص لا يمكن أن يتجاوز 255 حرفاً',
            'description.max' => 'الوصف لا يمكن أن يتجاوز 1000 حرفاً'
        ]);

        $specialty->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $request->has('status') ? true : false,
        ]);

        return redirect()->route('specialties.index')
            ->with('success', 'تم تحديث التخصص بنجاح');
    }

    public function destroy(Category $specialty)
    {
        // التحقق من وجود أطباء مرتبطين بهذا التخصص
        if ($specialty->doctors()->count() > 0) {
            return redirect()->route('specialties.index')
                ->with('error', 'لا يمكن حذف هذا التخصص لأنه مرتبط بأطباء');
        }

        $specialty->delete();

        return redirect()->route('specialties.index')
            ->with('success', 'تم حذف التخصص بنجاح');
    }
}
