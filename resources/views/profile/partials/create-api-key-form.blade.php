<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('API Keys') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Create new API key.") }}
        </p>
    </header>

    <form method="post" action="{{ route('tokens.store') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="create_api_key_name" :value="__('Name')" />
            <x-text-input id="create_api_key_name" name="name" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->storeToken->get('name')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Create') }}</x-primary-button>
            <x-secondary-button onclick="window.location='{{ route('tokens.index') }}'">
                {{ __('View All Tokens') }}
            </x-secondary-button>
        </div>
    </form>

    <x-modal name="show-api-key" :show="session('token')" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('API Key Created') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Your new API key is:') }}
            </p>

            <div class="mt-6">
                <x-text-input value="{{ session('token') }}" type="text" class="mt-1 block w-full" readonly />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</section>