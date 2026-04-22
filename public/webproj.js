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
let currentModalItemCount = 0;
let voucherDiscount = 0;
const BULK_MIN_PAGES = 100;
const SINTRA_BULK_MIN_PCS = 10;
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
        fileTypeValue: item.fileTypeValue || '',
        contentTypeValue: item.contentTypeValue || ''
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

function updateActiveNavLink(sectionId = 'home') {
    document.querySelectorAll('.nav-horizontal .nav-link').forEach((link) => {
        const isActive = link.dataset.section === sectionId;
        link.classList.toggle('is-active', isActive);
        if (isActive) {
            link.setAttribute('aria-current', 'page');
        } else {
            link.removeAttribute('aria-current');
        }
    });
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
        updateActiveNavLink(sectionId);
        
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
        updateActiveNavLink('services');
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
        else if (serviceId.startsWith('PCS-') || serviceId.startsWith('XRX-') || serviceId.startsWith('PCP-') || serviceId.startsWith('DOC-PCPY-')) modalKey = 'photo';
        else if (serviceId.startsWith('DOC-')) modalKey = 'doc';
        else if (serviceId.startsWith('LFP-') || serviceId.startsWith('SINTRA-')) modalKey = 'largeformat';
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
            { name: "Text Only", imgs: ["images/TXTONLY (B&W).png", "images/TXTONLY (PC).png", "images/TXTONLY (FC).png"], specs: "" },
            { name: "Text with Image", imgs: ["images/TXTWI (B&W).png", "images/TXTWI (PC).png", "images/TXTWI (FC).png"], specs: "" },
            { name: "Image Only", imgs: ["images/IO (B&W).png", "images/IO (PC).png", "images/IO (FC).png"], specs: "" }
        ]
    },
    'photo': {
        name: "PHOTOCOPY & SCANNING",
        type: "xerox",
        serviceImage: "images/Prdcts1.jpg",
        categories: [
            { name: "Photocopy", imgs: ["images/PHOTOC (B&W).png"], specs: "" },
            { name: "Scanning", imgs: ["images/TXTONLY (B&W).png"], specs: "" }
        ]
    },
    'id': {
        name: "ID & PHOTO SERVICES",
        type: "id",
        serviceImage: "images/Prdcts1.jpg",
        categories: [
            { name: "Package A", imgs: ["images/PCKGA.png"], specs: "" },
            { name: "Package B", imgs: ["images/PCKGB.png"], specs: "" },
            { name: "Package C", imgs: ["images/PCKGC.png"], specs: "" },
            { name: "Package D", imgs: ["images/PCKGD.png"], specs: "" },
            { name: "Package E", imgs: ["images/PCKGE.png"], specs: "" },
            { name: "Package F", imgs: ["images/PCKGF.png"], specs: "" },
            { name: "SINGLE PHOTO", imgs: ["images/SP (2-5).png", "images/SP (6-A4).png"], specs: "" }
        ]
    },
    'largeformat': {
        name: "LARGE FORMAT PRINTING",
        type: "largeformat",
        serviceImage: "images/Homesld1.jpg",
        categories: [
            { name: "SINTRA BOARD PRINTING", imgs: ["images/Homesld1.jpg"], specs: "" }
        ]
    },
    'bind': {
        name: "LAMINATION & BINDING",
        type: "binding",
        serviceImage: "images/Homesld2.jpg",
        categories: [
            { name: "LAMINATION", imgs: ["images/Homesld2.jpg"], specs: "" },
            { name: "SPIRAL BINDING", imgs: ["images/Homesld1.jpg"], specs: "" }
        ]
    },
    'special': {
        name: "CUSTOM SPECIAL PRINTING",
        type: "special",
        serviceImage: "images/Homesld3.jpg",
        categories: [
            { name: "CUSTOM PRINT JOB", imgs: ["images/Homesld3.jpg"], specs: "" }
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
    glossy: 100.00,
    matte: 100.00,
    leather: 110.00,
    canvas_matte: 110.00,
    glittered: 120.00,
    finish_3d: 120.00,
    rainbow: 130.00,
    broken_glass: 130.00
};

const sintraBulkPricing = {
    glossy: 90.00,
    matte: 90.00,
    leather: 100.00,
    canvas_matte: 100.00,
    glittered: 110.00,
    finish_3d: 110.00,
    rainbow: 120.00,
    broken_glass: 120.00
};

const largeFormatFinishOptions = [
    { value: 'glossy', label: 'Glossy Finish' },
    { value: 'matte', label: 'Matte Finish' },
    { value: 'leather', label: 'Leather Finish' },
    { value: 'canvas_matte', label: 'Canvas Matte Finish' },
    { value: 'glittered', label: 'Glittered Finish' },
    { value: 'finish_3d', label: '3D Finish' },
    { value: 'rainbow', label: 'Rainbow Finish' },
    { value: 'broken_glass', label: 'Broken Glass Finish' }
];

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
    'Package E': "Inclusions: 6pcs 1.5x1.5", 'Package F': "Inclusions: 5pcs Wallet Size (2.5 x 3.5 in)"
};

const idPackageOptions = [
    { value: 'Package A', label: 'Package A - 1x1 & 2x2 Mixed' },
    { value: 'Package B', label: 'Package B - 1x1 (8pcs)' },
    { value: 'Package C', label: 'Package C - 2x2 (8pcs)' },
    { value: 'Package D', label: 'Package D - Passport Size (5pcs)' },
    { value: 'Package E', label: 'Package E - 1.5 x 1.5 (6pcs)' },
    { value: 'Package F', label: 'Package F - Wallet Size (5pcs)' }
];

const idWorkflowPackageOptions = {
    id_photo: ['Package A', 'Package B', 'Package C', 'Package E', 'Package F'],
    passport_visa: ['passport', 'visa']
};

const fileTypeChoices = {
    printing: ['PDF', 'DOCX', 'PPTX', 'PNG'],
    xerox: ['PDF', 'DOCX', 'JPG', 'PNG'],
    id: ['JPG', 'PNG', 'PDF'],
    largeformat: ['PDF', 'PNG', 'PSD', 'AI'],
    binding: ['PDF', 'DOCX', 'PPTX'],
    special: ['PDF', 'PNG', 'PSD', 'AI']
};

const idWorkflowMeta = {
    id_photo: {
        label: 'ID Photo',
        note: 'Standard ID photo preparation and printing for school, company, and general profile requirements. Upload a clear high-resolution image for the best facial detail and print alignment.',
        inclusionLabel: 'Standard ID photo prep and print'
    },
    passport_visa: {
        label: 'Passport / Visa',
        note: 'Use this workflow for passport or visa photo preparation and printing. Choose Passport or Visa in Document Type, then confirm the embassy or visa-center photo requirement before checkout.',
        inclusionLabel: 'Passport / visa photo prep'
    },
    single_photo_print: {
        label: 'Single Photo Print',
        note: 'Best for standalone photo printing. Recommended for keepsakes, profile prints, or single-size reprints on premium photo paper.',
        inclusionLabel: 'Single photo print workflow'
    }
};

