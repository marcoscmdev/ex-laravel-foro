<x-forum.layouts.app>

    <!-- component: forum/layouts/app -->

    <div class="flex items-center gap-2 w-full my-8">
       <livewire:heart :heartable="$question" />

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

                @auth
                <div class="flex items-center gap-2">
                    @can('update', $question)
                    <a href="{{route('questions.edit', $question )}}" class="text-xs font-semibold hover:underline">
                        Edit
                    </a>
                    @endcan

                    @can('delete', $question)
                    <form action="{{route('questions.destroy', $question  ) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta pregunta?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-md bg-red-600 hover:bg-red-500 px-2 py-1 text-xs font-semibold text-white cursor-pointer">
                            Eliminar
                        </button>
                    </form>
                    @endcan
                </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="my-4">
        <p class="text-gray-200">
            {{$question -> description}}
        </p>
        <livewire:comment :commentable="$question" />
    </div>
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

            <livewire:heart :heartable="$comment" :key="'question-comment-heart-'.$comment->id" />
        </li>

        @endforeach

    </ul>

    <p class="text-gray-500">
        <a href="#" class="rounded-md text-xs hover:underline cursor-pointer">
            Agregar comentario
        </a>
    </p>

    <ul class="space-y-4">
        @foreach($question->answers as $answer)
        <li>
            <div class="flex items-start gap-2">
                <livewire:heart :heartable="$answer" :key="'answer-heart-'.$answer->id" />

                <div>
                    <p class="text-sm text-gray-300">
                        {{$answer->content}}
                    </p>
                    <!-- Comments -->
                    <p class="text-xs text-gray-500">
                        {{$answer->user->name}} | {{$answer->created_at->format('d M Y')}}
                    </p>

                    <ul class="my-4 space-y-2">
                        @foreach($answer->comments as $comment)
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

                            <livewire:heart :heartable="$comment" :key="'answer-comment-heart-'.$comment->id" />
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

   <div class="mt-8">
    @auth
    <h3 class="text-lg font-semibold mb-2">Tu Respuesta...</h3>

    <form action="{{route('answer.store', $question)}}" method="POST">
        @csrf

        <div class="mb-2">
            <textarea name="content" rows="6" class="w-full p-2 border rounded-md text-xs" required></textarea>
            @error('content')<span class="block text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="rounded-md bg-blue-600 hover:bg-blue-500 px-4 py-2 text-white cursor-pointer">
            Enviar Respuesta
        </button>
    </form>
    @else
    <p class="text-gray-500">
        <a href="{{route('login')}}" class="text-blue-600 hover::underline">
            Inicia sesión para responder
        </a>
    </p>
    @endauth
</div>

</x-forum.layouts.app>