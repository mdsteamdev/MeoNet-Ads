// ads.js
function loadAds() {
  const overlay = document.createElement("div");
  overlay.id = "overlay";
  overlay.style.cssText = `display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 999;`;

  const popup = document.createElement("div");
  popup.id = "popup";
  popup.style.cssText = `background-color: white; border: none; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); position: relative; z-index: 2; animation: fadeIn 0.5s ease-out; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; max-width: 467px; margin-top: 20px;`;

  const closeBtn = document.createElement("span");
  closeBtn.id = "closeBtn";
  closeBtn.innerHTML = '<i class="fas fa-times"></i>';
  closeBtn.style.cssText = `position: absolute; top: 10px; right: 10px; cursor: pointer; font-size: 30px; color: #555; border: none; padding: 0px 10px; border-radius: 50%; transition: opacity 0.3s; z-index: 999; height: 38px; line-height: 38px; text-align: center; opacity: 0.7;`;
  closeBtn.onclick = function () {
    overlay.style.display = "none";
  };

  const adsText = document.createElement("span");
  adsText.id = "adsText";
  adsText.textContent = "Quảng cáo bởi MeoNet Ads";
  adsText.style.cssText = `position: absolute; top: 10px; left: 10px; font-size: 9.5pt; color: #888; z-index: 999; padding: 2px 5px; border-radius: 3px; opacity: 0.7; transition: opacity 0.3s;`;

  const imageLink = document.createElement("a");
  imageLink.id = "imageLink";

  const randomImage = document.createElement("img");
  randomImage.id = "randomImage";
  randomImage.alt = "Random Image";

  const disableToday = document.createElement("div");
  disableToday.id = "disableToday";
  disableToday.style.cssText = `display: flex; align-items: center; justify-content: center; padding: 10px;`;

  const disableTodayCheckbox = document.createElement("input");
  disableTodayCheckbox.type = "checkbox";
  disableTodayCheckbox.id = "disableTodayCheckbox";
  disableTodayCheckbox.style.marginRight = "5px";

  const disableTodayLabel = document.createElement("label");
  disableTodayLabel.htmlFor = "disableTodayCheckbox";
  disableTodayLabel.textContent = "Tắt hiển thị quảng cáo trong hôm nay";

  disableToday.appendChild(disableTodayCheckbox);
  disableToday.appendChild(disableTodayLabel);

  imageLink.appendChild(randomImage);
  popup.appendChild(closeBtn);
  popup.appendChild(adsText);
  popup.appendChild(imageLink);
  popup.appendChild(disableToday);
  overlay.appendChild(popup);
  document.body.appendChild(overlay);

  const disableTodayCheckboxElement = document.getElementById("disableTodayCheckbox");
  const today = new Date().toLocaleDateString();
  const popupShown = sessionStorage.getItem("popupShown-" + today);

  if (!popupShown || (popupShown === "true" && disableTodayCheckboxElement.checked === false)) {
    overlay.style.display = "flex";

    const imageData = [
      { src: "https://mlnk.vn/themes/Ads/Popup/MLApp_350x496.jpg", link: "https://mlnk.vn/#", targetBlank: true },
      { src: "https://mlnk.vn/themes/Ads/Popup/diadao-poster.jpg", link: "https://mlnk.vn/#", targetBlank: false },
      { src: "https://mlnk.vn/themes/Ads/Popup/mickey17-poster.jpg", link: "https://mlnk.vn/Mickey17-MoMo", targetBlank: true },
      { src: "https://mlnk.vn/themes/Ads/Popup/qrcheck350_469.jpg", link: "https://mlnk.vn/tools/qr-code-reader", targetBlank: false },
      { src: "https://mlnk.vn/themes/Ads/Popup/lana-poster.jpg", link: "https://safehandsexpress.com", targetBlank: true }
    ];

    const randomIndex = Math.floor(Math.random() * imageData.length);
    const selectedImage = imageData[randomIndex];

    randomImage.src = selectedImage.src;
    randomImage.dataset.link = selectedImage.link;
    imageLink.href = selectedImage.link;

    if (selectedImage.targetBlank) {
      imageLink.target = "_blank";
    } else {
      imageLink.target = "_self";
    }

    randomImage.onload = function () {
      const colorThief = new ColorThief();
      const dominantColor = colorThief.getColor(randomImage);
      const rgbColor = `rgb(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]})`;

      closeBtn.style.backgroundColor = rgbColor;
      adsText.style.backgroundColor = `rgba(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]}, 0.8)`;
      disableToday.style.backgroundColor = `rgba(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]}, 0.2)`;
      disableToday.parentElement.style.backgroundColor = `rgba(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]}, 0.2)`;
    };

    disableTodayCheckboxElement.addEventListener("change", function () {
      if (this.checked) {
        sessionStorage.setItem("popupShown-" + today, "false");
      } else {
        sessionStorage.setItem("popupShown-" + today, "true");
      }
    });

    if (popupShown === "false") {
      disableTodayCheckboxElement.checked = true;
    } else {
      disableTodayCheckboxElement.checked = false;
    }
    sessionStorage.setItem("popupShown-" + today, "true");
  }
}

document.addEventListener("DOMContentLoaded", loadAds);
