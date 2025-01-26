<div>
    @include('passkeys::components.partials.authenticateScript')

    <form id="passkey-login-form" method="POST" action="{{ route('passkeys.login') }}">
        @csrf
    </form>

    @if($message = session()->get('authenticatePasskey::message'))
        <div class="bg-red-100 text-red-700 p-4 border border-red-400 rounded">
            {{ $message }}
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (browserSupportsWebAuthn()) {
                document.getElementById('passkey-button').style.display = 'block';
            }
        });

        function authUsingPasskey() {
            console.log('Authenticating using Passkey...');
            authenticateWithPasskey()
                .then(response => {
                    console.log("Authentication successful");
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('passkey-error-message').innerText = 'Authentication using passkey failed. Please try again.';
                });
        }
    </script>

    <button id="passkey-button" style="display: none;" onclick="event.preventDefault(); authUsingPasskey()" {{ $attributes }}>
        @if ($slot->isEmpty())
            <div class="underline cursor-pointer">
                Authenticate using Passkey
            </div>
        @else
            {{ $slot }}
        @endif
    </button>
    <div id="passkey-error-message" class="text-red-500 mt-2"></div>
</div>
