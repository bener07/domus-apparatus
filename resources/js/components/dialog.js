function hasParams(func){
    return typeof func === 'function' && func.length > 0;
}

export class SwalDialog{
    static success(title, text, confirmFunction = () => {}, dismissFunction = () => {}, aditionalValues = {}) {
        return SwalDialog.defaultAlert("success", title, text, confirmFunction, dismissFunction, aditionalValues);
    }

    static error(title, text, confirmFunction = () => {}, dismissFunction = () => {}, aditionalValues = {}) {
        return SwalDialog.defaultAlert("error", title, text, confirmFunction, dismissFunction, aditionalValues);
    }

    static warning(title, text, confirmFunction = () => {}, dismissFunction = () => {}, aditionalValues = {}) {
        return SwalDialog.defaultAlert("warning", title, text, confirmFunction, dismissFunction, aditionalValues);
    }

    static info(title, text, confirmFunction = () => {}, dismissFunction = () => {}, aditionalValues = {}) {
        return SwalDialog.defaultAlert("info", title, text, confirmFunction, dismissFunction, aditionalValues);
    }

    static defaultAlert(type, title, text, confirmFunction = () => {}, dismissFunction = () => {}, aditionalValues = {}) {
        return Swal.fire({
            icon: type,
            title: title,
            text: text,
            ...aditionalValues, // Spread the additional values object here
        }).then(function (result) {
            if (result.isConfirmed) {
                if(hasParams(confirmFunction)) {
                    confirmFunction(result);
                }
                confirmFunction();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                if(hasParams) {
                    dismissFunction(result)
                }
                dismissFunction();
            }
        });
    }
}