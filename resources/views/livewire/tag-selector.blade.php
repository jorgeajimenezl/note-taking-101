<div>
    <div class="flex flex-wrap gap-2" id="tags-container">
        @foreach($tags as $tag)
            <div class="flex items-center bg-blue-500 text-white rounded-full px-3 py-1">
                {{ $tag->name }}
                <input type="hidden" name="{{ $name }}[]" value="{{ $tag->id }}"/>
                @if (!$readonly)
                    <button type="button" class="ml-2 text-sm text-white hover:text-gray-200" wire:click="deleteTag({{ $tag->id }})">
                        &times;
                    </button>
                @endif
            </div>
        @endforeach

        @if(!$readonly)
            <button type="button" class="flex items-center bg-gray-200 text-gray-700 rounded-full px-3 py-1 hover:bg-gray-300" wire:click="updateDialogVisibility(true)">
                +
            </button>
        @endif
    </div>
    @include('components.form._form-error-handling')

    @if($showDialog)
        <div id="tagDialog" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 w-1/2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Add a Tag</h2>
                    <button wire:click="updateDialogVisibility(false)" class="text-gray-500 hover:text-gray-700" type="button">&times;</button>
                </div>
                <input type="text" wire:model.live="searchTag" placeholder="Search tags..." class="border rounded w-full px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                <div id="tagSuggestions" class="mt-4 max-h-60 overflow-y-auto">
                    @foreach($filteredTags as $tag)
                        <div class="flex items-center p-2 hover:bg-gray-100 cursor-pointer" wire:click="addTag({{ $tag->id }})">
                            {{ $tag->name }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <script>
        function toggleTagDialog(show) {
            const dialog = document.getElementById('tagDialog');
            dialog.classList.toggle('hidden', !show);
        }
    </script>
</div>