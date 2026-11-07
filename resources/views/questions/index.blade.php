<x-forum.layouts.app>

    <div class="my-8">
        
        @foreach($questions as $question)

        <div class="mb-4">
            <h2 class="text-2xl font-bold">
                <a href="{{ route('questions.show', $question)}}" class="hover:underline">
                    {{ $question->title}}
                </a>
            </h2>
            
            <div class="flex gap-2">
                <p class="text-xs text-gray-500">
                    <span class="font-semibold">User</span> |
                    {{ $question->category->name }} |
                    {{$question->created_at->format('d M Y')}}
                </p>
            </div>
        </div>
        
        @endforeach
        
        {{ $questions->links() }}
    </div>

</x-forum.layouts.app>