//Fonction pour l'animation de l'ajout au panier on y incorpore deux valeur (object= le filtre de la console Slim, fat , all) (value=l'id de la xbox)
function addToCartAnim(object ,value) {

    const addToCart = 'addToCart' + object + value;
    document.getElementById(addToCart).style.marginLeft = '-100%';
    document.getElementById(addToCart).style.animationName = 'cart-slide-left';
    const addFromCartForm = 'addFromCartFormAll' + value;
    document.getElementById(addFromCartForm).submit();
   


}
//Fonction pour l'animation de la suppression au panier on y incorpore deux valeur (object= le filtre de la console Slim, fat , all) (value=l'id de la xbox)
function deleteFromCartAnim(object, value) {
    const addToCart = 'addToCart' + object + value;
    document.getElementById(addToCart).style.marginLeft = '0';
    document.getElementById(addToCart).style.animationName = 'cart-slide-right';
    const deleteFromCartForm = 'deleteFromCartFormAll' + value;
    document.getElementById(deleteFromCartForm).submit();
  


}
//Fonction pour l'animation du bouton de la suppression au panier la croix s'affiche au survol
function addCartUp(value) {
    document.getElementById(value).style.marginTop = '-84%';
    document.getElementById(value).style.animationName = 'add-cart-up';
}
// fonction inverse lorsque l'on sort du survol
function addCartDown(value) {
    document.getElementById(value).style.marginTop = '0';
    document.getElementById(value).style.animationName = 'add-cart-down';
}


//Fonction pour afficher les information de la console au clique de l'icone I on y incorpore deux valeur (object= le filtre de la console Slim, fat , all) (value=l'id de la xbox)
function infoGrow( value) {

  
        const info = 'info' + value;
        const text = 'textinfoAll' + value;
        const iconInfo = 'iconInfoAll' + value;
        const iconClose = 'closeInfoAll' + value;
        document.getElementById(info).style.width = '100%';
        document.getElementById(info).style.height = '100%';
        document.getElementById(info).style.borderRadius = '0';
        document.getElementById(info).style.animationName = 'info-grow';
        document.getElementById(iconInfo).style.display = 'none';
        document.getElementById(iconClose).style.display = 'block';
        document.getElementById(text).style.animationName = 'text-grow';
        document.getElementById(text).style.display = 'flex';
        document.getElementById(text).style.marginTop = '0';

    






}

//Fonction inverse au clique de de la croix pour fermer le menu info
function infoDown( value) {
    
    
        const info = 'info' + value;
        const text = 'textinfoAll' + value;
        const iconInfo = 'iconInfoAll' + value;
        const iconClose = 'closeInfoAll' + value;
        document.getElementById(info).style.width = '50px';
        document.getElementById(info).style.height = '50px';
        document.getElementById(info).style.borderRadius = '0px 0px 0% 100%';
        document.getElementById(info).style.animationName = 'info-down';
        document.getElementById(iconInfo).style.display = 'block';
        document.getElementById(iconClose).style.display = 'none';
        document.getElementById(text).style.animationName = 'text-down';
        document.getElementById(text).style.display = 'none';
        document.getElementById(text).style.marginTop = '-1000px';

    


}
//Fonction pour afficher les information de la console au clique de l'icone I on y incorpore deux valeur (object= le filtre de la console Slim, fat , all) (value=l'id de la xbox)
function infoGrowMobile( value) {

  
        const info = 'infoMobile' + value;
        const text = 'textinfoMobile' + value;
        const iconInfo = 'iconInfoMobile' + value;
        const iconClose = 'closeInfoMobile' + value;
        document.getElementById(info).style.width = '100%';
        document.getElementById(info).style.height = '100%';
        document.getElementById(info).style.borderRadius = '0';
        document.getElementById(info).style.animationName = 'info-grow-mobile';
        document.getElementById(iconInfo).style.display = 'none';
        document.getElementById(iconClose).style.display = 'block';
        document.getElementById(text).style.animationName = 'text-grow';
        document.getElementById(text).style.display = 'flex';
        document.getElementById(text).style.marginTop = '0';

    






}

//Fonction inverse au clique de de la croix pour fermer le menu info
function infoDownMobile( value) {
    
    
        const info = 'infoMobile' + value;
        const text = 'textinfoMobile' + value;
        const iconInfo = 'iconInfoMobile' + value;
        const iconClose = 'closeInfoMobile' + value;
        document.getElementById(info).style.width = '100px';
        document.getElementById(info).style.height = '100px';
        document.getElementById(info).style.borderRadius = '0px 0px 0% 100%';
        document.getElementById(info).style.animationName = 'info-down-mobile';
        document.getElementById(iconInfo).style.display = 'block';
        document.getElementById(iconClose).style.display = 'none';
        document.getElementById(text).style.animationName = 'text-down';
        document.getElementById(text).style.display = 'none';
        document.getElementById(text).style.marginTop = '-1000px';

    


}



