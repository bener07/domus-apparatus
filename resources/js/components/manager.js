import {API} from '../utilities/api';
import { SwalDialog } from './dialog';



export class Modal {

    constructor(endpoint, html, title, didOpen = () => {}, preConfirm = () => {}, confirm = () => {}){
        this.endpoint = endpoint;
        this.html = html;
        this.title = title;
        this.didOpen = didOpen;
        this.preConfirm = preConfirm;
        this.confirm = confirm;
    }

    build(){
        SwalDialog.defaultAlert(
            '',
            this.title,
            '',
            (result) => this.confirm(result),
            () => {},
            {
                html: this.html,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Salvar',
                customClass: 'swal-form',
                didOpen: this.didOpen,
                preConfirm: this.preConfirm,
            }
        );
    }
}