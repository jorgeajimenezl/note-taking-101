<form id="task-form" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method("POST")
    <div class="container mx-auto bg-white rounded-lg shadow-lg my-4 sm:my-5 max-w-screen-md p-4 sm:p-5">
        <div class="mx-auto max-w-7xl px-3 sm:px-4 py-4 sm:py-6">
            <div class="title-section flex items-center justify-between">
                <x-text-input id="task-title" data-mark="input-field"
                    class="text-lg sm:text-xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable"
                    placeholder="Title" name="title" required />
            </div>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />

            <x-input-label class="mt-4 mb-2" :value="__('Tags')" />
            <livewire:tag-selector name="tags" :readonly="false" />

            <div class="mt-4"></div>
            <x-textarea-input id="task-description" name="description" placeholder="Description"
                class="w-full min-h-[100px] text-sm sm:text-base" value="" />

            <!-- Attachments Section -->
            <div class="flex items-center justify-between mt-4 mb-2">
                <x-input-label :value="__('Attachments')" />
                <button type="button"
                    class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-sm transition-colors duration-200"
                    onclick="addAttchment()">
                    <i class="fas fa-plus mr-1"></i> Add
                </button>
            </div>
            <div class="attachments-section mt-2 space-y-2"></div>
            @if ($errors->has('attachments.*'))
                <div class="text-red-500 text-sm mt-2">
                    {{ $errors->first('attachments.*') }}
                </div>
            @endif

            <div class="flex justify-end mt-6">
                <x-primary-button id="save-button" type="submit" class="w-full sm:w-auto">
                    Create
                </x-primary-button>
            </div>
        </div>
    </div>

    <script>
        function addAttchment() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'attachments[]';
            fileInput.classList.add('hidden');
            fileInput.click();

            fileInput.addEventListener('change', function (e) {
                console.log(e.target.files);
                if (e.target.files.length === 1) {
                    addAttchmentItem(e);
                }
            });
            fileInput.addEventListener('cancel', function () {
                fileInput.remove();
            });
        }
        function addAttchmentItem(e) {
            const attachmentPlaceholder = document.createElement('div');
            attachmentPlaceholder.classList.add('attachment-placeholder', 'bg-gray-100', 'p-2', 'rounded', 'mb-2', 'flex', 'items-center', 'justify-between');
            attachmentPlaceholder.innerHTML = `
                <div class="flex items center">
                    <i class="fas fa-paperclip mr-2"></i> ${e.target.files[0].name}
                </div>
                <button type="button" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            const removeButton = attachmentPlaceholder.querySelector('button');
            removeButton.addEventListener('click', function () {
                attachmentPlaceholder.remove();
            });
            attachmentPlaceholder.appendChild(e.target);
            document.querySelector('.attachments-section').appendChild(attachmentPlaceholder);
        }
    </script>
</form>