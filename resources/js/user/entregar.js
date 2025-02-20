import { User } from '../utilities/user';
import { SwalDialog } from '../utilities/dialog';


const MAX_DESCRIPTION_LENGTH = 40;
const MAX_TITLE_LENGTH = 20;


export function loadUserRequests(){
    let userProducts = $('#user-products');

    User.getUserRequests().then(function (response){
        let html = '';
        response.data.forEach(requisicao => {
            html += `
            <div class="col-12">
                <div class="card mb-3 w-100">
                    <div class="row g-0">
                        <div class="card-body d-flex justify-content-between">
                            <div class="col-md-8">
                                <h5 class="card-title fs-5 text-capitalize">${requisicao.title}</h5>
                                <p class="card-text">Total de equipamentos requisitados: ${requisicao.quantity}</p>
                            </div>
                            <div class="col-md-4">
                                <button class="w-100 btn btn-danger my-2" id="delete-requisicao-${requisicao.id}">Anular Requisição</button>
                                <button class="w-100 btn btn-success my-2" data-toggle="modal" id="qr-modal-${requisicao.id}">
                                    <i class="bi bi-qr-code"></i>
                                    QR Code
                                </button>
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#products-${requisicao.id}" aria-expanded="false" aria-controls="products-${requisicao.id}">
                                    arrow down
                                </button>
                            </div>
                        </div>
                        <div class="collapse" id="products-${requisicao.id}">
                            <div class="card card-body" id="products-${requisicao.id}-div">
                            ${requisicao.base_products.map((product) => 
                                `<div class="d-flex w-100 justify-content-between">
                                    <div class="col-md-9">
                                        <h6 class="card-subtitle mb-2 text-muted">${product.name}</h6>
                                        <p class="card-text">${product.details}</p>
                                    </div>
                                    <div class="col-md-3">
                                        Quantidade Requisitado: ${product.quantity}
                                    </div>
                                </div>`)}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        });
        userProducts.html(html);
        response.data.forEach(requisicao => {
            $('#qr-modal-' + requisicao.id).on('click', function (event) {
                let qrCode = new QRCode(this.querySelector('.modal-body'), requisicao.qrCode);
            });
            $(`#delete-requisicao-${requisicao.id}`).on('click', function (event){
                SwalDialog.confirm('Tem certeza que deseja anular a requisição?', {
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                }).then((result) => {
                    if (result.isConfirmed) {
                        User.deleteRequisicao(requisicao.id).then((response) => {
                            if(response.success){
                                loadUserRequests();
                            }
                        });
                    }
                });
            })
        })
    });
}
loadUserRequests();
let showMessage = $('meta[name="showDeliveryMessage"]').attr('content');
if(showMessage == 1){
    SwalDialog.info('A entrega de produtos é feita pelos funcionários!', '',
        () => {},
        () => {},
    
        {
            html: `
            <p class="fs-5 fw-bold">Para cada requisição que fez, tem um código QR associado.<br> Basta apresentar ao funcionário responsável e o resto será tratado pelo mesmo.</p><br>
            <div class="col-sm-12">
                <form class="form" action="/api/user/deliveryMessage" method="post">
                    <div class="input-group input-group-sm">
                        <input type="checkbox" class="form-check-input" id="showDeliveryMessage" value="0"/>
                        <label class="checkbox-inline fw-normal fs-5" id="showDeliveryMessageLabel" for="showDeliveryMessage">Não Mostrar novamente</label>
                    </div>
                </form>
            </div>`,
            preConfirm: () => {
                const checkbox = document.getElementById("showDeliveryMessage");
                if (!checkbox.checked) {
                    return true;
                }
                let showForm = new FormData();
                showForm.append('showDeliveryMessage', false);
                // If checked, send a request
                let status = User.changeDeliveryMessage(showForm).then((response) => { return response.success})
                return status
                .then(response => {
                    if (!response) {
                        throw new Error(response.statusText);
                    }
                    return response;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            }
        },
    );
}