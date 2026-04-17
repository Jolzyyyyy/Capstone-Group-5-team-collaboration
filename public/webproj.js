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
const fallbackImage = 'images/Prdcts1.jpg';

// LOGIN SIMULATION
let isLoggedIn = false; 

const heroSlides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.dot');
let slideInterval = setInterval(nextHeroSlide, 8000);

// Initialize Cart from LocalStorage
let cart = JSON.parse(localStorage.getItem('printCart')) || [];

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
    const productDetail = document.getElementById('productDetail');
    const pageWrapper = document.getElementById('pageWrapper');
    const mainHeader = document.getElementById('mainHeader');

    if (productDetail) productDetail.style.display = 'none';
    if (pageWrapper) pageWrapper.style.display = 'block';
    if (mainHeader) mainHeader.classList.remove('detail-active');

    if (sectionId === 'services') {
        document.body.classList.add('services-active');
        if (mainHeader) mainHeader.classList.add('scrolled'); 
    } 
    else if (sectionId === 'home') {
        if (window.scrollY < 50) {
            if (mainHeader) mainHeader.classList.remove('scrolled');
        }
        if (isLoggedIn) {
            document.body.classList.add('services-active');
        } else {
            document.body.classList.remove('services-active');
        }
    } 
    else {
        if (mainHeader) mainHeader.classList.add('scrolled');
        if (!isLoggedIn) {
            document.body.classList.remove('services-active');
        }
    }

    const sections = document.querySelectorAll('.section');
    sections.forEach(sec => {
        sec.classList.remove('active');
        sec.style.display = 'none';
    });

    const target = document.getElementById(sectionId);
    if (target) {
        target.style.display = 'block';
        setTimeout(() => { target.classList.add('active'); }, 10);
        
        if (sectionId === 'home') {
            window.scrollTo({ top: 0, behavior: 'instant' });
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
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
        if (mainHeader) mainHeader.classList.add('scrolled');
    }
});

