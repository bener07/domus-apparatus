import { Parties } from './parties';

function loadProducts() {
    Parties.getParties()
    .then(function (parties) {
        parties.data.forEach(party => {
            const tagsHtml = party.tags.map(tag => `<a class="badge bg-dark text-white m-1 text-decoration-none" href="/tag/${tag.id}">${tag.name}</a>`).join(" ");
            party = `
            <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Sale badge-->
                        <div class="position-absolute flex justify-content-end flex-wrap" style="right: 0.4rem;">
                            ${tagsHtml}
                        </div>
                        <!-- party image-->
                        <a href="/party/${party.id}">
                            <img class="card-img-top" src="${ party.img }" alt="..." />
                        </a>
                        <!-- party details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- party name-->
                                <h5 class="fw-bolder">${party.name}</h5>
                                <div class="">${party.details}</div>
                                <!-- party reviews-->
                                <div class="d-flex justify-content-center small text-warning mb-2">
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                </div>
                                <!-- party price-->
                                Entry Ticket: ${party.price} €
                            </div>
                        </div>
                        <!-- party actions-->
                        <div class="card-footer pt-0 border-top-0 bg-transparent px-1">
                            <div class="text-center text-center flex justify-content-around">
                                <a class="btn btn-secondary mt-auto" href="/party/${party.id}">View Details</a>
                                <button class="btn btn-primary mt-auto fw-bold" onclick="joinPartyList(${party.id})">Join List</button>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('#parties').append(party);
        });
    });
}

$(document).ready(function () {
   loadProducts();
});