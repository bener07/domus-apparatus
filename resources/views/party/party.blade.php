@extends('layout')
@section('title')
{{ $party->name }}
@endsection
@section('head')
    <meta name="partyId" content="{{ $party->id }}">
@endsection
@section('content')
<style>
    .img-thumbnail {
        width: 50px;
        height: 50px;
        margin: 10px 0;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .thumbnail-container {
        width: 100%;
        display: flex;
        justify-content: flex-start;
        align-items: center;

    }
    #mainImage{
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: transform 0.3s ease;
        margin: 0;
    }
</style>
<section class="py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 flex-column-reverse row">
                <div class="thumbnail-container col-lg-2">
                    <img src="{{ $party->featured_image  }}" alt="Thumbnail Featured" onclick="changeImage('{{ $party->featured_image }}')" class="img-fluid mb-3 mb-lg-0 img-thumbnail">
                    @foreach($party->images as $key => $image)
                        <img src="{{ $image  }}" alt="Thumbnail {{ $key }}" onclick="changeImage('{{ $image }}')" class="img-fluid mb-3 mb-lg-0 img-thumbnail">
                    @endforeach
                </div>
                <div class="col">
                    <img src="{{ $party->featured_image }}" alt="Featured Image" id="mainImage">
                </div>
            </div>
            <div class="col-md-8">
                <h3 class="text-center">
                    {{ $party->name }}
                </h3>
                <dl>
                    {{ $party->details }}
                </dl>
                <button class="btn btn-primary" onclick="joinPartyList()">Join Party List</button>
            </div>
        </div>
    </div>
    <!-- Be present above all else. - Naval Ravikant -->
</section>
@endsection
@section('scripts')
<script type="text/javascript">
function changeImage(img){
    $('#mainImage').attr('src', img);
}
function joinPartyList() {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let partyId = $('meta[name="partyId"]').attr('content');
    console.log(partyId);
    Swal.fire({
        title: "Do you confirm",
        text: "You will be added to the party list",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, add me!"
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
                title: "Successfully Added",
                text: "You are now part of the party list",
                icon: "success",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Okay"
            })
            },
            error: function (xhr, status, error) {
                Swal.fire({
                title: xhr.responseJSON.message,
                text: "An error occurred",
                icon: "error",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Okay"
            })
            }
        });
        }
    });
}
</script>
@endsection