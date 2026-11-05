
<x-forum.layouts.home>

@foreach($questions as $question)
        <li class="flex justify-between gap-4 py-4">
            <div class="flex gap-4">
                <div class="size-8 rounded-full flex items-center justify-center" style="background-color: {{ $question->category->color }};">
                    <!-- logo -->
                </div>
                <div class="flex-auto">
                    <p class="text-sm font-semibold text-gray-900">
                        <a href="#" class="hover:underline">{{ $question->title }}</a>
                    </p>
                    <p class="mt-1 text-xs text-gray-500">{{ $question->user->name }}</p>
                </div>
            </div>
    
            <div class="hidden sm:flex sm:flex-col sm:items-end">
                <p class="text-sm text-gray-900">{{ $question->category->name }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $question->created_at->format('d M Y') }}</p>
            </div>
        </li>
        
    @endforeach
</x-forum.layouts.home>