/**
 * Printify & Co. - Core JavaScript
 * FULL UPDATED VERSION: 8.9 (STRICT COMPLETE VERSION)
 * FEATURES: Transparent Nav, Document ID Sync (TX, TWI, IM), Corrected Xerox ID Update & Auto-Slide Sync
 */

// --- GLOBAL VARIABLES ---
let heroIndex = 0;
let currentCategoryType = "";
let currentCategorySet = [];
let currentPreviewIndex = 0;
let currentSlideIndex = 0;
let voucherDiscount = 0;

let currentServiceVariations = [];

// --- DOM HELPER ---
const $id = (id) => document.getElementById(id);

// LOGIN SIMULATION
let isLoggedIn = document.body.dataset.loggedIn === "true";

const heroSlides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.dot');
let slideInterval = heroSlides.length ? setInterval(nextHeroSlide, 8000) : null;

// Initialize Cart from LocalStorage
let cart = JSON.parse(localStorage.getItem('printCart')) || [];

// --- CART STORAGE HELPER ---
function saveCart(){
    localStorage.setItem('printCart', JSON.stringify(cart));
}

// --- HERO SECTION FUNCTIONS ---
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

// --- NAVIGATION & SECTION LOGIC ---
function jumpTo(sectionId) {
    const productDetail = $id('productDetail');
    const pageWrapper = $id('pageWrapper');
    const mainHeader = $id('mainHeader');
    const target = $id(sectionId);

    if (!target) return;

    if (productDetail) productDetail.style.display = 'none';
    if (pageWrapper) pageWrapper.style.display = 'block';
    if (mainHeader) mainHeader.classList.remove('detail-active');

    document.body.classList.remove('services-active', 'about-active', 'contact-active');

    if (sectionId === 'products') {
        document.body.classList.add('services-active');
    } else if (sectionId === 'about') {
        document.body.classList.add('about-active');
    } else if (sectionId === 'contact') {
        document.body.classList.add('contact-active');
    }

    const sections = document.querySelectorAll('.section');
    sections.forEach(sec => {
        sec.classList.remove('active');
        sec.style.display = 'none';
    });

    target.style.display = 'block';

    requestAnimationFrame(() => {
        target.classList.add('active');
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    history.replaceState(null, null, '#' + sectionId);
}

window.addEventListener('scroll', function () {
    const mainHeader = $id('mainHeader');
    if (!mainHeader) return;

    if (window.scrollY > 50) {
        mainHeader.classList.add('scrolled');
// Compatibility alias used by welcome.blade.php nav links
function showSection(sectionId) {
    jumpTo(sectionId);
    return false;
}

window.addEventListener('scroll', function() {
    const mainHeader = document.getElementById('mainHeader');
    const activeSection = document.querySelector('.section.active');
    
    if (activeSection && activeSection.id === 'home') {
        if (window.scrollY > 50) {
            mainHeader.classList.add('scrolled');
        } else {
            mainHeader.classList.remove('scrolled');
        }
    } else {
        mainHeader.classList.remove('scrolled');
    }
});

function backToMain() {
    document.body.classList.add('services-active');
    jumpTo('products');
}

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
    'Package A': "Inclusions: 4pcs 2x2 & 8pcs 1x1", 'Package B': "Inclusions: 8pcs 1x1",
    'Package C': "Inclusions: 8pcs 2x2", 'Package D': "Inclusions: 5pcs Passport Size",
    'Package E': "Inclusions: 6pcs 1.5x1.5", 'Package F': "Inclusions: 5pcs Wallet Size"
};

// --- MODAL & DETAIL UI LOGIC ---
function openModal(serviceId) {
    const service = window.servicesData?.[serviceId];

    if (!service) {
        console.error('Service not found for ID:', serviceId);
        return;
    }

    currentServiceVariations = service.variations || [];
    currentCategoryType = (service.category || '').toLowerCase();

    const modalTitle = $id('modalTitle');
    const track = $id('categoryTrack');

    if (modalTitle) {
        modalTitle.innerText = service.name || 'PRINTING SERVICE';
    }

if (track) {
    track.innerHTML = '';
    currentCategorySet = currentServiceVariations;

    currentCategorySet.forEach((variation, index) => {

        const image = variation.image || service.image || 'images/Prdcts1.jpg';

        const serviceTitle = service.name || 'SERVICE';

        const subTitle =
            variation.package_type ||
            variation.product_size ||
            variation.finish_type ||
            variation.color_mode ||
            variation.service_item_id ||
            `Option ${index + 1}`;

        const descriptionParts = [
            variation.printing_category,
            variation.color_mode,
            variation.product_size,
            variation.finish_type
        ].filter(Boolean);

        const description = descriptionParts.join(' • ');

        track.innerHTML += `
            <div class="category-card" onclick="openDetail(${index})">

                <h4>${serviceTitle}</h4>

                <img src="${image}" alt="${subTitle}">

                <div class="category-subtitle">${subTitle}</div>

                <div class="category-description">${description || ''}</div>

                <div class="category-label">PACKAGE</div>

                <button type="button" class="select-type-btn">
                    SELECT TYPE
                </button>

            </div>
        `;
    });

    currentSlideIndex = 0;
    track.style.transform = `translateX(0)`;
    updateModalButtons();
}

    const modal = $id('productModal');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }
}

function closeModal() {
    const modal = $id('productModal');
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }
}

// ADD THIS FUNCTION HERE
function moveSlide(dir) {
    const track = document.getElementById('categoryTrack');
    if (!track) return;

    const cards = track.children.length;
    currentSlideIndex = Math.max(0, Math.min(currentSlideIndex + dir, cards - 1));

    track.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
    updateModalButtons();
}

function updateModalButtons() {
    const prev = $id('modalPrev');
    const next = $id('modalNext');
    const track = $id('categoryTrack');

    if (!prev || !next || !track) return;

    const totalCards = track.children.length;

    prev.style.display = currentSlideIndex <= 0 ? 'none' : 'flex';
    next.style.display = currentSlideIndex >= totalCards - 1 ? 'none' : 'flex';
}

function getPreviewTrack(){return $id('previewTrack');}
function getPreviewImages(){const track=getPreviewTrack();return track?Array.from(track.querySelectorAll('img')):[];}
function setPreviewIndex(index){
  const track=getPreviewTrack(),imgs=getPreviewImages();
  if(!track||imgs.length===0)return;
  currentPreviewIndex=Math.max(0,Math.min(index,imgs.length-1));
  track.style.transition='transform .35s ease';
  track.style.transform=`translateX(-${currentPreviewIndex*100}%)`;
  updatePreviewButtons();
}

function syncPreviewFromDropdowns(){
  const colorModeDropdown=$id('colorMode'),paperSizeDropdown=$id('paperSize'),detailTitle=$id('detailTitle');
  if(!detailTitle)return;
  if((currentCategoryType==='printing'||currentCategoryType==='xerox')&&colorModeDropdown){setPreviewIndex(colorModeDropdown.selectedIndex);return;}
  if(currentCategoryType==='id'&&detailTitle.innerText==='PACKAGE'&&paperSizeDropdown){setPreviewIndex(paperSizeDropdown.selectedIndex);return;}
  if(currentCategoryType==='id'&&detailTitle.innerText==='SINGLE PHOTO'&&paperSizeDropdown){setPreviewIndex(paperSizeDropdown.selectedIndex<=3?0:1);return;}
  if(currentCategoryType==='largeformat')setPreviewIndex(0);
}

function openDetail(index) {
    const variation = currentCategorySet[index];
    if (!variation) return;

    const previewTrack = document.getElementById('previewTrack');

    if (previewTrack) {
        const image = variation.image || 'images/Prdcts1.jpg';

        previewTrack.innerHTML = `
            <img src="${image}" alt="${variation.service_item_id || 'Service'}">
        `;

        currentPreviewIndex = 0;
        previewTrack.style.transform = 'translateX(0)';
    }

    document.body.classList.add('services-active');

    const detailTitle = $id('detailTitle');
    const currentServiceId = $id('currentServiceId');
    const currentServiceName = $id('currentServiceName');
    const retailAmount = $id('retailAmount');
    const bulkAmount = $id('bulkAmount');
    const totalAmount = $id('totalAmount');
    const quantityInput = $id('quantityInput');

    const label =
        variation.printing_category ||
        variation.package_type ||
        variation.finish_type ||
        variation.product_size ||
        'Service Detail';

    if (detailTitle) detailTitle.innerText = label;
    if (currentServiceId) currentServiceId.innerText = variation.service_item_id || 'N/A';
    if (currentServiceName) currentServiceName.innerText = label;

    const retail = Number(variation.retail_price || 0);
    const bulk = Number(variation.bulk_price || 0);
    const qty = parseInt(quantityInput?.value || 1);

    if (retailAmount) retailAmount.innerText = retail.toFixed(2);
    if (bulkAmount) bulkAmount.innerText = bulk.toFixed(2);

    const selectedPriceType = document.querySelector('input[name="priceType"]:checked')?.value || 'retail';
    const unitPrice = selectedPriceType === 'bulk' ? bulk : retail;

    if (totalAmount) totalAmount.innerText = (unitPrice * qty).toFixed(2);

    const sidebarTrack = $id('sidebarTrack');
    if (sidebarTrack) {
        sidebarTrack.innerHTML = '';

        currentCategorySet.forEach((item, idx) => {
            const itemLabel =
                item.printing_category ||
                item.package_type ||
                item.finish_type ||
                item.product_size ||
                item.service_item_id ||
                `Option ${idx + 1}`;
            
            const itemImage = item.image || 'images/Prdcts1.jpg';

            sidebarTrack.innerHTML += `
                <div class="sidebar-item ${idx === index ? 'active' : ''}" onclick="openDetail(${idx})">
                    <img src="${itemImage}" alt="${itemLabel}">
                    <p>${itemLabel}</p>
                </div>
            `;
        });
    }

    closeModal();

    const pageWrapper = $id('pageWrapper');
    const productDetail = $id('productDetail');

    if (pageWrapper) pageWrapper.style.display = 'none';
    if (productDetail) productDetail.style.display = 'block';

    window.scrollTo(0, 0);
}

// --- DROPDOWN BUILDERS ---
function updateDropdownsForID(categoryName) {
    const paperSize = $id('paperSize');
    const printCategory = $id('printCategory');
    const colorMode = $id('colorMode');
    paperSize.innerHTML = '';

    if (categoryName === "PACKAGE") {
        ['Package A', 'Package B', 'Package C', 'Package D', 'Package E', 'Package F'].forEach(opt => {
            let el = document.createElement('option'); el.value = opt; el.textContent = opt; paperSize.appendChild(el);
        });
    } else {
        const photoOptions = [
            {val: '2R', label: '2R (2.5x3.5)'}, {val: '3R', label: '3R (3.5x5.0)'}, {val: '4R', label: '4R (4.0x6.0)'},
            {val: '5R', label: '5R (5.0x7.0)'}, {val: '6R', label: '6R (6.0x8.0)'}, {val: '8R', label: '8R (8.0x10.0)'}, {val: 'A4', label: 'A4 (8.27x11.69)'}
        ];
        photoOptions.forEach(opt => {
            let el = document.createElement('option'); el.value = opt.val; el.textContent = opt.label; paperSize.appendChild(el);
        });
    }
    printCategory.innerHTML = '<option value="id_photo">Photo Services</option>';
    colorMode.innerHTML = '<option value="full">Full Color</option>';
}

function updateDropdownsForLargeFormat(categoryName) {
    const paperSize = $id('paperSize');
    const printCategory = $id('printCategory');
    const colorMode = $id('colorMode');

    if (categoryName === "SINTRA BOARD PRINTING") {
        paperSize.innerHTML = '<option value="a4">A4 (8.27 x 11.69)</option>';
        printCategory.innerHTML = `
            <option value="Glossy">Finish: Glossy</option><option value="Matte">Finish: Matte</option>
            <option value="Leather">Finish: Leather</option><option value="Canvas Matte">Finish: Canvas Matte</option>
            <option value="Glittered">Finish: Glittered</option><option value="3D">Finish: 3D</option>
            <option value="Rainbow">Finish: Rainbow</option><option value="Broken Glass">Finish: Broken Glass</option>`;
        colorMode.innerHTML = '<option value="full">Full Color</option>';
    }
}

// --- UPDATED PRICE LOGIC ---
function updatePrice() {
    const currentServiceId = $id('currentServiceId');
    const retailAmount = $id('retailAmount');
    const bulkAmount = $id('bulkAmount');
    const totalAmount = $id('totalAmount');
    const quantityInput = $id('quantityInput');

    if (!currentServiceId || !retailAmount || !bulkAmount || !totalAmount || !quantityInput) {
        return;
    }

    const selectedVariation = currentCategorySet.find(
        item => item.service_item_id === currentServiceId.innerText
    );

    if (!selectedVariation) return;

    const retail = Number(selectedVariation.retail_price || 0);
    const bulk = Number(selectedVariation.bulk_price || 0);
    const qty = parseInt(quantityInput.value || 1);
    const selectedPriceType =
        document.querySelector('input[name="priceType"]:checked')?.value || 'retail';

    retailAmount.innerText = retail.toFixed(2);
    bulkAmount.innerText = bulk.toFixed(2);

    const unitPrice = selectedPriceType === 'bulk' ? bulk : retail;
    totalAmount.innerText = (unitPrice * qty).toFixed(2);
}

/**
 * AUTO-SYNC LOGIC: Updated movePreview
 */
function movePreview(dir) {
    const track = $id('previewTrack');
    const imgs = track.querySelectorAll('img');
    const totalImgs = imgs.length;
    
    currentPreviewIndex = Math.max(0, Math.min(currentPreviewIndex + dir, totalImgs - 1));
    track.style.transform = `translateX(-${currentPreviewIndex * 100}%)`;

    // SYNC COLOR MODE FOR DOCUMENT PRINTING & XEROX
    const colorModeDropdown = $id('colorMode');
    if ((currentCategoryType === "printing" || currentCategoryType === "xerox") && colorModeDropdown) {
        if (colorModeDropdown.options[currentPreviewIndex]) {
            colorModeDropdown.selectedIndex = currentPreviewIndex;
            updatePrice();
        }
    }

    // SYNC SIZE/PACKAGE FOR ID
    const paperSizeDropdown = $id('paperSize');
    if (currentCategoryType === "id" && paperSizeDropdown) {
        if (paperSizeDropdown.options[currentPreviewIndex]) {
            paperSizeDropdown.selectedIndex = currentPreviewIndex;
            updatePrice();
        }
    }

    updatePreviewButtons();
}

function updatePreviewButtons() {
    const prev = $id('detailPrevBtn');
    const next = $id('detailNextBtn');
    const imgs = document.querySelectorAll('#previewTrack img');
    const totalImgs = imgs.length;
    if (prev) prev.style.display = (currentPreviewIndex === 0) ? 'none' : 'flex';
    if (next) next.style.display = (currentPreviewIndex >= totalImgs - 1) ? 'none' : 'flex';
}

function bindDetailControlSync() {
    ['printCategory', 'colorMode', 'paperSize'].forEach((id) => {
        const el = document.getElementById(id);
        if (!el || el.dataset.syncBound === 'true') return;
        el.addEventListener('change', () => {
            syncPreviewFromDropdowns();
            updatePrice();
        });
        el.dataset.syncBound = 'true';
    });
}

function bindDetailButtons() {
    const qtyInput = document.getElementById('qtyInput');
    if (qtyInput && qtyInput.dataset.bound !== 'true') {
        qtyInput.addEventListener('change', () => updatePrice());
        qtyInput.dataset.bound = 'true';
    }
    updatePreviewButtons();
}

function changeQty(d) {
    const q = $id('quantityInput');
    if (!q) return;

    q.value = Math.max(1, (parseInt(q.value) || 1) + d);
    updatePrice();
}

// --- CART SYSTEM ---
function toggleCart() {
    const overlay = $id('cartOverlay');
    const drawer = $id('cartDrawer');
    if (overlay) overlay.classList.toggle('active');
    if (drawer) drawer.classList.toggle('active');
    renderCart();
}

function addToCart() {
    const title = $id('currentServiceName')?.innerText || 'Service';
    const qty = parseInt($id('quantityInput')?.value || '1');
    const totalStr = ($id('totalAmount')?.innerText || '0').replace(/,/g, '');
    const sId = $id('currentServiceId')?.innerText || '';

    if (!sId) {
        alert("Please select a service first.");
        return;
    }

    cart.push({
        id: Date.now(),
        name: title,
        details: `ID: ${sId}`,
        qty: qty,
        total_price: parseFloat(totalStr),
        img: '',
        checked: true
    });

    saveCart();
    updateCartBadge();
    toggleCart();
}

function updateCartBadge(){const badge=$id('cartBadge');if(badge)badge.innerText=cart.length;}
function removeFromCart(index){cart.splice(index,1);saveCart();updateCartBadge();renderCart();}
function toggleItemCheck(index) {if (!cart[index]) return;
    cart[index].checked = !cart[index].checked;
    saveCart();calculateCartTotal();renderCart();
}

function calculateCartTotal() {const totalEl = $id('drawerTotal');if (!totalEl) return;
    const total = cart
        .filter(item => item.checked)
        .reduce((sum, item) => sum + (Number(item.total_price) || 0), 0);
    totalEl.innerText = total.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function renderCart() {
    const list = $id('cartItemsList');
    if (!list) return;
    list.innerHTML = '';
    cart.forEach((item, index) => {
        list.innerHTML += `
            <div class="cart-item">
                <input type="checkbox" ${item.checked ? 'checked' : ''} onchange="toggleItemCheck(${index})">
                <img src="${item.img}">
                <div class="cart-item-info">
                    <h4>${item.name}</h4>
                    <p style="font-size:10px; color:#777;">${item.details}</p>
                    <p class="cart-item-price">Qty: ${item.qty} | ₱${item.total_price.toLocaleString()}</p>
                    <span onclick="removeFromCart(${index})" style="color:red; cursor:pointer; font-size:11px;">REMOVE</span>
                </div>
            </div>`;
    });
    calculateCartTotal();
}

function getBgUrl(el) {
    const bg = window.getComputedStyle(el).backgroundImage;
    if (!bg) return null;

    const match = bg.match(/url\(["']?(.*?)["']?\)/);
    return match ? match[1] : null;
}

function preloadImages(urls) {
    urls.forEach(url => {
        if (!url) return;
        const img = new Image();
        img.src = url;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.documentElement.classList.add('page-loading');
    document.body.classList.add('page-loading');

    if (isLoggedIn) {
        document.body.classList.add('services-active');
    } else {
        document.body.classList.remove('services-active');
    }

    const initialSection = window.location.hash
        ? window.location.hash.substring(1)
        : 'home';

    jumpTo(initialSection);
    updateCartBadge();

    heroIndex = 0;
    updateHero();

    const heroBgUrls = Array.from(document.querySelectorAll('.hero-slide')).map(getBgUrl);
    preloadImages(heroBgUrls);

    document.documentElement.classList.remove('page-loading');
    document.body.classList.remove('page-loading');

    if (slideInterval) clearInterval(slideInterval);
    slideInterval = null;

    handleContactForm();
    initMap();

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) entry.target.classList.add('animate');
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.section').forEach((s) => observer.observe(s));
});

function checkoutSelected(){
  const selected=cart.filter(i=>i.checked);
  if(selected.length===0){alert("Please select at least 1 item to checkout.");return;}
  const payload={items:selected.map(i=>({name:i.name,qty:i.qty,unit_price:(i.total_price/i.qty),service_code:(i.details&&i.details.includes("ID: "))?(i.details.split("ID: ")[1].split(" |")[0]).trim():null,price_type:"retail"}))};
  const tokenTag=document.querySelector('meta[name="csrf-token"]'),token=tokenTag?tokenTag.getAttribute('content'):'';
  fetch('/cart/sync',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify(payload)})
  .then(res=>{if(!res.ok)throw new Error("Sync failed");return res.json();})
  .then(()=>{window.location.href='/checkout';})
  .catch(()=>{alert("Checkout failed. Please try again.");});
}

function placeOrderNow() {
    const title = $id('currentServiceName')?.innerText || '';
    const qty = parseInt($id('quantityInput')?.value || '1');
    const totalStr = ($id('totalAmount')?.innerText || '0').replace(/,/g, '');
    const sId = $id('currentServiceId')?.innerText || '';

    if (!title || !sId) {
        alert("Please select a service first.");
        return;
    }

    const selectedPriceType =
        document.querySelector('input[name="priceType"]:checked')?.value || 'retail';

    const payload = {
        items: [{
            name: title,
            qty: qty,
            unit_price: parseFloat(totalStr) / qty,
            service_code: sId,
            price_type: selectedPriceType
        }]
    };

    const tokenTag = document.querySelector('meta[name="csrf-token"]');
    const token = tokenTag ? tokenTag.getAttribute('content') : '';

    fetch('/cart/sync', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify(payload)
    })
    .then(res => {
        if (!res.ok) throw new Error("Sync failed");
        return res.json();
    })
    .then(() => {
        window.location.href = '/checkout';
    })
    .catch(() => {
        alert("Checkout failed. Please try again.");
    });
}

// --- FORMS & EXTERNAL APIS ---
function handleContactForm() {
    const btn = document.querySelector('.contact-form button');
    if (!btn) return;

    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const nameInput = document.querySelector('input[placeholder="Your Name"]');
        const emailInput = document.querySelector('input[placeholder="Email Address"]');
        const msgInput = document.querySelector('textarea');

        if(!nameInput.value || !emailInput.value || !msgInput.value) return alert("Please fill in all fields.");
        
        btn.innerText = "SENDING...";
        btn.disabled = true;

        setTimeout(() => {
            alert(`Thank you, ${nameInput.value}! Your message has been sent.`);
            btn.innerText = "SEND MESSAGE";
            btn.disabled = false;
            nameInput.value = ''; emailInput.value = ''; msgInput.value = '';
        }, 1500);
    });
}

function initMap() {
    const mapDiv = document.querySelector('.map-placeholder');
    if (mapDiv) {
        mapDiv.innerHTML = `<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15442.271842835922!2d121.0504!3d14.6137!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDM2JzQ5LjMiTiAxMjHCsDAzJzAxLjQiRQ!5e0!3m2!1sen!2sph!4v1620000000000!5m2!1sen!2sph" width="100%" height="100%" style="border:0; border-radius:8px;" allowfullscreen="" loading="lazy"></iframe>`;
    }
}

// --- INITIALIZATION ---
document.addEventListener('DOMContentLoaded', () => {
    if (isLoggedIn) {
        document.body.classList.add('services-active');
    } else {
        document.body.classList.remove('services-active');
    }

    jumpTo('home');
    updateCartBadge();
    updateHero();
    handleContactForm();
    initMap();
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { 
            if(entry.isIntersecting) entry.target.classList.add('animate');
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.section').forEach(s => observer.observe(s));
});
