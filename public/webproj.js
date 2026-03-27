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

const printingPricing={
  text_only:{bw:{short:[3.50,3.00],a4:[4.00,3.00],legal:[5.00,4.00],long:[5.00,4.00]},partial:{short:[5.00,4.50],a4:[6.00,5.00],legal:[7.00,6.00],long:[7.00,6.00]},full:{short:[10.00,9.00],a4:[12.00,10.00],legal:[14.00,12.00],long:[14.00,12.00]}},
  text_image:{bw:{short:[4.50,4.00],a4:[5.00,4.00],legal:[6.00,5.00],long:[6.00,5.00]},partial:{short:[7.00,6.00],a4:[8.00,7.00],legal:[9.00,8.00],long:[9.00,8.00]},full:{short:[13.00,12.00],a4:[15.00,13.00],legal:[17.00,15.00],long:[17.00,15.00]}},
  image_only:{bw:{short:[8.00,7.00],a4:[10.00,9.00],legal:[12.00,11.00],long:[12.00,11.00]},partial:{short:[13.00,12.00],a4:[15.00,13.00],legal:[18.00,16.00],long:[18.00,16.00]},full:{short:[20.00,18.00],a4:[25.00,22.00],legal:[30.00,25.00],long:[30.00,25.00]}}
};
const xeroxPricing={text_only:{bw:{short:[2.00,1.50],a4:[2.50,2.00],legal:[3.00,2.00],long:[3.00,2.00]}},text_image:{bw:{short:[4.00,3.50],a4:[5.00,4.00],legal:[6.00,5.00],long:[6.00,5.00]}},image_only:{bw:{short:[8.00,7.00],a4:[10.00,9.00],legal:[12.00,11.00],long:[12.00,11.00]}}};
const idPricing={'PACKAGE':{'Package A':60.00,'Package B':40.00,'Package C':50.00,'Package D':60.00,'Package E':45.00,'Package F':60.00},'SINGLE PHOTO':{'IDP-SP-001':5.00,'IDP-SP-002':8.00,'IDP-SP-003':12.00,'IDP-SP-004':15.00,'IDP-SP-005':25.00,'IDP-SP-006':40.00,'IDP-SP-007':45.00}};
const sintraPricing={'Glossy':100.00,'Matte':100.00,'Leather':110.00,'Canvas Matte':110.00,'Glittered':120.00,'3D':120.00,'Rainbow':130.00,'Broken Glass':130.00};
const idDetails={'Package A':"Inclusions: 4pcs 2x2 & 8pcs 1x1",'Package B':"Inclusions: 8pcs 1x1",'Package C':"Inclusions: 8pcs 2x2",'Package D':"Inclusions: 5pcs Passport Size",'Package E':"Inclusions: 6pcs 1.5x1.5",'Package F':"Inclusions: 5pcs Wallet Size"};

function bindFavoriteButtons(){
  document.querySelectorAll('.heart-icon,.favorite-btn,.fav-btn,.wishlist-btn').forEach(btn=>{
    if(btn.dataset.favoriteBound==='true')return;
    btn.dataset.favoriteBound='true';
    btn.addEventListener('mouseenter',function(){const card=this.closest('.service-item');if(card)card.classList.add('heart-hover');});
    btn.addEventListener('mouseleave',function(){const card=this.closest('.service-item');if(card)card.classList.remove('heart-hover');});
    btn.addEventListener('click',function(e){
      e.preventDefault();e.stopPropagation();
      this.classList.toggle('active');this.classList.toggle('favorited');
      const card=this.closest('.service-item');
      if(card){card.classList.toggle('favorite-active');card.classList.remove('heart-hover');}
    });
  });
}

