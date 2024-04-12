document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll(".collage-image");
    
    images.forEach(image => {
        image.addEventListener("click", function() {
            enlargeImage(this);
        });
    });
    
    function enlargeImage(image) {
        const overlay = document.createElement("div");
        overlay.classList.add("overlay");
        const enlargedImage = document.createElement("img");
        enlargedImage.src = image.src;
        enlargedImage.classList.add("enlarged-image");
        overlay.appendChild(enlargedImage);
        document.body.appendChild(overlay);
        
        overlay.addEventListener("click", function() {
            document.body.removeChild(overlay);
        });
    }
});