const idColorVariations = {
    id_photo: [
        { value: 'full', label: 'Full Color' },
        { value: 'bw', label: 'Black & White' },
        { value: 'matte_tone', label: 'Matte Tone' }
    ],
    passport_visa: [
        { value: 'full', label: 'Full Color' },
        { value: 'bw', label: 'Black & White' },
        { value: 'studio_bright', label: 'Studio Bright' }
    ],
    single_photo_print: [
        { value: 'full', label: 'Full Color' },
        { value: 'bw', label: 'Black & White' },
        { value: 'warm_tone', label: 'Warm Tone' },
        { value: 'cool_tone', label: 'Cool Tone' }
    ]
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
        if (currentCategoryType === 'id') {
            const workflowItems = getIdWorkflowModalItems();
            currentModalItemCount = workflowItems.length;
            workflowItems.forEach((item) => {
                track.innerHTML += `
                    <div class="category-card id-category-card" onclick="openIdWorkflowDetail('${item.workflow}')">
                        <div class="category-card-media id-category-media">
                            <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.label)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                        </div>
                        <div class="category-card-body">
                            <div class="category-label">Printing Category</div>
                            <h4>${escapeHtml(item.label)}</h4>
                        </div>
                        <button type="button" class="select-type-btn">View Option</button>
                    </div>`;
            });
        } else {
            currentModalItemCount = currentCategorySet.length;
            currentCategorySet.forEach((cat, index) => {
                const previewImage = withFallbackImage(cat.imgs?.[0] || data.serviceImage);
                const caption = getGalleryCaption(cat.name);
                const isSelected = currentSelectedOptionIndex === index;
                const metaLabel = 'Printing Category';
                const metaValue = caption.sub || '';
                const cardTypeClass = currentCategoryType === 'id'
                    ? 'id-category-card'
                    : (currentCategoryType === 'xerox' ? 'copy-category-card' : (['largeformat', 'binding', 'special'].includes(currentCategoryType) ? 'promo-category-card' : ''));
                const mediaTypeClass = currentCategoryType === 'id'
                    ? 'id-category-media'
                    : (currentCategoryType === 'xerox' ? 'copy-category-media' : (['largeformat', 'binding', 'special'].includes(currentCategoryType) ? 'promo-category-media' : ''));
                track.innerHTML += `
                    <div class="category-card ${isSelected ? 'is-selected' : ''} ${cardTypeClass}" onclick="openDetail(${index})">
                        <div class="category-card-media ${mediaTypeClass}">
                            <img src="${escapeHtml(previewImage)}" alt="${escapeHtml(cat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                        </div>
                        <div class="category-card-body">
                            <div class="category-label">${escapeHtml(metaLabel)}</div>
                            <h4>${escapeHtml(caption.main)}</h4>
                            ${metaValue ? `<div class="category-context">${escapeHtml(metaValue)}</div>` : ''}
                        </div>
                        <button type="button" class="select-type-btn">
                            ${isSelected ? 'Selected' : 'View Option'}
                        </button>
                    </div>`;
            });
        }
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
    const total = currentModalItemCount || currentCategorySet.length;
    currentSlideIndex += dir;
    if (currentSlideIndex < 0) currentSlideIndex = 0;
    if (currentSlideIndex >= total) currentSlideIndex = total - 1;
    track.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
    updateModalButtons();
}

function updateModalButtons() {
    const prev = document.getElementById('modalPrev');
    const next = document.getElementById('modalNext');
    const total = currentModalItemCount || currentCategorySet.length;
    if (prev) prev.style.display = (currentSlideIndex === 0) ? 'none' : 'flex';
    if (next) next.style.display = (currentSlideIndex >= total - 1) ? 'none' : 'flex';
}

function getCurrentDetailCategory() {
    return currentCategorySet[currentSelectedOptionIndex] || currentCategorySet[0] || { name: '', imgs: [], specs: '' };
}

function getIdCategoryIndexByName(categoryName) {
    return currentCategorySet.findIndex((category) => category.name === categoryName);
}

function getIdPackageDisplayLabel(value) {
    const labels = {
        passport: 'Passport',
        visa: 'Visa'
    };
    return labels[value] || value;
}

function getIdPhotoPackageLabel(value) {
    const labels = {
        'Package A': 'Package A: Mixed 1x1 & 2x2',
        'Package B': 'Package B: 1x1 Set',
        'Package C': 'Package C: 2x2 Set',
        'Package E': 'Package D: 1.5 x 1.5 Set',
        'Package F': 'Package E: Wallet Size Set'
    };
    return labels[value] || value;
}

function getIdPackageHighlight(value) {
    const inclusion = idDetails[value] || '';
    return inclusion.replace(/^Inclusions:\s*/i, '').trim();
}

function getIdPackageUseCase(value) {
    const useCases = {
        'Package A': 'Best for mixed 1x1 and 2x2 needs',
        'Package B': 'Best for compact ID requirements',
        'Package C': 'Best for standard 2x2 submissions',
        'Package E': 'Best for compact square-profile photo sets',
        'Package F': 'Best for wallet-size photo copies'
    };

    return useCases[value] || 'Best for school, office, and profile requirements';
}

function getActiveContentTypeValue() {
    return document.getElementById('contentTypeSelect')?.value || '';
}

function getXeroxPreviewImage(printCategoryValue, serviceOptionValue, colorValue) {
    const imageMap = {
        text_only: {
            bw: 'images/TXTONLY (B&W).png',
            partial: 'images/TXTONLY (PC).png',
            full: 'images/TXTONLY (FC).png'
        },
        text_image: {
            bw: 'images/TXTWI (B&W).png',
            partial: 'images/TXTWI (PC).png',
            full: 'images/TXTWI (FC).png'
        },
        image_only: {
            bw: 'images/IO (B&W).png',
            partial: 'images/IO (PC).png',
            full: 'images/IO (FC).png'
        }
    };

    const categoryFallback = printCategoryValue === 'scanning'
        ? 'images/TXTONLY (B&W).png'
        : 'images/PHOTOC (B&W).png';

    return withFallbackImage(imageMap[serviceOptionValue]?.[colorValue] || categoryFallback);
}

function getIdPhotoPackageMeta(value) {
    const meta = {
        'Package A': { visibleLabel: 'Package A', code: 'A' },
        'Package B': { visibleLabel: 'Package B', code: 'B' },
        'Package C': { visibleLabel: 'Package C', code: 'C' },
        'Package E': { visibleLabel: 'Package D', code: 'D' },
        'Package F': { visibleLabel: 'Package E', code: 'E' }
    };

    return meta[value] || { visibleLabel: value, code: serviceIdCode(value) };
}

function getCurrentPrintCategoryLabel() {
    return document.getElementById('printCategory')?.selectedOptions[0]?.textContent?.trim() || '';
}

function getCurrentSecondarySelectionLabel() {
    return document.getElementById('paperSize')?.selectedOptions[0]?.textContent?.trim() || '';
}

function getGalleryCaption(categoryName) {
    const workflowLabel = getCurrentPrintCategoryLabel();
    if (currentCategoryType !== 'id' || !workflowLabel) {
        return { main: categoryName, sub: '' };
    }

    if (workflowLabel === 'Passport / Visa') {
        return {
            main: categoryName,
            sub: `${workflowLabel} - ${getCurrentSecondarySelectionLabel() || 'Passport'}`
        };
    }

    return {
        main: categoryName,
        sub: workflowLabel
    };
}

function getServiceSeriesCode() {
    if (currentCategoryType === 'printing') return 'DOC';
    if (currentCategoryType === 'xerox') return 'PCS';
    if (currentCategoryType === 'id') return 'IDP';
    if (currentCategoryType === 'largeformat') return 'LFP';
    if (currentCategoryType === 'binding') return 'BND';
    if (currentCategoryType === 'special') return 'CSP';
    return 'SRV';
}

function getBulkRule(categoryValue = '') {
    if (currentCategoryType === 'largeformat' && categoryValue === 'sintra_board_printing') {
        return {
            threshold: SINTRA_BULK_MIN_PCS,
            unit: 'pcs',
            note: `Bulk pricing is available for ${SINTRA_BULK_MIN_PCS}+ pcs on A4 Sintra Board orders. Confirm your final quantity before checkout.`
        };
    }

    return {
        threshold: BULK_MIN_PAGES,
        unit: 'pages',
        note: `Bulk pricing is available for ${BULK_MIN_PAGES}+ pages. Upload a print-ready file and confirm the final page count before checkout.`
    };
}

function buildDisplayServiceId(...parts) {
    return [getServiceSeriesCode(), ...parts.filter(Boolean)].join('-');
}