function applyServiceCardEffects(){
  document.querySelectorAll('.service-item').forEach(card=>{
    if(card.dataset.fxBound==='true')return;
    card.dataset.fxBound='true';
    const img=card.querySelector('.service-image-wrapper img');
    if(!img)return;
    card.addEventListener('mousemove',function(e){
      const wrap=card.querySelector('.service-image-wrapper');
      if(!wrap)return;
      const r=wrap.getBoundingClientRect(),x=e.clientX-r.left,y=e.clientY-r.top,rx=((y/r.height)-0.5)*6,ry=((x/r.width)-0.5)*-6;
      img.style.transform=`scale(1.16) rotateX(${rx}deg) rotateY(${ry}deg)`;
    });
    card.addEventListener('mouseenter',function(){img.style.transition='transform .28s ease';});
    card.addEventListener('mouseleave',function(){img.style.transition='transform .38s ease';img.style.transform='scale(1.08) rotateX(0deg) rotateY(0deg)';});
  });
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

function moveSlide(dir){
  const track=document.getElementById('categoryTrack');if(!track)return;
  const total=currentCategorySet.length;currentSlideIndex+=dir;if(currentSlideIndex<0)currentSlideIndex=0;if(currentSlideIndex>=total)currentSlideIndex=total-1;
  track.style.transform=`translateX(-${currentSlideIndex*100}%)`;updateModalButtons();
}

function updateModalButtons(){
  const prev=document.getElementById('modalPrev'),next=document.getElementById('modalNext');
  if(prev)prev.style.display=(currentSlideIndex===0)?'none':'flex';
  if(next)next.style.display=(currentSlideIndex>=currentCategorySet.length-1)?'none':'flex';
}

function openDetail(index){
  const cat=currentCategorySet[index];if(!cat)return;
  document.body.classList.add('services-active');
  const titleHeader=document.getElementById('detailTitleHeader'),specsBox=document.getElementById('productSpecs'),printCategory=document.getElementById('printCategory'),colorMode=document.getElementById('colorMode'),paperSize=document.getElementById('paperSize');
  if(titleHeader)titleHeader.innerText=cat.name;
  if(specsBox)specsBox.innerHTML=cat.specs;

  if(currentCategoryType==='printing'||currentCategoryType==='xerox'){
    if(printCategory)printCategory.innerHTML=`<option value="text_only">Text Only</option><option value="text_image">Text with Image</option><option value="image_only">Image Only</option>`;
    if(paperSize)paperSize.innerHTML=`<option value="short">Short (8.5 x 11)</option><option value="a4">A4 (8.27 x 11.69)</option><option value="long">Long (8.5 x 13)</option>`;
    if(colorMode)colorMode.innerHTML=`<option value="bw">B&W</option><option value="partial">Partial Color</option><option value="full">Full Color</option>`;
    if(cat.name.includes("Text Only")||cat.name.includes("B&W")){if(printCategory)printCategory.value="text_only";if(colorMode)colorMode.value="bw";}
    else if(cat.name.includes("Text with Image")||cat.name.includes("Partial")){if(printCategory)printCategory.value="text_image";if(colorMode)colorMode.value="partial";}
    else if(cat.name.includes("Image Only")||cat.name.includes("Full")){if(printCategory)printCategory.value="image_only";if(colorMode)colorMode.value="full";}
  }

  if(currentCategoryType==="id")updateDropdownsForID(cat.name);
  if(currentCategoryType==="largeformat")updateDropdownsForLargeFormat(cat.name);

  const previewTrack=document.getElementById('previewTrack');
  if(previewTrack){
    previewTrack.innerHTML='';
    cat.imgs.forEach(imgSrc=>{previewTrack.innerHTML+=`<img src="${imgSrc}" style="min-width:100%;height:100%;object-fit:contain;display:block;">`;});
  }

  currentPreviewIndex=0;
  if(previewTrack){previewTrack.style.transition='transform .35s ease';previewTrack.style.transform='translateX(0)';}

  const sidebarTrack=document.getElementById('sidebarTrack');
  if(sidebarTrack){
    sidebarTrack.innerHTML='';
    currentCategorySet.forEach((sidebarCat,idx)=>{sidebarTrack.innerHTML+=`<div class="sidebar-item ${idx===index?'active':''}" onclick="openDetail(${idx})"><img src="${sidebarCat.imgs[0]}"><p>${sidebarCat.name}</p></div>`;});
  }

  const modal=document.getElementById('productModal'),pageWrapper=document.getElementById('pageWrapper'),productDetail=document.getElementById('productDetail'),mainHeader=document.getElementById('mainHeader');
  if(modal)modal.classList.remove('active');
  if(pageWrapper)pageWrapper.style.display='none';
  if(productDetail)productDetail.style.display='block';
  if(mainHeader)mainHeader.classList.add('detail-active');

  bindDetailControlSync();
  bindDetailButtons();
  syncPreviewFromDropdowns();
  updatePreviewButtons();
  updatePrice();
  window.scrollTo({top:0,behavior:'auto'});
}

function updateDropdownsForID(categoryName){
  const paperSize=document.getElementById('paperSize'),printCategory=document.getElementById('printCategory'),colorMode=document.getElementById('colorMode');
  if(!paperSize||!printCategory||!colorMode)return;
  paperSize.innerHTML='';
  if(categoryName==="PACKAGE"){
    ['Package A','Package B','Package C','Package D','Package E','Package F'].forEach(opt=>{let el=document.createElement('option');el.value=opt;el.textContent=opt;paperSize.appendChild(el);});
  }else{
    [{val:'IDP-SP-001',label:'2R (2.5x3.5)'},{val:'IDP-SP-002',label:'3R (3.5x5.0)'},{val:'IDP-SP-003',label:'4R (4.0x6.0)'},{val:'IDP-SP-004',label:'5R (5.0x7.0)'},{val:'IDP-SP-005',label:'6R (6.0x8.0)'},{val:'IDP-SP-006',label:'8R (8.0x10.0)'},{val:'IDP-SP-007',label:'A4 (8.27x11.69)'}].forEach(opt=>{let el=document.createElement('option');el.value=opt.val;el.textContent=opt.label;paperSize.appendChild(el);});
  }
  printCategory.innerHTML='<option value="id_photo">Photo Services</option>';
  colorMode.innerHTML='<option value="full">Full Color</option>';
}

function updateDropdownsForLargeFormat(categoryName){
  const paperSize=document.getElementById('paperSize'),printCategory=document.getElementById('printCategory'),colorMode=document.getElementById('colorMode');
  if(!paperSize||!printCategory||!colorMode)return;
  if(categoryName==="SINTRA BOARD PRINTING"){
    paperSize.innerHTML='<option value="a4">A4 (8.27 x 11.69)</option>';
    printCategory.innerHTML=`<option value="Glossy">Finish: Glossy</option><option value="Matte">Finish: Matte</option><option value="Leather">Finish: Leather</option><option value="Canvas Matte">Finish: Canvas Matte</option><option value="Glittered">Finish: Glittered</option><option value="3D">Finish: 3D</option><option value="Rainbow">Finish: Rainbow</option><option value="Broken Glass">Finish: Broken Glass</option>`;
    colorMode.innerHTML='<option value="full">Full Color</option>';
  }
}

function updatePrice(){
  const printCategoryEl=document.getElementById('printCategory'),detailTitleEl=document.getElementById('detailTitleHeader'),colorModeEl=document.getElementById('colorMode'),paperSizeEl=document.getElementById('paperSize'),qtyInput=document.getElementById('qtyInput'),specsDisplay=document.getElementById('productSpecs'),serviceIdDisplay=document.getElementById('currentServiceId');
  if(!printCategoryEl||!detailTitleEl||!colorModeEl||!paperSizeEl||!qtyInput)return;
  const categoryValue=printCategoryEl.value,categoryName=detailTitleEl.innerText,color=colorModeEl.value,size=paperSizeEl.value,qty=parseInt(qtyInput.value)||1,priceTypeInput=document.querySelector('input[name="priceType"]:checked'),priceType=priceTypeInput?priceTypeInput.value:'retail';
  let retail=0,bulk=0,computedId="N/A";

  if(currentCategoryType==="printing"){
    const docIdMap={"text_only":{"bw":"DOC-TX-001","partial":"DOC-TX-002","full":"DOC-TX-003"},"text_image":{"bw":"DOC-TWI-004","partial":"DOC-TWI-005","full":"DOC-TWI-006"},"image_only":{"bw":"DOC-IM-007","partial":"DOC-IM-008","full":"DOC-IM-009"}};
    computedId=docIdMap[categoryValue][color];
    const p=printingPricing[categoryValue][color][size];retail=p[0];bulk=p[1];
    const bulkAmount=document.getElementById('bulkAmount');if(bulkAmount)bulkAmount.innerText=bulk.toFixed(2);
  }else if(currentCategoryType==="id"){
    if(categoryName==="PACKAGE"){
      const packageIdMap={'Package A':"IDP-PKG-001",'Package B':"IDP-PKG-002",'Package C':"IDP-PKG-003",'Package D':"IDP-PKG-004",'Package E':"IDP-PKG-005",'Package F':"IDP-PKG-006"};
      computedId=packageIdMap[size];
      if(specsDisplay)specsDisplay.innerHTML=`Premium Photo Paper (260gsm)<br><strong style="color:#e67e22;">${idDetails[size]||""}</strong>`;
      retail=idPricing['PACKAGE'][size]||0;
    }else{
      computedId=size;
      if(specsDisplay)specsDisplay.innerHTML=`Premium Photo Paper (260gsm)`;
      retail=idPricing['SINGLE PHOTO'][size]||0;
    }
    bulk=retail;const bulkAmount=document.getElementById('bulkAmount');if(bulkAmount)bulkAmount.innerText="Fixed";
  }else if(currentCategoryType==="largeformat"){
    computedId="SINTRA-001";retail=sintraPricing[categoryValue]||0;bulk=retail;const bulkAmount=document.getElementById('bulkAmount');if(bulkAmount)bulkAmount.innerText="Fixed";
  }else if(currentCategoryType==="xerox"){
    const xeroxIdMap={"bw":"DOC-PCPY-001","partial":"DOC-PCPY-002","full":"DOC-PCPY-003"};
    computedId=xeroxIdMap[color]||"DOC-PCPY-001";
    const p=xeroxPricing[categoryValue].bw[size];retail=p[0];bulk=p[1];
    const bulkAmount=document.getElementById('bulkAmount');if(bulkAmount)bulkAmount.innerText=bulk.toFixed(2);
  }

  if(serviceIdDisplay)serviceIdDisplay.innerText=computedId;
  const retailAmount=document.getElementById('retailAmount');if(retailAmount)retailAmount.innerText=retail.toFixed(2);
  const unitPrice=(priceType==='retail')?retail:bulk,total=unitPrice*qty,totalAmount=document.getElementById('totalAmount');
  if(totalAmount)totalAmount.innerText=total.toLocaleString(undefined,{minimumFractionDigits:2});
}

function movePreview(dir){
  const imgs=getPreviewImages();if(imgs.length===0)return;
  currentPreviewIndex=Math.max(0,Math.min(currentPreviewIndex+dir,imgs.length-1));
  setPreviewIndex(currentPreviewIndex);syncDropdownsFromPreview();
}

function updatePreviewButtons(){
  const prev=document.getElementById('detailPrevBtn'),next=document.getElementById('detailNextBtn'),totalImgs=getPreviewImages().length;
  if(prev)prev.style.display=(currentPreviewIndex===0||totalImgs<=1)?'none':'flex';
  if(next)next.style.display=(currentPreviewIndex>=totalImgs-1||totalImgs<=1)?'none':'flex';
}

function changeQty(d){const q=document.getElementById('qtyInput');if(!q)return;q.value=Math.max(1,parseInt(q.value||'1',10)+d);updatePrice();}

function toggleCart(){
  const overlay=document.getElementById('cartOverlay'),drawer=document.getElementById('cartDrawer');
  if(overlay)overlay.classList.toggle('active');
  if(drawer)drawer.classList.toggle('active');
  renderCart();
}

function addToCart(){
  const title=document.getElementById('detailTitleHeader')?.innerText||'',size=document.getElementById('paperSize')?.value||'',qty=parseInt(document.getElementById('qtyInput')?.value||'1',10),totalStr=(document.getElementById('totalAmount')?.innerText||'0').replace(/,/g,''),firstImg=document.querySelector('#previewTrack img'),sId=document.getElementById('currentServiceId')?.innerText||'';
  let detailText=`ID: ${sId} | Size: ${String(size).toUpperCase()}`;
  if(currentCategoryType==="largeformat")detailText+=` | Finish: ${document.getElementById('printCategory')?.value||''}`;
  cart.push({id:Date.now(),name:title,details:detailText,qty:qty,price:parseFloat(totalStr),img:firstImg?firstImg.src:'',checked:true});
  localStorage.setItem('printCart',JSON.stringify(cart));
  updateCartBadge();toggleCart();
}

function updateCartBadge(){const badge=document.getElementById('cartBadge');if(badge)badge.innerText=cart.length;}
function removeFromCart(index){cart.splice(index,1);localStorage.setItem('printCart',JSON.stringify(cart));updateCartBadge();renderCart();}

function renderCart(){
  const list=document.getElementById('cartItemsList');if(!list)return;list.innerHTML='';
  cart.forEach((item,index)=>{list.innerHTML+=`<div class="cart-item"><label class="cart-item-check-wrap"><input type="checkbox" class="cart-item-check" ${item.checked?'checked':''} onchange="toggleItemCheck(${index})"></label><img src="${item.img}"><div class="cart-item-info"><h4>${item.name}</h4><p style="font-size:10px; color:#777;">${item.details}</p><p class="cart-item-price">Qty: ${item.qty} | ₱${item.price.toLocaleString()}</p><span onclick="removeFromCart(${index})" style="color:red; cursor:pointer; font-size:11px;">REMOVE</span></div></div>`;});
  calculateCartTotal();
}

function toggleItemCheck(index){cart[index].checked=!cart[index].checked;localStorage.setItem('printCart',JSON.stringify(cart));calculateCartTotal();}
function calculateCartTotal(){const drawerTotal=document.getElementById('drawerTotal');let subtotal=cart.reduce((acc,item)=>item.checked?acc+item.price:acc,0);if(drawerTotal)drawerTotal.innerText=subtotal.toLocaleString(undefined,{minimumFractionDigits:2});}

function handleContactForm(){
  const btn=document.querySelector('#contactForm button');if(!btn)return;
  btn.addEventListener('click',e=>{
    e.preventDefault();
    const nameInput=document.querySelector('input[placeholder="Your Name"]'),emailInput=document.querySelector('input[placeholder="Email Address"]'),msgInput=document.querySelector('textarea');
    if(!nameInput?.value||!emailInput?.value||!msgInput?.value)return alert("Please fill in all fields.");
    btn.innerText="SENDING...";btn.disabled=true;
    setTimeout(()=>{alert(`Thank you, ${nameInput.value}! Your message has been sent.`);btn.innerText="SEND MESSAGE";btn.disabled=false;nameInput.value='';emailInput.value='';msgInput.value='';},1500);
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