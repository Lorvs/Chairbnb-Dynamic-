function zoomImage(image) {
    const modal=document.getElementById("imageModal");
    const zoomed=document.getElementById("zoomedImage");
    if(modal && zoomed){modal.style.display="flex";zoomed.src=image.src;}
}
function closeImage(){const modal=document.getElementById("imageModal");if(modal)modal.style.display="none";}
