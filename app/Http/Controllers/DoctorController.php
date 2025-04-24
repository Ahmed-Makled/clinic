public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:doctors',
        'phone' => 'required|string|max:20',
        'password' => 'required|string|min:6',
        'price' => 'required|numeric|min:0',
        'categories' => 'required|array|min:1',
        'categories.*' => 'exists:categories,id',
        'bio' => 'nullable|string',
        'experience_years' => 'nullable|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ], [
        'password.required' => 'كلمة المرور مطلوبة',
        'price.required' => 'سعر الكشف مطلوب',
        'categories.required' => 'يجب اختيار تخصص واحد على الأقل'
    ]);

    // Hash the password
    $validated['password'] = bcrypt($validated['password']);

    $doctor = Doctor::create($validated);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('doctors', 'public');
        $doctor->image = $imagePath;
        $doctor->save();
    }

    $doctor->categories()->sync($request->categories);

    return redirect()->route('doctors.index')
        ->with('success', 'تم إضافة الطبيب بنجاح');
}
