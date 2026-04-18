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
const BULK_MIN_PAGES = 100;
const fallbackImage = 'images/Prdcts1.jpg';
let currentModalKey = '';
let currentSelectedOptionIndex = -1;
let currentEditingCartId = null;

// LOGIN SIMULATION
let isLoggedIn = false; 

const heroSlides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.dot');
let slideInterval = setInterval(nextHeroSlide, 8000);

function normalizeCartItem(item, index) {
    if (!item || typeof item !== 'object') return null;

    const parsedQty = Math.max(1, parseInt(item.qty, 10) || 1);
    const parsedPrice = Number(item.price);

    return {
        id: item.id || `cart-${Date.now()}-${index}`,
        name: String(item.name || item.title || 'Service Item'),
        details: String(item.details || item.description || 'Selected service option'),
        qty: parsedQty,
        price: Number.isFinite(parsedPrice) ? parsedPrice : 0,
        img: item.img || item.image || fallbackImage,
        checked: item.checked !== false,
        fileName: String(item.fileName || ''),
        hasAttachment: item.hasAttachment != null ? Boolean(item.hasAttachment) : Boolean(item.fileName),
        categoryType: item.categoryType || '',
        modalKey: item.modalKey || '',
        selectedIndex: Number.isFinite(Number(item.selectedIndex)) ? Number(item.selectedIndex) : null,
        printCategoryValue: item.printCategoryValue || '',
        colorModeValue: item.colorModeValue || '',
        paperSizeValue: item.paperSizeValue || '',
        serviceOptionValue: item.serviceOptionValue || '',
        fileTypeValue: item.fileTypeValue || ''
    };
}

function loadCart() {
    try {
        const stored = JSON.parse(localStorage.getItem('printCart')) || [];
        if (!Array.isArray(stored)) return [];
        return stored
            .map((item, index) => normalizeCartItem(item, index))
            .filter(Boolean);
    } catch (error) {
        localStorage.removeItem('printCart');
        return [];
    }
}

function persistCart() {
    localStorage.setItem('printCart', JSON.stringify(cart));
}

// Initialize Cart from LocalStorage
let cart = loadCart();

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
    currentPreviewIndex = 0;
    currentSelectedOptionIndex = -1;

    const productDetail = document.getElementById('productDetail');
    const pageWrapper = document.getElementById('pageWrapper');
    const mainHeader = document.getElementById('mainHeader');
    const servicesSection = document.getElementById('services');

    document.body.classList.add('services-active');
    if (productDetail) productDetail.style.display = 'none';
    if (pageWrapper) pageWrapper.style.display = 'block';
    if (mainHeader) mainHeader.classList.remove('detail-active');

    document.querySelectorAll('.section').forEach((section) => {
        section.classList.remove('active');
        section.style.display = 'none';
    });

    if (servicesSection) {
        servicesSection.style.display = 'block';
        setTimeout(() => servicesSection.classList.add('active'), 10);
        window.scrollTo({ top: Math.max(0, servicesSection.offsetTop - 80), behavior: 'smooth' });
    }
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

function getModalKeyForType(type) {
    const entry = Object.entries(allData).find(([, value]) => value.type === type);
    return entry ? entry[0] : '';
}

function resolveCartItemContext(item) {
    if (!item) return { modalKey: '', selectedIndex: 0 };

    const serviceIdMatch = String(item.details || '').match(/ID:\s*([A-Z-0-9]+)/i);
    const serviceId = serviceIdMatch ? serviceIdMatch[1].toUpperCase() : '';

    let modalKey = item.modalKey || '';
    if (!modalKey) {
        if (serviceId.startsWith('IDP-')) modalKey = 'id';
        else if (serviceId.startsWith('DOC-PCPY-')) modalKey = 'photo';
        else if (serviceId.startsWith('DOC-')) modalKey = 'doc';
        else if (serviceId.startsWith('SINTRA-')) modalKey = 'largeformat';
        else if (serviceId.startsWith('BND-')) modalKey = 'bind';
        else if (serviceId.startsWith('CSP-')) modalKey = 'special';
        else if (item.categoryType) modalKey = getModalKeyForType(item.categoryType);
    }

    const data = allData[modalKey];
    if (!data) return { modalKey: '', selectedIndex: 0 };

    let selectedIndex = Number.isFinite(Number(item.selectedIndex)) ? Number(item.selectedIndex) : -1;
    if (selectedIndex < 0 || !data.categories[selectedIndex]) {
        selectedIndex = data.categories.findIndex((category) => category.name === item.name);
    }

    return {
        modalKey,
        selectedIndex: selectedIndex >= 0 ? selectedIndex : 0
    };
}

