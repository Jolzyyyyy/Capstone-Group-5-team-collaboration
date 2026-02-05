/**
 * Printify & Co. - Core JavaScript
 * FULL UPDATED: Added SINTRA BOARD PRINTING under Large Format
 * VERSION: 5.3 (Document Printing 3-Slides Update)
 */

let heroIndex = 0;
const heroSlides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.dot');
let slideInterval = setInterval(nextHeroSlide, 8000); 

// Initialize Cart from LocalStorage
let cart = JSON.parse(localStorage.getItem('printCart')) || [];
let voucherDiscount = 0;
let currentCategoryType = ""; 

// --- HERO SECTION ---
function updateHero() {
    if (heroSlides.length === 0) return;
    heroSlides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    
    if (heroSlides[heroIndex]) heroSlides[heroIndex].classList.add('active');
    if (dots[heroIndex]) dots[heroIndex].classList.add('active');
}

function nextHeroSlide() {
    if (heroSlides.length === 0) return;
    heroIndex = (heroIndex + 1) % heroSlides.length;
    updateHero();
}

function jumpToHero(index) {
    clearInterval(slideInterval);
    heroIndex = index;
    updateHero();
    slideInterval = setInterval(nextHeroSlide, 8000); 
}

// --- DATA DEFINITION ---
const allData = {
    'doc': { 
        name: "DOCUMENT PRINTING", 
        type: "printing",
        categories: [
            {
                name: "Text Only", 
                // 3 SLIDES ADDED
                imgs: ["images/TXTONLY (B&W).png", "images/TXTONLY (PC).png", "images/TXTONLY (FC).png"], 
                specs: "Paper: Bond Paper, 80gsm. Standard document printing for reports and letters." 
            },
            {
                name: "Text with Image", 
                // 3 SLIDES ADDED
                imgs: ["images/TXTWI (B&W).png", "images/TXTWI (PC).png", "images/TXTWI (FC).png"], 
                specs: "Paper: Bond Paper, 80gsm. Mixed text & image printing." 
            },
            {
                name: "Image Only", 
                // 3 SLIDES ADDED
                imgs: ["images/IO (B&W).png", "images/IO (PC).png", "images/IO (FC).png"], 
                specs: "Paper: Bond Paper, 80gsm. High-ink coverage for documents with graphics." 
            }
        ]
    },
    'photo': { 
        name: "PHOTOCOPY & SCANNING", 
        type: "xerox",
        categories: [
            {
                name:  "B&W Photocopy",
                imgs: ["images/PHOTOC (FC).png"], 
                specs: "Standard 80gsm Copy Paper. Fast and clear duplication." 
            },
            {
                name: "Partial Color Copy", 
                imgs: ["images/PHOTOC (PC).png"], 
                specs: "Standard 80gsm. Best for forms with small colored logos or text." 
            },
            {
                name: "Full Color Copy",
                imgs: ["images/PHOTOC (B&W).png"], 
                specs: "Standard 80gsm. Full vibrant color duplication."
            }
        ]
    },
    'id': { 
        name: "ID & PHOTO SERVICES", 
        type: "id", 
        categories: [
            {
                name: "PACKAGE", 
                // 6 SLIDES
                imgs: ["images/PCKGA.png", "images/PCKGB.png", "images/PCKGC.png", "images/PCKGD.png", "images/PCKGE.png", "images/PCKGF.png"], 
                specs: "Best value bundles for applications and school." 
            },
            {
                name: "SINGLE PHOTO", 
                // 2 SLIDES
                imgs: ["images/SP (2-5).png", "images/SP (6-A4).png"], 
                specs: "High-quality prints for frames and memories." 
            }
        ]
    },
    'largeformat': {
        name: "LARGE FORMAT PRINTING",
        type: "largeformat",
        categories: [
            {
                name: "SINTRA BOARD PRINTING",
                imgs: ["sintra1.jpg"],
                specs: "Material: Sintra Board (3mm Flat PVC), A4 Size.<br>Durable, moisture-resistant, and lightweight PVC foam board.<br>Smooth surface direct print, intended for indoor display."
            }
        ]
    }
};

// --- PRICING DATABASES ---

