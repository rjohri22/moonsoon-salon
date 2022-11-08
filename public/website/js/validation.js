class ValidateData {
    requiredCheck(inpt) {
        if (inpt.trim() == "") {
            return false;
        } else {
            return true;
        }
    }
    onlyAlphabets(inpt) {
        var regex = /^[a-zA-Z' ']*$/;
        if (regex.test(inpt)) {
            return true;
        } else {
            return false;
        }
    }
    alphaNumeric(inpt) {
        var regex = /^[a-zA-Z0-9' '-.:/]*$/;
        if (regex.test(inpt)) {
            return true;
        } else {
            return false;
        }
    }
    primeNumeric(inpt) {
        var regex = /^[0-9.]*$/;
        if (regex.test(inpt)) {
            return true;
        } else {
            return false;
        }
    }
    validateEmail(sEmail) {
        var reEmail = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        if (!sEmail.match(reEmail)) {
            return false;
        } else {
            return true;
        }
    }
    validateMobileNumber(inpt) {
        var regex = /[6-9][0-9]{9}/gm;
        if (inpt.length !=10 ) {
            return false;
        } else if(regex.test(inpt))
        {
            return true;
        } else {
            return false;
        }
    }
    validateDLNumber(inpt) {
        var regex =
            /^(([A-Za-z]{2}[0-9]{2})( )|([A-Za-z]{2}[0-9]{2}-))((19|20)[0-9][0-9])[0-9]{7}$/g;
        if (regex.test(inpt)) {
            return true;
        } else {
            return false;
        }
    }
    validateIFSC(inpt) {
        var regex = /^([A-Za-z]){4}[0]([A-Za-z0-9]){6}/g;
        if (regex.test(inpt)) {
            return true;
        } else {
            return false;
        }
    }
    validateVehicle(inpt) {
        var regex = /^[A-Za-z]{2}[-][0-9]{1,2}[A-Za-z]{1,2}[-][0-9]{3,4}$/g;
        if (regex.test(inpt)) {
            return true;
        } else {
            return false;
        }
    }
    validateAadhar(inpt) {
        var regex = /^[2-9]{1}[0-9]{3}\s{1}[0-9]{4}\s{1}[0-9]{4}$/;
        if (regex.test(inpt)) {
            return true;
        } else {
            return false;
        }
    }
    checkPassword(inpt) {
        var regex =
            /^(?=.\d)(?=.[a-z])(?=.[A-Z])(?=.[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
        if (inpt.match(regex)) {
            return true;
        } else {
            return false;
        }
    }
}

const validate = new ValidateData();
