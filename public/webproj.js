/**
 * Printify & Co. - Core JavaScript
 * FULL UPDATED VERSION: 11.0
 * FIXES:
 * - fixed detail preview slide arrows
 * - fixed dropdown/preview sync
 * - fixed add to cart button click
 * - fixed buy now button click
 * - fixed favorite heart full orange/white fill state
 * - reduced lag by removing duplicate bindings
 * - kept your full setup and logic
 */

let heroIndex=0,currentCategoryType="",currentCategorySet=[],currentPreviewIndex=0,currentSlideIndex=0,voucherDiscount=0,isLoggedIn=false,slideInterval=null,cart=JSON.parse(localStorage.getItem('printCart'))||[];
const heroSlides=document.querySelectorAll('.hero-slide'),dots=document.querySelectorAll('.dot');

function getBgUrl(el){const bg=window.getComputedStyle(el).backgroundImage;if(!bg||bg==='none')return null;const m=bg.match(/url\(["']?(.*?)["']?\)/);return m?m[1]:null;}
function preloadImages(urls=[]){const clean=urls.filter(Boolean);return Promise.all(clean.map(src=>new Promise(resolve=>{const img=new Image();img.onload=resolve;img.onerror=resolve;img.src=src;})));}

function updateHero(){if(heroSlides.length===0)return;heroSlides.forEach(s=>s.classList.remove('active'));dots.forEach(d=>d.classList.remove('active'));if(heroSlides[heroIndex])heroSlides[heroIndex].classList.add('active');if(dots[heroIndex])dots[heroIndex].classList.add('active');}
function nextHeroSlide(){if(heroSlides.length===0)return;heroIndex=(heroIndex+1)%heroSlides.length;updateHero();}
function jumpToHero(index){heroIndex=index;updateHero();}

function jumpTo(sectionId){
  const productDetail=document.getElementById('productDetail'),pageWrapper=document.getElementById('pageWrapper'),mainHeader=document.getElementById('mainHeader');
  if(productDetail)productDetail.style.display='none';
  if(pageWrapper)pageWrapper.style.display='block';
  if(mainHeader)mainHeader.classList.remove('detail-active');
  if(sectionId==='products'){document.body.classList.add('services-active');if(mainHeader)mainHeader.classList.add('scrolled');}
  else if(sectionId==='home'){if(window.scrollY<50&&mainHeader)mainHeader.classList.remove('scrolled');if(isLoggedIn)document.body.classList.add('services-active');else document.body.classList.remove('services-active');}
  else{if(mainHeader)mainHeader.classList.add('scrolled');if(!isLoggedIn)document.body.classList.remove('services-active');}
  document.querySelectorAll('.section').forEach(sec=>{sec.classList.remove('active');sec.style.display='none';});
  const target=document.getElementById(sectionId);
  if(target){target.style.display='block';setTimeout(()=>target.classList.add('active'),10);window.scrollTo({top:0,behavior:sectionId==='home'?'auto':'smooth'});}
}

window.addEventListener('scroll',function(){
  const mainHeader=document.getElementById('mainHeader'),activeSection=document.querySelector('.section.active');
  if(activeSection&&activeSection.id==='home'){if(window.scrollY>50){if(mainHeader)mainHeader.classList.add('scrolled');}else{if(mainHeader)mainHeader.classList.remove('scrolled');}}
  else{if(mainHeader)mainHeader.classList.add('scrolled');}
});

function backToMain(){document.body.classList.add('services-active');jumpTo('products');}

const allData={
  'doc':{name:"DOCUMENT PRINTING",type:"printing",categories:[
    {name:"Text Only",imgs:["images/TXTONLY (B&W).png","images/TXTONLY (PC).png","images/TXTONLY (FC).png"],specs:"Paper: Bond Paper, 80gsm. Standard document printing for reports and letters."},
    {name:"Text with Image",imgs:["images/TXTWI (B&W).png","images/TXTWI (PC).png","images/TXTWI (FC).png"],specs:"Paper: Bond Paper, 80gsm. Mixed text & image printing."},
    {name:"Image Only",imgs:["images/IO (B&W).png","images/IO (PC).png","images/IO (FC).png"],specs:"Paper: Bond Paper, 80gsm. High-ink coverage for documents with graphics."}
  ]},
  'photo':{name:"PHOTOCOPY & SCANNING",type:"xerox",categories:[
    {name:"B&W Photocopy",imgs:["images/PHOTOC (FC).png"],specs:"Standard 80gsm Copy Paper. Fast and clear duplication."},
    {name:"Partial Color Copy",imgs:["images/PHOTOC (PC).png"],specs:"Standard 80gsm. Best for forms with small colored logos or text."},
    {name:"Full Color Copy",imgs:["images/PHOTOC (B&W).png"],specs:"Standard 80gsm. Full vibrant color duplication."}
  ]},
  'id':{name:"ID & PHOTO SERVICES",type:"id",categories:[
    {name:"PACKAGE",imgs:["images/PCKGA.png","images/PCKGB.png","images/PCKGC.png","images/PCKGD.png","images/PCKGE.png","images/PCKGF.png"],specs:"Best value bundles for applications and school."},
    {name:"SINGLE PHOTO",imgs:["images/SP (2-5).png","images/SP (6-A4).png"],specs:"High-quality prints for frames and memories."}
  ]},
  'largeformat':{name:"LARGE FORMAT PRINTING",type:"largeformat",categories:[
    {name:"SINTRA BOARD PRINTING",imgs:["sintra1.jpg"],specs:"Material: Sintra Board (3mm Flat PVC), A4 Size.<br>Durable, moisture-resistant, and lightweight PVC foam board.<br>Smooth surface direct print, intended for indoor display."}
  ]}
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

function getPreviewTrack(){return document.getElementById('previewTrack');}
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
  const colorModeDropdown=document.getElementById('colorMode'),paperSizeDropdown=document.getElementById('paperSize'),detailTitle=document.getElementById('detailTitleHeader');
  if(!detailTitle)return;
  if((currentCategoryType==='printing'||currentCategoryType==='xerox')&&colorModeDropdown){setPreviewIndex(colorModeDropdown.selectedIndex);return;}
  if(currentCategoryType==='id'&&detailTitle.innerText==='PACKAGE'&&paperSizeDropdown){setPreviewIndex(paperSizeDropdown.selectedIndex);return;}
  if(currentCategoryType==='id'&&detailTitle.innerText==='SINGLE PHOTO'&&paperSizeDropdown){setPreviewIndex(paperSizeDropdown.selectedIndex<=3?0:1);return;}
  if(currentCategoryType==='largeformat')setPreviewIndex(0);
}

function syncDropdownsFromPreview(){
  const colorModeDropdown=document.getElementById('colorMode'),paperSizeDropdown=document.getElementById('paperSize'),detailTitle=document.getElementById('detailTitleHeader');
  if(!detailTitle)return;
  if((currentCategoryType==='printing'||currentCategoryType==='xerox')&&colorModeDropdown){if(colorModeDropdown.options[currentPreviewIndex])colorModeDropdown.selectedIndex=currentPreviewIndex;updatePrice();return;}
  if(currentCategoryType==='id'&&detailTitle.innerText==='PACKAGE'&&paperSizeDropdown){if(paperSizeDropdown.options[currentPreviewIndex])paperSizeDropdown.selectedIndex=currentPreviewIndex;updatePrice();return;}
  if(currentCategoryType==='id'&&detailTitle.innerText==='SINGLE PHOTO'&&paperSizeDropdown){if(currentPreviewIndex===0&&paperSizeDropdown.options[0])paperSizeDropdown.selectedIndex=0;else if(currentPreviewIndex===1&&paperSizeDropdown.options[4])paperSizeDropdown.selectedIndex=4;updatePrice();}
}

function bindDetailControlSync(){
  const printCategory=document.getElementById('printCategory'),colorMode=document.getElementById('colorMode'),paperSize=document.getElementById('paperSize'),qtyInput=document.getElementById('qtyInput');
  if(printCategory&&printCategory.dataset.boundPreview!=='true'){printCategory.dataset.boundPreview='true';printCategory.addEventListener('change',()=>{updatePrice();syncPreviewFromDropdowns();});}
  if(colorMode&&colorMode.dataset.boundPreview!=='true'){colorMode.dataset.boundPreview='true';colorMode.addEventListener('change',()=>{updatePrice();syncPreviewFromDropdowns();});}
  if(paperSize&&paperSize.dataset.boundPreview!=='true'){paperSize.dataset.boundPreview='true';paperSize.addEventListener('change',()=>{updatePrice();syncPreviewFromDropdowns();});}
  if(qtyInput&&qtyInput.dataset.boundQty!=='true'){qtyInput.dataset.boundQty='true';qtyInput.addEventListener('input',updatePrice);qtyInput.addEventListener('change',updatePrice);}
  document.querySelectorAll('input[name="priceType"]').forEach(input=>{if(input.dataset.boundPriceType==='true')return;input.dataset.boundPriceType='true';input.addEventListener('change',updatePrice);});
}

function bindDetailButtons(){
  const addBtn=document.querySelector('.btn-cart'),buyBtn=document.querySelector('.btn-buy'),prevBtn=document.getElementById('detailPrevBtn'),nextBtn=document.getElementById('detailNextBtn');
  if(addBtn&&addBtn.dataset.boundAdd!=='true'){addBtn.dataset.boundAdd='true';addBtn.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();addToCart();});}
  if(buyBtn&&buyBtn.dataset.boundBuy!=='true'){buyBtn.dataset.boundBuy='true';buyBtn.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();placeOrderNow();});}
  if(prevBtn&&prevBtn.dataset.boundNav!=='true'){prevBtn.dataset.boundNav='true';prevBtn.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();movePreview(-1);});}
  if(nextBtn&&nextBtn.dataset.boundNav!=='true'){nextBtn.dataset.boundNav='true';nextBtn.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();movePreview(1);});}
}

