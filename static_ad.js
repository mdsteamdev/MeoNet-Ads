(function () {
    // Kiểm tra xem FontAwesome đã được tích hợp chưa
    const fontAwesomeLink = document.querySelector('link[href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"]');
    if (!fontAwesomeLink) {
        // Nếu chưa, thêm thẻ <link> để tích hợp FontAwesome
        const link = document.createElement('link');
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
        link.rel = 'stylesheet';
        document.head.appendChild(link);
    }

    // Lấy loại hiển thị từ thuộc tính data-type
    const scriptTag = document.currentScript;
    const adType = scriptTag.getAttribute('data-type') || 'banner';

    // Gọi API để lấy danh sách quảng cáo
    fetch('get_ads.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch ads');
            }
            return response.json();
        })
        .then(ads => {
            if (!ads || ads.length === 0) {
                console.error('No ads available.');
                return;
            }

            // Chọn ngẫu nhiên một quảng cáo
            const randomAd = ads[Math.floor(Math.random() * ads.length)];

            // Gửi yêu cầu tăng lượt hiển thị cho quảng cáo
            fetch(`track_view.php?id=${randomAd.id}`)
                .then(response => {
                    if (!response.ok) {
                        console.error('Failed to track view for ad ID:', randomAd.id);
                    }
                })
                .catch(error => console.error('Error tracking view:', error));

            // Tạo badge "Quảng cáo bởi MeoNet Ads"
            const createBadge = () => {
                const badge = document.createElement('div');
                badge.style.position = 'absolute';
                badge.style.top = '5px';
                badge.style.left = '5px';
                badge.style.background = '#fff';
                badge.style.color = '#333';
                badge.style.fontSize = '10px';
                badge.style.padding = '2px 5px';
                badge.style.borderRadius = '3px';
                badge.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
                badge.style.transition = 'all 0.3s ease';
                badge.innerHTML = `<a href="https://meoden.xyz/ads" target="_blank" style="text-decoration: none; color: inherit;">Quảng cáo bởi MeoNet Ads</a>`;

                // Thêm hiệu ứng hover
                badge.addEventListener('mouseover', () => {
                    badge.style.background = '#333';
                    badge.style.color = '#fff';
                });
                badge.addEventListener('mouseout', () => {
                    badge.style.background = '#fff';
                    badge.style.color = '#333';
                });

                return badge;
            };

            if (adType === 'banner') {
                // Hiển thị banner tĩnh với kích thước 600x150
                const adContainer = document.createElement('div');
                adContainer.style.position = 'relative';
                adContainer.style.textAlign = 'center';
                adContainer.style.margin = '20px auto';
                adContainer.style.width = '600px';
                adContainer.style.height = '150px';
                adContainer.style.border = '2px solid #ccc'; // Viền nhỏ hơn
                adContainer.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
                adContainer.style.overflow = 'hidden';
                adContainer.style.borderRadius = '5px';

                adContainer.innerHTML = `
                    <a href="track_click.php?id=${randomAd.id}" target="_blank" style="display: block; width: 100%; height: 100%;">
                        <img src="${randomAd.file_path}" alt="Ad" style="width: 100%; height: 100%; object-fit: cover;">
                    </a>
                `;

                // Thêm badge vào banner
                adContainer.appendChild(createBadge());

                document.body.insertBefore(adContainer, document.body.firstChild);
            } else if (adType === 'popup') {
                // Kiểm tra cookie để chỉ hiển thị một lần trong ngày
                const hasSeenAd = document.cookie.split('; ').find(row => row.startsWith('seenAd='));
                if (!hasSeenAd) {
                    // Tạo popup quảng cáo với kích thước 450x550
                    const adPopup = document.createElement('div');
                    adPopup.id = 'ad-popup';
                    adPopup.style.position = 'fixed';
                    adPopup.style.top = '50%';
                    adPopup.style.left = '50%';
                    adPopup.style.transform = 'translate(-50%, -50%)';
                    adPopup.style.zIndex = '1000';
                    adPopup.style.background = 'white';
                    adPopup.style.padding = '10px';
                    adPopup.style.border = '2px solid #ccc'; // Viền nhỏ hơn
                    adPopup.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                    adPopup.style.width = '450px';
                    adPopup.style.height = '550px';
                    adPopup.style.textAlign = 'center';
                    adPopup.style.borderRadius = '5px';
                    adPopup.style.overflow = 'hidden';

                    // Nội dung quảng cáo
                    adPopup.innerHTML = `
                        <a href="track_click.php?id=${randomAd.id}" target="_blank" style="display: block; width: 100%; height: 100%;">
                            <img src="${randomAd.file_path}" alt="Ad" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                        <button id="close-ad" style="position: absolute; top: 10px; right: 10px; background: rgba(255, 255, 255, 0.8); border: none; font-size: 18px; cursor: pointer; color: #333; border-radius: 50%; padding: 10px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-times"></i>
                        </button>
                        <div style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); font-size: 12px; color: #333;">
                            <a href="https://meoden.xyz/ads" target="_blank" style="text-decoration: none; color: inherit;">Quảng cáo bởi MeoNet Ads</a>
                        </div>
                        <div style="position: absolute; bottom: 10px; right: 10px; font-size: 12px; color: #333; cursor: pointer;" id="hide-ad-for-day">
                            Không hiện quảng cáo trong ngày
                        </div>
                    `;

                    // Thêm popup vào trang
                    document.body.appendChild(adPopup);

                    // Xử lý nút đóng
                    document.getElementById('close-ad').addEventListener('click', () => {
                        adPopup.remove();
                    });

                    // Xử lý không hiển thị quảng cáo trong ngày
                    document.getElementById('hide-ad-for-day').addEventListener('click', () => {
                        document.cookie = 'seenAd=true; path=/; max-age=' + (24 * 60 * 60);
                        adPopup.remove();
                    });
                }
            }
        })
        .catch(error => console.error('Error fetching ads:', error));
})();