function isCartItemEditable(item) {
    const { modalKey } = resolveCartItemContext(item);
    return Boolean(modalKey && allData[modalKey]);
}

function formatPeso(value) {
    return `PHP ${Number(value || 0).toFixed(2)}`;
}

function getOptionPricingSummary(option) {
    const retail = Number(option?.retail_price || 0);
    const bulk = Number(option?.bulk_price || 0);

    if (retail > 0 || bulk > 0) {
        if (bulk > 0 && bulk !== retail) {
            return `Retail ${formatPeso(retail)} | Bulk ${formatPeso(bulk)}`;
        }

        return `Starts at ${formatPeso(retail || bulk)}`;
    }

    if (currentCategoryType === 'printing') return 'Retail from PHP 3.50';
    if (currentCategoryType === 'xerox') return 'Retail from PHP 2.00';
    if (currentCategoryType === 'id') {
        if (option?.name && option.name !== 'SINGLE PHOTO') {
            return `Package price ${formatPeso(idPricing['PACKAGE'][option.name] || 0)}`;
        }
        return 'Retail from PHP 5.00';
    }
    if (currentCategoryType === 'largeformat') return 'Starts at PHP 100.00';

    return 'View pricing in details';
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
            { name: "Package A", imgs: ["images/PCKGA.png"], specs: "Premium Photo Paper (260gsm). Best value bundle for mixed 1x1 and 2x2 requirements." },
            { name: "Package B", imgs: ["images/PCKGB.png"], specs: "Premium Photo Paper (260gsm). Ideal for 1x1 photo requirements." },
            { name: "Package C", imgs: ["images/PCKGC.png"], specs: "Premium Photo Paper (260gsm). Ideal for 2x2 photo requirements." },
            { name: "Package D", imgs: ["images/PCKGD.png"], specs: "Premium Photo Paper (260gsm). Passport size photo package." },
            { name: "Package E", imgs: ["images/PCKGE.png"], specs: "Premium Photo Paper (260gsm). 1.5 x 1.5 package for ID use." },
            { name: "Package F", imgs: ["images/PCKGF.png"], specs: "Premium Photo Paper (260gsm). Wallet size package." },
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

const bindingPricing = {
    lamination: {
        standard: { a4: [35.00, 30.00], legal: [45.00, 40.00] },
        premium: { a4: [50.00, 45.00], legal: [60.00, 55.00] }
    },
    spiral_binding: {
        standard: { a4: [120.00, 110.00], legal: [140.00, 130.00] },
        premium: { a4: [160.00, 150.00], legal: [180.00, 170.00] }
    }
};

const specialPricing = {
    custom_layout: {
        standard: { a4: [150.00, 140.00], a3: [220.00, 210.00], custom: [300.00, 300.00] },
        premium: { a4: [210.00, 200.00], a3: [290.00, 280.00], custom: [380.00, 380.00] }
    },
    marketing_collateral: {
        standard: { a4: [180.00, 170.00], a3: [260.00, 250.00], custom: [340.00, 340.00] },
        premium: { a4: [240.00, 230.00], a3: [320.00, 310.00], custom: [420.00, 420.00] }
    },
    sticker_cut: {
        standard: { a4: [200.00, 190.00], a3: [280.00, 270.00], custom: [360.00, 360.00] },
        premium: { a4: [260.00, 250.00], a3: [340.00, 330.00], custom: [440.00, 440.00] }
    }
};

const idDetails = {
    'Package A': "Inclusions: 4pcs 2x2 & 8pcs 1x1", 'Package B': "Inclusions: 8pcs 1x1",
    'Package C': "Inclusions: 8pcs 2x2", 'Package D': "Inclusions: 5pcs Passport Size",
    'Package E': "Inclusions: 6pcs 1.5x1.5", 'Package F': "Inclusions: 5pcs Wallet Size"
};

const fileTypeChoices = {
    printing: ['PDF', 'DOCX', 'PPTX', 'PNG'],
    xerox: ['PDF', 'DOCX', 'JPG', 'PNG'],
    id: ['JPG', 'PNG', 'PDF'],
    largeformat: ['PDF', 'PNG', 'PSD', 'AI'],
    binding: ['PDF', 'DOCX', 'PPTX'],
    special: ['PDF', 'PNG', 'PSD', 'AI']
};

// --- MODAL & DETAIL UI LOGIC ---
function openModal(key) {
    const data = allData[key] || { name: "PRINTING SERVICE", categories: [] };
    if (currentModalKey !== key) {
        currentSelectedOptionIndex = -1;
    }
    currentCategoryType = data.type;
    currentModalKey = key;

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
            const pricingSummary = getOptionPricingSummary(cat);
            const specsSnippet = String(cat.specs || '')
                .replace(/<br\s*\/?>/gi, ' ')
                .replace(/\s+/g, ' ')
                .trim();
            const isSelected = currentSelectedOptionIndex === index;
            track.innerHTML += `
                <div class="category-card ${isSelected ? 'is-selected' : ''} ${currentCategoryType === 'id' ? 'id-category-card' : ''} ${currentCategoryType === 'xerox' ? 'copy-category-card' : ''}" onclick="openDetail(${index})">
                    <div class="category-card-media ${currentCategoryType === 'id' ? 'id-category-media' : ''} ${currentCategoryType === 'xerox' ? 'copy-category-media' : ''}">
                        <img src="${escapeHtml(previewImage)}" alt="${escapeHtml(cat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                    </div>
                    <div class="category-card-body">
                        <div class="category-label">Service option</div>
                        <h4>${escapeHtml(cat.name)}</h4>
                        <div class="category-subtitle">${escapeHtml(optionMeta)}</div>
                        <div class="category-price">${escapeHtml(pricingSummary)}</div>
                        <div class="category-description">${escapeHtml(specsSnippet || 'Choose this option to view full specifications and pricing.')}</div>
                    </div>
                    <button type="button" class="select-type-btn">
                        ${isSelected ? 'Selected' : 'View Option'}
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

function getCurrentDetailCategory() {
    return currentCategorySet[currentSelectedOptionIndex] || currentCategorySet[0] || { name: '', imgs: [], specs: '' };
}

function setSelectOptions(selectId, values, placeholder, preferredValue = '') {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '';

    if (placeholder) {
        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.textContent = placeholder;
        select.appendChild(placeholderOption);
    }

    values.forEach((value) => {
        const option = document.createElement('option');
        if (typeof value === 'string') {
            option.value = value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');
            option.textContent = value;
        } else {
            option.value = value.value;
            option.textContent = value.label;
        }
        select.appendChild(option);
    });

    const fallbackValue = select.options[placeholder ? 1 : 0]?.value || '';
    select.value = preferredValue && [...select.options].some((option) => option.value === preferredValue)
        ? preferredValue
        : fallbackValue;
}

function renderDetailGallery(cat, index) {
    if (!cat) return;

    currentSelectedOptionIndex = index;
    document.getElementById('detailTitleHeader').innerText = cat.name;
    document.getElementById('productSpecs').innerHTML = cat.specs;

    const previewTrack = document.getElementById('previewTrack');
    previewTrack.innerHTML = '';
    cat.imgs.forEach((imgSrc) => {
        previewTrack.innerHTML += `<img src="${escapeHtml(withFallbackImage(imgSrc))}" alt="${escapeHtml(cat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';" style="min-width:100%; height:100%; object-fit:contain;">`;
    });

    currentPreviewIndex = 0;
    previewTrack.style.transform = 'translateX(0)';
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
}

function updateServiceSelectors(categoryName) {
    const optionSelect = document.getElementById('serviceOptionSelect');
    const fileTypeSelect = document.getElementById('fileTypeSelect');
    const currentOptionValue = optionSelect?.value || '';
    const currentFileTypeValue = fileTypeSelect?.value || '';
    const size = document.getElementById('paperSize')?.value || '';

    let options = [];

    if (currentCategoryType === 'printing') {
        options = ['Standard Print', 'Fast Turnaround', 'Collated Set', 'Layout Check'];
    } else if (currentCategoryType === 'xerox') {
        options = ['Walk-in Copy', 'Sorted Set', 'Stapled Set'];
    } else if (currentCategoryType === 'id') {
        options = categoryName !== 'SINGLE PHOTO'
            ? ['Basic Retouch', 'Soft Copy Included', 'Glossy Finish', 'Cut and Trim']
            : ['Glossy Finish', 'Matte Finish', 'Borderless Crop', 'Photo Correction'];
    } else if (currentCategoryType === 'largeformat') {
        options = ['Ready to Mount', 'Indoor Display', 'Photo Finish Check'];
    } else if (currentCategoryType === 'binding') {
        options = ['Clear Cover Set', 'Spiral Finish', 'Laminated Cover'];
    } else if (currentCategoryType === 'special') {
        options = ['Custom Quote', 'Layout Consultation', 'Rush Request'];
    }

    setSelectOptions('serviceOptionSelect', options, 'Select Option', currentOptionValue);

    const fileTypes = fileTypeChoices[currentCategoryType] || ['PDF', 'PNG'];
    setSelectOptions('fileTypeSelect', fileTypes, 'Select File Type', currentFileTypeValue);

    if (currentCategoryType === 'id' && categoryName === 'SINGLE PHOTO' && size && optionSelect && !optionSelect.value) {
        optionSelect.value = optionSelect.options[1]?.value || '';
    }
}

function refreshServicePanel() {
    const materialEl = document.getElementById('serviceMaterial');
    const inclusionsEl = document.getElementById('serviceInclusions');
    const notesEl = document.getElementById('serviceNotes');
    const category = getCurrentDetailCategory();
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const paperSize = document.getElementById('paperSize');

    const selectedCategory = printCategory?.selectedOptions[0]?.textContent || '';
    const selectedColor = colorMode?.selectedOptions[0]?.textContent || '';
    const selectedSize = paperSize?.selectedOptions[0]?.textContent || '';

    let material = 'Premium 80gsm Bond Paper';
    let inclusions = 'Select a variation to view inclusions and service details.';
    let notes = 'Files that require editing, layout adjustments, or design enhancement may have additional charges depending on the type and complexity of the service needed. For best results, please upload high-resolution files.';

    if (currentCategoryType === 'printing') {
        material = 'Premium 80gsm Bond Paper';
        inclusions = `${selectedCategory || category.name} | ${selectedColor || 'B&W'} | ${selectedSize || 'Short Size'} prints`;
        notes = 'Best for reports, reviewers, handouts, and classroom materials. Upload PDF or DOCX files for the cleanest output.';
    } else if (currentCategoryType === 'xerox') {
        material = 'Standard 80gsm Copy Paper';
        inclusions = `${category.name} | ${selectedSize || 'Short Size'} copy service`;
        notes = 'Photocopy output follows your selected paper size. Bring clean originals for sharper and more consistent duplication.';
    } else if (currentCategoryType === 'id') {
        material = 'Premium Photo Paper (260gsm)';
        inclusions = category.name !== 'SINGLE PHOTO'
            ? (idDetails[category.name] || 'Choose a package to see inclusions.')
            : `${selectedSize || '2R'} full-color photo print`;
        notes = 'For ID, passport, visa, and official document use. High-resolution uploads are recommended for the best facial detail and skin tone reproduction.';
    } else if (currentCategoryType === 'largeformat') {
        material = 'Sintra Board (3mm Flat PVC)';
        inclusions = `${printCategory?.value || 'Glossy'} finish on A4 display print`;
        notes = 'Large format jobs are prepared for display use. Final tones may vary slightly depending on finish and lighting conditions.';
    } else if (currentCategoryType === 'binding') {
        material = 'Document Cover and Binding Supplies';
        inclusions = `${category.name} | ${selectedSize || 'A4'} | ${selectedColor || 'Standard'} finish`;
        notes = 'Recommended for reports, proposals, and thesis materials. Final turnaround depends on page count and cover stock availability.';
    } else if (currentCategoryType === 'special') {
        material = 'Custom Production Materials';
        inclusions = `${selectedCategory || category.name} | ${selectedSize || 'Custom size'} | ${selectedColor || 'Standard'} production`;
        notes = 'Custom jobs may require manual review before approval. Final pricing can change based on layout, trimming, finishing, or specialty stock.';
    }

    if (materialEl) materialEl.textContent = material;
    if (inclusionsEl) inclusionsEl.textContent = inclusions;
    if (notesEl) notesEl.textContent = notes;

    updateServiceSelectors(category.name);
}

function openDetail(index) {
    const cat = currentCategorySet[index];
    if (!cat) return;

    document.body.classList.add('services-active');

    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const paperSize = document.getElementById('paperSize');

    if (currentCategoryType === 'printing' || currentCategoryType === 'xerox') {
        printCategory.innerHTML = `<option value="text_only">Text Only</option><option value="text_image">Text with Image</option><option value="image_only">Image Only</option>`;
        paperSize.innerHTML = `<option value="short">Short (8.5 x 11)</option><option value="a4">A4 (8.27 x 11.69)</option><option value="legal">Legal (8.5 x 14)</option>`;
        colorMode.innerHTML = `<option value="bw">B&W</option><option value="partial">Partial Color</option><option value="full">Full Color</option>`;

        const printIndex = Math.max(index, 0);
        printCategory.value = ['text_only', 'text_image', 'image_only'][printIndex] || 'text_only';
        colorMode.value = ['bw', 'partial', 'full'][Math.min(printIndex, 2)] || 'bw';
    }

    if (currentCategoryType === "id") updateDropdownsForID(cat.name);
    if (currentCategoryType === "largeformat") updateDropdownsForLargeFormat(cat.name);
    if (currentCategoryType === "binding") {
        printCategory.innerHTML = `<option value="lamination">Lamination</option><option value="spiral_binding">Spiral Binding</option>`;
        colorMode.innerHTML = `<option value="standard">Standard</option><option value="premium">Premium</option>`;
        paperSize.innerHTML = `<option value="a4">A4</option><option value="legal">Legal</option>`;
        printCategory.value = index === 0 ? 'lamination' : 'spiral_binding';
        colorMode.value = 'standard';
    }
    if (currentCategoryType === "special") {
        printCategory.innerHTML = `<option value="custom_layout">Custom Layout</option><option value="marketing_collateral">Marketing Collateral</option><option value="sticker_cut">Sticker Cut</option>`;
        colorMode.innerHTML = `<option value="standard">Standard Production</option><option value="premium">Premium Production</option>`;
        paperSize.innerHTML = `<option value="a4">A4</option><option value="a3">A3</option><option value="custom">Custom Size</option>`;
        printCategory.value = ['custom_layout', 'marketing_collateral', 'sticker_cut'][index] || 'custom_layout';
        colorMode.value = 'standard';
    }

    renderDetailGallery(cat, index);
    refreshServicePanel();

    document.getElementById('productModal').classList.remove('active');
    document.getElementById('productModal').style.display = 'none';
    document.getElementById('pageWrapper').style.display = 'none';
    document.getElementById('productDetail').style.display = 'block';
    document.getElementById('mainHeader').classList.add('detail-active');

    updatePrice();
    window.scrollTo(0, 0);
}

function syncPreviewFromDropdowns() {
    if (!currentCategorySet.length) return;

    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const paperSize = document.getElementById('paperSize');
    const previewTrack = document.getElementById('previewTrack');

    if (!previewTrack) return;

    if (currentCategoryType === 'printing' || currentCategoryType === 'xerox') {
        const categoryIndexMap = { text_only: 0, text_image: 1, image_only: 2 };
        const nextIndex = categoryIndexMap[printCategory?.value] ?? currentSelectedOptionIndex ?? 0;
        if (nextIndex !== currentSelectedOptionIndex && currentCategorySet[nextIndex]) {
            renderDetailGallery(currentCategorySet[nextIndex], nextIndex);
        }
        currentPreviewIndex = Math.min(colorMode?.selectedIndex ?? 0, previewTrack.querySelectorAll('img').length - 1);
    } else if (currentCategoryType === 'id') {
        currentPreviewIndex = Math.min(paperSize?.selectedIndex ?? 0, previewTrack.querySelectorAll('img').length - 1);
    } else {
        const specialIndexMap = { custom_layout: 0, marketing_collateral: 1, sticker_cut: 2, lamination: 0, spiral_binding: 1 };
        const nextIndex = specialIndexMap[printCategory?.value] ?? currentSelectedOptionIndex ?? 0;
        if (nextIndex !== currentSelectedOptionIndex && currentCategorySet[nextIndex]) {
            renderDetailGallery(currentCategorySet[nextIndex], nextIndex);
        }
        currentPreviewIndex = 0;
    }

    previewTrack.style.transform = `translateX(-${Math.max(currentPreviewIndex, 0) * 100}%)`;
    updatePreviewButtons();
    refreshServicePanel();
}

// --- DROPDOWN BUILDERS ---
function updateDropdownsForID(categoryName) {
    const paperSize = document.getElementById('paperSize');
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    paperSize.innerHTML = '';

    if (categoryName !== "SINGLE PHOTO") {
        const el = document.createElement('option');
        el.value = categoryName;
        el.textContent = categoryName;
        paperSize.appendChild(el);
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
    const retailInput = document.querySelector('input[name="priceType"][value="retail"]');
    const bulkInput = document.querySelector('input[name="priceType"][value="bulk"]');
    const bulkThresholdNote = document.getElementById('bulkThresholdNote');
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
        if (categoryName !== "SINGLE PHOTO") {
            const packageIdMap = {
                'Package A': "IDP-PKG-001", 'Package B': "IDP-PKG-002", 'Package C': "IDP-PKG-003",
                'Package D': "IDP-PKG-004", 'Package E': "IDP-PKG-005", 'Package F': "IDP-PKG-006"
            };
            computedId = packageIdMap[categoryName];
            specsDisplay.innerHTML = `Premium Photo Paper (260gsm)<br><strong style="color:#e67e22;">${idDetails[categoryName] || ""}</strong>`;
            retail = idPricing['PACKAGE'][categoryName] || 0;
        } else {
            computedId = "IDP-SP-" + size;
            specsDisplay.innerHTML = `Premium Photo Paper (260gsm)`;
            retail = idPricing[categoryName][size] || 0;
        }
        bulk = retail;
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }
    // 3. LARGE FORMAT
    else if (currentCategoryType === "largeformat") {
        computedId = "SINTRA-001";
        retail = sintraPricing[categoryValue] || 0;
        bulk = retail;
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }
    else if (currentCategoryType === "binding") {
        const bindingIdMap = { lamination: "BND-LAM-001", spiral_binding: "BND-SPR-002" };
        computedId = bindingIdMap[categoryValue] || "BND-LAM-001";
        const p = bindingPricing[categoryValue]?.[color]?.[size] || [0, 0];
        retail = p[0];
        bulk = p[1];
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }
    else if (currentCategoryType === "special") {
        const specialIdMap = {
            custom_layout: "CSP-LYT-001",
            marketing_collateral: "CSP-MKT-002",
            sticker_cut: "CSP-STK-003"
        };
        computedId = specialIdMap[categoryValue] || "CSP-LYT-001";
        const p = specialPricing[categoryValue]?.[color]?.[size] || [0, 0];
        retail = p[0];
        bulk = p[1];
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
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

    const bulkEligible = qty >= BULK_MIN_PAGES;
    if (bulkInput) {
        bulkInput.disabled = !bulkEligible;
        if (!bulkEligible && bulkInput.checked && retailInput) {
            retailInput.checked = true;
        }
    }
    if (bulkThresholdNote) {
        bulkThresholdNote.textContent = bulkEligible
            ? `Bulk price applied for ${qty} pages.`
            : `Bulk price available at ${BULK_MIN_PAGES}+ pages.`;
    }

    if (serviceIdDisplay) serviceIdDisplay.innerText = computedId;
    document.getElementById('retailAmount').innerText = retail.toFixed(2);
    const priceType = bulkEligible && priceTypeInput && priceTypeInput.value === 'bulk' ? 'bulk' : 'retail';
    const unitPrice = (priceType === 'retail') ? retail : bulk;
    const total = unitPrice * qty;
    document.getElementById('totalAmount').innerText = total.toLocaleString(undefined, {minimumFractionDigits: 2});
    refreshServicePanel();
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
function setCartOpen(isOpen) {
    const overlay = document.getElementById('cartOverlay');
    const drawer = document.getElementById('cartDrawer');
    if (overlay) overlay.classList.toggle('active', isOpen);
    if (drawer) drawer.classList.toggle('active', isOpen);
    if (isOpen) renderCart();
}

function setCartMessage(message, tone = 'info') {
    const voucherMsg = document.getElementById('voucherMsg');
    if (!voucherMsg) return;

    const toneMap = {
        info: '#b45309',
        success: '#15803d',
        error: '#dc2626'
    };

    voucherMsg.textContent = message;
    voucherMsg.style.color = toneMap[tone] || toneMap.info;
}

function toggleCart() {
    const drawer = document.getElementById('cartDrawer');
    const isOpen = drawer ? !drawer.classList.contains('active') : false;
    setCartOpen(isOpen);
}

function buildCurrentCartItem() {
    const title = document.getElementById('detailTitleHeader').innerText;
    const size = document.getElementById('paperSize').value;
    const sizeLabel = document.getElementById('paperSize').selectedOptions[0]?.textContent || size;
    const serviceOption = document.getElementById('serviceOptionSelect')?.selectedOptions[0]?.textContent || 'Standard';
    const qty = Math.max(1, parseInt(document.getElementById('qtyInput').value || '1', 10));
    const totalStr = document.getElementById('totalAmount').innerText.replace(/,/g, '');
    const firstImg = document.querySelector('#previewTrack img');
    const sId = document.getElementById('currentServiceId').innerText;
    const total = parseFloat(totalStr);
    const fileInput = document.getElementById('fileUploadInput');
    const attachedFile = fileInput?.files?.[0] || null;
    const existingItem = currentEditingCartId ? cart.find((item) => item.id === currentEditingCartId) : null;

    if (!title || !sId || Number.isNaN(total) || total <= 0) {
        alert('Please choose a service option first.');
        return null;
    }

    let detailText = `ID: ${sId} | Size: ${sizeLabel} | Option: ${serviceOption}`;
    if (currentCategoryType === "largeformat") detailText += ` | Finish: ${document.getElementById('printCategory').value}`;

    return {
        id: Date.now(),
        name: title,
        details: detailText,
        qty: qty,
        price: total,
        img: firstImg ? firstImg.src : fallbackImage,
        checked: true,
        fileName: attachedFile ? attachedFile.name : (existingItem?.fileName || ''),
        hasAttachment: attachedFile ? true : Boolean(existingItem?.hasAttachment),
        categoryType: currentCategoryType,
        modalKey: currentModalKey || getModalKeyForType(currentCategoryType),
        selectedIndex: currentSelectedOptionIndex,
        printCategoryValue: document.getElementById('printCategory')?.value || '',
        colorModeValue: document.getElementById('colorMode')?.value || '',
        paperSizeValue: document.getElementById('paperSize')?.value || '',
        serviceOptionValue: document.getElementById('serviceOptionSelect')?.value || '',
        fileTypeValue: document.getElementById('fileTypeSelect')?.value || ''
    };
}

function addOrUpdateCartItem(cartItem) {
    if (currentEditingCartId) {
        const editingIndex = cart.findIndex((item) => item.id === currentEditingCartId);
        if (editingIndex >= 0) {
            cart[editingIndex] = { ...cart[editingIndex], ...cartItem, id: currentEditingCartId };
            currentEditingCartId = null;
            return cart[editingIndex];
        }
    }

    const existingIndex = cart.findIndex((item) => item.name === cartItem.name && item.details === cartItem.details);

    if (existingIndex >= 0) {
        cart[existingIndex].qty += cartItem.qty;
        cart[existingIndex].price += cartItem.price;
        cart[existingIndex].checked = true;
        cart[existingIndex].fileName = cartItem.fileName || cart[existingIndex].fileName || '';
        cart[existingIndex].hasAttachment = Boolean(cart[existingIndex].fileName);
        cart[existingIndex].categoryType = cartItem.categoryType;
        cart[existingIndex].modalKey = cartItem.modalKey;
        cart[existingIndex].selectedIndex = cartItem.selectedIndex;
        cart[existingIndex].printCategoryValue = cartItem.printCategoryValue;
        cart[existingIndex].colorModeValue = cartItem.colorModeValue;
        cart[existingIndex].paperSizeValue = cartItem.paperSizeValue;
        cart[existingIndex].serviceOptionValue = cartItem.serviceOptionValue;
        cart[existingIndex].fileTypeValue = cartItem.fileTypeValue;
        return cart[existingIndex];
    }

    cart.push(cartItem);
    return cartItem;
}

function editCartItem(index) {
    const item = cart[index];
    if (!item) return;

    const { modalKey, selectedIndex } = resolveCartItemContext(item);
    const data = allData[modalKey];
    if (!data) {
        alert('This cart item cannot be edited yet. Please remove it and add it again from the service page.');
        return;
    }

    currentEditingCartId = item.id;
    currentCategoryType = data.type;
    currentModalKey = modalKey;
    currentCategorySet = data.categories;

    openDetail(selectedIndex);

    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const paperSize = document.getElementById('paperSize');
    const serviceOption = document.getElementById('serviceOptionSelect');
    const fileType = document.getElementById('fileTypeSelect');
    const qtyInput = document.getElementById('qtyInput');

    if (printCategory && item.printCategoryValue) printCategory.value = item.printCategoryValue;
    if (colorMode && item.colorModeValue) colorMode.value = item.colorModeValue;
    if (paperSize && item.paperSizeValue) paperSize.value = item.paperSizeValue;

    syncPreviewFromDropdowns();

    if (serviceOption && item.serviceOptionValue) serviceOption.value = item.serviceOptionValue;
    if (fileType && item.fileTypeValue) fileType.value = item.fileTypeValue;
    if (qtyInput && item.qty) qtyInput.value = item.qty;

    updatePrice();
    setCartOpen(false);
    setCartMessage('Editing cart item. Reattach a file if you want to replace the current attachment.', item.hasAttachment ? 'info' : 'error');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function addToCart() {
    const cartItem = buildCurrentCartItem();
    if (!cartItem) return;

    const wasEditing = Boolean(currentEditingCartId);
    addOrUpdateCartItem(cartItem);
    persistCart();
    updateCartBadge();
    renderCart();
    setCartOpen(true);
    setCartMessage(
        wasEditing
            ? 'Cart item updated. Double-check your attached file before checkout.'
            : 'Item added to cart. Double-check your attached file before checkout.',
        cartItem.hasAttachment ? 'success' : 'info'
    );
}

function updateCartBadge() {
    const badge = document.getElementById('cartBadge');
    if (!badge) return;
    const totalItems = cart.reduce((sum, item) => sum + (parseInt(item.qty, 10) || 0), 0);
    badge.innerText = totalItems;
}

function removeFromCart(index) {
    cart.splice(index, 1);
    persistCart();
    updateCartBadge();
    renderCart();
}

function renderCart() {
    const list = document.getElementById('cartItemsList');
    if (!list) return;

    const normalizedCart = cart
        .map((item, index) => normalizeCartItem(item, index))
        .filter(Boolean);

    if (normalizedCart.length !== cart.length) {
        cart = normalizedCart;
        persistCart();
        updateCartBadge();
    }

    if (!cart.length) {
        list.innerHTML = `
            <div class="cart-empty-state">
                <h4>Your cart is empty</h4>
                <p>Add a service from the detail page to see it here.</p>
            </div>`;
        calculateCartTotal();
        return;
    }

    list.innerHTML = '';
    cart.forEach((item, index) => {
        const cartImage = withFallbackImage(item.img);
        const safePrice = Number(item.price) || 0;
        const editable = isCartItemEditable(item);
        const fileStatus = item.hasAttachment
            ? `File: ${item.fileName}`
            : 'No file attached yet';
        const legacyWarning = editable
            ? ''
            : '<p class="cart-item-legacy-warning">Refresh item: this older cart entry needs to be removed and added again before it can be edited.</p>';
        const editButtonLabel = editable ? 'Edit Item' : 'Refresh Item';
        list.innerHTML += `
            <div class="cart-item">
                <input type="checkbox" ${item.checked ? 'checked' : ''} onchange="toggleItemCheck(${index})">
                <img src="${escapeHtml(cartImage)}" alt="${escapeHtml(item.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                <div class="cart-item-info">
                    <h4>${escapeHtml(item.name)}</h4>
                    <p class="cart-item-details">${escapeHtml(item.details)}</p>
                    <p class="cart-item-file ${item.hasAttachment ? 'has-file' : 'missing-file'}">${escapeHtml(fileStatus)}</p>
                    ${legacyWarning}
                    <p class="cart-item-price">Qty: ${item.qty} | PHP ${safePrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                    <button type="button" class="cart-item-edit ${editable ? '' : 'is-warning'}" onclick="editCartItem(${index})">${editButtonLabel}</button>
                    <button type="button" class="cart-item-remove" onclick="removeFromCart(${index})">Remove</button>
                </div>
            </div>`;
    });
    calculateCartTotal();
}

function toggleItemCheck(index) {
    if (!cart[index]) return;
    cart[index].checked = !cart[index].checked;
    persistCart();
    calculateCartTotal();
}

function calculateCartTotal() {
    const drawerTotal = document.getElementById('drawerTotal');
    let subtotal = cart.reduce((acc, item) => item.checked ? acc + (Number(item.price) || 0) : acc, 0);
    subtotal = Math.max(0, subtotal - voucherDiscount);
    if (drawerTotal) drawerTotal.innerText = subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
}

function applyVoucher() {
    const voucherInput = document.getElementById('voucherCode');
    const voucherMsg = document.getElementById('voucherMsg');
    if (!voucherInput || !voucherMsg) return;

    const code = voucherInput.value.trim().toUpperCase();
    if (!code) {
        voucherDiscount = 0;
        voucherMsg.textContent = 'Enter a voucher code first.';
        voucherMsg.style.color = '#b45309';
        calculateCartTotal();
        return;
    }

    if (code === 'PRINT10') {
        voucherDiscount = 10;
        voucherMsg.textContent = 'Voucher applied: PHP 10.00 off';
        voucherMsg.style.color = '#15803d';
    } else {
        voucherDiscount = 0;
        voucherMsg.textContent = 'Invalid voucher code.';
        voucherMsg.style.color = '#dc2626';
    }

    calculateCartTotal();
}

function checkoutSelected() {
    const selectedItems = cart.filter((item) => item.checked);
    if (!selectedItems.length) {
        alert('Select at least one cart item before checkout.');
        return;
    }

    const itemsMissingAttachment = selectedItems.filter((item) => !item.hasAttachment);
    if (itemsMissingAttachment.length) {
        setCartMessage('Attach a file to every selected cart item before checkout.', 'error');
        alert('Checkout blocked. One or more selected items do not have an attached file.');
        return;
    }

    setCartMessage(`Checkout ready for ${selectedItems.length} item(s).`, 'success');
    alert(`Checkout ready for ${selectedItems.length} item(s).`);
}

function placeOrderNow() {
    const fileInput = document.getElementById('fileUploadInput');
    if (!fileInput || !fileInput.files || !fileInput.files.length) {
        alert('Attach a file before placing your order.');
        return;
    }

    const cartItem = buildCurrentCartItem();
    if (!cartItem) return;

    const wasEditing = Boolean(currentEditingCartId);
    addOrUpdateCartItem(cartItem);
    persistCart();
    updateCartBadge();
    renderCart();
    setCartOpen(true);
    setCartMessage(
        wasEditing
            ? 'Cart item updated. Review your file and selected items before checkout.'
            : 'Order item added. Review your file and selected items before checkout.',
        'success'
    );
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