const printingPricing = {
    text_only: {
        bw: { short: [3.50, 3.00], a4: [4.00, 3.00], legal: [5.00, 4.00] },
        partial: { short: [5.00, 4.50], a4: [6.00, 5.00], legal: [7.00, 6.00] },
        full: { short: [10.00, 9.00], a4: [12.00, 10.00], legal: [14.00, 12.00] }
    },
    text_image: {
        bw: { short: [4.50, 4.00], a4: [5.00, 4.00], legal: [6.00, 5.00] },
        partial: { short: [7.00, 6.00], a4: [8.00, 7.00], legal: [9.00, 8.00] },
        full: { short: [13.00, 12.00], a4: [15.00, 13.00], legal: [17.00, 15.00] }
    },
    image_only: {
        bw: { short: [8.00, 7.00], a4: [10.00, 9.00], legal: [12.00, 11.00] },
        partial: { short: [13.00, 12.00], a4: [15.00, 13.00], legal: [18.00, 16.00] },
        full: { short: [20.00, 18.00], a4: [25.00, 22.00], legal: [30.00, 25.00] }
    }
};

const xeroxPricing = {
    text_only: { bw: { short: [2.00, 1.50], a4: [2.50, 2.00], legal: [3.00, 2.00] } },
    text_image: { bw: { short: [4.00, 3.50], a4: [5.00, 4.00], legal: [6.00, 5.00] } },
    image_only: { bw: { short: [8.00, 7.00], a4: [10.00, 9.00], legal: [12.00, 11.00] } }
};

const idPricing = {
    'PACKAGE': { 'Package A': 60.00, 'Package B': 40.00, 'Package C': 50.00, 'Package D': 60.00, 'Package E': 45.00, 'Package F': 60.00 },
    'SINGLE PHOTO': { '2R': 5.00, '3R': 8.00, '4R': 12.00, '5R': 15.00, '6R': 25.00, '8R': 40.00, 'A4': 45.00 }
};

const sintraPricing = {
    'Glossy': 100.00, 'Matte': 100.00, 'Leather': 110.00, 'Canvas Matte': 110.00,
    'Glittered': 120.00, '3D': 120.00, 'Rainbow': 130.00, 'Broken Glass': 130.00
};

const idDetails = {
    'Package A': "Inclusions: 4pcs 2x2 & 8pcs 1x1",
    'Package B': "Inclusions: 8pcs 1x1",
    'Package C': "Inclusions: 8pcs 2x2",
    'Package D': "Inclusions: 5pcs Passport Size",
    'Package E': "Inclusions: 6pcs 1.5x1.5",
    'Package F': "Inclusions: 5pcs Wallet Size"
};

let currentCategorySet = [];
let currentPreviewIndex = 0;
let currentSlideIndex = 0;

// --- NAVIGATION ---
function jumpTo(id) {
    const detailSection = document.getElementById('productDetail');
    if(detailSection && detailSection.style.display === 'block') {
        backToMain();
        setTimeout(() => {
            const target = document.getElementById(id);
            if(target) window.scrollTo({ top: target.offsetTop - 70, behavior: 'smooth' });
        }, 100);
    } else {
        const target = document.getElementById(id);
        if(target) window.scrollTo({ top: target.offsetTop - 70, behavior: 'smooth' });
    }
}

// --- MODAL & DETAIL LOGIC ---
function openModal(key) {
    const data = allData[key] || { name: "PRINTING SERVICE", categories: [] };
    currentCategoryType = data.type; 
    
    const modalTitle = document.getElementById('modalTitle');
    const track = document.getElementById('categoryTrack');
    
    if (modalTitle) modalTitle.innerText = data.name;
    if (track) {
        track.innerHTML = '';
        currentCategorySet = data.categories;
        currentCategorySet.forEach((cat, index) => {
            track.innerHTML += `
                <div class="category-card">
                    <img src="${cat.imgs[0]}">
                    <h4>${cat.name}</h4>
                    <p onclick="openDetail(${index})">SELECT TYPE</p>
                </div>`;
        });
        currentSlideIndex = 0;
        track.style.transform = `translateX(0)`;
        updateModalButtons();
    }
    const modal = document.getElementById('productModal');
    if (modal) modal.classList.add('active');
}

