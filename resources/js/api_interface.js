export class API{
    constructor(){
        this.Products = [];
    }

    static makeAuthenticatedRequest(url, method, data='', successFunction=() => {Products.getUserProducts()}){
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
                contentType: 'application/json; charset=utf-8',
                success: function(data){
                    resolve(data);
                    successFunction(data);
                },
                error: function (xhr, status, error) {
                    console.error('Error while making request: ', error);
                    reject(error);
                }
            });
        });
    }

    static showApiErrors(apiResponse) {
        // Extract errors and message from the response
        const errors = apiResponse.data || [];
        const message = apiResponse.message || apiResponse;
    
        // Build an HTML list of errors
        const errorListHtml = errors
            .map(err => `<li>${Object.values(err).join(': ')}</li>`)
            .join('');
    
        // Show SweetAlert with the errors
        Swal.fire({
            title: 'Errors Occurred',
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