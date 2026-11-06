<x-forum.layouts.app>

    <!-- component: forum/layouts/app -->

    <div class="flex items-center gap-2 w-full my-8">
        <div>&hearts;</div>

        <div class="w-full">
            <h2 class="text-2xl font-bold md:text-3xl">
                {{$question ->title}}
            </h2>

            <div class="flex justify-between">
                <p class="text-xs text-gray-500">
                    <span class="font-semibold">{{ $question->user->name }}</span> |
                    {{$question->category->name}} |
                    {{$question->created_at->format('d M Y')}}
                </p>

                <div class="flex items-center gap-2">
                    <a href="#" class="text-xs font-semibold hover:underline">
                        Edit
                    </a>

                    <form action="#" onsubmit="return confirm('¿Estás seguro de eliminar esta pregunta?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-md bg-red-600 hover:bg-red-500 px-2 py-1 text-xs font-semibold text-white cursor-pointer">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="my-4">
        <p class="text-gray-200">
{{$question -> description}}
    </p>

     <ul class="my-4 space-y-2">
    @foreach($question->comments as $comment)
    <li class="flex items-center gap-2">
        <p class="text-xs bg-white/10 p-4 rounded-md">
            <span class="text-gray-500">
                {{$comment->user->name}} | 
                {{$comment->created_at->format('d M Y')}}
            </span>
            <span class="text-gray-300">
                {{$comment->content}}
            </span>
        </p>

        <div>&hearts;</div>
    </li>
    
    @endforeach

</ul>

<p class="text-gray-500">
    <a href="#" class="rounded-md text-xs hover:underline cursor-pointer">
        Agregar comentario
    </a>
</p>
    </div>

    <ul class="space-y-4">

        @foreach($answer->comments as $comment)
            <li>
                <div class="flex items-start gap-2">
                    <div>&hearts;</div>

                    <div>
                        <p class="text-sm text-gray-300">
                            {{$answer->content}}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{$answer->user->name}} | {{$answer->created_at->format('d M Y')}}
                        </p>

                        <!-- Comments -->
                    </div>
                </div>
            </li>

        <li>
            <div class="flex items-start gap-2">
                <div>&hearts;</div>

                <div>
                    <p class="text-sm text-gray-300">
                    {{$answer->content}}
                    </p>
                    <!-- Comments -->
                </p>
                    <p class="text-xs text-gray-500">
                        {{$answer->user->name}} | {{$answer->created_at->format('d M Y')}}
                    </p>

                     <ul class="my-4 space-y-2">
    @foreach($question->comments as $comment)
    <li class="flex items-center gap-2">
        <p class="text-xs bg-white/10 p-4 rounded-md">
            <span class="text-gray-500">
                {{$comment->user->name}} | 
                {{$comment->created_at->format('d M Y')}}
            </span>
            <span class="text-gray-300">
                {{$comment->content}}
            </span>
        </p>

        <div>&hearts;</div>
    </li>
    
    @endforeach

</ul>

<p class="text-gray-500">
    <a href="#" class="rounded-md text-xs hover:underline cursor-pointer">
        Agregar comentario
    </a>
</p>
                </div>
            </div>
        </li>

       @endforeach

    </ul>

    <!-- component: forum/layouts/app -->

</x-forum.layouts.app>