function backToMain() {
    document.body.classList.add('services-active');
    jumpTo('services');
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function withFallbackImage(src) {
    return src || fallbackImage;
}

// --- DATA DEFINITION ---
const allData = {
    'doc': {
        name: "DOCUMENT PRINTING",
        type: "printing",
        serviceImage: "images/Prdcts1.jpg",
        categories: [
            { name: "Text Only", imgs: ["images/TXTONLY (B&W).png", "images/TXTONLY (PC).png", "images/TXTONLY (FC).png"], specs: "Paper: Bond Paper, 80gsm. Standard document printing for reports and letters." },
            { name: "Text with Image", imgs: ["images/TXTWI (B&W).png", "images/TXTWI (PC).png", "images/TXTWI (FC).png"], specs: "Paper: Bond Paper, 80gsm. Mixed text & image printing." },
            { name: "Image Only", imgs: ["images/IO (B&W).png", "images/IO (PC).png", "images/IO (FC).png"], specs: "Paper: Bond Paper, 80gsm. High-ink coverage for documents with graphics." }
        ]
    },
    'photo': {
        name: "PHOTOCOPY & SCANNING",
        type: "xerox",
        serviceImage: "images/Prdcts1.jpg",
        categories: [
            { name: "B&W Photocopy", imgs: ["images/PHOTOC (FC).png"], specs: "Standard 80gsm Copy Paper. Fast and clear duplication." },
            { name: "Partial Color Copy", imgs: ["images/PHOTOC (PC).png"], specs: "Standard 80gsm. Best for forms with small colored logos or text." },
            { name: "Full Color Copy", imgs: ["images/PHOTOC (B&W).png"], specs: "Standard 80gsm. Full vibrant color duplication." }
        ]
    },
    'id': {
        name: "ID & PHOTO SERVICES",
        type: "id",
        serviceImage: "images/Prdcts1.jpg",
        categories: [
            { name: "PACKAGE", imgs: ["images/PCKGA.png", "images/PCKGB.png", "images/PCKGC.png", "images/PCKGD.png", "images/PCKGE.png", "images/PCKGF.png"], specs: "Best value bundles for applications and school." },
            { name: "SINGLE PHOTO", imgs: ["images/SP (2-5).png", "images/SP (6-A4).png"], specs: "High-quality prints for frames and memories." }
        ]
    },
    'largeformat': {
        name: "LARGE FORMAT PRINTING",
        type: "largeformat",
        serviceImage: "images/Prdcts1.jpg",
        categories: [
            { name: "SINTRA BOARD PRINTING", imgs: ["sintra1.jpg"], specs: "Material: Sintra Board (3mm Flat PVC), A4 Size.<br>Durable, moisture-resistant, and lightweight PVC foam board.<br>Smooth surface direct print, intended for indoor display." }
        ]
    },
    'bind': {
        name: "LAMINATION & BINDING",
        type: "binding",
        serviceImage: "images/Prdcts1.jpg",
        categories: [
            { name: "LAMINATION", imgs: ["images/Prdcts1.jpg"], specs: "Protective lamination for documents, certificates, and printed materials." },
            { name: "SPIRAL BINDING", imgs: ["images/Prdcts1.jpg"], specs: "Binding service for reports, reviewers, and presentation materials." }
        ]
    },
    'special': {
        name: "CUSTOM SPECIAL PRINTING",
        type: "special",
        serviceImage: "images/Prdcts1.jpg",
        categories: [
            { name: "CUSTOM PRINT JOB", imgs: ["images/Prdcts1.jpg"], specs: "Custom print requests for specialty layouts, materials, and bespoke output needs." }
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
    'Package A': "Inclusions: 4pcs 2x2 & 8pcs 1x1", 'Package B': "Inclusions: 8pcs 1x1",
    'Package C': "Inclusions: 8pcs 2x2", 'Package D': "Inclusions: 5pcs Passport Size",
    'Package E': "Inclusions: 6pcs 1.5x1.5", 'Package F': "Inclusions: 5pcs Wallet Size"
};

// --- MODAL & DETAIL UI LOGIC ---
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
            const previewImage = withFallbackImage(cat.imgs?.[0] || data.serviceImage);
            const imageCount = Array.isArray(cat.imgs) ? cat.imgs.length : 0;
            const optionMeta = imageCount > 1 ? `${imageCount} previews available` : 'Single preview available';
            const specsSnippet = String(cat.specs || '')
                .replace(/<br\s*\/?>/gi, ' ')
                .replace(/\s+/g, ' ')
                .trim();
            track.innerHTML += `
                <div class="category-card" onclick="openDetail(${index})">
                    <div class="category-card-media">
                        <img src="${escapeHtml(previewImage)}" alt="${escapeHtml(cat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                    </div>
                    <div class="category-card-body">
                        <div class="category-label">Service option</div>
                        <h4>${escapeHtml(cat.name)}</h4>
                        <div class="category-subtitle">${escapeHtml(optionMeta)}</div>
                        <div class="category-description">${escapeHtml(specsSnippet || 'Choose this option to view full specifications and pricing.')}</div>
                    </div>
                    <button type="button" class="select-type-btn">
                        View Option
                    </button>
                </div>`;
        });
        currentSlideIndex = 0;
        track.style.transform = `translateX(0)`;
        updateModalButtons();
    }
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }
}

