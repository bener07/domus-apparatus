import { SwalDialog } from "./dialog";

export class API{
    constructor(){
        this.Products = [];
    }

    static makeAuthenticatedRequest(url, method, data='', successFunction=() => {}){
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                method: method,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                xhrFields: {
                    withCredentials: true
                 },
                contentType: data instanceof FormData ? false : 'application/json; charset=utf-8',
                processData: !(data instanceof FormData),
                success: function(data){
                    resolve(data);
                    successFunction(data);
                },
                error: function (xhr, status, error) {
                    console.error('Error while making request: ', error);
                    reject(error);
                    API.showApiErrors(xhr);
                }
            });
        });
    }

    static showApiErrors(apiResponse) {
        // Extract errors and message from the response1
        console.log(apiResponse);
        apiResponse = apiResponse.responseJSON;
        const message = apiResponse.message || apiResponse;
        const errors = apiResponse.error || [];
        
        let errorMessages = [];

        if (errors.responseJSON) {
            errorMessages = Object.values(errors.responseJSON);
        } else if (errors.responseText) {
            try {
                const parsedResponse = JSON.parse(errors.responseText);
                errorMessages = Object.values(parsedResponse);
            } catch (e) {
                errorMessages.push('An unknown error occurred.');
            }
        } else {
            errorMessages.push('An unexpected error occurred.');
        }
        
        // Build an HTML list of errors
        const errorListHtml = `<ul>${errorMessages.map(err => `<li>${err}</li>`).join('')}</ul>`;
        
        
    
        // Show SweetAlert with the errors
        Swal.fire({
            title: 'Ocorreu um erro',
            html: `
                <p>${message}</p>
                <ul style="text-align: left; color: red; margin-top: 10px;">
                    ${errorListHtml}
                </ul>
            `,
            icon: 'error',
            confirmButtonText: 'Okay'
        });
    }

    
}