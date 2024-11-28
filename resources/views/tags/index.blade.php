<!-- resources/views/tags/index.blade.php -->
<x-app-layout title="Tags">
    <div class="flex justify-end my-5 max-w-screen-md mx-auto">
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Create</a>
    </div>
    <div class="container mx-auto bg-white rounded-lg shadow-lg my-5 max-w-screen-md p-5">
        <ul class="w-3/4 flex flex-wrap">
            @foreach ($tags as $tag)
                <li class="border-b border-gray-300 py-2 bg-gray-200 mt-2.5 p-2.5 rounded flex items-center mr-2 mb-2">
                    <i class="fa-solid fa-tag mr-2"></i>
                    <span class="text-base mr-2">{{ $tag->name }}</span>
                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ml-2 text-red-500 hover:text-red-700">
                            &times;
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>