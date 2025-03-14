import { Cart } from '../utilities/cart';
import { SwalDialog } from '../components/dialog';
import { Disciplines } from '../utilities/disciplines';
import { Classes } from '../utilities/classes';
import { showLoading, hideLoading } from '../app';

export function loadCart() {
    window.pageCart = new Cart('#checkoutDiv', '#messager');
    window.pageCart.updateProducts(window.products);
}

$(document).ready(function () {
    Disciplines.loadDisciplines();
    Classes.loadClasses();
    $('#checkoutBtn').on('click', function(){
        SwalDialog.info('Introduza a Sala, disciplina e, opcionalmente, observações', '',
            (result)=>{
                let data = {
                    'room': result.value.room,
                    'discipline': result.value.discipline
                };
                if(result.value.optionalText != ''){
                    data.optional_text = result.value.optionalText;
                }
                showLoading(400);
                Cart.checkout(data).then((response)=>{
                    SwalDialog.success(response.message, response.data,
                        () => { window.location.reload() },
                        () => { window.location.reload() }
                    );
                    hideLoading(400);
                });
            },
            ()=>{},
            {
                html: `<div class="col-lg-12 d-flex flex-column">
                            <label for="swal-input-room" class="mt-2" style="display:block; margin-bottom:5px;">Sala</label>
                            <div class="d-flex flex-row rounded">
                                <select class="form-select rounded" required id="swal-input-room" name="room">
                                    <option value="" disabled selected>Selecione uma Sala</option>
                                    ${
                                        window.classes.map(room => `<option value="${room.id}">${room.location} - ${room.name}</option>`).join('')
                                    }
                                </select>
                            </div>
                            <label for="swal-input-discipline" class="mt-2" style="display:block; margin-bottom:5px;">Disciplina</label>
                            <div class="d-flex flex-row rounded">
                                <select class="form-select rounded" required id="swal-input-discipline" name="discipline">
                                    <option value="" disabled selected>Selecione uma Disciplina</option>
                                    ${
                                        window.disciplines.map(discipline => `<option value="${discipline.id}">${discipline.name}</option>`).join('')
                                    }
                                </select>
                            </div>
                            <label for="swal-input-optional-text" style="display:block; margin-top:10px; margin-bottom:5px;">Observações adicionais</label>
                            <input id="swal-input-optional-text" class="swal2-input m-0" placeholder="Ex: É de extrema importância" value="">
                        </div>`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                preConfirm: function () {
                    const room = $('#swal-input-room').val();
                    const discipline = $('#swal-input-discipline').val();
                    if (!room || !discipline) {
                        Swal.showValidationMessage('Sala e Disciplinas são campos obrigatórios!');
                        return null;
                    }
                    return {
                        room,
                        discipline,
                        optionalText: $('#swal-input-optional-text').val() ?? ''
                    };
                }
            }
        );
    });
});

loadCart();