function openModal(key){
  const data=allData[key]||{name:"PRINTING SERVICE",categories:[]},modalTitle=document.getElementById('modalTitle'),track=document.getElementById('categoryTrack');
  currentCategoryType=data.type;
  if(modalTitle)modalTitle.innerText=data.name;
  if(track){
    track.innerHTML='';currentCategorySet=data.categories;
    currentCategorySet.forEach((cat,index)=>{track.innerHTML+=`<div class="category-card"><img src="${cat.imgs[0]}"><h4>${cat.name}</h4><p onclick="openDetail(${index})">SELECT TYPE</p></div>`;});
    currentSlideIndex=0;track.style.transform='translateX(0)';updateModalButtons();
  }
  const modal=document.getElementById('productModal');if(modal)modal.classList.add('active');
}

function closeModal(){const modal=document.getElementById('productModal');if(modal)modal.classList.remove('active');}

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

function initMap(){
  const mapDiv=document.querySelector('.map-placeholder');
  if(mapDiv)mapDiv.innerHTML=`<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12345!2d121.0!3d14.5!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDMwJzAwLjAiTiAxMjHCsDAwJzAwLjAiRQ!5e0!3m2!1sen!2sph!4v123456789" width="100%" height="100%" style="border:0; border-radius:8px;" allowfullscreen="" loading="lazy"></iframe>`;
}

