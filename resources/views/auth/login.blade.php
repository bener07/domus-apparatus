<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="light-button d-flex justify-content-evenly">
            <a class="bt" id="google-btn" href="{{ route('auth.google') }}">
                <div class="light-holder">
                    <div class="dot"></div>
                    <div class="light"></div>
                </div>
                <div class="button-holder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                        <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
                    </svg>
                </div>
            </a>
            <a class="bt" id="github-btn" href="{{ route('auth.github') }}">
                <div class="light-holder">
                    <div class="dot"></div>
                    <div class="light"></div>
                </div>
                <div class="button-holder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"/>
                    </svg>
                </div>
            </a>
            <a class="bt" id="facebook-btn">
                <div class="light-holder">
                    <div class="dot"></div>
                    <div class="light"></div>
                </div>
                <div class="button-holder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                    </svg>
                </div>
            </a>
            {{-- <button class="bt" id="discord-btn">
                <div class="light-holder">
                    <div class="dot"></div>
                    <div class="light"></div>
                </div>
                <div class="button-holder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-discord" viewBox="0 0 16 16">
                        <path d="M13.545 2.907a13.2 13.2 0 0 0-3.257-1.011.05.05 0 0 0-.052.025c-.141.25-.297.577-.406.833a12.2 12.2 0 0 0-3.658 0 8 8 0 0 0-.412-.833.05.05 0 0 0-.052-.025c-1.125.194-2.22.534-3.257 1.011a.04.04 0 0 0-.021.018C.356 6.024-.213 9.047.066 12.032q.003.022.021.037a13.3 13.3 0 0 0 3.995 2.02.05.05 0 0 0 .056-.019q.463-.63.818-1.329a.05.05 0 0 0-.01-.059l-.018-.011a9 9 0 0 1-1.248-.595.05.05 0 0 1-.02-.066l.015-.019q.127-.095.248-.195a.05.05 0 0 1 .051-.007c2.619 1.196 5.454 1.196 8.041 0a.05.05 0 0 1 .053.007q.121.1.248.195a.05.05 0 0 1-.004.085 8 8 0 0 1-1.249.594.05.05 0 0 0-.03.03.05.05 0 0 0 .003.041c.24.465.515.909.817 1.329a.05.05 0 0 0 .056.019 13.2 13.2 0 0 0 4.001-2.02.05.05 0 0 0 .021-.037c.334-3.451-.559-6.449-2.366-9.106a.03.03 0 0 0-.02-.019m-8.198 7.307c-.789 0-1.438-.724-1.438-1.612s.637-1.613 1.438-1.613c.807 0 1.45.73 1.438 1.613 0 .888-.637 1.612-1.438 1.612m5.316 0c-.788 0-1.438-.724-1.438-1.612s.637-1.613 1.438-1.613c.807 0 1.451.73 1.438 1.613 0 .888-.631 1.612-1.438 1.612"/>
                    </svg>
                </div>
            </button> --}}
        </div>
        
        <div class="flex-column">
            <p class="text-white text-center py-3">
                {{ __('Or login with') }}
            </p>
        </div>
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex justify-content-between py-2" style="justify-content: space-between">
            <div class="col" style="align-content: center; justify-content: center;">
                <label for="remember_me" class="inline-flex items-center" >
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>
            <div class="col flex justify-content-end" style="align-content: center">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
        </div>
        <x-primary-button type="submit" style="width: 100%">
            {{ __('Log in') }}
        </x-primary-button>

        <div class="flex justify-content-center mt-4" style="justify-content:center">
            @if (Route::has('register'))
            <p class="text-white text-sm">{{ __("Don't Have an account?") }}</p>
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                    {{ __(' Sign Up') }}
                </a>
            @endif
        </div>
    </form>
    <style>
:root{
    --bs-discord: 88, 101, 242, 1;
    --bs-facebook: 24, 119, 242;
    --bs-google: 251, 188, 5;
    --bs-github: 255, 255, 255;
}

.light-button a.bt {
    position: relative;
    height: 150px;
    display: flex;
    align-items: flex-end;
    outline: none;
    background: none;
    border: none;
    cursor: pointer;
}

.light-button a.bt .button-holder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 50px;
    width: 50px;
    background-color: #0a0a0a;
    border-radius: 5px;
    color: #0f0f0f;
    font-weight: 700;
    transition: 300ms;
    outline: #0f0f0f 2px solid;
    outline-offset: 20;
}

.light-button a.bt .button-holder svg {
    height: 50px;
    fill: #0f0f0f;
    transition: 300ms;
}

.light-button a.bt .light-holder {
    position: absolute;
    height: 150px;
    width: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.light-button a.bt .light-holder .dot {
    position: absolute;
    top: 0;
    width: 10px;
    height: 10px;
    background-color: #0a0a0a;
    border-radius: 10px;
    z-index: 2;
}

.light-button a.bt .light-holder .light {
    position: absolute;
    top: 0;
    width: 50px;
    height: 150px;
    clip-path: polygon(50% -4%, 0% 70%, 100% 70%);
    background: transparent;
}

/* Discord Button*/
#discord-btn{
    --bs-colors: var(--bs-discord);
}

#facebook-btn{
    --bs-colors: var(--bs-facebook);
}

#google-btn{
    --bs-colors: var(--bs-google);
}

#github-btn{
    --bs-colors: var(--bs-github);
}

.light-button a.bt:hover .button-holder svg{
    fill: rgba(var(--bs-colors));
}

.light-button a.bt:hover .button-holder{
    color: rgba(var(--bs-colors));
    outline: rgba(var(--bs-colors)) 2px solid;
    outline-offset: 2px;
}

.light-button a.bt:hover .light-holder .light {
    background: rgb(255, 255, 255);
    background: linear-gradient(
        180deg,
        rgba(var(--bs-colors)) 0%,
        rgba(255, 255, 255, 0) 75%,
        rgba(255, 255, 255, 0) 100%
    );
}
/* For devices smaller than tablet size (max-width: 1024px) */
@media only screen and (max-width: 1024px) {
    
    .light-button a.bt .button-holder svg{
        fill: rgba(var(--bs-colors));
    }

    .light-button a.bt .button-holder{
        color: rgba(var(--bs-colors));
        outline: rgba(var(--bs-colors)) 2px solid;
        outline-offset: 2px;
    }

    .light-button a.bt .light-holder .light {
        background: rgb(255, 255, 255);
        background: linear-gradient(
            180deg,
            rgba(var(--bs-colors)) 0%,
            rgba(255, 255, 255, 0) 75%,
            rgba(255, 255, 255, 0) 100%
        );
    }
}

    </style>
</x-guest-layout>