function closeModal() {
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }
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

    document.body.classList.add('services-active');

    document.getElementById('detailTitleHeader').innerText = cat.name;
    document.getElementById('productSpecs').innerHTML = cat.specs;

    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const paperSize = document.getElementById('paperSize');

    if (currentCategoryType === 'printing' || currentCategoryType === 'xerox') {
        printCategory.innerHTML = `<option value="text_only">Text Only</option><option value="text_image">Text with Image</option><option value="image_only">Image Only</option>`;
        paperSize.innerHTML = `<option value="short">Short (8.5 x 11)</option><option value="a4">A4 (8.27 x 11.69)</option><option value="legal">Legal (8.5 x 14)</option>`;
        colorMode.innerHTML = `<option value="bw">B&W</option><option value="partial">Partial Color</option><option value="full">Full Color</option>`;

        if (cat.name.includes("Text Only") || cat.name.includes("B&W")) {
            printCategory.value = "text_only"; colorMode.value = "bw";
        } else if (cat.name.includes("Text with Image") || cat.name.includes("Partial")) {
            printCategory.value = "text_image"; colorMode.value = "partial";
        } else if (cat.name.includes("Image Only") || cat.name.includes("Full")) {
            printCategory.value = "image_only"; colorMode.value = "full";
        }
    }

    if (currentCategoryType === "id") updateDropdownsForID(cat.name);
    if (currentCategoryType === "largeformat") updateDropdownsForLargeFormat(cat.name);

    const previewTrack = document.getElementById('previewTrack');
    previewTrack.innerHTML = '';
    cat.imgs.forEach(imgSrc => {
        previewTrack.innerHTML += `<img src="${escapeHtml(withFallbackImage(imgSrc))}" alt="${escapeHtml(cat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';" style="min-width:100%; height:100%; object-fit:contain;">`;
    });

    currentPreviewIndex = 0;
    previewTrack.style.transform = `translateX(0)`;
    updatePreviewButtons();

    const sidebarTrack = document.getElementById('sidebarTrack');
    sidebarTrack.innerHTML = '';
    currentCategorySet.forEach((sidebarCat, idx) => {
        const sidebarImage = withFallbackImage(sidebarCat.imgs?.[0]);
        sidebarTrack.innerHTML += `
            <div class="sidebar-item ${idx === index ? 'active' : ''}" onclick="openDetail(${idx})">
                <img src="${escapeHtml(sidebarImage)}" alt="${escapeHtml(sidebarCat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                <p>${sidebarCat.name}</p>
            </div>`;
    });

    document.getElementById('productModal').classList.remove('active');
    document.getElementById('productModal').style.display = 'none';
    document.getElementById('pageWrapper').style.display = 'none';
    document.getElementById('productDetail').style.display = 'block';
    document.getElementById('mainHeader').classList.add('detail-active');

    updatePrice();
    window.scrollTo(0, 0);
}

