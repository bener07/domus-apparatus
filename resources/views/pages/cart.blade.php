<x-app-layout>
    <section class="h-100">
        <div class="container h-100 py-5">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-10">
                    <h3 class="fs-4">Data de requisição</h3>
                    <p class="alert alert-info">A data a <strong class="text-danger">vermelho</strong> é quando pretende requisitar os equipamentos abaixo. A data a <strong class="text-success">verde</strong> é quando pretende entregar os equipamentos</p>
                    <div class="flex-row d-flex justify-content-evenly align-items-center">
                        <h3 class="fs-3 text-danger">{{ $data_de_reserva }}</h3>
                        <i class="bi bi-arrow-right fs-1"></i>
                        <h3 class="fs-3 text-success">{{ $data_de_entrega_prevista }}</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="fw-normal fs-2 mb-0">Equipamentos</h1>
                    </div>
                    @foreach ($cart->items as $item)
                        <div class="card rounded-3 mb-4">
                            <div class="card-body p-4">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-md-2 col-lg-2 col-xl-2">
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img1.webp"
                                            class="img-fluid rounded-3" alt="Cotton T-shirt">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3">
                                        <p class="lead fw-normal mb-2">Basic T-shirt</p>
                                        <p><span class="text-muted">Size: </span>M <span class="text-muted">Color:
                                            </span>Grey</p>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                            onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                        <input id="form1" min="0" name="quantity" value="{{ $item->quantity }}" type="number"
                                            class="form-control form-control-sm" />

                                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                            onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                        <h5 class="mb-0"></h5>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                        <a href="#!" class="text-danger"><i class="fas fa-trash fa-lg"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="card mb-4">
                        <div class="card-body p-4 d-flex flex-column">
                            <div data-mdb-input-init class="form-outline flex-fill">
                                Data de requisição:
                            </div>
                            <button type="button" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-outline-info btn-lg ms-3">Alterar</button>
                            <div data-mdb-input-init class="form-outline flex-fill">
                                Data de Entrega:
                            </div>
                            <button type="button" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-outline-info btn-lg ms-3">Alterar</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <button type="button" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-primary btn-block btn-lg">
                                Fazer requisição
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</x-app-layout>
