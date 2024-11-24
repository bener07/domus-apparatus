<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
            </div>
            <form class="user" method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input placeholder="Enter Email Address..." id="email" aria-describedby="emailHelp"
                        class="block mt-1 form-control form-control-user" type="email"
                        name="email" :value="old('email')"
                        required autofocus autocomplete="username"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4 form-group">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 form-control form-control-user"
                                    type="password"
                                    name="password"
                                    placeholder="Password"
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
                </div>
                <x-primary-button>
                    {{ __('Login') }}
                </x-primary-button>
                <hr>
                <div class="flex-column">
                    <p class=" text-center py-3">
                        {{ __('Or login with') }}
                    </p>
                </div>
                @if(Route::has('auth.google'))
                <a href="{{ route('auth.google') }}" class="btn btn-google btn-user btn-block">
                    <i class="fab fa-google fa-fw"></i> Login with Google
                </a>
                @endif
                @if(Route::has('auth.facebook'))
                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                    <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                </a>
                @endif
            </form>
            <hr>
            <div class="text-center col flex justify-content-end" style="align-content: center">
                @if (Route::has('password.request'))
                    <a class=" small underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
            </div>
            <div class="text-center">
                @if (Route::has('register'))
                <p class="text-center">{{ __("Don't Have an account?") }}</p>
                    <a class="small underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                        {{ __(' Sign Up') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>