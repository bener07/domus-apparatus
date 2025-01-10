<x-app-layout>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Administrador') }}
            </h2>
    
        </header>
    
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>
    
        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')
    
            <div>
                <x-text-input placeholder="Utilizador"  type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            
            <div>
                <x-text-input placeholder="Password"  type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-text-input placeholder="Confirmar Password"  type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Escola') }}
            </h2>
            <div>
                <x-text-input placeholder="Nome da Escola"  type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div>
                <x-text-input placeholder="Localização da Escola"  type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div>
                <x-text-input placeholder="Sala dos equipamentos"  type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="name" :value="__('Logotipo')" />
                <div>
                    <label for="files" class="btn">Selecionar imagem</label>
                    <input id="files" style="visibility:hidden;" type="file">
                  </div>                  
            </div>
                
    
            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Adicionar') }}</x-primary-button>
    
                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"   
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </section>
    
</x-app-layout>
