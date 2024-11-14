@extends('layout')
@section('title')
PW Shop
@endsection
@section('content')  
<!-- Header-->
<header class="bg-secondary-subtle py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder">Party Like An Animal</h1>
            <p class="lead fw-normal text-white-50 mb-0">There's no such thing as to much fun</p>
        </div>
    </div>
</header>
<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-3 row-cols-xl-4 justify-content-center" id="parties">
        </div>
    </div>
</section>
@endsection
@section('scripts')
@vite(['resources/js/index.js'])
<script>
function joinPartyList(partyId) {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    Swal.fire({
        title: "Do you confirm",
        text: "You will be added to the party list",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, add me!",
        template: '#my-template',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
            url: '/api/user/party',
            method: 'POST',
            data: JSON.stringify({partyId: partyId}),
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            xhrFields: {
                withCredentials: true
                },
            contentType: 'application/json; charset=utf-8',
            success: function(data){
                Swal.fire({
                    title: 'You are now added to the party list!',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'See More',
                    cancelButtonText: 'Go To My Party List',
                    customClass: {
                        confirmButton: 'btn btn-secondary mx-3',  // optional custom styling
                        cancelButton: 'btn btn-primary mx-3'
                    },
                    buttonsStyling: false // use this to avoid default SweetAlert2 button styling
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Action for "See More" button
                        runConfetti();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Action for "Go to My Parties" button
                        window.location.href = '{{ route("dashboard") }}'; // replace with your "My Parties" URL
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error while making request: ', error);
                Swal.fire({
                    title: xhr.responseJSON.message,
                    icon: 'error',
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
                    }
                });
            }
        });
        }
    });
}
</script>
@endsection