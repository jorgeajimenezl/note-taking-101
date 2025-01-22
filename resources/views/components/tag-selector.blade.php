<div class="my-1"></div>
<div class="flex flex-wrap gap-2" id="tags-container">    
</div>
@include('components.form._form-error-handling')

<div id="tagDialog" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-1/2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Add a Tag</h2>
            <button onclick="toggleTagDialog(false)" class="text-gray-500 hover:text-gray-700" type="button">&times;</button>
        </div>
        <input id="searchTag" type="text" placeholder="Search tags..." 
               class="border rounded w-full px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" 
               oninput="filterTags()">
        <div id="tagSuggestions" class="mt-4 max-h-60 overflow-y-auto">
            <!-- Filtered Tag Suggestions Go Here -->
        </div>
    </div>
</div>

<script>
    let tags = {!! json_encode($tags) !!};
    let allTags = {!! json_encode($allTags) !!};
    renderTags();

    function toggleTagDialog(show) {
        const dialog = document.getElementById('tagDialog');
        dialog.classList.toggle('hidden', !show);
    }

    function filterTags() {
        const searchInput = document.getElementById('searchTag').value.toLowerCase();
        const suggestions = document.getElementById('tagSuggestions');
        suggestions.innerHTML = '';

        const filteredTags = allTags.filter(tag => 
            tag.name.toLowerCase().includes(searchInput) && !tags.some(t => t.id === tag.id)
        );

        filteredTags.forEach(tag => {
            const suggestion = document.createElement('div');
            suggestion.className = 'flex items-center p-2 hover:bg-gray-100 cursor-pointer';
            suggestion.textContent = tag.name;
            suggestion.onclick = () => addExistingTag(tag);
            suggestions.appendChild(suggestion);
        });
    }

    function addExistingTag(tag) {
        tags.push(tag);
        renderTags();
        toggleTagDialog(false);
    }

    function renderTags() {
        const tagsContainer = document.getElementById('tags-container');
        tagsContainer.innerHTML = '';

        tags.forEach(tag => {
            const chip = `
                <div class="flex items-center bg-blue-500 text-white rounded-full px-3 py-1">
                    ${tag.name}
                    <input type="hidden" data-mark="input-field" name="{{ $name }}[]" id="{{ $id }}" value="${tag.id}">
                    @if (!$readonly)
                    <button type="button" class="ml-2 text-sm text-white hover:text-gray-200" onclick="removeTag(${tag.id})">
                        &times;
                    </button>
                    @endif
                </div>`;
            tagsContainer.innerHTML += chip;
        });

        @if (!$readonly)
        // Add the "+" button back
        tagsContainer.innerHTML += `
            <button type="button" class="flex items-center bg-gray-200 text-gray-700 rounded-full px-3 py-1 hover:bg-gray-300" 
                    onclick="toggleTagDialog(true)">
                +
            </button>`;
        @endif
    }

    function removeTag(tagId) {
        tags = tags.filter(tag => tag.id !== tagId);
        renderTags();
    }
</script>