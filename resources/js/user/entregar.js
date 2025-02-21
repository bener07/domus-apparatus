import { User } from '../utilities/user';
import { SwalDialog } from '../utilities/dialog';
import QRCode from 'qrcode';


const MAX_DESCRIPTION_LENGTH = 40;
const MAX_TITLE_LENGTH = 20;


function loadRequestsToFront(response, userProducts) {
    let html = '';
    let colorStatus;
    let autorizationState;
    response.forEach(requisicao => {
        switch (requisicao.status) {
            case 'em confirmacao':
                colorStatus = 'warning';
                break;
            case 'confirmado':
                colorStatus ='success';
                break;
            case 'rejeitado':
                colorStatus ='danger';
                break;
            case 'cancelado':
                colorStatus ='info';
                break;
            default:
                colorStatus = 'secondary';
                break;
        }
        if(requisicao.autorizacao != 'em confirmacao'){
            autorizationState = 'disabled';
        }
        html += `
        <div class="col-12">
            <div class="card mb-3 w-100">
                <div class="row g-0">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between">
                        <div class="col-md-8">
                            <h5 class="card-title fs-5 text-capitalize">${requisicao.title}</h5>
                            <span class="fw-bold btn btn-${colorStatus}">${requisicao.autorizacao}</span>
                            <p class="card-text my-4">
                                Sala: <strong> ${requisicao.room.name} (${requisicao.room.location}) </strong>
                                <br>
                                Disciplina: <strong> ${requisicao.discipline.name} </strong>
                                <br>
                                Total de equipamentos requisitados: <strong>${requisicao.quantity} </strong>
                                <br>
                                Administrador responsável: <strong>${requisicao.admin}</strong>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <button class="justify-content-between d-flex w-100 btn btn-danger my-2 ${autorizationState}" id="delete-requisicao-${requisicao.id}">
                                Anular Requisição
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="justify-content-between d-flex w-100 btn btn-success my-2" data-toggle="modal" id="qr-modal-${requisicao.id}">
                                QR Code
                                <i class="bi bi-qr-code"></i>
                            </button>
                            <a class="justify-content-between d-flex w-100 btn btn-secondary" data-bs-toggle="collapse" href="#products-${requisicao.id}" aria-expanded="false" aria-controls="products-${requisicao.id}">
                                Equipamentos <i class="bi bi-arrow-down-square-fill"></i>
                            </a>
                        </div>
                    </div>
                    <div class="collapse px-3" id="products-${requisicao.id}">
                        <div class="card card-body" id="products-${requisicao.id}-div">
                        ${requisicao.base_products.map(product => 
                            `<div class="d-flex w-100 justify-content-between">
                                <div class="col-md-9">
                                    <h6 class="card-subtitle mb-2 fw-bold">${product.name}</h6>
                                </div>
                                <div class="col-md-3">
                                    Quantidade Requisitada: ${product.quantity}
                                </div>
                            </div>`).join('')}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    });
    userProducts.html(html);
    response.forEach(requisicao => {
        $('#qr-modal-' + requisicao.id).on('click', function (event) {
            // Gerar o QR Code
            QRCode.toDataURL(requisicao.qrCode, function (err, url) {
                if (err) {
                    console.error('Erro ao gerar o QR Code:', err);
                    return;
                }
        
                // Exibir o QR Code no SweetAlert
                Swal.fire({
                    title: 'QR Code',
                    html: `<img src="${url}" alt="QR Code" style="width: 200px; height: 200px;" />`, // Ajuste o tamanho conforme necessário
                    width: 'auto',
                    padding: '3em'
                });
            });
        });
        
        $(`#delete-requisicao-${requisicao.id}`).on('click', function (event){
            SwalDialog.warning('Tem certeza que deseja anular a requisição?', '',
                (result) => {
                    if (result.isConfirmed) {
                        User.deleteRequisicao(requisicao.id).then((response) => {
                            if(response.success){
                                loadUserRequests();
                            }
                        });
                    }
                },
                () => {},
                {
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                    showCancelButton: true
            });
        })
    })
    return ;
}

export function loadUserRequests(){
    User.getUserRequests().then(function (response){
        let confirmed = [];
        let requests = [];
        let denied = [];

        response.data.forEach(requisicao => {
            switch (requisicao.autorizacao) {
                case 'confirmado':
                    confirmed.push(requisicao);
                    break;
                case 'em confirmacao':
                    requests.push(requisicao);
                    break;
                case'rejeitado':
                    denied.push(requisicao);
                    break;
            }
        });
    
        if(confirmed.length > 0) {
            let userConfirmed = $('#user-confirmed');
            loadRequestsToFront(confirmed, userConfirmed); 
        }
        if(requests.length > 0) {
            let userRequests = $('#user-requests');
            loadRequestsToFront(requests, userRequests);
        }
        if(denied.length > 0) {
            let userDenied = $('#user-denied');
            loadRequestsToFront(denied, userDenied);
        }
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