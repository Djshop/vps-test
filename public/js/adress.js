function newAdressFunction() {
    if (document.getElementById("newAdress").checked == true) {
        document.getElementById("sameAdress").checked = false;
        document.getElementById("adressNewHide").style.display = "none";
        document.getElementById("adressOldHide").style.display = "block";
    } else {
        document.getElementById("sameAdress").checked = true;
        document.getElementById("adressOldHide").style.display = "none";
        document.getElementById("adressNewHide").style.display = "block";
    }





}
function sameAdressFunction() {

    if (document.getElementById("sameAdress").checked == true) {
        document.getElementById("newAdress").checked = false;
        document.getElementById("adressOldHide").style.display = "none";
        document.getElementById("adressNewHide").style.display = "block";
    } else {
        document.getElementById("newAdress").checked = true;
        document.getElementById("adressNewHide").style.display = "none";
        document.getElementById("adressOldHide").style.display = "block";
    }




}