export class Parties{
    constructor(){
        this.Parties = [];
    }

    static makeAuthenticatedRequest(url, method, data='', successFunction=() => {Parties.getUserParties()}){
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                method: method,
                data: JSON.stringify(data),
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                xhrFields: {
                    withCredentials: true
                 },                       
                contentType: 'application/json; charset=utf-8',
                success: function(data){
                    resolve(data);
                    successFunction();
                },
                error: function (xhr, status, error) {
                    console.error('Error while making request: ', error);
                    reject(error);
                }
            });
        });
    }

    static getParties() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/api/party',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    resolve(data); // Resolve the promise with the party data
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching Parties:', error);
                    reject(error); // Reject the promise with the error
                }
            });
        });
    }
    static getUserParties(type){
        return Parties.makeAuthenticatedRequest('/api/user/'+type, "GET", {}, ()=>{});
    }
    static removeUserFromParty(partyId, successFunction){
        return Parties.makeAuthenticatedRequest('/api/user/party', 'DELETE', {partyId:partyId}, successFunction);
    }
}