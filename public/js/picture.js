function myFunction(imgs) {
    
    // Get the expanded image
    var expandImg = document.getElementById("expandedImg");
    var expandFullImg = document.getElementById("expandedFullImg");
    // Get the image text
    
    // Use the same src in the expanded image as the image being clicked on from the grid
    expandImg.src = imgs.src;
    expandFullImg.src = imgs.src;
    // Use the value of the alt attribute of the clickable image as text inside the expanded image
    
    // Show the container element (hidden with CSS)
    expandImg.parentElement.style.display = "flex";
   
  }

  function addPicture() {
    document.getElementById("addPictureForm").style.display = "block" ;
 
    
}
  function closeAddForm() {
    document.getElementById("addPictureForm").style.display = "none" ;
 
    
}
function fullPicture() {
    
  document.getElementById("fullPictureContain").style.display = "flex" ;
 
    
}
function fullPictureClose() {
    
  document.getElementById("fullPictureContain").style.display = "none" ;
 
    
}