// --- DROPDOWN BUILDERS ---
function updateDropdownsForID(categoryName) {
    const paperSize = document.getElementById('paperSize');
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
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
    const paperSize = document.getElementById('paperSize');
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');

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

// --- UPDATED PRICE & DYNAMIC SERVICE ID LOGIC ---
function updatePrice() {
    const categoryValue = document.getElementById('printCategory').value;
    const categoryName = document.getElementById('detailTitleHeader').innerText;
    const color = document.getElementById('colorMode').value;
    const size = document.getElementById('paperSize').value;
    const qtyInput = document.getElementById('qtyInput');
    const qty = parseInt(qtyInput.value) || 1;
    const priceTypeInput = document.querySelector('input[name="priceType"]:checked');
    const priceType = priceTypeInput ? priceTypeInput.value : 'retail';
    const specsDisplay = document.getElementById('productSpecs');
    const serviceIdDisplay = document.getElementById('currentServiceId');

    let retail = 0, bulk = 0;
    let computedId = "N/A";

    // 1. DOCUMENT PRINTING
    if (currentCategoryType === "printing") {
        const docIdMap = {
            "text_only": { "bw": "DOC-TX-001", "partial": "DOC-TX-002", "full": "DOC-TX-003" },
            "text_image": { "bw": "DOC-TWI-004", "partial": "DOC-TWI-005", "full": "DOC-TWI-006" },
            "image_only": { "bw": "DOC-IM-007", "partial": "DOC-IM-008", "full": "DOC-IM-009" }
        };
        computedId = docIdMap[categoryValue][color];
        const p = printingPricing[categoryValue][color][size];
        retail = p[0]; bulk = p[1];
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    } 
    // 2. ID PHOTO SERVICES
    else if (currentCategoryType === "id") {
        if (categoryName === "PACKAGE") {
            const packageIdMap = {
                'Package A': "IDP-PKG-001", 'Package B': "IDP-PKG-002", 'Package C': "IDP-PKG-003",
                'Package D': "IDP-PKG-004", 'Package E': "IDP-PKG-005", 'Package F': "IDP-PKG-006"
            };
            computedId = packageIdMap[size];
            specsDisplay.innerHTML = `Premium Photo Paper (260gsm)<br><strong style="color:#e67e22;">${idDetails[size] || ""}</strong>`;
        } else {
            computedId = "IDP-SP-" + size;
            specsDisplay.innerHTML = `Premium Photo Paper (260gsm)`;
        }
        retail = idPricing[categoryName][size] || 0;
        bulk = retail;
        document.getElementById('bulkAmount').innerText = "Fixed";
    }
    // 3. LARGE FORMAT
    else if (currentCategoryType === "largeformat") {
        computedId = "SINTRA-001";
        retail = sintraPricing[categoryValue] || 0;
        bulk = retail;
        document.getElementById('bulkAmount').innerText = "Fixed";
    }
    // 4. PHOTOCOPY & SCANNING (UPDATED IDs LOGIC)
    else if (currentCategoryType === "xerox") {
        const xeroxIdMap = {
            "bw": "DOC-PCPY-001",
            "partial": "DOC-PCPY-002",
            "full": "DOC-PCPY-003"
        };
        computedId = xeroxIdMap[color] || "DOC-PCPY-001";
        
        // Xerox currently only uses B&W pricing database as per requirements
        const p = xeroxPricing[categoryValue].bw[size];
        retail = p[0]; bulk = p[1];
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }

    if (serviceIdDisplay) serviceIdDisplay.innerText = computedId;
    document.getElementById('retailAmount').innerText = retail.toFixed(2);
    const unitPrice = (priceType === 'retail') ? retail : bulk;
    const total = unitPrice * qty;
    document.getElementById('totalAmount').innerText = total.toLocaleString(undefined, {minimumFractionDigits: 2});
}

/**
 * AUTO-SYNC LOGIC: Updated movePreview
 */
function movePreview(dir) {
    const track = document.getElementById('previewTrack');
    const imgs = track.querySelectorAll('img');
    const totalImgs = imgs.length;
    
    currentPreviewIndex = Math.max(0, Math.min(currentPreviewIndex + dir, totalImgs - 1));
    track.style.transform = `translateX(-${currentPreviewIndex * 100}%)`;

    // SYNC COLOR MODE FOR DOCUMENT PRINTING & XEROX
    const colorModeDropdown = document.getElementById('colorMode');
    if ((currentCategoryType === "printing" || currentCategoryType === "xerox") && colorModeDropdown) {
        if (colorModeDropdown.options[currentPreviewIndex]) {
            colorModeDropdown.selectedIndex = currentPreviewIndex;
            updatePrice();
        }
    }

    // SYNC SIZE/PACKAGE FOR ID
    const paperSizeDropdown = document.getElementById('paperSize');
    if (currentCategoryType === "id" && paperSizeDropdown) {
        if (paperSizeDropdown.options[currentPreviewIndex]) {
            paperSizeDropdown.selectedIndex = currentPreviewIndex;
            updatePrice();
        }
    }

    updatePreviewButtons();
}

function updatePreviewButtons() {
    const prev = document.getElementById('detailPrevBtn');
    const next = document.getElementById('detailNextBtn');
    const imgs = document.querySelectorAll('#previewTrack img');
    const totalImgs = imgs.length;
    if (prev) prev.style.display = (currentPreviewIndex === 0) ? 'none' : 'flex';
    if (next) next.style.display = (currentPreviewIndex >= totalImgs - 1) ? 'none' : 'flex';
}

function changeQty(d) {
    const q = document.getElementById('qtyInput');
    if (!q) return;
    q.value = Math.max(1, parseInt(q.value) + d);
    updatePrice();
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
    const sId = document.getElementById('currentServiceId').innerText;

    let detailText = `ID: ${sId} | Size: ${size.toUpperCase()}`;
    if (currentCategoryType === "largeformat") detailText += ` | Finish: ${document.getElementById('printCategory').value}`;

    cart.push({
        id: Date.now(),
        name: title,
        details: detailText,
        qty: qty,
        price: parseFloat(totalStr),
        img: firstImg ? firstImg.src : fallbackImage,
        checked: true
    });
    
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
        const cartImage = withFallbackImage(item.img);
        list.innerHTML += `
            <div class="cart-item">
                <input type="checkbox" ${item.checked ? 'checked' : ''} onchange="toggleItemCheck(${index})">
                <img src="${escapeHtml(cartImage)}" alt="${escapeHtml(item.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                <div class="cart-item-info">
                    <h4>${item.name}</h4>
                    <p style="font-size:10px; color:#777;">${item.details}</p>
                    <p class="cart-item-price">Qty: ${item.qty} | ₱${item.price.toLocaleString()}</p>
                    <span onclick="removeFromCart(${index})" style="color:red; cursor:pointer; font-size:11px;">REMOVE</span>
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

function placeOrderNow() {
    addToCart();
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