function getIdWorkflowSidebarItems() {
    const packageA = currentCategorySet[getIdCategoryIndexByName('Package A')] || currentCategorySet[0] || {};
    const packageD = currentCategorySet[getIdCategoryIndexByName('Package D')] || packageA;
    const singlePhoto = currentCategorySet[getIdCategoryIndexByName('SINGLE PHOTO')] || packageA;

    return [
        {
            workflow: 'id_photo',
            label: 'ID Photo',
            image: withFallbackImage(packageA.imgs?.[0])
        },
        {
            workflow: 'passport_visa',
            label: 'Passport / Visa',
            image: withFallbackImage(packageD.imgs?.[0])
        },
        {
            workflow: 'single_photo_print',
            label: 'Single Photo Print',
            image: withFallbackImage(singlePhoto.imgs?.[0])
        }
    ];
}

function getIdWorkflowModalItems() {
    const packageA = currentCategorySet[getIdCategoryIndexByName('Package A')] || currentCategorySet[0] || {};
    const packageD = currentCategorySet[getIdCategoryIndexByName('Package D')] || packageA;
    const singlePhoto = currentCategorySet[getIdCategoryIndexByName('SINGLE PHOTO')] || packageA;

    return [
        {
            workflow: 'id_photo',
            label: 'ID Photo',
            image: withFallbackImage(packageA.imgs?.[0])
        },
        {
            workflow: 'passport_visa',
            label: 'Passport / Visa',
            image: withFallbackImage(packageD.imgs?.[0])
        },
        {
            workflow: 'single_photo_print',
            label: 'Single Photo Print',
            image: withFallbackImage(singlePhoto.imgs?.[0])
        }
    ];
}

function selectIdWorkflow(workflowValue) {
    const printCategory = document.getElementById('printCategory');
    if (!printCategory || currentCategoryType !== 'id') return;

    printCategory.value = workflowValue;
    syncPreviewFromDropdowns();
    updatePrice();
}

function openIdWorkflowDetail(workflowValue) {
    const targetName = workflowValue === 'passport_visa'
        ? 'Package D'
        : (workflowValue === 'single_photo_print' ? 'SINGLE PHOTO' : 'Package A');
    const targetIndex = getIdCategoryIndexByName(targetName);
    if (targetIndex < 0) return;

    openDetail(targetIndex);

    const printCategory = document.getElementById('printCategory');
    if (printCategory) {
        printCategory.value = workflowValue;
        syncPreviewFromDropdowns();
        updatePrice();
    }
}

function getIdPhotoPreviewItems() {
    const allowedPackages = idWorkflowPackageOptions.id_photo || [];
    return allowedPackages
        .map((packageName) => {
            const category = currentCategorySet.find((item) => item.name === packageName);
            if (!category) return null;
            return {
                name: packageName,
                image: withFallbackImage(category.imgs?.[0]),
                specs: category.specs || ''
            };
        })
        .filter(Boolean);
}

function updatePaperSizeLabel(categoryName = '') {
    const label = document.getElementById('paperSizeLabel');
    if (!label) return;

    let nextLabel = 'Paper Size';

    if (currentCategoryType === 'id') {
        const workflowValue = document.getElementById('printCategory')?.value || '';
        if (workflowValue === 'passport_visa') {
            nextLabel = 'Document Type';
        } else if (workflowValue === 'id_photo') {
            nextLabel = 'ID Package';
        } else {
            nextLabel = categoryName === 'SINGLE PHOTO' ? 'Photo Size' : 'Selected Package';
        }
    } else if (currentCategoryType === 'largeformat') {
        nextLabel = 'Board Size';
    } else if (currentCategoryType === 'binding') {
        nextLabel = 'Document Size';
    } else if (currentCategoryType === 'special') {
        nextLabel = 'Output Size';
    }

    label.textContent = nextLabel;
}

function updateDetailFieldLabels(categoryName = '') {
    const printCategoryLabel = document.getElementById('printCategoryLabel');
    const serviceOptionLabel = document.getElementById('serviceOptionLabel');
    const contentTypeLabel = document.getElementById('contentTypeLabel');
    const contentTypeGroup = document.getElementById('contentTypeGroup');
    const detailInfoPanel = document.getElementById('detailInfoPanel');

    if (currentCategoryType === 'id') {
        if (detailInfoPanel) detailInfoPanel.classList.remove('is-xerox-layout');
        if (detailInfoPanel) detailInfoPanel.classList.remove('is-largeformat-layout');
        if (contentTypeGroup) contentTypeGroup.style.display = 'none';
        if (printCategoryLabel) {
            printCategoryLabel.textContent = 'Printing Category';
        }

        if (serviceOptionLabel) {
            serviceOptionLabel.textContent = 'Service Option';
        }
    } else if (currentCategoryType === 'largeformat') {
        if (detailInfoPanel) detailInfoPanel.classList.remove('is-xerox-layout');
        if (detailInfoPanel) detailInfoPanel.classList.add('is-largeformat-layout');
        if (contentTypeGroup) contentTypeGroup.style.display = '';
        if (printCategoryLabel) {
            printCategoryLabel.textContent = 'Printing Category';
        }
        if (contentTypeLabel) {
            contentTypeLabel.textContent = 'Finish Type';
        }
        if (serviceOptionLabel) {
            serviceOptionLabel.textContent = 'Finish Option';
        }
    } else if (currentCategoryType === 'xerox') {
        if (detailInfoPanel) detailInfoPanel.classList.add('is-xerox-layout');
        if (detailInfoPanel) detailInfoPanel.classList.remove('is-largeformat-layout');
        if (contentTypeGroup) contentTypeGroup.style.display = '';
        if (printCategoryLabel) {
            printCategoryLabel.textContent = 'Printing Category';
        }
        if (contentTypeLabel) {
            contentTypeLabel.textContent = 'Content Type';
        }
        if (serviceOptionLabel) {
            serviceOptionLabel.textContent = 'Service Option';
        }
    } else {
        if (detailInfoPanel) detailInfoPanel.classList.remove('is-xerox-layout');
        if (detailInfoPanel) detailInfoPanel.classList.remove('is-largeformat-layout');
        if (contentTypeGroup) contentTypeGroup.style.display = 'none';
        if (printCategoryLabel) {
            printCategoryLabel.textContent = 'Printing Category';
        }
        if (serviceOptionLabel) {
            serviceOptionLabel.textContent = 'Service Option';
        }
    }

    updatePaperSizeLabel(categoryName);
}

function renderMetaMaterial(value) {
    return `
        <span class="meta-kicker">Material</span>
        <span class="meta-main">${escapeHtml(value)}</span>
    `;
}

function renderMetaHighlights(parts) {
    const seen = new Set();
    const validParts = parts.filter((part) => {
        if (!part) return false;
        const key = String(part).trim().toLowerCase();
        if (!key || seen.has(key)) return false;
        seen.add(key);
        return true;
    });
    if (!validParts.length) {
        return '<span class="meta-empty">Select a variation to view inclusions and service details.</span>';
    }

    return `
        <span class="meta-kicker">Highlights</span>
        <div class="meta-chip-row">
            ${validParts.map((part) => `<span class="meta-chip">${escapeHtml(part)}</span>`).join('')}
        </div>
    `;
}

