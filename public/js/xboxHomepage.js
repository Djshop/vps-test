function homepage() {
    document.getElementById("xbxLogoAnim").style.position = 'absolute';
    document.getElementById("xbxLogoAnim").style.top = '50%';
    document.getElementById("xbxLogoAnim").style.left = '50%';
    document.getElementById("xbxLogoAnim").style.transform = 'translate(-50% , -50%)';
    document.getElementById("xbxLogoAnim").style.animationName = 'absolute';
    document.getElementById("fontxbxLogo").style.backgroundColor = 'green';
    setTimeout(function () {
        document.getElementById("xbxLogoAnim").style.width = '300%';
        document.getElementById("xbxLogoAnim").style.height = '300%';
        document.getElementById("xbxTop").style.height = '130px';
        document.getElementById("xbxLeft").style.height = '300px';
        document.getElementById("xbxRight").style.height = '300px';
        document.getElementById("xbxBottom").style.height = '300px';
       
        document.getElementById("fontxbxLogo").style.height = '100%';
        document.getElementById("fontxbxLogo").style.width = '100%';
        document.getElementById("fontxbxLogo").style.animationName = 'xboxExplodeFont';
        document.getElementById("xbxTop").style.animationName = 'xboxExplodeTop';
        document.getElementById("xbxLeft").style.animationName = 'xboxExplodeOther';
        document.getElementById("xbxRight").style.animationName = 'xboxExplodeOther';
        document.getElementById("xbxBottom").style.animationName = 'xboxExplodeOther';
        document.getElementById("xbxLogoAnim").style.animationName = 'xboxExplode';
        setTimeout(function () {
            document.getElementById("homePageHide").style.animationName = 'opacityDown';
            setTimeout(function () {
                document.getElementById("homePageHide").style.display = "none";
            }, 2000);
            document.getElementById("containerShopHide").style.display = "flex";
            document.getElementById("optionHide").style.display = "flex";
            document.getElementById("footer").style.display = "flex";
            document.getElementById("up").style.display = "flex";
        }, 500);
    }, 1000);


}
function homepagePhone() {
    document.getElementById("xbxLogoAnimPhone").style.position = 'absolute';
    document.getElementById("xbxLogoAnimPhone").style.top = '50%';
    document.getElementById("xbxLogoAnimPhone").style.left = '50%';
    document.getElementById("xbxLogoAnimPhone").style.transform = 'translate(-50% , -50%)';
    document.getElementById("xbxLogoAnimPhone").style.animationName = 'absolute';
    document.getElementById("fontxbxLogoPhone").style.backgroundColor = 'green';
    setTimeout(function () {
        document.getElementById("xbxLogoAnimPhone").style.width = '300%';
        document.getElementById("xbxLogoAnimPhone").style.height = '300%';
        document.getElementById("xbxTopPhone").style.height = '630px';
        document.getElementById("xbxLeftPhone").style.height = '900px';
        document.getElementById("xbxRightPhone").style.height = '900px';
        document.getElementById("xbxBottomPhone").style.height = '900px';
       
        document.getElementById("fontxbxLogoPhone").style.height = '100%';
        document.getElementById("fontxbxLogoPhone").style.width = '100%';
        document.getElementById("fontxbxLogoPhone").style.animationName = 'xboxExplodeFontPhone';
        document.getElementById("xbxTopPhone").style.animationName = 'xboxExplodeTopPhone';
        document.getElementById("xbxLeftPhone").style.animationName = 'xboxExplodeOtherPhone';
        document.getElementById("xbxRightPhone").style.animationName = 'xboxExplodeOtherPhone';
        document.getElementById("xbxBottomPhone").style.animationName = 'xboxExplodeOtherPhone';
        document.getElementById("xbxLogoAnimPhone").style.animationName = 'xboxExplodePhone';
        setTimeout(function () {
            document.getElementById("homePageHide").style.animationName = 'opacityDown';
            setTimeout(function () {
                document.getElementById("homePageHide").style.display = "none";
            }, 2000);
            document.getElementById("containerShopHide").style.display = "flex";
            document.getElementById("optionHide").style.display = "flex";
            document.getElementById("footer").style.display = "flex";
            document.getElementById("up").style.display = "flex";
        }, 500);
    }, 1000);


}