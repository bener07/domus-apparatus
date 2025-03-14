import './bootstrap.js';
import Alpine from 'alpinejs';
import { Cart } from './components/cart.js';
import { loadProducts } from './user/requisitar.js';
// import $ from 'jquery';

window.products = loadProducts();
window.cart = new Cart('#cart_items', '#messager');
window.Alpine = Alpine;

Alpine.start();

export function hideLoading(timeout) {
    setTimeout(() => {
        $('#loader').fadeOut(500, () => {
            $('#loader').css('visibility', 'hidden');
        });
    }, timeout);
}
export function showLoading(timeout) {
    setTimeout(() => {
        $('#loader').fadeIn(timeout, () => {
            $('#loader').css('visibility', 'visible');
        });
    }, timeout);
}

if ( $.active > 1){
    $(document).ready(function() {
        // Show loader when any AJAX request is sent
        // $(document).ajaxStart(function() {
        //     showLoading(100);
        // });

        // Hide loader when any AJAX request completes
        $(document).ajaxStop(function() {
            hideLoading(500);
        });
    
        // Optional: Hide loader on AJAX error
        // $(document).ajaxError(function() {
        //     hideLoading(200);
        // });
    });
}else{
    hideLoading(200)
    console.log('No ajax requests');
}

// Configure the change of the image
// Handle click on image containers
$(document).on('click', '.image_container', function () {
    const containerId = $(this).attr('id'); // Get the container's ID
    const inputId = containerId.replace('image-container', 'userImage'); // Derive the corresponding input ID
    $(`#${inputId}`).click(); // Trigger the file input click
});

// Handle image input change
$(document).on('change', '.user-image-input', function () {
    const inputId = $(this).attr('id'); // Get the input's ID
    const containerId = inputId.replace('userImage', 'image-container'); // Derive the corresponding container ID
    const featuredId = inputId.replace('userImage', 'featured'); // Derive the corresponding featured image ID

    const file = this.files[0]; // Get the selected file
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $(`#${featuredId}`).attr('src', e.target.result); // Update the featured image src
        };

        reader.readAsDataURL(file); // Read the file as a data URL
    } else {
        alert('Por favor, carregue uma imagem válida.'); // Validation message for invalid files
    }
});