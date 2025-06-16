<?php

namespace Modules\Specialties\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Specialties\Entities\Category;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Category::withCount('doctors')->orderBy('created_at', 'desc')->paginate(15);
        return view('specialties::admin.index', [
            'specialties' => $specialties,
            'title' => 'التخصصات'
        ]);
    }

    public function create()
    {
        return view('specialties::admin.create', [
            'title' => 'إضافة تخصص جديد'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        Category::create($validated);

        return redirect()->route('specialties.index')
            ->with('success', 'تم إضافة التخصص بنجاح');
    }

    public function edit(Category $specialty)
    {
        return view('specialties::admin.edit', [
            'specialty' => $specialty,
            'title' => 'تعديل التخصص'
        ]);
    }

    public function update(Request $request, Category $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $specialty->id,
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $specialty->update($validated);

        return redirect()->route('specialties.index')
            ->with('success', 'تم تحديث التخصص بنجاح');
    }

    public function destroy(Category $specialty)
    {
        if ($specialty->doctors()->exists()) {
            return redirect()->route('specialties.index')
                ->with('error', 'لا يمكن حذف التخصص لأنه مرتبط بأطباء');
        }

        $specialty->delete();

        return redirect()->route('specialties.index')
            ->with('success', 'تم حذف التخصص بنجاح');
    }
}
