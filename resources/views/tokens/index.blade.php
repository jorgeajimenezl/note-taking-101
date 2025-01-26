<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @foreach ($tokens as $token)
                <div class="p-4 bg-white shadow sm:rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-md font-medium text-gray-900">{{ $token->name }}</h3>
                            <p class="text-sm text-gray-600">Created on: {{ $token->created_at->format('M d, Y') }}</p>
                        </div>
                        <form method="post" action="{{ route('tokens.destroy', $token->id) }}">
                            @csrf
                            @method('delete')
                            <x-danger-button>{{ __('Delete') }}</x-danger-button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>