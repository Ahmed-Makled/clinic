<?php

namespace Modules\Specialties\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
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

        return view('specialties::index', compact('specialties'));
    }
}
