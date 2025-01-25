<form id="task-form" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method("POST")
    <div class="container mx-auto bg-white rounded-lg shadow-lg my-5 max-w-screen-md p-5">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="title-section flex items-center justify-between">
                <x-text-input id="task-title" data-mark="input-field" class="text-xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow editable" placeholder="Title" name="title" required/>
            </div>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />

            <x-input-label class="mt-4 mb-2" :value="__('Tags')"/>
            <livewire:tag-selector name="tags" :readonly="false"/>
            <div class="mt-4"></div>
            <x-textarea-input id="task-description" name="description" placeholder="Description" value=""/>

            <!-- Attachments Section -->
            <div class="flex items-center justify-between mt-4 mb-2">
                <x-input-label :value="__('Attachments')"/>
                <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-sm" onclick="addAttchment()">
                    <i class="fas fa-plus mr-1"></i> Add
                </button>
            </div>
            <div class="attachments-section mt-2"></div>
            @if ($errors->has('attachments.*'))
                <div class="text-red-500 text-sm mt-2">
                    {{ $errors->first('attachments.*') }}
                </div>
            @endif
            <div class="flex justify-end mt-4">
                <x-primary-button id="save-button" type="submit">Create</x-primary-button>
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

            fileInput.addEventListener('change', function(e) {
                console.log(e.target.files);
                if (e.target.files.length === 1) {
                    addAttchmentItem(e);
                }
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
            attachmentPlaceholder.appendChild(e.target);
            document.querySelector('.attachments-section').appendChild(attachmentPlaceholder);
        }
    </script>
</form>