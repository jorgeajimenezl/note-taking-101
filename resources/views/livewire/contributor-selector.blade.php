<div id="contributor-selector-modal"
    name="contributor-selector" 
    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center"
    x-data="{ isVisible: false }"
    @open-dialog.window="isVisible = true"
    @close-dialog.window="isVisible = false"
    x-show="isVisible"
>
    <div class="bg-white rounded-lg p-6 w-1/2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Share Task</h2>
            <button @click="$dispatch('close-dialog')" class="text-gray-500 hover:text-gray-700" type="button">&times;</button>
        </div>
        <div class="flex flex-wrap gap-2 mb-4" id="contributors-container">
            @foreach($contributors as $contributor)
                <div class="flex items-center space-x-2">
                    <?php
                        $colors = ['bg-red-500', 'bg-green-500', 'bg-blue-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500'];
                        $hash = crc32($contributor->email);
                        $color = $colors[$hash % count($colors)];
                    ?>
                    <button type="button" class="w-8 h-8 rounded-full {{ $color }} flex items-center justify-center text-white relative" wire:click="removeContributor('{{ $contributor->email }}')" title="{{ $contributor->name }} <{{ $contributor->email }}>">
                        <span class="initials">{{ strtoupper(substr($contributor->name, 0, 2)) }}</span>
                        <span class="remove-icon hidden absolute">&times;</span>
                    </button>
                </div>
            @endforeach
        </div>
        <div class="flex gap-2">
            <x-text-input type="email" placeholder="Enter contributor's email..." class="w-full px-3 py-2" wire:model="email"/>
            <x-select-input name="role" class="w-1/4 px-3 py-2" wire:model="role">
                <option value="viewer" selected>Viewer</option>
                <option value="editor">Editor</option>
            </x-select-input>
        </div>
        @if($errors->has('email'))
        <div id="email-error" wire:dirty.class="hidden" wire:target="email" class="text-red-500 text-xs ml-1 mt-1 italic">
            {{ $errors->first('email') }}
        </div>
        @endif
        <button type="button" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded" wire:click="addContributor">Add Contributor</button>
    </div>
    <style>
        .remove-icon {
            display: none;
        }
        .initials {
            display: block;
        }
        button:hover .initials {
            display: none;
        }
        button:hover .remove-icon {
            display: block;
        }
    </style>
</div>