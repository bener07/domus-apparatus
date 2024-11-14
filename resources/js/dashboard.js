import { Parties } from './parties';
import Swal from 'sweetalert2';


function removeUserFromParty(partyId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You will be removed from the party list",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove!"
      }).then((result) => {
        if (result.isConfirmed) {
            Parties.removeUserFromParty(partyId, loadUsersToDiv).then((result) => {
                console.log(result);
        });
        }
      });
      
}

function loadUsersToDiv() {
    let typeDiv = $('#parties').length > 0 ? $('#parties') : $('#events')
    Parties.getUserParties(typeDiv.attr('id')).then(function (response){
        let partyDiv = "";
        response.data.forEach(party => {
            partyDiv += `
                <div class="card my-4">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="card-header">
                                Tags
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">${party.name}</h5>
                                <p class="card-text">${party.details}</p>
                                <button class="btn btn-primary" href="/party/${party.id}" id="${party.id}" data-party-id="${party.id}">Remove from Party</button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div style="width: 100%;height: 220px;background-image: url(${party.img});background-size: cover;background-repeat: no-repeat;"
                            class="img-fluid rounded-start" alt="${party.name}">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            typeDiv.on('click', `#${party.id}`, function(){
                let partyId = $(this).data('party-id');
                removeUserFromParty(partyId);
            })
        });
        typeDiv.html(partyDiv);
    });
}

$(document).ready(function() {
    loadUsersToDiv();
});