function getSmartHighlights() {
    const printCategoryValue = document.getElementById('printCategory')?.value || '';
    const category = getCurrentDetailCategory();

    if (currentCategoryType === 'printing') {
        const categoryHighlights = {
            text_only: 'Sharp text output for reports, letters, and everyday documents',
            text_image: 'Balanced text and image printing for handouts, reviewers, and school work',
            image_only: 'Visual-focused page printing for posters, inserts, and graphics'
        };

        return [categoryHighlights[printCategoryValue] || 'Reliable document printing for school, office, and daily use'];
    }

    if (currentCategoryType === 'xerox') {
        const contentTypeValue = getActiveContentTypeValue() || 'text_only';
        const xeroxHighlights = {
            photocopy: {
                text_only: 'Fast, reliable duplication for reports, forms, and office paperwork',
                text_image: 'Clear duplication for mixed-content pages, reviewers, and handouts',
                image_only: 'Graphic-friendly duplication for visuals, inserts, and reference pages'
            },
            scanning: {
                text_only: 'Clean document scanning for archiving, upload-ready files, and digital sharing',
                text_image: 'Mixed-content scanning for modules, reviewers, and soft-copy submissions',
                image_only: 'Image-focused scanning for graphics, photos, and visual references'
            }
        };

        return [xeroxHighlights[printCategoryValue]?.[contentTypeValue] || 'Walk-in friendly document reproduction service'];
    }

    if (currentCategoryType === 'id') {
        if (printCategoryValue === 'passport_visa') {
            return ['Passport and visa photo prep with size guidance for official applications'];
        }

        if (printCategoryValue === 'single_photo_print') {
            return ['Premium photo printing for keepsakes, albums, and display-ready copies'];
        }

        const selectedPackage = document.getElementById('paperSize')?.value || category.name;
        const inclusion = getIdPackageHighlight(selectedPackage);
        const useCase = getIdPackageUseCase(selectedPackage);
        return [inclusion ? `${inclusion} - ${useCase}` : useCase];
    }

    if (currentCategoryType === 'largeformat') {
        const finishLabel = document.getElementById('contentTypeSelect')?.selectedOptions[0]?.textContent?.trim() || 'display-ready finish';
        return [`Large-format board printing with ${finishLabel.toLowerCase()} for signage and presentation displays`];
    }

    if (currentCategoryType === 'binding') {
        const bindingHighlights = {
            lamination: 'Protective lamination for preserving frequently handled documents',
            spiral_binding: 'Spiral binding for organized reports, reviewers, and presentation sets'
        };

        return [bindingHighlights[printCategoryValue] || 'Document finishing service for school and office submissions'];
    }

    if (currentCategoryType === 'special') {
        const specialHighlights = {
            custom_layout: 'Custom print setup for one-off layouts, tailored requests, and unique designs',
            marketing_collateral: 'Branded print production for flyers, promos, and marketing materials',
            sticker_cut: 'Sticker and label production with cut-ready output for custom applications'
        };

        return [specialHighlights[printCategoryValue] || 'Custom print service for specialized production requests'];
    }

    return [];
}

function updateDetailTitle(category) {
    const titleEl = document.getElementById('detailTitleHeader');
    const categoryEl = document.getElementById('detailCategoryHeader');
    if (!titleEl) return;

    const selectedPrintCategory = document.getElementById('printCategory')?.selectedOptions[0]?.textContent?.trim() || '';
    let titleText = category?.name || '';
    const parentCategory = allData[currentModalKey]?.name || '';

    if (currentCategoryType !== 'largeformat' && selectedPrintCategory) {
        titleText = selectedPrintCategory;
    }

    if (categoryEl) {
        categoryEl.textContent = parentCategory || category?.name || '';
    }

    titleEl.innerText = titleText || category?.name || '';
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

    const hasPreferredValue = preferredValue && [...select.options].some((option) => option.value === preferredValue);
    if (hasPreferredValue) {
        select.value = preferredValue;
        return;
    }

    select.value = placeholder ? '' : (select.options[0]?.value || '');
}

function renderDetailGallery(cat, index) {
    if (!cat) return;

    currentSelectedOptionIndex = index;
    document.getElementById('detailTitleHeader').innerText = cat.name;
    document.getElementById('productSpecs').innerHTML = cat.specs;
    document.getElementById('productSpecs').style.display = cat.specs ? '' : 'none';

    const previewTrack = document.getElementById('previewTrack');
    previewTrack.innerHTML = '';
    const workflowValue = document.getElementById('printCategory')?.value || '';
    if (currentCategoryType === 'id' && workflowValue === 'id_photo') {
        const previewItems = getIdPhotoPreviewItems();
        previewItems.forEach((item) => {
            previewTrack.innerHTML += `<img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';" style="min-width:100%; height:100%; object-fit:contain;">`;
        });
        const paperSize = document.getElementById('paperSize');
        const nextPreviewIndex = previewItems.findIndex((item) => item.name === (paperSize?.value || cat.name));
        currentPreviewIndex = nextPreviewIndex >= 0 ? nextPreviewIndex : 0;
    } else if (currentCategoryType === 'xerox') {
        const contentTypeValue = getActiveContentTypeValue() || 'text_only';
        const colorValue = document.getElementById('colorMode')?.value || 'bw';
        const previewImage = getXeroxPreviewImage(workflowValue || 'photocopy', contentTypeValue, colorValue);
        previewTrack.innerHTML = `<img src="${escapeHtml(previewImage)}" alt="${escapeHtml(cat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';" style="min-width:100%; height:100%; object-fit:contain;">`;
        currentPreviewIndex = 0;
    } else {
        cat.imgs.forEach((imgSrc) => {
            previewTrack.innerHTML += `<img src="${escapeHtml(withFallbackImage(imgSrc))}" alt="${escapeHtml(cat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';" style="min-width:100%; height:100%; object-fit:contain;">`;
        });
        currentPreviewIndex = 0;
    }
    previewTrack.style.transform = 'translateX(0)';
    previewTrack.style.transform = `translateX(-${Math.max(currentPreviewIndex, 0) * 100}%)`;
    updatePreviewButtons();

    const sidebarTrack = document.getElementById('sidebarTrack');
    sidebarTrack.innerHTML = '';
    if (currentCategoryType === 'id') {
        const activeWorkflow = document.getElementById('printCategory')?.value || 'id_photo';
        getIdWorkflowSidebarItems().forEach((item) => {
            sidebarTrack.innerHTML += `
                <div class="sidebar-item ${item.workflow === activeWorkflow ? 'active' : ''}" onclick="selectIdWorkflow('${item.workflow}')">
                    <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.label)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                    <p>
                        <span class="sidebar-caption-main">${escapeHtml(item.label)}</span>
                    </p>
                </div>`;
        });
    } else {
        currentCategorySet.forEach((sidebarCat, idx) => {
            const sidebarImage = withFallbackImage(sidebarCat.imgs?.[0]);
            const caption = getGalleryCaption(sidebarCat.name);
            sidebarTrack.innerHTML += `
                <div class="sidebar-item ${idx === index ? 'active' : ''}" onclick="openDetail(${idx})">
                    <img src="${escapeHtml(sidebarImage)}" alt="${escapeHtml(sidebarCat.name)}" onerror="this.onerror=null;this.src='${fallbackImage}';">
                    <p>
                        <span class="sidebar-caption-main">${escapeHtml(caption.main)}</span>
                        ${caption.sub ? `<span class="sidebar-caption-sub">${escapeHtml(caption.sub)}</span>` : ''}
                    </p>
                </div>`;
        });
    }
}

