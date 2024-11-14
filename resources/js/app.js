import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';

window.Alpine = Alpine;

Alpine.start();

function hideLoading(timeout) {
    setTimeout(() => {
        $('#loader').fadeOut(500, () => {
            $('#loader').css('visibility', 'hidden');
        });
    }, timeout);
}
function showLoading(timeout) {
    setTimeout(() => {
        $('#loader').fadeIn(timeout, () => {
            $('#loader').css('visibility', 'visible');
        });
    }, timeout);
}

if ( $.active > 0){
    $(document).ready(function() {
        // Show loader when any AJAX request is sent
        $(document).ajaxStart(function() {
            showLoading(100);
        });
    
        // Hide loader when any AJAX request completes
        $(document).ajaxStop(function() {
            hideLoading(500);
        });
    
        // Optional: Hide loader on AJAX error
        $(document).ajaxError(function() {
            hideLoading(200);
        });
    });
}else{
    hideLoading(200)
    console.log('No ajax requests');
}