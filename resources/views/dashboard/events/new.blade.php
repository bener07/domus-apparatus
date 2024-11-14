<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-blue dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-8 bg-blue dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Add new Event') }}
                                </h2>
                        
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Create a new event") }}
                                </p>
                            </header>
                        
                            <form class="mt-6 space-y-6" enctype="multipart/form-data" id="eventForm">
                                @csrf
                                <div>
                                    <x-input-label for="name" :value="__('Event Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                                <div>
                                    <x-input-label for="details" :value="__('Details')"/>
                                    <x-text-input name="details" id="details" type="text" class="mt-1 block w-full" :value="old('details')"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('details')" />
                                </div>
                                <div>
                                    <x-input-label for="description" :value="__('Description')" />
                                    <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description')" required/>
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>
                                <div>
                                    <x-input-label for="price" :value="__('Ticket Price')" />
                                    <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" :value="old('price')" required/>
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>
                                <div>
                                    <x-input-label for="local" :value="__('Address')" />
                                    <x-text-input id="local" name="local" type="text" class="mt-1 block w-full" :value="old('local')" required/>
                                    <x-input-error class="mt-2" :messages="$errors->get('local')" />
                                </div>
                                <div>
                                    <input type="file" src="" name="featured_image" alt="Featured_Image">
                                    <input type="file" id="images" name="images[]" accept="image/*" multiple>
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-primary-button id="submitEventBtn">{{ __('Save') }}</x-primary-button>
                                </div>
                            </form>
                        </section>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<x-slot name="scripts">
    <script>
        $('#eventForm').on('submit', function(event){
            event.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '/api/party',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers:{
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    console.log(response);
                    Swal.fire({
                        title: response.message,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Go to My Parties',
                        cancelButtonText: 'See More',
                        customClass: {
                            confirmButton: 'btn btn-secondary mx-3',  // optional custom styling
                            cancelButton: 'btn btn-primary mx-3'
                        },
                        buttonsStyling: false // use this to avoid default SweetAlert2 button styling
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Action for "Go to My Parties" button
                            window.location.href = '{{ route("dashboard") }}'; // replace with your "My Parties" URL
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Action for "See More" button
                        }})
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: xhr.responseJSON.message,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Go to My Parties',
                        cancelButtonText: 'Add more',
                        customClass: {
                            confirmButton: 'btn btn-secondary mx-3',  // optional custom styling
                            cancelButton: 'btn btn-primary mx-3'
                        },
                        buttonsStyling: false // use this to avoid default SweetAlert2 button styling
                    }).then((result) => {
                    if (result.isConfirmed) {
                        // Action for "Go to My Parties" button
                        window.location.href = '{{ route("dashboard") }}'; // replace with your "My Parties" URL
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Action for "See More" button
                        window.location.href = '{{ route("dashboard.newEvent") }}'; // replace with your "My Parties" URL
                    }})
                }
            });
        })
    </script>
</x-slot>
</x-app-layout>