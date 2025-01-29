<div id="contributor-selector-modal"
    name="contributor-selector" 
    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;"
    x-data="{ isVisible: false }"
    @open-dialog.window="isVisible = true"
    @close-dialog.window="isVisible = false"
    x-show="isVisible"
>
    <div class="bg-white rounded-lg p-4 sm:p-6 w-full sm:w-3/4 md:w-2/3 lg:w-1/2 max-w-2xl mx-4">
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-bold">Share Task</h2>
            <button @click="$dispatch('close-dialog')" 
                    class="p-2 text-gray-500 hover:text-gray-700 transition-colors duration-200" 
                    type="button">&times;</button>
        </div>

        <div class="flex flex-wrap gap-2 sm:gap-3 mb-4 sm:mb-6" id="contributors-container">
            @foreach($contributors as $contributor)
                <div class="flex items-center">
                    <?php
                        $colors = ['bg-red-500', 'bg-green-500', 'bg-blue-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500'];
                        $hash = crc32($contributor->email);
                        $color = $colors[$hash % count($colors)];
                    ?>
                    <button type="button" 
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full {{ $color }} flex items-center justify-center text-white relative hover:opacity-90 transition-opacity duration-200" 
                            wire:click="removeContributor('{{ $contributor->email }}')" 
                            title="{{ $contributor->name }} <{{ $contributor->email }}>">
                        <span class="initials text-sm sm:text-base">{{ strtoupper(substr($contributor->name, 0, 2)) }}</span>
                        <span class="remove-icon hidden absolute text-lg sm:text-xl">&times;</span>
                    </button>
                </div>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
            <x-text-input type="email" 
                         placeholder="Enter contributor's email..." 
                         class="w-full px-3 py-2 text-sm sm:text-base" 
                         wire:model="email"/>
            <x-select-input name="role" 
                           class="w-full sm:w-1/4 px-3 py-2 text-sm sm:text-base" 
                           wire:model="role">
                <option value="viewer" selected>Viewer</option>
                <option value="editor">Editor</option>
            </x-select-input>
        </div>

        @if($errors->has('email'))
        <div id="email-error" 
             wire:dirty.class="hidden" 
             wire:target="email" 
             class="text-red-500 text-xs sm:text-sm ml-1 mt-1 italic">
            {{ $errors->first('email') }}
        </div>
        @endif

        <button type="button" 
                class="mt-4 sm:mt-6 w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors duration-200" 
                wire:click="addContributor">
            Add Contributor
        </button>
    </div>

    <style>
        .remove-icon { display: none; }
        .initials { display: block; }
        button:hover .initials { display: none; }
        button:hover .remove-icon { display: block; }
    </style>
</div>