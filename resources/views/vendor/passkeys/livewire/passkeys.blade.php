<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Manage Passkeys') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Passkeys allow for a more secure, seamless authentication experience on supported devices.') }}
        </p>
    </header>

    <form name="createPasskey" wire:submit="validatePasskeyProperties"  class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="create_passkey_passkey_name" :value="__('Passkey Name')"/>
            <x-text-input id="create_passkey_passkey_name" name="name" class="mt-1 block w-full" wire:model="name"/>
            <x-input-error :messages="$errors->passkeyForm->get('name')" class="mt-2"/>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Create Passkey') }}</x-primary-button>
        </div>
    </form>

    @if(! $passkeys->isEmpty())
    <div class="mt-6">
        <h3 class="font-medium text-gray-900">{{ __('Your Passkeys') }}</h3>
        <ul class="mt-2">
            @foreach($passkeys as $passkey)
                <li class="px-2 py-2 flex justify-between items-center">
                    <div class="flex flex-col">
                        <span class="font-semibold">{{ $passkey->name }}</span>
                        <span class="font-thin text-sm text-gray-600">Added {{ $passkey->created_at->diffForHumans() }}</span>
                    </div>

                    <x-danger-button class="" wire:click="deletePasskey({{ $passkey->id }})">
                        Remove
                    </x-danger-button>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    @include('passkeys::livewire.partials.createScript')
</section>
