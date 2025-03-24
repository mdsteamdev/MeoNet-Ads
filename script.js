function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Mở tab mặc định
document.addEventListener("DOMContentLoaded", function() {
    document.querySelector(".tablinks").click();
});

function previewImage() {
    const file = document.getElementById('file').files[0];
    const preview = document.getElementById('imagePreview');
    const reader = new FileReader();

    reader.onloadend = function() {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
}

async function createAd() {
    const form = document.getElementById('newAdForm');
    const formData = new FormData(form);
    const response = await fetch('create_ad.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.text();
    alert(result);
    location.reload();
}

async function editAd(adId) {
    const response = await fetch('edit_ad.php?id=' + adId);
    const result = await response.text();
    document.getElementById('NewAd').innerHTML = result;
    openTab(event, 'NewAd');
}

async function updateAd(adId) {
    const form = document.getElementById('editAdForm');
    const formData = new FormData(form);
    const response = await fetch('edit_ad.php?id=' + adId, {
        method: 'POST',
        body: formData
    });
    const result = await response.text();
    alert(result);
    location.reload();
}

async function deleteAd(adId) {
    const response = await fetch('delete_ad.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            id: adId
        })
    });
    const result = await response.text();
    alert(result);
    location.reload();
}

async function createPublisher() {
    const form = document.getElementById('newPublisherForm');
    const formData = new FormData(form);
    const response = await fetch('create_publisher.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.text();
    alert(result);
    location.reload();
}

async function editPublisher(publisherId) {
    const response = await fetch('edit_publisher.php?id=' + publisherId);
    const result = await response.text();
    document.getElementById('Publishers').innerHTML = result;
    openTab(event, 'Publishers');
}

async function deletePublisher(publisherId) {
    const response = await fetch('delete_publisher.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            id: publisherId
        })
    });
    const result = await response.text();
    alert(result);
    location.reload();
}

 async function generateEmbedCode() {
     const tagFilter = document.getElementById('embed_tag_filter').value;
     const response = await fetch('generate_embed_code.php', {
         method: 'POST',
         body: new URLSearchParams({
             tag: tagFilter
         })
     });
     const result = await response.text();
     document.getElementById('embed_code_result').innerHTML = result;
 }