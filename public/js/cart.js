function cartValidate() {
   

    document.getElementById("titleCart").style.display = "none" ;
    var x = document.getElementsByClassName("cartRecap");
    var i;
    for (i = 0; i < x.length; i++) {
        x[i].style.display = 'none';
    }

    document.getElementById("subTotal").style.display = "none" ;
    if(document.getElementById("errorsCart")){

        document.getElementById("errorsCart").style.display = "none" ;
    }

    document.getElementById("validateBtn").style.display = "none" ;
    document.getElementById("deleteAllCartId").style.display = "none" ;
    document.getElementById("stepOne").style.display = "none" ;
    document.getElementById("methodCart").style.display = "none" ;
    document.getElementById("keepCart").style.display = "none" ;
    document.getElementById("keepAdress").style.display = "block" ;
    document.getElementById("deleveryService").style.display = "block" ;
    document.getElementById("stepTwo").style.display = "flex" ;
    document.getElementById("titleAdress").style.display = "block" ;
    document.getElementById("adressBtn").style.display = "block" ;
    document.getElementById("adressRecap").style.display = "flex" ;
 
    
    
    
    
}
function breadcrumbCart() {
    document.getElementById("stepTwo").style.display = "none" ;
    document.getElementById("titleAdress").style.display = "none" ;
    document.getElementById("adressBtn").style.display = "none" ;
    document.getElementById("adressRecap").style.display = "none" ;
    document.getElementById("deleveryService").style.display = "none" ;
    document.getElementById("keepAdress").style.display = "none" ;
    document.getElementById("keepCart").style.display = "block" ;
    document.getElementById("methodCart").style.display = "flex" ;

    document.getElementById("titleCart").style.display = "block" ;
    var x = document.getElementsByClassName("cartRecap");
    var i;
    for (i = 0; i < x.length; i++) {
        x[i].style.display = 'flex';
    }
    
    document.getElementById("subTotal").style.display = "flex" ;
    document.getElementById("deleteAllCartId").style.display = "flex" ;
    
    document.getElementById("stepOne").style.display = "flex" ;
    document.getElementById("validateBtn").style.display = "block" ;
    
}

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