function moveSlide(dir) {
    const track = document.getElementById('categoryTrack');
    const total = currentCategorySet.length;
    currentSlideIndex += dir;
    if (currentSlideIndex < 0) currentSlideIndex = 0;
    if (currentSlideIndex >= total) currentSlideIndex = total - 1;
    track.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
    updateModalButtons();
}

function updateModalButtons() {
    const prev = document.getElementById('modalPrev');
    const next = document.getElementById('modalNext');
    if (prev) prev.style.display = (currentSlideIndex === 0) ? 'none' : 'flex';
    if (next) next.style.display = (currentSlideIndex >= currentCategorySet.length - 1) ? 'none' : 'flex';
}

function openDetail(index) {
    const cat = currentCategorySet[index];
    if (!cat) return;

    document.getElementById('detailTitleHeader').innerText = cat.name;
    document.getElementById('productSpecs').innerHTML = cat.specs; 
    
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const paperSize = document.getElementById('paperSize');

    if (currentCategoryType === 'printing' || currentCategoryType === 'xerox') {
        printCategory.innerHTML = `
            <option value="text_only">Text Only</option>
            <option value="text_image">Text with Image</option>
            <option value="image_only">Image Only</option>`;
        
        paperSize.innerHTML = `
            <option value="short">Short (8.5 x 11)</option>
            <option value="a4">A4 (8.27 x 11.69)</option>
            <option value="legal">Legal (8.5 x 14)</option>`;
        
        colorMode.innerHTML = `
            <option value="bw">B&W</option>
            <option value="partial">Partial Color</option>
            <option value="full">Full Color</option>`;

        if (cat.name.includes("Text Only") || cat.name.includes("B&W")) {
            printCategory.value = "text_only"; colorMode.value = "bw";
        } else if (cat.name.includes("Partial")) {
            printCategory.value = "text_image"; colorMode.value = "partial";
        } else if (cat.name.includes("Full")) {
            printCategory.value = "image_only"; colorMode.value = "full";
        }
    }

    if (currentCategoryType === "id") {
        updateDropdownsForID(cat.name);
    }

    if (currentCategoryType === "largeformat") {
        updateDropdownsForLargeFormat(cat.name);
    }

    const previewTrack = document.getElementById('previewTrack');
    previewTrack.innerHTML = '';
    cat.imgs.forEach(imgSrc => { 
        previewTrack.innerHTML += `<img src="${imgSrc}" style="min-width:100%; height:100%; object-fit:contain;">`; 
    });
    
    currentPreviewIndex = 0;
    previewTrack.style.transform = `translateX(0)`;
    updatePreviewButtons();
    
    const sidebarTrack = document.getElementById('sidebarTrack');
    sidebarTrack.innerHTML = '';
    currentCategorySet.forEach((sidebarCat, idx) => {
        sidebarTrack.innerHTML += `
            <div class="sidebar-item ${idx === index ? 'active' : ''}" onclick="openDetail(${idx})">
                <img src="${sidebarCat.imgs[0]}">
                <p>${sidebarCat.name}</p>
            </div>`;
    });

    document.getElementById('productModal').classList.remove('active');
    document.getElementById('pageWrapper').style.display = 'none'; 
    document.getElementById('productDetail').style.display = 'block'; 
    document.getElementById('mainHeader').classList.add('detail-active');
    
    updatePrice();
    window.scrollTo(0, 0); 
}

function updateDropdownsForID(categoryName) {
    const paperSize = document.getElementById('paperSize');
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');

    paperSize.innerHTML = ''; 
    
    if (categoryName === "PACKAGE") {
        const pkgOptions = ['Package A', 'Package B', 'Package C', 'Package D', 'Package E', 'Package F'];
        pkgOptions.forEach(opt => {
            let el = document.createElement('option');
            el.value = opt; el.textContent = opt;
            paperSize.appendChild(el);
        });
    } else if (categoryName === "SINGLE PHOTO") {
        const photoOptions = [
            {val: '2R', label: '2R (2.5 x 3.5 inches)'},
            {val: '3R', label: '3R (3.5 x 5.0 inches)'},
            {val: '4R', label: '4R (4.0 x 6.0 inches)'},
            {val: '5R', label: '5R (5.0 x 7.0 inches)'},
            {val: '6R', label: '6R (6.0 x 8.0 inches)'},
            {val: '8R', label: '8R (8.0 x 10.0 inches)'},
            {val: 'A4', label: 'A4 (8.27 x 11.69 inches)'}
        ];
        photoOptions.forEach(opt => {
            let el = document.createElement('option');
            el.value = opt.val; el.textContent = opt.label;
            paperSize.appendChild(el);
        });
    }

    printCategory.innerHTML = '<option value="id_photo">Photo Services</option>';
    colorMode.innerHTML = '<option value="full">Full Color</option>';
}

