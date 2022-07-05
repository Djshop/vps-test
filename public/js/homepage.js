function homepage() {
    document.getElementById("homePageHide").style.animationName = 'opacityDown';
    setTimeout(function(){
        document.getElementById("homePageHide").style.display = "none" ;
    }, 2000);
    document.getElementById("containerLoginHide").style.display = "flex" ;
    document.getElementById("footer").style.display = "flex" ;
    document.getElementById("up").style.display = "flex" ;
   
      
  }