function updateServiceSelectors(categoryName) {
    const optionSelect = document.getElementById('serviceOptionSelect');
    const contentTypeSelect = document.getElementById('contentTypeSelect');
    const fileTypeSelect = document.getElementById('fileTypeSelect');
    const currentOptionValue = optionSelect?.value || '';
    const currentContentTypeValue = contentTypeSelect?.value || '';
    const currentFileTypeValue = fileTypeSelect?.value || '';
    const size = document.getElementById('paperSize')?.value || '';
    const printCategoryValue = document.getElementById('printCategory')?.value || '';

    let options = [];

    if (currentCategoryType === 'printing') {
        options = ['Standard Print', 'Fast Turnaround', 'Collated Set', 'Layout Check'];
    } else if (currentCategoryType === 'xerox') {
        const contentTypes = [
            { value: 'text_only', label: 'Text Only' },
            { value: 'text_image', label: 'Text with Image' },
            { value: 'image_only', label: 'Image Only' }
        ];
        setSelectOptions('contentTypeSelect', contentTypes, 'Select Content Type', currentContentTypeValue);
        options = printCategoryValue === 'scanning'
            ? [
                { value: 'standard_scan', label: 'Standard Scan' },
                { value: 'multi_page_scan', label: 'Multi-page Scan' },
                { value: 'archive_ready_file', label: 'Archive-ready File' }
            ]
            : [
                { value: 'walk_in_copy', label: 'Walk-in Copy' },
                { value: 'sorted_set', label: 'Sorted Set' },
                { value: 'stapled_set', label: 'Stapled Set' }
            ];
    } else if (currentCategoryType === 'id') {
        const packageSelectionValue = document.getElementById('paperSize')?.value || '';
        if (printCategoryValue === 'passport_visa' && packageSelectionValue === 'passport') {
            options = ['Passport Crop Check', 'Background Compliance Check', 'Glossy Finish', 'Basic Retouch'];
        } else if (printCategoryValue === 'passport_visa' && packageSelectionValue === 'visa') {
            options = ['Visa Spec Review', 'Background Compliance Check', 'Glossy Finish', 'Basic Retouch'];
        } else if (printCategoryValue === 'single_photo_print') {
            options = ['Glossy Finish', 'Matte Finish', 'Borderless Crop', 'Photo Editing / Retouch'];
        } else {
            options = categoryName !== 'SINGLE PHOTO'
                ? ['Basic Retouch', 'Background Cleanup', 'Glossy Finish', 'Soft Copy Included']
                : ['Glossy Finish', 'Matte Finish', 'Borderless Crop', 'Photo Editing / Retouch'];
        }
    } else if (currentCategoryType === 'largeformat') {
        setSelectOptions('contentTypeSelect', largeFormatFinishOptions, 'Select Finish Type', currentContentTypeValue || 'glossy');
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
    const specsEl = document.getElementById('productSpecs');
    const category = getCurrentDetailCategory();
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const paperSize = document.getElementById('paperSize');

    const selectedCategory = printCategory?.selectedOptions[0]?.textContent || '';
    const selectedColor = colorMode?.selectedOptions[0]?.textContent || '';
    const selectedSize = paperSize?.selectedOptions[0]?.textContent || '';

    let material = 'Premium 80gsm Bond Paper';
    let inclusionParts = [];
    let notes = 'Files that require editing, layout adjustments, or design enhancement may have additional charges depending on the type and complexity of the service needed. For best results, please upload high-resolution files.';

    if (currentCategoryType === 'printing') {
        material = 'Premium 80gsm Bond Paper';
        inclusionParts = getSmartHighlights();
        const printingNotes = {
            text_only: 'Ideal for reports, letters, and everyday paperwork. Upload a clean PDF or DOCX file for sharper, more professional-looking output.',
            text_image: 'Great for handouts, modules, and mixed-content pages. Upload high-quality files so text stays clear and images print more smoothly.',
            image_only: 'Best for graphic-heavy pages, inserts, and visual materials. Use high-resolution files for stronger color coverage and cleaner detail.'
        };
        notes = printingNotes[printCategory?.value] || 'Reliable document printing for school, office, and presentation needs. Upload a clean file for the best-looking result.';
    } else if (currentCategoryType === 'xerox') {
        material = 'Standard 80gsm Copy Paper';
        inclusionParts = getSmartHighlights();
        notes = printCategory?.value === 'scanning'
            ? 'Best for turning paper documents into upload-ready digital files. Bring flat, clean originals for clearer scans and easier archiving or sharing.'
            : 'Designed for fast, dependable duplication of reports, forms, and office paperwork. Clean originals help produce sharper and more consistent copies.';
    } else if (currentCategoryType === 'id') {
        material = 'Premium Photo Paper (260gsm)';
        const workflow = idWorkflowMeta[printCategory?.value] || idWorkflowMeta.id_photo;
        if (printCategory?.value === 'passport_visa') {
            const packageTypeLabel = getIdPackageDisplayLabel(paperSize?.value || 'passport');
            inclusionParts = getSmartHighlights();
            notes = packageTypeLabel === 'Passport'
                ? 'Prepared for passport-photo printing with a cleaner, more official-looking finish. Please confirm the latest passport-photo requirement before checkout.'
                : 'Best for visa-photo preparation and printing. Since size rules can vary by embassy or consulate, please confirm the exact requirement before ordering.';
        } else if (printCategory?.value === 'single_photo_print') {
            inclusionParts = getSmartHighlights();
            notes = 'A great choice for keepsakes, framed prints, and display copies. Upload a clear, high-resolution image for better sharpness and color depth.';
        } else {
            inclusionParts = getSmartHighlights();
            const idPackageNotes = {
                'Package A': 'A practical bundle for mixed 1x1 and 2x2 needs. Upload a clear, front-facing photo for better facial detail and cleaner print alignment.',
                'Package B': 'Best for compact 1x1 requirements and quick profile-photo needs. A high-resolution image will help keep the final prints sharp and balanced.',
                'Package C': 'A solid option for standard 2x2 submissions and formal requirements. Clear image files help produce sharper details and more even output.',
                'Package D': 'Prepared for passport-style photo requests and official-looking output. Upload a clear recent image for better detail and cleaner cropping.',
                'Package E': 'A neat option for compact square-format photo requests. Use a clear image to keep the final prints crisp and well-centered.',
                'Package F': 'Great for wallet-size copies and portable keepsake prints. Upload a sharp image for cleaner detail in the smaller output size.'
            };
            notes = idPackageNotes[category.name] || workflow.note;
        }
    } else if (currentCategoryType === 'largeformat') {
        material = 'Sintra Board (3mm Flat PVC)';
        inclusionParts = getSmartHighlights();
        const finishLabel = document.getElementById('contentTypeSelect')?.selectedOptions[0]?.textContent?.trim() || 'selected finish';
        notes = `Best for signage, mounted visuals, and display-ready presentations. ${finishLabel} adds a more tailored large-format finish depending on the look you want for the final board.`;
    } else if (currentCategoryType === 'binding') {
        material = 'Document Cover and Binding Supplies';
        inclusionParts = getSmartHighlights();
        notes = printCategory?.value === 'lamination'
            ? 'A smart choice for protecting certificates, forms, and frequently handled documents. Turnaround may vary depending on sheet count and finishing demand.'
            : 'Ideal for reports, reviewers, proposals, and presentation sets. Turnaround may vary depending on page count and cover-stock availability.';
    } else if (currentCategoryType === 'special') {
        material = 'Custom Production Materials';
        inclusionParts = getSmartHighlights();
        notes = 'Best for custom requests that need layout review, special finishing, or non-standard output. Final pricing may change depending on trimming, complexity, and production requirements.';
    }

    if (materialEl) materialEl.innerHTML = renderMetaMaterial(material);
    if (inclusionsEl) inclusionsEl.innerHTML = renderMetaHighlights(inclusionParts);
    if (notesEl) notesEl.textContent = notes;
    if (specsEl) {
        specsEl.textContent = '';
        specsEl.style.display = 'none';
    }

    updateDetailTitle(category);
    updateDetailFieldLabels(category.name);
    updateServiceSelectors(category.name);
}

function openDetail(index) {
    const cat = currentCategorySet[index];
    if (!cat) return;

    document.body.classList.add('services-active');
    updateActiveNavLink('services');

    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const paperSize = document.getElementById('paperSize');

    if (currentCategoryType === 'printing') {
        printCategory.innerHTML = `<option value="text_only">Text Only</option><option value="text_image">Text with Image</option><option value="image_only">Image Only</option>`;
        paperSize.innerHTML = `<option value="short">Short (8.5 x 11)</option><option value="a4">A4 (8.27 x 11.69)</option><option value="legal">Legal (8.5 x 14)</option>`;
        colorMode.innerHTML = `<option value="bw">B&W</option><option value="partial">Partial Color</option><option value="full">Full Color</option>`;

        const printIndex = Math.max(index, 0);
        printCategory.value = ['text_only', 'text_image', 'image_only'][printIndex] || 'text_only';
        colorMode.value = ['bw', 'partial', 'full'][Math.min(printIndex, 2)] || 'bw';
    }

    if (currentCategoryType === 'xerox') {
        printCategory.innerHTML = `<option value="photocopy">Photocopy</option><option value="scanning">Scanning</option>`;
        paperSize.innerHTML = `<option value="short">Short (8.5 x 11)</option><option value="a4">A4 (8.27 x 11.69)</option><option value="legal">Legal (8.5 x 14)</option>`;
        colorMode.innerHTML = `<option value="bw">B&W</option><option value="partial">Partial Color</option><option value="full">Full Color</option>`;
        printCategory.value = index === 1 ? 'scanning' : 'photocopy';
        colorMode.value = 'bw';
        const contentTypeSelect = document.getElementById('contentTypeSelect');
        if (contentTypeSelect && !contentTypeSelect.value) {
            setSelectOptions('contentTypeSelect', [
                { value: 'text_only', label: 'Text Only' },
                { value: 'text_image', label: 'Text with Image' },
                { value: 'image_only', label: 'Image Only' }
            ], 'Select Content Type', 'text_only');
        }
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

    if (currentCategoryType === 'printing') {
        const categoryIndexMap = { text_only: 0, text_image: 1, image_only: 2 };
        const nextIndex = categoryIndexMap[printCategory?.value] ?? currentSelectedOptionIndex ?? 0;
        if (nextIndex !== currentSelectedOptionIndex && currentCategorySet[nextIndex]) {
            renderDetailGallery(currentCategorySet[nextIndex], nextIndex);
        }
        currentPreviewIndex = Math.min(colorMode?.selectedIndex ?? 0, previewTrack.querySelectorAll('img').length - 1);
    } else if (currentCategoryType === 'xerox') {
        const categoryIndexMap = { photocopy: 0, scanning: 1 };
        const nextIndex = categoryIndexMap[printCategory?.value] ?? currentSelectedOptionIndex ?? 0;
        if (nextIndex !== currentSelectedOptionIndex && currentCategorySet[nextIndex]) {
            renderDetailGallery(currentCategorySet[nextIndex], nextIndex);
        } else if (currentCategorySet[nextIndex]) {
            renderDetailGallery(currentCategorySet[nextIndex], nextIndex);
        }
        currentPreviewIndex = 0;
    } else if (currentCategoryType === 'id') {
        const workflowValue = printCategory?.value || 'id_photo';
        const shouldUsePackages = workflowValue !== 'single_photo_print';
        const currentPaperValue = paperSize?.value || '';

        if (workflowValue === 'passport_visa') {
            const passportPackageIndex = getIdCategoryIndexByName('Package D');
            if (passportPackageIndex >= 0 && passportPackageIndex !== currentSelectedOptionIndex) {
                renderDetailGallery(currentCategorySet[passportPackageIndex], passportPackageIndex);
            }
            updateDropdownsForID(currentPaperValue || 'passport');
            currentPreviewIndex = 0;
        } else if (shouldUsePackages) {
            const selectedPackageName = paperSize?.value || getCurrentDetailCategory().name;
            const nextIndex = getIdCategoryIndexByName(selectedPackageName);
            if (nextIndex >= 0 && nextIndex !== currentSelectedOptionIndex) {
                renderDetailGallery(currentCategorySet[nextIndex], nextIndex);
            } else if (nextIndex >= 0) {
                currentSelectedOptionIndex = nextIndex;
                currentPreviewIndex = Math.max(0, (idWorkflowPackageOptions.id_photo || []).indexOf(selectedPackageName));
                previewTrack.style.transform = `translateX(-${Math.max(currentPreviewIndex, 0) * 100}%)`;
                updatePreviewButtons();
            }
            updateDropdownsForID(selectedPackageName);
        } else {
            if (getCurrentDetailCategory().name !== 'SINGLE PHOTO') {
                const singlePhotoIndex = getIdCategoryIndexByName('SINGLE PHOTO');
                if (singlePhotoIndex >= 0) {
                    renderDetailGallery(currentCategorySet[singlePhotoIndex], singlePhotoIndex);
                }
            }
            updateDropdownsForID('SINGLE PHOTO');
            currentPreviewIndex = Math.min(paperSize?.selectedIndex ?? 0, previewTrack.querySelectorAll('img').length - 1);
        }
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
    const currentColorValue = colorMode?.value || 'full';
    const currentWorkflowValue = printCategory?.value || 'id_photo';
    const currentPaperValue = paperSize?.value || '';
    const packageBasedWorkflow = currentWorkflowValue !== 'single_photo_print';
    paperSize.innerHTML = '';

    if (packageBasedWorkflow) {
        const allowedPackages = idWorkflowPackageOptions[currentWorkflowValue] || idWorkflowPackageOptions.id_photo;
        if (currentWorkflowValue === 'passport_visa') {
            allowedPackages.forEach((optionValue) => {
                const el = document.createElement('option');
                el.value = optionValue;
                el.textContent = getIdPackageDisplayLabel(optionValue);
                paperSize.appendChild(el);
            });
        } else {
            idPackageOptions
                .filter((option) => allowedPackages.includes(option.value))
                .forEach((option) => {
                    const el = document.createElement('option');
                    el.value = option.value;
                    el.textContent = currentWorkflowValue === 'id_photo'
                        ? getIdPhotoPackageLabel(option.value)
                        : option.label;
                    paperSize.appendChild(el);
                });
        }
    } else {
        const photoOptions = [
            {val: '2R', label: '2R (2.5x3.5)'}, {val: '3R', label: '3R (3.5x5.0)'}, {val: '4R', label: '4R (4.0x6.0)'},
            {val: '5R', label: '5R (5.0x7.0)'}, {val: '6R', label: '6R (6.0x8.0)'}, {val: '8R', label: '8R (8.0x10.0)'}, {val: 'A4', label: 'A4 (8.27x11.69)'}
        ];
        photoOptions.forEach(opt => {
            let el = document.createElement('option'); el.value = opt.val; el.textContent = opt.label; paperSize.appendChild(el);
        });
    }
    printCategory.innerHTML = `
        <option value="id_photo">ID Photo</option>
        <option value="passport_visa">Passport / Visa</option>
        <option value="single_photo_print">Single Photo Print</option>`;
    printCategory.value = [...printCategory.options].some((option) => option.value === currentWorkflowValue)
        ? currentWorkflowValue
        : (categoryName === "SINGLE PHOTO" ? 'single_photo_print' : 'id_photo');

    if (paperSize) {
        if (printCategory.value === 'single_photo_print') {
            const nextValue = [...paperSize.options].some((option) => option.value === currentPaperValue)
                ? currentPaperValue
                : categoryName;
            paperSize.value = [...paperSize.options].some((option) => option.value === nextValue) ? nextValue : (paperSize.options[0]?.value || '');
        } else if (printCategory.value === 'passport_visa') {
            const nextValue = ['passport', 'visa'].includes(currentPaperValue)
                ? currentPaperValue
                : (['passport', 'visa'].includes(categoryName?.toLowerCase()) ? categoryName.toLowerCase() : 'passport');
            paperSize.value = [...paperSize.options].some((option) => option.value === nextValue)
                ? nextValue
                : 'passport';
        } else {
            const allowedPackages = idWorkflowPackageOptions[printCategory.value] || idWorkflowPackageOptions.id_photo;
            const normalizedPackageName = categoryName === 'SINGLE PHOTO' ? allowedPackages[0] : categoryName;
            paperSize.value = [...paperSize.options].some((option) => option.value === normalizedPackageName)
                ? normalizedPackageName
                : (allowedPackages[0] || '');
        }
    }

    const workflowColorOptions = idColorVariations[printCategory.value] || idColorVariations.id_photo;
    setSelectOptions('colorMode', workflowColorOptions, '', currentColorValue);
    updateDetailFieldLabels(categoryName);
}

function updateDropdownsForLargeFormat(categoryName) {
    const paperSize = document.getElementById('paperSize');
    const printCategory = document.getElementById('printCategory');
    const colorMode = document.getElementById('colorMode');
    const contentTypeSelect = document.getElementById('contentTypeSelect');
    const currentFinishValue = contentTypeSelect?.value || 'glossy';

    if (categoryName === "SINTRA BOARD PRINTING") {
        paperSize.innerHTML = '<option value="a4">A4 (8.27 x 11.69)</option>';
        printCategory.innerHTML = '<option value="sintra_board_printing">Sintra Board Printing</option>';
        colorMode.innerHTML = `
            <option value="full">Full Color</option>
            <option value="bw">Black & White</option>
            <option value="matte_tone">Matte Tone</option>
            <option value="high_contrast">High Contrast</option>`;
        if (contentTypeSelect) {
            setSelectOptions('contentTypeSelect', largeFormatFinishOptions, 'Select Finish Type', currentFinishValue);
        }
    }
    updatePaperSizeLabel(categoryName);
}

function buildServiceIdPartMap() {
    return {
        text_only: 'TX',
        text_image: 'TWI',
        image_only: 'IM',
        photocopy: 'COPY',
        scanning: 'SCAN',
        walk_in_copy: 'WIC',
        sorted_set: 'SRT',
        stapled_set: 'STP',
        standard_scan: 'STD',
        multi_page_scan: 'MPS',
        archive_ready_file: 'ARF',
        sintra_board_printing: 'SINTRA',
        id_photo: 'ID',
        passport_visa: 'PV',
        single_photo_print: 'SP',
        short: 'SHT',
        a4: 'A4',
        legal: 'LGL',
        long: 'LNG',
        passport: 'PASS',
        visa: 'VISA',
        bw: 'BW',
        partial: 'PC',
        full: 'FC',
        high_contrast: 'HC',
        matte_tone: 'MT',
        studio_bright: 'SB',
        warm_tone: 'WT',
        cool_tone: 'CT',
        standard: 'STD',
        premium: 'PRM',
        glossy: 'GLS',
        matte: 'MAT',
        leather: 'LTH',
        canvas_matte: 'CVM',
        glittered: 'GLT',
        finish_3d: '3D',
        broken_glass: 'BRG',
        spiral_binding: 'SPR',
        lamination: 'LAM',
        custom_layout: 'LYT',
        marketing_collateral: 'MKT',
        sticker_cut: 'STK',
        'Package A': 'PKGA',
        'Package B': 'PKGB',
        'Package C': 'PKGC',
        'Package D': 'PKGD',
        'Package E': 'PKGE',
        'Package F': 'PKGF',
        '2R': '2R',
        '3R': '3R',
        '4R': '4R',
        '5R': '5R',
        '6R': '6R',
        '8R': '8R'
    };
}

function serviceIdCode(value, fallback = 'NA') {
    if (!value) return fallback;

    const map = buildServiceIdPartMap();
    if (map[value]) return map[value];

    const normalized = String(value).trim();
    if (map[normalized]) return map[normalized];

    const underscored = normalized.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');
    if (map[underscored]) return map[underscored];

    const words = normalized.match(/[A-Za-z0-9]+/g) || [];
    if (!words.length) return fallback;

    const code = words
        .map((word) => /\d/.test(word) ? word.toUpperCase() : word[0].toUpperCase())
        .join('')
        .slice(0, 6);

    return code || fallback;
}

function composeServiceId(baseId, ...parts) {
    return [baseId, ...parts.filter(Boolean)].join('-');
}

function updateBulkPriceVisibility(categoryValue = '') {
    const retailInput = document.querySelector('input[name="priceType"][value="retail"]');
    const bulkInput = document.querySelector('input[name="priceType"][value="bulk"]');
    const bulkOption = document.getElementById('bulkPriceOption');
    const bulkThresholdNote = document.getElementById('bulkThresholdNote');
    const hideBulkForId = currentCategoryType === 'id' && ['id_photo', 'passport_visa'].includes(categoryValue);

    if (hideBulkForId && retailInput) {
        retailInput.checked = true;
    }

    if (bulkOption) {
        bulkOption.style.display = hideBulkForId ? 'none' : '';
    }

    if (bulkThresholdNote) {
        bulkThresholdNote.style.display = hideBulkForId ? 'none' : '';
    }

    if (bulkInput) {
        bulkInput.disabled = hideBulkForId;
    }
}

// --- UPDATED PRICE & DYNAMIC SERVICE ID LOGIC ---
function updatePrice() {
    const categoryValue = document.getElementById('printCategory').value;
    const categoryName = getCurrentDetailCategory().name;
    const color = document.getElementById('colorMode').value;
    const size = document.getElementById('paperSize').value;
    const contentTypeValue = getActiveContentTypeValue() || '';
    const selectedPaperLabel = document.getElementById('paperSize')?.selectedOptions[0]?.textContent || size;
    const serviceOptionValue = document.getElementById('serviceOptionSelect')?.value || '';
    const serviceOptionText = document.getElementById('serviceOptionSelect')?.selectedOptions[0]?.textContent || '';
    const qtyInput = document.getElementById('qtyInput');
    const qty = parseInt(qtyInput.value) || 1;
    const priceTypeInput = document.querySelector('input[name="priceType"]:checked');
    const retailInput = document.querySelector('input[name="priceType"][value="retail"]');
    const bulkInput = document.querySelector('input[name="priceType"][value="bulk"]');
    const bulkThresholdNote = document.getElementById('bulkThresholdNote');
    const specsDisplay = document.getElementById('productSpecs');
    const serviceIdDisplay = document.getElementById('currentServiceId');
    const bulkRule = getBulkRule(categoryValue);

    let retail = 0, bulk = 0;
    let computedId = "N/A";

    updateBulkPriceVisibility(categoryValue);

    // 1. DOCUMENT PRINTING
    if (currentCategoryType === "printing") {
        computedId = buildDisplayServiceId(
            serviceIdCode(categoryValue),
            serviceIdCode(color),
            serviceIdCode(size),
            serviceIdCode(serviceOptionValue || serviceOptionText)
        );
        const p = printingPricing[categoryValue][color][size];
        retail = p[0]; bulk = p[1];
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    } 
    // 2. ID PHOTO SERVICES
    else if (currentCategoryType === "id") {
        const packageNameMap = {
            'package a': 'Package A',
            'package b': 'Package B',
            'package c': 'Package C',
            'package d': 'Package D',
            'package e': 'Package E',
            'package f': 'Package F'
        };
        const normalizedCategoryName = packageNameMap[categoryName.toLowerCase()] || categoryName;

        if (categoryName !== "SINGLE PHOTO") {
            const workflowCode = serviceIdCode(categoryValue);
            const selectedIdPackageValue = size;
            const idPhotoMeta = getIdPhotoPackageMeta(selectedIdPackageValue);
            const packageCode = categoryValue === 'passport_visa'
                ? serviceIdCode(size)
                : idPhotoMeta.code;
            computedId = buildDisplayServiceId(
                workflowCode,
                packageCode,
                serviceIdCode(color),
                serviceIdCode(serviceOptionValue || serviceOptionText)
            );
            specsDisplay.innerHTML = '';
            retail = idPricing['PACKAGE'][normalizedCategoryName] || 0;
        } else {
            computedId = buildDisplayServiceId(
                "SP",
                serviceIdCode(size),
                serviceIdCode(color),
                serviceIdCode(serviceOptionValue || serviceOptionText)
            );
            specsDisplay.innerHTML = '';
            retail = idPricing[categoryName][size] || 0;
        }
        bulk = retail;
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }
    // 3. LARGE FORMAT
    else if (currentCategoryType === "largeformat") {
        computedId = buildDisplayServiceId(
            serviceIdCode(categoryValue),
            serviceIdCode(size),
            serviceIdCode(color),
            serviceIdCode(contentTypeValue || 'glossy'),
            serviceIdCode(serviceOptionValue || serviceOptionText)
        );
        const selectedFinish = contentTypeValue || 'glossy';
        retail = sintraPricing[selectedFinish] || 0;
        bulk = sintraBulkPricing[selectedFinish] || retail;
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }
    else if (currentCategoryType === "binding") {
        computedId = buildDisplayServiceId(
            serviceIdCode(categoryValue),
            serviceIdCode(color),
            serviceIdCode(size),
            serviceIdCode(serviceOptionValue || serviceOptionText)
        );
        const p = bindingPricing[categoryValue]?.[color]?.[size] || [0, 0];
        retail = p[0];
        bulk = p[1];
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }
    else if (currentCategoryType === "special") {
        computedId = buildDisplayServiceId(
            serviceIdCode(categoryValue),
            serviceIdCode(color),
            serviceIdCode(size),
            serviceIdCode(serviceOptionValue || serviceOptionText)
        );
        const p = specialPricing[categoryValue]?.[color]?.[size] || [0, 0];
        retail = p[0];
        bulk = p[1];
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }
    // 4. PHOTOCOPY & SCANNING (UPDATED IDs LOGIC)
    else if (currentCategoryType === "xerox") {
        computedId = buildDisplayServiceId(
            serviceIdCode(categoryValue),
            serviceIdCode(contentTypeValue || 'text_only'),
            serviceIdCode(size),
            serviceIdCode(color),
            serviceIdCode(serviceOptionValue || serviceOptionText)
        );
        
        const p = xeroxPricing[contentTypeValue || 'text_only']?.bw?.[size] || [0, 0];
        retail = p[0]; bulk = p[1];
        document.getElementById('bulkAmount').innerText = bulk.toFixed(2);
    }

    const bulkEligible = qty >= bulkRule.threshold;
    if (bulkInput) {
        const hideBulkForId = currentCategoryType === 'id' && ['id_photo', 'passport_visa'].includes(categoryValue);
        bulkInput.disabled = hideBulkForId || !bulkEligible;
        if (!bulkEligible && bulkInput.checked && retailInput) {
            retailInput.checked = true;
        }
    }
    if (bulkThresholdNote) {
        const hideBulkForId = currentCategoryType === 'id' && ['id_photo', 'passport_visa'].includes(categoryValue);
        if (!hideBulkForId) {
            bulkThresholdNote.textContent = bulkEligible
                ? `Bulk price applied for ${qty} ${bulkRule.unit}.`
                : bulkRule.note;
        }
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
            if ((document.getElementById('printCategory')?.value || '') === 'id_photo') {
                const selectedPackageName = paperSizeDropdown.value;
                const selectedIndex = getIdCategoryIndexByName(selectedPackageName);
                if (selectedIndex >= 0) {
                    currentSelectedOptionIndex = selectedIndex;
                }
            }
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
    if (isOpen) {
        renderCart();
        const cartList = document.getElementById('cartItemsList');
        if (cartList) cartList.scrollTop = 0;
    }
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

function resetDetailOrderingState() {
    currentEditingCartId = null;

    const fileInput = document.getElementById('fileUploadInput');
    const qtyInput = document.getElementById('qtyInput');
    const retailInput = document.querySelector('input[name="priceType"][value="retail"]');
    const contentType = document.getElementById('contentTypeSelect');
    const serviceOption = document.getElementById('serviceOptionSelect');
    const fileType = document.getElementById('fileTypeSelect');

    if (currentCategorySet.length) {
        openDetail(0);
    }

    if (contentType) contentType.selectedIndex = 0;
    if (serviceOption) serviceOption.selectedIndex = 0;
    if (fileType) fileType.selectedIndex = 0;
    if (qtyInput) qtyInput.value = 1;
    if (retailInput) retailInput.checked = true;
    if (fileInput) fileInput.value = '';

    updatePrice();
}

function buildCurrentCartItem() {
    const title = document.getElementById('detailTitleHeader').innerText;
    const size = document.getElementById('paperSize').value;
    const sizeLabel = document.getElementById('paperSize').selectedOptions[0]?.textContent || size;
    const contentTypeSelect = document.getElementById('contentTypeSelect');
    const contentTypeValue = contentTypeSelect?.value || '';
    const contentTypeLabel = contentTypeSelect?.selectedOptions[0]?.textContent || '';
    const serviceOptionSelect = document.getElementById('serviceOptionSelect');
    const serviceOptionValue = serviceOptionSelect?.value || '';
    const serviceOption = serviceOptionSelect?.selectedOptions[0]?.textContent || '';
    const qty = Math.max(1, parseInt(document.getElementById('qtyInput').value || '1', 10));
    const totalStr = document.getElementById('totalAmount').innerText.replace(/,/g, '');
    const firstImg = document.querySelector('#previewTrack img');
    const sId = document.getElementById('currentServiceId').innerText.trim();
    const total = parseFloat(totalStr);
    const fileInput = document.getElementById('fileUploadInput');
    const attachedFile = fileInput?.files?.[0] || null;
    const existingItem = currentEditingCartId ? cart.find((item) => item.id === currentEditingCartId) : null;

    if (currentCategoryType === 'xerox' && !contentTypeValue) {
        alert('Please choose a content type first.');
        return null;
    }

    if (!serviceOptionValue) {
        alert('Please choose a service option first.');
        return null;
    }

    if (!title || !sId || sId === 'undefined' || sId === 'N/A' || Number.isNaN(total) || total <= 0) {
        alert('Please complete the service selection first.');
        return null;
    }

    let detailText = `ID: ${sId} | Size: ${sizeLabel} | Option: ${serviceOption}`;
    if (currentCategoryType === 'xerox' && contentTypeLabel) {
        detailText = `ID: ${sId} | Size: ${sizeLabel} | Content Type: ${contentTypeLabel} | Option: ${serviceOption}`;
    }
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
        fileTypeValue: document.getElementById('fileTypeSelect')?.value || '',
        contentTypeValue: contentTypeValue
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
        cart[existingIndex].contentTypeValue = cartItem.contentTypeValue;
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
    const contentType = document.getElementById('contentTypeSelect');
    const serviceOption = document.getElementById('serviceOptionSelect');
    const fileType = document.getElementById('fileTypeSelect');
    const qtyInput = document.getElementById('qtyInput');

    if (printCategory && item.printCategoryValue) printCategory.value = item.printCategoryValue;
    if (colorMode && item.colorModeValue) colorMode.value = item.colorModeValue;
    if (paperSize && item.paperSizeValue) paperSize.value = item.paperSizeValue;
    if (contentType && item.contentTypeValue) contentType.value = item.contentTypeValue;

    syncPreviewFromDropdowns();

    if (contentType && item.contentTypeValue) contentType.value = item.contentTypeValue;
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
    resetDetailOrderingState();
    renderCart();
    setCartOpen(true);
    setCartMessage(
        wasEditing
            ? 'Cart item updated. The form was reset so you can start a new selection.'
            : 'Item added to cart. The form was reset so you can continue shopping.',
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
    resetDetailOrderingState();
    renderCart();
    setCartOpen(true);
    setCartMessage(
        wasEditing
            ? 'Cart item updated. Review your cart before checkout.'
            : 'Order item added. Review your cart before checkout.',
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