function updateDropdownsForLargeFormat(categoryName) {
    const paperSize = document.getElementById('paperSize');
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');

    if (categoryName === "SINTRA BOARD PRINTING") {
        paperSize.innerHTML = '<option value="a4">A4 (8.27 x 11.69)</option>';
        printCategory.innerHTML = `
            <option value="Glossy">Finish: Glossy</option>
            <option value="Matte">Finish: Matte</option>
            <option value="Leather">Finish: Leather</option>
            <option value="Canvas Matte">Finish: Canvas Matte</option>
            <option value="Glittered">Finish: Glittered</option>
            <option value="3D">Finish: 3D</option>
            <option value="Rainbow">Finish: Rainbow</option>
            <option value="Broken Glass">Finish: Broken Glass</option>
        `;
        colorMode.innerHTML = '<option value="full">Full Color</option>';
    }
}

function updatePrice() {
    const categoryValue = document.getElementById('printCategory').value;
    const categoryName = document.getElementById('detailTitleHeader').innerText; 
    const color = document.getElementById('colorMode').value; 
    const size = document.getElementById('paperSize').value; 
    const qty = parseInt(document.getElementById('qtyInput').value) || 1;
    const priceType = document.querySelector('input[name="priceType"]:checked').value;
    const specsDisplay = document.getElementById('productSpecs');

    let retail = 0;
    let bulk = 0;

    if (currentCategoryType === "id") {
        retail = idPricing[categoryName][size] || 0;
        bulk = retail;
        
        if (categoryName === "PACKAGE") {
            const inclusion = idDetails[size] || "";
            specsDisplay.innerHTML = `Premium Quality Photo Paper (260gsm)<br><strong style="color:#e67e22;">${inclusion}</strong>`;
        } else {
            specsDisplay.innerHTML = `Premium Quality Photo Paper (260gsm)`;
        }
        
        document.getElementById('retailAmount').innerText = retail.toFixed(2);
        document.getElementById('bulkAmount').innerText = "Fixed";
    } 
    else if (currentCategoryType === "largeformat") {
        if (categoryName === "SINTRA BOARD PRINTING") {
            retail = sintraPricing[categoryValue] || 0;
            bulk = retail; 
            document.getElementById('retailAmount').innerText = retail.toFixed(2);
            document.getElementById('bulkAmount').innerText = "Fixed";
        }
    }
    else if (currentCategoryType === "xerox") {
        const pricing = xeroxPricing[categoryValue].bw[size]; 
        retail = pricing[0]; bulk = pricing[1];
        document.getElementById('retailAmount').innerText = retail.toFixed(2);
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    } 
    else if (currentCategoryType === "printing") {
        const pricing = printingPricing[categoryValue][color][size];
        retail = pricing[0]; bulk = pricing[1];
        document.getElementById('retailAmount').innerText = retail.toFixed(2);
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }

    const unitPrice = (priceType === 'retail') ? retail : bulk;
    const total = unitPrice * qty;
    document.getElementById('totalAmount').innerText = total.toLocaleString(undefined, {minimumFractionDigits: 2});
}

function movePreview(dir) {
    const track = document.getElementById('previewTrack');
    const totalImgs = track.querySelectorAll('img').length;
    currentPreviewIndex += dir;
    if (currentPreviewIndex < 0) currentPreviewIndex = 0;
    if (currentPreviewIndex >= totalImgs) currentPreviewIndex = totalImgs - 1;
    track.style.transform = `translateX(-${currentPreviewIndex * 100}%)`;
    updatePreviewButtons();
}

function updatePreviewButtons() {
    const prev = document.getElementById('detailPrevBtn');
    const next = document.getElementById('detailNextBtn');
    const totalImgs = document.querySelectorAll('#previewTrack img').length;
    if (prev) prev.style.display = (currentPreviewIndex === 0) ? 'none' : 'flex';
    if (next) next.style.display = (currentPreviewIndex >= totalImgs - 1) ? 'none' : 'flex';
}

