<div class="w-full md:w-1/3 px-4 mb-4">
    <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-200">
        <div class="text-center border-b border-gray-200 pb-3 mb-3">
            <img src="{{ asset('assets/images' . $doctor->avatar) }}" class="rounded-full w-24 h-24 object-cover mx-auto" alt="{{ $doctor->name }}">
            <div class="mt-3 flex justify-center">
                @for($i = 0; $i < 5; $i++)
                    <i class="uil-star {{ $i < $doctor->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                @endfor
            </div>
        </div>
        <ul class="list-none p-0 space-y-2">
            <li class="inline-flex items-center space-x-2">
                <i class="uil-book text-gray-600"></i>
                <span>{{ $doctor->degree }}</span>
            </li>
            <li class="inline-flex items-center space-x-2">
                <i class="uil-bill text-gray-600"></i>
                <span>{{ $doctor->price }} <small>ج م</small></span>
            </li>
        </ul>
        <h5 class="text-lg font-semibold mb-3">{{ $doctor->name }}</h5>
        <p class="text-gray-600 mb-4">{{ Str::limit($doctor->description, 125) }}</p>
        <a href="{{ route('doctors.show', $doctor->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors duration-200">
            إقرأ المزيد <i class="uil-angle-left mr-1"></i>
        </a>
    </div>
</div>
