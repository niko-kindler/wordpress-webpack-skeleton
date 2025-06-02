document.addEventListener('DOMContentLoaded', function() {
    let expandedImgContainer = document.createElement('div');
    expandedImgContainer.classList.add('expanded-img-container');

    let expandedImage = document.createElement('img');
    expandedImgContainer.appendChild(expandedImage);

    let expandedImgTitle = document.createElement('div');
    expandedImgTitle.classList.add('expanded-img-title');
    expandedImgContainer.appendChild(expandedImgTitle);

    let closeButton = document.createElement('button');
    closeButton.innerHTML = 'x';
    closeButton.classList.add('close-btn');
    closeButton.addEventListener('click', function(e) {
        e.stopPropagation();
        expandedImgContainer.style.display = 'none';
    });
    closeButton.addEventListener('touchend', function(e) {
        e.stopPropagation();
        expandedImgContainer.style.display = 'none';
    });
    expandedImgContainer.appendChild(closeButton);

    expandedImgContainer.addEventListener('click', function() {
        expandedImgContainer.style.display = 'none';
    });
    expandedImgContainer.addEventListener('touchend', function() {
        expandedImgContainer.style.display = 'none';
    });

    document.body.appendChild(expandedImgContainer);

    let galleryItems = document.querySelectorAll('.image-gallery li');
    if (galleryItems.length === 0) return; 
    galleryItems.forEach(item => {
        function expandImage() {
            let imageSrc = item.querySelector('img').src;
            expandedImage.src = imageSrc;

            let imageTitle = item.querySelector('.overlay-text') ? item.querySelector('.overlay-text').textContent : '';
            expandedImgTitle.textContent = imageTitle;

            expandedImgContainer.style.display = 'flex';
        }

        item.addEventListener('click', expandImage);
        item.addEventListener('touchend', expandImage);

        // Attach touchend event to the overlay as well
        let overlay = item.querySelector('.overlay');
        if (overlay) {
            overlay.addEventListener('touchend', function(e) {
                e.stopPropagation();
                expandImage();
            });
        }
    });
});