function backToMain() {
    document.getElementById('productDetail').style.display = 'none';
    document.getElementById('pageWrapper').style.display = 'block';
    document.getElementById('mainHeader').classList.remove('detail-active');
    handleScrollIcons();
}

function changeQty(d) {
    let q = document.getElementById('qtyInput');
    let v = parseInt(q.value) + d;
    if(v < 1) v = 1; 
    q.value = v;
    updatePrice();
}

function closeModal() { 
    const modal = document.getElementById('productModal');
    if (modal) modal.classList.remove('active'); 
}

// --- CART SYSTEM ---
function toggleCart() {
    const overlay = document.getElementById('cartOverlay');
    const drawer = document.getElementById('cartDrawer');
    if (overlay) overlay.classList.toggle('active');
    if (drawer) drawer.classList.toggle('active');
    renderCart();
}

function addToCart() {
    const title = document.getElementById('detailTitleHeader').innerText;
    const size = document.getElementById('paperSize').value;
    const qty = parseInt(document.getElementById('qtyInput').value);
    const totalStr = document.getElementById('totalAmount').innerText.replace(/,/g, '');
    const firstImg = document.querySelector('#previewTrack img');

    let detailText = `Size: ${size.toUpperCase()}`;
    if (currentCategoryType === "largeformat") {
        const finish = document.getElementById('printCategory').value;
        detailText += ` | Finish: ${finish}`;
    }

    const item = {
        id: Date.now(),
        name: title,
        details: detailText,
        qty: qty,
        price: parseFloat(totalStr),
        img: firstImg ? firstImg.src : '',
        checked: true
    };
    
    cart.push(item);
    localStorage.setItem('printCart', JSON.stringify(cart));
    updateCartBadge();
    toggleCart(); 
}

function updateCartBadge() {
    const badge = document.getElementById('cartBadge');
    if (badge) badge.innerText = cart.length;
}

function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem('printCart', JSON.stringify(cart));
    updateCartBadge();
    renderCart();
}

function renderCart() {
    const list = document.getElementById('cartItemsList');
    if (!list) return;
    list.innerHTML = '';
    cart.forEach((item, index) => {
        list.innerHTML += `
            <div class="cart-item" style="display:flex; gap:10px; padding:10px; border-bottom:1px solid #eee;">
                <input type="checkbox" ${item.checked ? 'checked' : ''} onchange="toggleItemCheck(${index})">
                <img src="${item.img}" style="width:50px; height:50px; object-fit:cover;">
                <div class="cart-item-info">
                    <h4 style="margin:0; font-size:14px;">${item.name}</h4>
                    <p style="font-size:10px; color:#777; margin:2px 0;">${item.details}</p>
                    <p style="font-size:12px; margin:0;">Qty: ${item.qty} | ₱${item.price.toLocaleString()}</p>
                    <span onclick="removeFromCart(${index})" style="color:red; cursor:pointer; font-size:10px;">Remove</span>
                </div>
            </div>`;
    });
    calculateCartTotal();
}

function toggleItemCheck(index) {
    cart[index].checked = !cart[index].checked;
    localStorage.setItem('printCart', JSON.stringify(cart));
    calculateCartTotal();
}

function calculateCartTotal() {
    const drawerTotal = document.getElementById('drawerTotal');
    let subtotal = cart.reduce((acc, item) => item.checked ? acc + item.price : acc, 0);
    if (drawerTotal) drawerTotal.innerText = subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
}

function handleScrollIcons() {
    const servicesSection = document.getElementById('products');
    const navCart = document.getElementById('navCart');
    const detailView = document.getElementById('productDetail');
    if (!navCart) return;
    if ((detailView && detailView.style.display === 'block') || (servicesSection && window.scrollY >= servicesSection.offsetTop - 300)) {
        navCart.style.display = 'flex';
    } else {
        navCart.style.display = 'none';
    }
}

// --- INITIALIZE ---
document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();
    updateHero();
    window.addEventListener('scroll', handleScrollIcons);
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if(entry.isIntersecting) entry.target.classList.add('animate'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.section').forEach(s => observer.observe(s));
});