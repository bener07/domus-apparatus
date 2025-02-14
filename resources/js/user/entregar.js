import { User } from '../utilities/user';

loadUserRequests(){
    let userProducts = $('#user-products');

    User.loadUserRequests().then(function (response){
        let html = '';
        response.data.forEach(product => {
            html += `${product.name}`;
        });

    });
}