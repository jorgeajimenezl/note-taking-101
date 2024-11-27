<!-- resources/views/tags/index.blade.php -->
<x-app-layout title="Tags">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-4">
            <a href="{{ route('tags.create') }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Create</a>
        </div>
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