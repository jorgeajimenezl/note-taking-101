<x-app-layout title="Tags">
    <div class="flex justify-end my-5 max-w-screen-md mx-auto">
        <a href="{{ route('tags.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Create</a>
    </div>
    <div class="container mx-auto bg-white rounded-lg shadow-lg my-5 max-w-screen-md p-5">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Tags</h2>
        <ul class="flex flex-wrap gap-4">
            @foreach ($tags as $tag)
                <li class="border border-gray-300 py-1 px-4 bg-gray-200 rounded-full flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-base font-medium text-gray-800">{{ $tag->name }}</span>
                    </div>
                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ml-2 text-red-500 hover:text-red-700">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>