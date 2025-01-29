<x-app-layout title="Tags">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-screen-md">
        <div class="flex justify-end my-4 sm:my-5">
            <a href="{{ route('tags.create') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Create Tag
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 lg:p-8">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Tags</h2>
            
            <ul class="flex flex-wrap gap-2 sm:gap-4">
                @foreach ($tags as $tag)
                    <li class="flex-shrink-0 border border-gray-300 py px-4 bg-gray-200 rounded-full flex items-center justify-between group hover:bg-gray-300 transition-colors duration-200">
                        <span class="text-sm sm:text-base font-medium text-gray-800 mr-2">
                            {{ $tag->name }}
                        </span>
                        
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-1 text-red-500 hover:text-red-700 transition-colors duration-200"
                                    aria-label="Delete tag">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
            
            @if($tags->isEmpty())
                <p class="text-gray-500 text-center py-4">No tags found. Create one!</p>
            @endif
        </div>
    </div>
</x-app-layout>