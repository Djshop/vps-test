function account() {
    //Js pour afficher le bon contenu
    document.getElementById("adressEditForm").style.display = "none";
    document.getElementById("passwordEditForm").style.display = "none";
    document.getElementById("verifiedForm").style.display = "none";
    document.getElementById("userInfoAccount").style.animationName = "opacityUp";
    document.getElementById("userInfoAccount").style.display = "flex";
    // Js pour changez le style du bouton cliquez
    document.getElementById("adressChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("adressChoice").style.color = "#efefef";
    document.getElementById("passwordChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("passwordChoice").style.color = "#efefef";
    if (document.getElementById("verifiedChoice")) {
        document.getElementById("verifiedChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
        document.getElementById("verifiedChoice").style.color = "#efefef";
    }
    document.getElementById("accountChoice").style.boxShadow = "#f66b0e91 0px 5px 15px";
    document.getElementById("accountChoice").style.color = "#f66b0e";

}
function adress() {
    //Js pour afficher le bon contenu
    document.getElementById("userInfoAccount").style.display = "none";
    document.getElementById("passwordEditForm").style.display = "none";
    document.getElementById("verifiedForm").style.display = "none";
    document.getElementById("adressEditForm").style.animationName = "opacityUp";
    document.getElementById("adressEditForm").style.display = "flex";
    // Js pour changez le style du bouton cliquez
    document.getElementById("accountChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("accountChoice").style.color = "#efefef";
    document.getElementById("passwordChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("passwordChoice").style.color = "#efefef";
    if (document.getElementById("verifiedChoice")) {
        document.getElementById("verifiedChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
        document.getElementById("verifiedChoice").style.color = "#efefef";
    }
    document.getElementById("adressChoice").style.boxShadow = "#f66b0e91 0px 5px 15px";
    document.getElementById("adressChoice").style.color = "#f66b0e";
}
function password() {
    //Js pour afficher le bon contenu
    document.getElementById("adressEditForm").style.display = "none";
    document.getElementById("userInfoAccount").style.display = "none";
    document.getElementById("verifiedForm").style.display = "none";
    document.getElementById("passwordEditForm").style.animationName = "opacityUp";
    document.getElementById("passwordEditForm").style.display = "flex";
    // Js pour changez le style du bouton cliquez
    document.getElementById("adressChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("adressChoice").style.color = "#efefef";
    document.getElementById("accountChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("accountChoice").style.color = "#efefef";
    if (document.getElementById("verifiedChoice")) {
        document.getElementById("verifiedChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
        document.getElementById("verifiedChoice").style.color = "#efefef";
    }
    document.getElementById("passwordChoice").style.boxShadow = "#f66b0e91 0px 5px 15px";
    document.getElementById("passwordChoice").style.color = "#f66b0e";
}
function verified() {
    //Js pour afficher le bon contenu
    document.getElementById("adressEditForm").style.display = "none";
    document.getElementById("passwordEditForm").style.display = "none";
    document.getElementById("userInfoAccount").style.display = "none";
    document.getElementById("verifiedForm").style.animationName = "opacityUp";
    document.getElementById("verifiedForm").style.display = "flex";
    // Js pour changez le style du bouton cliquez
    document.getElementById("adressChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("adressChoice").style.color = "#efefef";
    document.getElementById("passwordChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("passwordChoice").style.color = "#efefef";
    document.getElementById("accountChoice").style.boxShadow = "rgb(0 0 0 / 40%) 0px 2px 4px, rgb(0 0 0 / 30%) 0px 7px 13px -3px, rgb(0 0 0 / 20%) 0px -3px 0px inset";
    document.getElementById("accountChoice").style.color = "#efefef";
    document.getElementById("verifiedChoice").style.boxShadow = "#f66b0e91 0px 5px 15px";
    document.getElementById("verifiedChoice").style.color = "#f66b0e";
}
