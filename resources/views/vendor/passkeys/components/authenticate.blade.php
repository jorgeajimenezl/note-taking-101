<div>
    @include('passkeys::components.partials.authenticateScript')  
    
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
    @session('authenticatePasskey::message')
        <div class="text-red-500 mt-2">Invalid passkey. Please try again.</div>
    @endsession
</div>
