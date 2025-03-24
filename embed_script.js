document.addEventListener("DOMContentLoaded", function () {
    console.log("DOMContentLoaded event fired.");

    const scriptElements = document.getElementsByTagName('script');
    const scriptElement = scriptElements[scriptElements.length - 1]; // Lấy phần tử script cuối cùng được nhúng

    if (!scriptElement) {
        console.error('Script element not found.');
        return;
    }

    const displayType = scriptElement.getAttribute('data-display-type');
    const filterType = scriptElement.getAttribute('data-filter-type');
    const filterValue = scriptElement.getAttribute('data-filter-value');

    console.log("displayType:", displayType);
    console.log("filterType:", filterType);
    console.log("filterValue:", filterValue);

    if (!displayType) {
        console.error('Required data attributes are missing.');
        return;
    }

    const overlay = document.createElement('div');
    overlay.id = 'overlay';
    overlay.style.display = 'flex';
    document.body.appendChild(overlay);
    console.log("Overlay element created and appended.");

    const popup = document.createElement('div');
    popup.id = 'popup';
    overlay.appendChild(popup);
    console.log("Popup element created and appended.");

    const closeBtn = document.createElement('span');
    closeBtn.id = 'closeBtn';
    closeBtn.innerHTML = '<i class="fas fa-times"></i>';
    closeBtn.addEventListener('click', function () {
        overlay.style.display = 'none';
    });
    popup.appendChild(closeBtn);
    console.log("Close button created and appended.");

    const adsText = document.createElement('span');
    adsText.id = 'adsText';
    adsText.textContent = 'Quảng cáo bởi MeoNet Ads';
    popup.appendChild(adsText);
    console.log("Ads text created and appended.");

    const imageLink = document.createElement('a');
    imageLink.id = 'imageLink';
    popup.appendChild(imageLink);
    console.log("Image link created and appended.");

    const randomImage = document.createElement('img');
    randomImage.id = 'randomImage';
    imageLink.appendChild(randomImage);
    console.log("Random image element created and appended.");

    const disableTodayBtn = document.createElement('button');
    disableTodayBtn.id = 'disableTodayBtn';
    disableTodayBtn.textContent = 'KHÔNG HIỂN THỊ QUẢNG CÁO TRONG NGÀY';
    disableTodayBtn.addEventListener('click', function () {
        localStorage.setItem("popupShown-" + new Date().toLocaleDateString(), "false");
        overlay.style.display = 'none';
    });
    popup.appendChild(disableTodayBtn);
    console.log("Disable today button created and appended.");

    let fetchUrl = `http://localhost:8888/get_ads.php?filter_type=${filterType}&filter_value=${filterValue}`;
    if (!filterType || !filterValue) {
        fetchUrl = "http://localhost:8888/get_ads.php";
    }

    fetch(fetchUrl)
        .then(response => response.json())
        .then(imageData => {
            console.log("Fetched image data:", imageData);
            if (imageData.length > 0) {
                const randomIndex = Math.floor(Math.random() * imageData.length);
                const selectedImage = imageData[randomIndex];
                randomImage.src = selectedImage.file_path;
                imageLink.href = selectedImage.link;

                randomImage.onload = function () {
                    console.log("Random image loaded.");
                    const colorThief = new ColorThief();
                    const dominantColor = colorThief.getColor(randomImage);
                    const rgbColor = `rgb(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]})`;

                    closeBtn.style.backgroundColor = rgbColor;
                    adsText.style.backgroundColor = `rgba(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]}, 0.8)`;
                    disableTodayBtn.style.backgroundColor = `rgba(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]}, 0.2)`;
                    popup.style.backgroundColor = `rgba(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]}, 0.2)`;
                };
            } else {
                overlay.style.display = 'none';
                console.log("No image data found, hiding overlay.");
            }
        })
        .catch(error => {
            console.error("Error fetching image data:", error);
        });

    const popupShown = localStorage.getItem("popupShown-" + new Date().toLocaleDateString());
    console.log("Popup shown:", popupShown);
    if (popupShown === "false") {
        overlay.style.display = 'none';
    } else {
        overlay.style.display = 'flex';
    }
});