document.addEventListener('DOMContentLoaded',()=>{
  document.documentElement.classList.add('page-loading');document.body.classList.add('page-loading');
  if(isLoggedIn)document.body.classList.add('services-active');else document.body.classList.remove('services-active');
  jumpTo('home');updateCartBadge();bindFavoriteButtons();applyServiceCardEffects();
  heroIndex=0;updateHero();
  const heroBgUrls=Array.from(document.querySelectorAll('.hero-slide')).map(getBgUrl);
  preloadImages(heroBgUrls).then(()=>{document.documentElement.classList.remove('page-loading');document.body.classList.remove('page-loading');});
  if(slideInterval)clearInterval(slideInterval);slideInterval=null;
  handleContactForm();initMap();
  const observer=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(entry.isIntersecting)entry.target.classList.add('animate');});},{threshold:0.1});
  document.querySelectorAll('.section').forEach(s=>observer.observe(s));
  bindDetailControlSync();bindDetailButtons();
});

function checkoutSelected(){
  const selected=cart.filter(i=>i.checked);
  if(selected.length===0){alert("Please select at least 1 item to checkout.");return;}
  const payload={items:selected.map(i=>({name:i.name,qty:i.qty,unit_price:(i.price/i.qty),service_code:(i.details&&i.details.includes("ID: "))?(i.details.split("ID: ")[1].split(" |")[0]).trim():null,price_type:"retail"}))};
  const tokenTag=document.querySelector('meta[name="csrf-token"]'),token=tokenTag?tokenTag.getAttribute('content'):'';
  fetch('/cart/sync',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify(payload)})
  .then(res=>{if(!res.ok)throw new Error("Sync failed");return res.json();})
  .then(()=>{window.location.href='/payment/checkout';})
  .catch(()=>{alert("Checkout failed. Please try again.");});
}

function placeOrderNow(){
  const name=document.getElementById('detailTitleHeader')?.innerText||'Item',qty=parseInt(document.getElementById('qtyInput')?.value||'1',10),serviceCode=document.getElementById('currentServiceId')?.innerText||null,priceTypeInput=document.querySelector('input[name="priceType"]:checked'),priceType=priceTypeInput?priceTypeInput.value:'retail',retail=parseFloat((document.getElementById('retailAmount')?.innerText||'0').replace(/,/g,'')),bulkRaw=document.getElementById('bulkAmount')?.innerText||'0',bulk=bulkRaw==='Fixed'?retail:parseFloat(String(bulkRaw).replace(/,/g,'')),unitPrice=(priceType==='bulk')?bulk:retail;
  if(!unitPrice||unitPrice<=0){alert("Invalid price. Please try again.");return;}
  const payload={name:name,qty:qty>0?qty:1,unit_price:unitPrice,price_type:priceType,service_code:serviceCode};
  const tokenTag=document.querySelector('meta[name="csrf-token"]'),token=tokenTag?tokenTag.getAttribute('content'):'';
  fetch('/cart/buy-now',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify(payload)})
  .then(res=>{if(!res.ok)throw new Error("Buy now failed");return res.json();})
  .then(()=>{window.location.href='/payment/checkout';})
  .catch(()=>{alert("Place order failed. Please try again.");});
}

document.querySelector(".nav-search-btn").addEventListener("click", function () {
  const query = document.getElementById("navSearchInput").value;

  if(query.trim() !== ""){
      alert("Searching for: " + query);
  }
});