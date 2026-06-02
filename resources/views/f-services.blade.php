<section id="products" class="pfsvc">
  <div class="pfsvc-wrap">
    <div class="pfsvc-layout">
      <aside class="pfsvc-browse">
        <h3>BROWSE SERVICES</h3>
        <button type="button" class="active" data-filter="all"><i class="fa-solid fa-table-cells-large"></i>All Services</button>
        <button type="button" data-filter="doc"><i class="fa-solid fa-print"></i>Document Printing</button>
        <button type="button" data-filter="photo"><i class="fa-solid fa-copy"></i>Photocopy &amp; Scanning</button>
        <button type="button" data-filter="id"><i class="fa-solid fa-id-card"></i>ID &amp; Photo Services</button>
        <button type="button" data-filter="bind"><i class="fa-solid fa-book-open"></i>Lamination &amp; Binding</button>
        <button type="button" data-filter="largeformat"><i class="fa-solid fa-image"></i>Large Format Printing</button>
        <button type="button" data-filter="special"><i class="fa-solid fa-star"></i>Custom Special Printing</button>

        <div class="pfsvc-filter">
          <h4>FILTER BY</h4>
          <div class="pfsvc-filter-head"><span>Price Range</span><i class="fa-solid fa-chevron-down"></i></div>
          <div class="pfsvc-range-wrap"><input type="range" id="pfsvcPrice" min="0" max="100" value="100" step="1" disabled></div>
          <div class="pfsvc-filter-head pfsvc-last"><span>Turnaround Time</span><i class="fa-solid fa-chevron-down"></i></div>
          <select id="pfsvcTime" class="pfsvc-time">
            <option value="all">Any Time</option>
            <option value="same-day">Same Day</option>
            <option value="next-day">Next Day</option>
            <option value="custom">Custom</option>
          </select>
        </div>
      </aside>

      <main class="pfsvc-main">
        <div class="pfsvc-head">
          <div>
            <span>WHAT WE DO</span>
            <h2>Our Featured Services</h2>
            <p>Professional printing solutions tailored to your business and personal needs.</p>
          </div>
        </div>

        <div class="pfsvc-tools" aria-label="Service controls">
          <button type="button" class="pfsvc-chip active" onclick="setServiceCategory('all')"><i class="fa-solid fa-table-cells-large"></i> All Services</button>
          <div class="pfsvc-tool-right">
            <label class="pfsvc-sort">Sort by:
              <select id="pfsvcSort" onchange="sortServices()">
                <option value="popular">Popular</option>
                <option value="name">Name</option>
                <option value="category">Category</option>
              </select>
            </label>
            <button type="button" class="pfsvc-view active" onclick="setServiceView('grid')" aria-label="Grid view"><i class="fa-solid fa-grip"></i></button>
            <button type="button" class="pfsvc-view" onclick="setServiceView('list')" aria-label="List view"><i class="fa-solid fa-list"></i></button>
          </div>
        </div>

        <div class="pfsvc-grid">
          <div class="pfsvc-card" data-category="doc" data-time="same-day" onclick="openService('doc')">
            <div class="pfsvc-img"><img src="{{ asset('images/optimized/Document PrintingS.webp') }}" alt="Document Printing" loading="eager" fetchpriority="high" decoding="async"><div class="pfsvc-icon"><i class="fa-solid fa-print"></i></div></div>
            <div class="pfsvc-body"><h3>DOCUMENT PRINTING</h3><p>High-quality black &amp; white or color document printing.</p><button type="button">LEARN MORE <i class="fa-solid fa-arrow-right"></i></button></div>
          </div>

          <div class="pfsvc-card" data-category="photo" data-time="same-day" onclick="openService('photo')">
            <div class="pfsvc-img"><img src="{{ asset('images/optimized/PhotocopyS.webp') }}" alt="Photocopy and Scanning" loading="eager" fetchpriority="high" decoding="async"><div class="pfsvc-icon"><i class="fa-solid fa-copy"></i></div></div>
            <div class="pfsvc-body"><h3>PHOTOCOPY &amp; SCANNING</h3><p>Clear photocopies and high-resolution scanning services.</p><button type="button">LEARN MORE <i class="fa-solid fa-arrow-right"></i></button></div>
          </div>

          <div class="pfsvc-card" data-category="id" data-time="same-day" onclick="openService('id')">
            <div class="pfsvc-img"><img src="{{ asset('images/optimized/Photo IDS.webp') }}" alt="ID and Photo Services" loading="eager" fetchpriority="high" decoding="async"><div class="pfsvc-icon"><i class="fa-solid fa-id-card"></i></div></div>
            <div class="pfsvc-body"><h3>ID &amp; PHOTO SERVICES</h3><p>Professional ID pictures and photo printing services.</p><button type="button">LEARN MORE <i class="fa-solid fa-arrow-right"></i></button></div>
          </div>

          <div class="pfsvc-card" data-category="bind" data-time="next-day" onclick="openService('bind')">
            <div class="pfsvc-img"><img src="{{ asset('images/optimized/Lamination & BindingS.webp') }}" alt="Lamination and Binding" loading="eager" decoding="async"><div class="pfsvc-icon"><i class="fa-solid fa-book-open"></i></div></div>
            <div class="pfsvc-body"><h3>LAMINATION &amp; BINDING</h3><p>Durable lamination and clean document binding solutions.</p><button type="button">LEARN MORE <i class="fa-solid fa-arrow-right"></i></button></div>
          </div>

          <div class="pfsvc-card" data-category="largeformat" data-time="next-day" onclick="openService('largeformat')">
            <div class="pfsvc-img"><img src="{{ asset('images/optimized/Large FormatingS.webp') }}" alt="Large Format Printing" loading="eager" decoding="async"><div class="pfsvc-icon"><i class="fa-solid fa-image"></i></div></div>
            <div class="pfsvc-body"><h3>LARGE FORMAT PRINTING</h3><p>Banners, posters, tarpaulins, and oversized print output.</p><button type="button">LEARN MORE <i class="fa-solid fa-arrow-right"></i></button></div>
          </div>

          <div class="pfsvc-card" data-category="special" data-time="custom" onclick="openService('special')">
            <div class="pfsvc-img"><img src="{{ asset('images/optimized/Custom SpecialS.webp') }}" alt="Custom Special Printing" loading="eager" decoding="async"><div class="pfsvc-icon"><i class="fa-solid fa-star"></i></div></div>
            <div class="pfsvc-body"><h3>CUSTOM SPECIAL PRINTING</h3><p>Personalized printing for custom designs and special projects.</p><button type="button">LEARN MORE <i class="fa-solid fa-arrow-right"></i></button></div>
          </div>

          <div class="pfsvc-empty" id="pfsvcEmpty">
            <i class="fa-solid fa-circle-info"></i>
            <h4>No featured service found</h4>
            <p>This category is available for inquiry. Please contact us for more details.</p>
          </div>
        </div>

        <div class="pfsvc-bottom">
          <div class="pfsvc-bottom-icon"><i class="fa-solid fa-truck-fast"></i></div>
          <div class="pfsvc-bottom-text"><h4>Bulk Order or Regular Printing Needs?</h4><p>Get support for business and bulk printing orders.</p></div>
          <a href="/contact">Contact Us <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <section class="pfdetail" id="serviceDetailSection">
          <div class="pfdetail-card">
            <div class="pfdetail-icon" id="detailIcon"><i class="fa-solid fa-print"></i></div>
            <div>
              <span>SELECTED SERVICE</span>
              <h3 id="detailTitle">Document Printing - Text Only</h3>
              <p id="detailDesc">Plain document printing service.</p>
              <button type="button" onclick="goToSelectedServiceDetail()">OPEN SERVICE DETAILS <i class="fa-solid fa-arrow-right"></i></button>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</section>

<div class="pfdeck-pop" id="serviceDeckPop">
  <button type="button" class="pfdeck-close" onclick="closeServiceDeck()" aria-label="Close service options"><i class="fa-solid fa-xmark"></i></button>

  <div class="pfdeck-title">
    <span id="serviceDeckKicker"><i class="fa-solid fa-print"></i> DOCUMENT PRINTING</span>
    <h3 id="serviceDeckHeading">Choose Print Option</h3>
    <p>Select one card to continue.</p>
  </div>

  <button type="button" class="pfdeck-arrow pfdeck-left" id="serviceDeckLeft" onclick="moveServiceDeck(-1)" aria-label="Previous option"><i class="fa-solid fa-chevron-left"></i></button>

  <div class="pfdeck-stage" id="serviceDeck"></div>

  <button type="button" class="pfdeck-arrow pfdeck-right" id="serviceDeckRight" onclick="moveServiceDeck(1)" aria-label="Next option"><i class="fa-solid fa-chevron-right"></i></button>

  <div class="pfdeck-bottom">
    <div class="pfdeck-dots" id="serviceDeckDots"></div>
    <button type="button" class="pfdeck-continue" id="serviceDeckBtn" onclick="proceedServiceOption()">
      <span>Continue with Text Only</span>
      <i class="fa-solid fa-arrow-right"></i>
    </button>
  </div>
</div>

<div class="pfsvc-modal" id="serviceModal">
  <div class="pfsvc-modal-box">
    <button type="button" class="pfsvc-close" onclick="closeModal()">×</button>
    <div class="pfsvc-modal-icon" id="modalIcon"><i class="fa-solid fa-print"></i></div>
    <h3 id="modalTitle">Service Title</h3>
    <p id="modalDescription">Service description here.</p>
  </div>
</div>

<style>
.pfsvc{width:100vw;margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);padding:32px 18px 70px 70px;background:#fff;font-family:'Inter','Poppins',sans-serif;box-sizing:border-box;scroll-margin-top:90px}.pfsvc *{box-sizing:border-box}.pfsvc-wrap{width:100%;max-width:none;margin:0;padding:0}.pfsvc-layout{display:grid;grid-template-columns:285px minmax(0,1fr);gap:80px;align-items:start;width:100%;max-width:1270px;margin:0}.pfsvc-browse{background:#fff;border:1px solid #111827;border-radius:10px;padding:22px 20px 24px;box-shadow:0 14px 35px rgba(0,0,0,.045);overflow:hidden;position:sticky;top:95px}.pfsvc-browse h3,.pfsvc-filter h4{margin:0 0 18px;color:#111;font-size:13px;font-weight:900;letter-spacing:0}.pfsvc-browse button{width:100%;height:38px;border:0;background:#fff;color:#555;display:flex;align-items:center;gap:13px;padding:0 12px;border-radius:4px;font-size:13px;font-weight:700;cursor:pointer;transition:.22s;text-align:left}.pfsvc-browse button i{width:18px;text-align:center;color:#777;font-size:15px}.pfsvc-browse button.active{background:#fff1e8;color:#e8752b;border-left:3px solid #e8752b}.pfsvc-browse button.active i,.pfsvc-browse button:hover i{color:#e8752b}.pfsvc-browse button:hover{background:#fff7f1;color:#e8752b}.pfsvc-filter{margin-top:18px;padding-top:22px;border-top:1px solid #eee}.pfsvc-filter-head{display:flex;align-items:center;justify-content:space-between;color:#555;font-size:13px;font-weight:700;margin-bottom:20px}.pfsvc-range-wrap{height:18px;position:relative;margin:0 0 22px}.pfsvc-range-wrap:before{content:"";position:absolute;left:10px;right:10px;top:8px;height:3px;background:#f3b08b}.pfsvc-range-wrap input{position:relative;width:100%;height:18px;margin:0;background:transparent;appearance:none;cursor:not-allowed;z-index:2;opacity:.75}.pfsvc-range-wrap input::-webkit-slider-thumb{appearance:none;width:15px;height:15px;border:3px solid #ef6b24;background:#fff;border-radius:50%}.pfsvc-range-wrap input::-moz-range-thumb{width:15px;height:15px;border:3px solid #ef6b24;background:#fff;border-radius:50%}.pfsvc-last{border-top:1px solid #eee;padding-top:18px;margin-bottom:10px}.pfsvc-time{width:100%;height:35px;border:1px solid #eee;border-radius:5px;padding:0 10px;color:#555;font:700 12px 'Poppins';outline:none;background:#fff}.pfsvc-main{min-width:0;padding-top:24px}.pfsvc-head{display:flex;justify-content:space-between;align-items:flex-start;gap:25px;margin-bottom:10px}.pfsvc-head span{display:block;color:#ff5a1f;font-size:13px;font-weight:900;letter-spacing:0;margin-bottom:8px}.pfsvc-head h2{margin:0 0 9px;color:#111;font-family:'Playfair Display',serif;font-size:38px;line-height:1.03;font-weight:700;letter-spacing:0}.pfsvc-head p{margin:0;color:#555;font-size:14px;line-height:1.6}.pfsvc-tools{display:flex;align-items:center;justify-content:space-between;gap:14px;margin:0 0 16px;width:100%}.pfsvc-tool-right{display:flex;align-items:center;justify-content:flex-end;gap:9px}.pfsvc-chip,.pfsvc-view{height:34px;border:1px solid #ff7a00;border-radius:5px;background:#ff7a00;color:#000;display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:0 11px;font-size:11px;font-weight:900;cursor:pointer;transition:.2s}.pfsvc-chip.active,.pfsvc-view.active,.pfsvc-chip:hover,.pfsvc-view:hover{background:#111827;border-color:#111827;color:#fff}.pfsvc-sort{height:34px;display:inline-flex;align-items:center;gap:8px;color:#111;font-size:11px;font-weight:800}.pfsvc-sort select{height:34px;min-width:110px;border:1px solid #111827;border-radius:5px;background:#fff;padding:0 9px;font:800 11px 'Poppins';outline:none}.pfsvc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:38px}.pfsvc-grid.list-view{grid-template-columns:1fr}.pfsvc-grid.list-view .pfsvc-card{display:grid;grid-template-columns:310px minmax(0,1fr)}.pfsvc-grid.list-view .pfsvc-img,.pfsvc-grid.list-view .pfsvc-img img{height:160px}.pfsvc-card{background:#fff;border:1px solid #111827;border-radius:8px;overflow:hidden;cursor:pointer;box-shadow:0 12px 28px rgba(0,0,0,.055);transition:.28s}.pfsvc-card:hover{transform:translateY(-6px);background:#fff7f2;box-shadow:0 20px 42px rgba(0,0,0,.12)}.pfsvc-img{height:125px;position:relative;background:#eee;overflow:visible}.pfsvc-img img{width:100%;height:125px;display:block;object-fit:cover;transition:.4s}.pfsvc-card:hover .pfsvc-img img{transform:scale(1.05)}.pfsvc-icon{position:absolute;left:16px;bottom:-20px;width:48px;height:48px;background:#fff;border-radius:7px;display:flex;align-items:center;justify-content:center;color:#111;font-size:19px;box-shadow:0 10px 24px rgba(0,0,0,.16);z-index:3}.pfsvc-body{padding:32px 16px 17px}.pfsvc-body h3{margin:0 0 7px;color:#111;font-family:'Poppins',sans-serif;font-size:12.5px;font-weight:600;letter-spacing:0}.pfsvc-body p{min-height:35px;margin:0 0 19px;color:#666;font-size:11.5px;line-height:1.55}.pfsvc-body button{border:0;background:transparent;padding:0;color:#d36f19;font-size:10.5px;font-weight:900;letter-spacing:0;cursor:pointer}.pfsvc-empty{display:none;grid-column:1/-1;background:#fff;border:1px dashed #111827;border-radius:8px;padding:32px 22px;text-align:center;color:#666;box-shadow:0 12px 28px rgba(0,0,0,.04)}.pfsvc-empty i{width:46px;height:46px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#fff1e8;color:#ff5a1f;font-size:20px;margin-bottom:12px}.pfsvc-empty h4{margin:0 0 6px;color:#111;font-size:14px;font-weight:900}.pfsvc-empty p{margin:0;color:#666;font-size:12.5px}.pfsvc-bottom{margin-top:26px;background:#fff8f2;border:1px solid #111827;border-radius:8px;padding:14px 16px;display:flex;align-items:center;gap:14px;width:min(100%,760px)}.pfsvc-bottom-icon{width:42px;height:42px;border-radius:50%;background:#ff5a1f;color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;flex:0 0 auto}.pfsvc-bottom h4{margin:0 0 3px;color:#111;font-size:13.5px;font-weight:900}.pfsvc-bottom p{margin:0;color:#666;font-size:11.5px;line-height:1.45}.pfsvc-bottom a{margin-left:auto;min-width:145px;text-align:center;padding:10px 14px;border:1px solid #ff7a00;border-radius:4px;color:#000;background:#ff7a00;text-decoration:none;font-size:10.5px;font-weight:800;white-space:nowrap;transition:.25s}.pfsvc-bottom a:hover{background:#111827;border-color:#111827;color:#fff}

.pfdeck-pop{position:fixed;inset:0;z-index:99999;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.58);font-family:'Poppins',sans-serif;overflow:hidden;padding:20px}.pfdeck-pop.active{display:flex}.pfdeck-close{position:absolute;top:21px;right:30px;width:45px;height:45px;border:1px solid rgba(255,255,255,.85);border-radius:50%;background:#fff;color:#151515;font-size:18px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 14px 30px rgba(0,0,0,.2);z-index:40;transition:.22s}.pfdeck-close:hover{background:#ff5a1f;color:#fff;border-color:#ff5a1f;transform:scale(1.05)}.pfdeck-title{position:absolute;top:160px;left:50%;transform:translateX(-50%);text-align:center;color:#fff;z-index:10}.pfdeck-title span{display:inline-flex;align-items:center;gap:7px;color:#ff5a1f;font-size:11px;font-weight:900;letter-spacing:1.7px}.pfdeck-title h3{margin:6px 0 3px;font-size:30px;line-height:1;font-weight:900;letter-spacing:-.7px}.pfdeck-title p{margin:0;color:rgba(255,255,255,.74);font-size:12px}.pfdeck-stage{position:relative;width:min(650px,92vw);height:410px;margin-top:110px;perspective:1200px;transform-style:preserve-3d}.pfdeck-card{position:absolute;left:50%;top:50%;width:252px;min-height:318px;background:#fff;border:1px solid #e8e8e8;border-radius:20px;padding:12px 12px 13px;text-align:center;box-shadow:0 24px 54px rgba(0,0,0,.34);cursor:pointer;transform-origin:center 112%;transition:transform .9s cubic-bezier(.16,1,.3,1),opacity .35s ease,filter .35s ease,box-shadow .35s ease,border-color .35s ease;will-change:transform}.pfdeck-card.has-photo{padding:10px 10px 12px;min-height:320px}.pfdeck-pop.moving .pfdeck-card{transition:transform 1s cubic-bezier(.16,1,.3,1),opacity .35s ease,filter .35s ease}.pfdeck-pop.entering .pfdeck-card{opacity:0!important;transform:translate(-50%,-50%) translateY(65px) scale(.76) rotate(0deg)!important}.pfdeck-card.active{z-index:7;border-color:#ff5a1f;box-shadow:0 34px 78px rgba(0,0,0,.43)}.pfdeck-card.left,.pfdeck-card.right{z-index:4;filter:brightness(.96)}.pfdeck-card:hover{filter:none}.pfdeck-img{height:154px;border-radius:16px;background:#fafafa;border:1px solid #eee;display:flex;align-items:center;justify-content:center;margin-bottom:12px;overflow:hidden}.pfdeck-img.with-photo{height:205px;min-height:205px;max-height:205px;padding:7px;border:1px solid #f1d7c9;background:#fff7f2;overflow:hidden;margin-bottom:8px}.pfdeck-service-photo{width:100%;height:100%;max-height:none;display:block;object-fit:contain;object-position:center;border-radius:15px;box-shadow:0 10px 22px rgba(0,0,0,.12)}.pfdeck-card h4{margin:0 0 4px;color:#111;font-size:15px;line-height:1.08;font-weight:900;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.pfdeck-card p{margin:0 auto;color:#666;font-size:10.5px;line-height:1.25;min-height:14px;max-width:205px;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}.pfdeck-card small{display:block;margin-top:5px;color:#555;font-size:10.5px}.pfdeck-card small b{color:#ff5a1f}.paper{width:92px;height:114px;background:#fff;border:1px solid #ddd;border-radius:7px;box-shadow:0 10px 20px rgba(0,0,0,.1);padding:15px 11px}.paper b,.paper span{display:block;height:5px;border-radius:9px;background:#cfcfcf;margin-bottom:9px}.paper b{width:55%;background:#aaa}.bars{height:63px;display:flex;align-items:flex-end;gap:6px;margin-bottom:9px}.bars i{width:13px;background:#bbb;border-radius:4px 4px 0 0}.bars i:nth-child(1){height:24px}.bars i:nth-child(2){height:45px}.bars i:nth-child(3){height:35px}.bars i:nth-child(4){height:60px}.tiles{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-bottom:9px}.tiles i{height:28px;border-radius:7px;background:#ff5a1f}.tiles i:nth-child(2){background:#ffb03a}.tiles i:nth-child(3){background:#269fe0}.tiles i:nth-child(4){background:#36b870}.pfdeck-service-visual{width:96px;height:116px;border-radius:18px;background:#fff1e8;color:#ff5a1f;display:flex;align-items:center;justify-content:center;font-size:45px;box-shadow:inset 0 0 0 1px #ffd6c2,0 10px 20px rgba(0,0,0,.08)}.pfdeck-arrow{position:absolute;top:55%;transform:translateY(-50%);width:47px;height:47px;border:1px solid rgba(255,255,255,.92);border-radius:50%;background:rgba(255,255,255,.97);color:#111;box-shadow:0 15px 34px rgba(0,0,0,.24);cursor:pointer;z-index:36;transition:.22s;display:flex;align-items:center;justify-content:center;font-size:14px}.pfdeck-arrow:before{content:"";position:absolute;inset:-5px;border-radius:50%;background:rgba(255,90,31,.16);opacity:0;transition:.22s;z-index:-1}.pfdeck-arrow:hover{background:#ff5a1f;color:#fff;border-color:#ff5a1f;transform:translateY(-50%) scale(1.08)}.pfdeck-arrow:hover:before{opacity:1}.pfdeck-left{left:calc(50% - 306px)}.pfdeck-right{right:calc(50% - 306px)}.pfdeck-bottom{position:absolute;left:50%;bottom:96px;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:8px;z-index:25}.pfdeck-dots{display:flex;gap:7px}.pfdeck-dots button{width:7px;height:7px;border:0;border-radius:50%;background:rgba(255,255,255,.46);cursor:pointer;padding:0;transition:.22s}.pfdeck-dots button.active{width:19px;border-radius:20px;background:#ff5a1f}.pfdeck-continue{height:38px;min-width:244px;border:0;border-radius:999px;background:#ff5a1f;color:#fff;display:flex;align-items:center;justify-content:center;gap:8px;padding:0 17px;font-size:11px;font-weight:800;letter-spacing:.25px;cursor:pointer;box-shadow:0 12px 25px rgba(255,90,31,.2);transition:.22s}.pfdeck-continue:hover{background:#e94b12;transform:translateY(-1px)}.pfdeck-continue i{font-size:10px}.pfdeck-card.only{transform-origin:center center}.pfdeck-pop.single .pfdeck-stage{width:min(340px,92vw)}.pfdeck-pop.single .pfdeck-dots{display:none}

.pfdetail{display:none;margin-top:26px;scroll-margin-top:95px}.pfdetail.show{display:block}.pfdetail-card{display:flex;gap:16px;align-items:flex-start;width:min(100%,760px);padding:20px;border:1px solid #fde1d1;background:#fff8f2;border-radius:12px;box-shadow:0 14px 32px rgba(0,0,0,.05)}.pfdetail-icon{width:52px;height:52px;border-radius:14px;background:#ff5a1f;color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;flex:0 0 auto}.pfdetail span{display:block;color:#ff5a1f;font-size:11px;font-weight:900;letter-spacing:1.5px;margin-bottom:4px}.pfdetail h3{margin:0 0 7px;color:#111;font-size:22px;font-weight:900}.pfdetail p{margin:0 0 14px;color:#555;font-size:13px;line-height:1.65}.pfdetail button{border:0;background:#ff5a1f;color:#fff;border-radius:7px;height:39px;padding:0 18px;font-size:11px;font-weight:900;letter-spacing:.5px;cursor:pointer}.pfdetail button i{margin-left:6px}.pfdetail button:hover{background:#e94b12}

.pfsvc-modal{position:fixed;inset:0;background:rgba(0,0,0,.55);display:none;align-items:center;justify-content:center;z-index:99999;padding:20px;font-family:'Poppins',sans-serif}.pfsvc-modal.active{display:flex}.pfsvc-modal-box{position:relative;width:100%;max-width:430px;background:#fff;border-radius:14px;padding:38px 30px 32px;text-align:center;box-shadow:0 25px 80px rgba(0,0,0,.25);animation:pfpop .25s ease}.pfsvc-close{position:absolute;top:10px;right:16px;border:0;background:transparent;font-size:31px;line-height:1;cursor:pointer;color:#111}.pfsvc-modal-icon{width:70px;height:70px;margin:0 auto 17px;border-radius:50%;background:#fff1e8;color:#ff5a1f;display:flex;align-items:center;justify-content:center;font-size:30px}.pfsvc-modal-box h3{margin:0 0 10px;color:#111;font-size:23px;font-weight:900}.pfsvc-modal-box p{margin:0;color:#666;font-size:14px;line-height:1.7}
@keyframes pfpop{from{opacity:0;transform:translateY(18px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}
@media(max-width:1100px){.pfsvc{padding-left:10px;padding-right:18px}.pfsvc-layout{grid-template-columns:260px minmax(0,1fr);gap:28px}.pfsvc-grid{grid-template-columns:repeat(2,1fr);gap:30px}.pfdeck-left{left:calc(50% - 246px)}.pfdeck-right{right:calc(50% - 246px)}}
@media(max-width:850px){.pfsvc{padding:25px 16px 55px}.pfsvc-layout{grid-template-columns:1fr}.pfsvc-browse{position:relative;top:auto}.pfsvc-main{padding-top:10px}.pfsvc-head{flex-direction:column}.pfsvc-head h2{font-size:32px}.pfsvc-grid{grid-template-columns:1fr}.pfsvc-bottom,.pfdetail-card{width:100%;flex-direction:column;align-items:flex-start}.pfsvc-bottom a{margin-left:0;width:100%}.pfdeck-title{top:118px}.pfdeck-title h3{font-size:27px}.pfdeck-stage{height:380px;margin-top:92px}.pfdeck-card{width:224px;min-height:302px}.pfdeck-card.has-photo{min-height:306px}.pfdeck-img.with-photo{height:188px;min-height:188px;max-height:188px}.pfdeck-left{left:calc(50% - 212px)}.pfdeck-right{right:calc(50% - 212px)}.pfdeck-bottom{bottom:78px}}
@media(max-width:600px){.pfdeck-card{width:204px}.pfdeck-card.right,.pfdeck-card.left{opacity:.82}.pfdeck-arrow{top:57%;width:40px;height:40px}.pfdeck-img{height:122px}.pfdeck-img.with-photo{height:170px;min-height:170px;max-height:170px}.paper{transform:scale(.86)}.pfdeck-continue{min-width:220px}.pfdeck-bottom{bottom:68px}.pfdeck-left{left:calc(50% - 182px)}.pfdeck-right{right:calc(50% - 182px)}}
</style>

<script>
const SERVICE_DETAIL_ROUTE="/service-detail";
const SERVICE_DETAIL_TARGET_SELECTOR="#productDetail, #serviceDetailInfo, #service-details, #serviceDetail, #productDetails, #serviceProductDetail";

const serviceDeckData={
  doc:{
    title:"DOCUMENT PRINTING",
    heading:"Choose Print Option",
    icon:"fa-solid fa-print",
    options:[
      {slug:"text-only",name:"Text Only",label:"TEXT ONLY",desc:"Plain B&W documents.",price:"from <b>₱1.00/page</b>",art:"text",icon:"fa-solid fa-file-lines",serviceId:"DOC-TX-001"},
      {slug:"text-graphics",name:"Text + Image",label:"TEXT WITH IMAGE",desc:"Text with simple images.",price:"from <b>₱2.00/page</b>",art:"graph",icon:"fa-solid fa-chart-simple",serviceId:"DOC-TWI-004"},
      {slug:"full-color",name:"Full Color",label:"FULL COLOR",desc:"Colored document print.",price:"from <b>₱5.00/page</b>",art:"color",icon:"fa-solid fa-palette",serviceId:"DOC-TX-003"}
    ]
  },
  photo:{
    title:"PHOTOCOPY & SCANNING",
    heading:"Choose Service Option",
    icon:"fa-solid fa-copy",
    options:[
      {slug:"photocopy",name:"Photocopy",label:"PHOTOCOPY",desc:"Clear copies.",price:"Available for <b>inquiry</b>",art:"icon",icon:"fa-solid fa-copy",serviceId:"DOC-PCPY-001"},
      {slug:"scanning",name:"Scanning",label:"SCANNING",desc:"Digital scans.",price:"Available for <b>inquiry</b>",art:"icon",icon:"fa-solid fa-magnifying-glass",serviceId:"DOC-SCN-001"}
    ]
  },
  id:{
    title:"ID & PHOTO SERVICES",
    heading:"Choose Service Option",
    icon:"fa-solid fa-id-card",
    options:[
      {slug:"id-photo",name:"ID Photo",label:"ID PHOTO",desc:"ID photo print.",price:"Available for <b>inquiry</b>",art:"image",image:"{{ asset('images/Photo ID (cover).png') }}",icon:"fa-solid fa-id-card",serviceId:"IDP-PKG-001"},
      {slug:"passport-visa",name:"Passport/Visa",label:"PASSPORT/VISA",desc:"Passport sizes.",price:"Available for <b>inquiry</b>",art:"icon",icon:"fa-solid fa-passport",serviceId:"IDP-PKG-004"},
      {slug:"single-photo-print",name:"Single Photo",label:"SINGLE PHOTO",desc:"Photo print.",price:"Available for <b>inquiry</b>",art:"icon",icon:"fa-solid fa-image",serviceId:"IDP-SP-003"}
    ]
  },
  bind:{
    title:"LAMINATION & BINDING",
    heading:"Choose Service Option",
    icon:"fa-solid fa-book-open",
    options:[
      {slug:"lamination",name:"Lamination",label:"LAMINATION",desc:"Protective film.",price:"Available for <b>inquiry</b>",art:"icon",icon:"fa-solid fa-layer-group",serviceId:"LAM-001"},
      {slug:"spiral-binding",name:"Spiral Binding",label:"SPIRAL BINDING",desc:"Bound reports.",price:"Available for <b>inquiry</b>",art:"icon",icon:"fa-solid fa-book-open",serviceId:"BND-SPR-001"}
    ]
  },
  largeformat:{
    title:"LARGE FORMAT PRINTING",
    heading:"Choose Service Option",
    icon:"fa-solid fa-image",
    options:[
      {slug:"sintra-board",name:"Sintra Board",label:"SINTRA BOARD",desc:"Rigid signage.",price:"Available for <b>inquiry</b>",art:"icon",icon:"fa-solid fa-border-all",serviceId:"LF-SIN-001"}
    ]
  },
  special:{
    title:"CUSTOM SPECIAL PRINTING",
    heading:"Choose Service Option",
    icon:"fa-solid fa-star",
    options:[
      {slug:"custom-special-printing",name:"Custom Print",label:"CUSTOM PRINT",desc:"Custom request.",price:"Available for <b>inquiry</b>",art:"icon",icon:"fa-solid fa-star",serviceId:"CSP-001"}
    ]
  }
};

const serviceDetails={
  fallback:["SERVICE INQUIRY","This service is available for inquiry. Please contact us for complete details.","fa-solid fa-circle-info"]
};

let currentCategory="all",activeServiceKey="doc",serviceActive=0,lastSelectedService=null;

function deckSafe(text){return String(text||"").replace(/[&<>"']/g,function(ch){return {"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[ch];});}
function stripHtml(text){const div=document.createElement("div");div.innerHTML=String(text||"");return div.textContent||div.innerText||"";}
function slugSafe(text){return String(text||"").toLowerCase().trim().replace(/&/g,"and").replace(/[^a-z0-9]+/g,"-").replace(/^-+|-+$/g,"");}

function serviceOptionArt(option){
  if(option.art==="text")return '<div class="paper text-paper"><b></b><span></span><span></span><span></span><span></span><span></span></div>';
  if(option.art==="graph")return '<div class="paper graph-paper"><b></b><div class="bars"><i></i><i></i><i></i><i></i></div><span></span></div>';
  if(option.art==="color")return '<div class="paper color-paper"><b></b><div class="tiles"><i></i><i></i><i></i><i></i></div><span></span></div>';
  if(option.art==="image"&&option.image)return `<img class="pfdeck-service-photo" src="${deckSafe(option.image)}" alt="${deckSafe(option.name||"Service image")}" loading="lazy" decoding="async">`;
  return `<div class="pfdeck-service-visual"><i class="${deckSafe(option.icon||"fa-solid fa-circle")}"></i></div>`;
}

function openService(key){
  if(serviceDeckData[key]){openServiceDeck(key);return;}
  const d=serviceDetails.fallback;
  document.getElementById("modalTitle").textContent=d[0];
  document.getElementById("modalDescription").textContent=d[1];
  document.getElementById("modalIcon").innerHTML=`<i class="${d[2]}"></i>`;
  document.getElementById("serviceModal").classList.add("active");
  document.body.style.overflow="hidden";
}

function openServiceDeck(key){
  const data=serviceDeckData[key];
  if(!data)return;
  const pop=document.getElementById("serviceDeckPop");
  activeServiceKey=key;
  serviceActive=0;
  document.getElementById("serviceDeckKicker").innerHTML=`<i class="${data.icon}"></i> ${deckSafe(data.title)}`;
  document.getElementById("serviceDeckHeading").textContent=data.heading;
  pop.classList.toggle("single",data.options.length===1);
  buildServiceDeck();
  pop.classList.add("active","entering");
  document.body.style.overflow="hidden";
  renderServiceDeck();
  setTimeout(()=>pop.classList.remove("entering"),80);
}

function buildServiceDeck(){
  const data=serviceDeckData[activeServiceKey];
  const deck=document.getElementById("serviceDeck");
  if(!data||!deck)return;
  deck.innerHTML=data.options.map((option,i)=>`
    <article class="pfdeck-card ${option.art==="image"?"has-photo":""}" data-index="${i}">
      <div class="pfdeck-img ${option.art==="image"?"with-photo":""}">${serviceOptionArt(option)}</div>
      <h4 title="${deckSafe(option.name)}">${deckSafe(option.name)}</h4>
      <p>${deckSafe(option.desc)}</p>
      <small>${option.price}</small>
    </article>
  `).join("");
}

function renderServiceDeck(){
  const data=serviceDeckData[activeServiceKey];
  const deck=document.getElementById("serviceDeck");
  if(!data||!deck)return;
  let cards=[...deck.querySelectorAll(".pfdeck-card")];
  if(cards.length!==data.options.length){buildServiceDeck();cards=[...deck.querySelectorAll(".pfdeck-card")];}
  const total=cards.length;
  cards.forEach((card,i)=>{
    const hasPhoto=card.classList.contains("has-photo");
    card.className=`pfdeck-card${hasPhoto?" has-photo":""}`;
    if(total===1){
      card.classList.add("active","only");
      card.style.transform="translate(-50%,-50%) translateX(0) translateY(-21px) rotate(0deg) scale(1.08)";
      return;
    }
    if(total===2){
      if(i===serviceActive){
        card.classList.add("active");
        card.style.transform="translate(-50%,-50%) translateX(0) translateY(-21px) rotate(0deg) scale(1.08)";
      }else if(serviceActive===0){
        card.classList.add("right");
        card.style.transform="translate(-50%,-50%) translateX(58px) translateY(29px) rotate(18deg) scale(.93)";
      }else{
        card.classList.add("left");
        card.style.transform="translate(-50%,-50%) translateX(-58px) translateY(29px) rotate(-18deg) scale(.93)";
      }
      return;
    }
    const pos=(i-serviceActive+total)%total;
    if(pos===0){
      card.classList.add("active");
      card.style.transform="translate(-50%,-50%) translateX(0) translateY(-21px) rotate(0deg) scale(1.08)";
    }else if(pos===1){
      card.classList.add("right");
      card.style.transform="translate(-50%,-50%) translateX(58px) translateY(29px) rotate(18deg) scale(.93)";
    }else{
      card.classList.add("left");
      card.style.transform="translate(-50%,-50%) translateX(-58px) translateY(29px) rotate(-18deg) scale(.93)";
    }
  });

  document.getElementById("serviceDeckDots").innerHTML=total>1?data.options.map((_,i)=>`<button type="button" class="${i===serviceActive?'active':''}" onclick="selectServiceDeck(${i})"></button>`).join(""):"";
  document.querySelector("#serviceDeckBtn span").textContent=`Continue with ${data.options[serviceActive].name}`;
  document.getElementById("serviceDeckLeft").style.display=total>1?"flex":"none";
  document.getElementById("serviceDeckRight").style.display=total>1?"flex":"none";
}

function moveServiceDeck(step){
  const data=serviceDeckData[activeServiceKey];
  if(!data||data.options.length<2)return;
  const pop=document.getElementById("serviceDeckPop");
  pop.classList.add("moving");
  serviceActive=(serviceActive+step+data.options.length)%data.options.length;
  renderServiceDeck();
  setTimeout(()=>pop.classList.remove("moving"),1000);
}

function selectServiceDeck(i){
  const data=serviceDeckData[activeServiceKey];
  if(!data||!data.options[i])return;
  const pop=document.getElementById("serviceDeckPop");
  pop.classList.add("moving");
  serviceActive=i;
  renderServiceDeck();
  setTimeout(()=>pop.classList.remove("moving"),1000);
}

function getSelectedServicePayload(){
  const data=serviceDeckData[activeServiceKey];
  const selected=data&&data.options[serviceActive]?data.options[serviceActive]:null;
  if(!data||!selected)return null;
  return {
    categoryKey:activeServiceKey,
    categoryTitle:data.title,
    categorySlug:slugSafe(data.title),
    serviceName:selected.name,
    serviceSlug:selected.slug||slugSafe(selected.name),
    serviceLabel:selected.label||selected.name,
    serviceDescription:selected.desc,
    servicePriceText:stripHtml(selected.price),
    serviceIcon:selected.icon||data.icon,
    serviceImage:selected.image||"",
    serviceId:selected.serviceId||"SRV-INQ-001"
  };
}

function proceedServiceOption(){
  const payload=getSelectedServicePayload();
  if(!payload)return;
  if(typeof requireSignedInForOrder==="function"&&!requireSignedInForOrder())return;
  lastSelectedService=payload;
  sessionStorage.setItem("selectedPrintifyService",JSON.stringify(payload));
  window.dispatchEvent(new CustomEvent("printifyServiceSelected",{detail:payload}));
  closeServiceDeck();
  openSelectedServiceDetail(payload);
}

function setDetailText(id,value){
  const el=document.getElementById(id);
  if(el)el.textContent=value;
}

function setDetailHtml(id,value){
  const el=document.getElementById(id);
  if(el)el.innerHTML=value;
}

function setSelectChoice(id,value,label){
  const select=document.getElementById(id);
  if(!select||!value)return;
  let option=[...select.options].find(item=>item.value===value);
  if(!option){
    option=new Option(label||value,value,true,true);
    select.add(option);
  }
  select.value=value;
}

function hydrateProductDetail(payload){
  if(!payload)return;
  const title=`${payload.categoryTitle} - ${payload.serviceName}`;
  const serviceId=payload.serviceId||"SRV-INQ-001";
  setDetailText("detailTitleHeader",payload.serviceName||payload.categoryTitle);
  setDetailText("breadcrumbCategory",payload.categoryTitle||"Services");
  setDetailText("breadcrumbService",payload.serviceName||"Selected Service");
  setDetailText("currentServiceId",serviceId);
  setDetailText("summaryServiceId",`Service ID: ${serviceId}`);
  setDetailText("summaryServiceName",payload.serviceName||"Selected Service");
  setDetailText("summaryServiceMeta",`${payload.categoryTitle||"Service"} • ${payload.servicePriceText||"Available for inquiry"}`);
  setDetailText("summaryServiceQty",`${Math.max(1,Number(document.getElementById("qtyInput")?.value)||1)} sheet`);
  setDetailText("productSpecs",payload.serviceDescription||"Selected printing service.");
  setSelectChoice("printCategory",serviceId,`${payload.serviceName||"Selected Service"} — ${serviceId}`);
  if(payload.serviceImage){
    const summaryImg=document.getElementById("summaryProductImage");
    if(summaryImg)summaryImg.src=payload.serviceImage;
  }
  if(typeof updatePrice==="function")updatePrice();
}

function openSelectedServiceDetail(payload){
  hydrateProductDetail(payload);
  const realDetail=document.querySelector(SERVICE_DETAIL_TARGET_SELECTOR);
  if(realDetail){
    realDetail.removeAttribute("hidden");
    realDetail.classList.add("active","show","selected-service-open");
    setTimeout(()=>realDetail.scrollIntoView({behavior:"smooth",block:"start"}),120);
    return;
  }

  const preview=document.getElementById("serviceDetailSection");
  if(preview){
    document.getElementById("detailTitle").textContent=`${payload.categoryTitle} - ${payload.serviceName}`;
    document.getElementById("detailDesc").textContent=payload.serviceDescription;
    document.getElementById("detailIcon").innerHTML=`<i class="${payload.serviceIcon}"></i>`;
    preview.classList.add("show");
    setTimeout(()=>preview.scrollIntoView({behavior:"smooth",block:"start"}),120);
    return;
  }

  goToSelectedServiceDetail();
}

function goToSelectedServiceDetail(){
  if(typeof requireSignedInForOrder==="function"&&!requireSignedInForOrder())return;
  let payload=lastSelectedService;
  if(!payload){
    try{payload=JSON.parse(sessionStorage.getItem("selectedPrintifyService")||"null");}catch(e){payload=null;}
  }
  if(!payload)return;

  if(SERVICE_DETAIL_ROUTE){
    const url=`${SERVICE_DETAIL_ROUTE}?category=${encodeURIComponent(payload.categorySlug)}&service=${encodeURIComponent(payload.serviceSlug)}`;
    window.location.href=url;
    return;
  }

  const realDetail=document.querySelector(SERVICE_DETAIL_TARGET_SELECTOR);
  if(realDetail){
    hydrateProductDetail(payload);
    realDetail.removeAttribute("hidden");
    realDetail.classList.add("active","show","selected-service-open");
    realDetail.scrollIntoView({behavior:"smooth",block:"start"});
    return;
  }

  window.location.hash="productDetail";
}

function closeServiceDeck(){document.getElementById("serviceDeckPop").classList.remove("active","entering","moving","single");document.body.style.overflow="";}
function closeModal(){document.getElementById("serviceModal").classList.remove("active");document.body.style.overflow="";}

const serviceDeckEl=document.getElementById("serviceDeck");
serviceDeckEl.addEventListener("click",e=>{
  const card=e.target.closest(".pfdeck-card");
  if(!card)return;
  const index=Number(card.dataset.index);
  if(index===serviceActive){proceedServiceOption();return;}
  selectServiceDeck(index);
});

function applyServiceFilters(){
  const selectedTime=document.getElementById("pfsvcTime").value,empty=document.getElementById("pfsvcEmpty");
  let visible=0;
  document.querySelectorAll(".pfsvc-card").forEach(card=>{
    const show=(currentCategory==="all"||card.dataset.category===currentCategory)&&(selectedTime==="all"||card.dataset.time===selectedTime);
    card.style.display=show?"":"none";
    if(show)visible++;
  });
  empty.style.display=visible===0?"block":"none";
}

function setServiceCategory(category){
  currentCategory=category||"all";
  document.querySelectorAll(".pfsvc-browse button").forEach(i=>i.classList.toggle("active",i.dataset.filter===currentCategory));
  document.querySelectorAll(".pfsvc-chip").forEach(i=>i.classList.toggle("active",currentCategory==="all"));
  applyServiceFilters();
}

function sortServices(){
  const grid=document.querySelector(".pfsvc-grid"),sort=document.getElementById("pfsvcSort")?.value||"popular";
  if(!grid)return;
  const cards=[...grid.querySelectorAll(".pfsvc-card")];
  cards.sort((a,b)=>{
    if(sort==="name")return a.querySelector("h3").textContent.localeCompare(b.querySelector("h3").textContent);
    if(sort==="category")return a.dataset.category.localeCompare(b.dataset.category);
    return Number(a.dataset.order||0)-Number(b.dataset.order||0);
  }).forEach(card=>grid.insertBefore(card,document.getElementById("pfsvcEmpty")));
  applyServiceFilters();
}

function setServiceView(view){
  const grid=document.querySelector(".pfsvc-grid");
  if(!grid)return;
  grid.classList.toggle("list-view",view==="list");
  document.querySelectorAll(".pfsvc-view").forEach((btn,i)=>btn.classList.toggle("active",(view==="grid"&&i===0)||(view==="list"&&i===1)));
}

document.querySelectorAll(".pfsvc-card").forEach((card,index)=>card.dataset.order=index);
document.querySelectorAll(".pfsvc-browse button").forEach(btn=>{
  btn.addEventListener("click",function(){
    setServiceCategory(this.dataset.filter||"all");
  });
});

document.getElementById("pfsvcTime").addEventListener("change",applyServiceFilters);

window.addEventListener("click",e=>{
  if(e.target.id==="serviceModal")closeModal();
  if(e.target.id==="serviceDeckPop")closeServiceDeck();
});

window.addEventListener("keydown",e=>{
  const deckOpen=document.getElementById("serviceDeckPop").classList.contains("active");
  if(e.key==="Escape"){closeModal();closeServiceDeck();}
  if(deckOpen&&e.key==="ArrowRight")moveServiceDeck(1);
  if(deckOpen&&e.key==="ArrowLeft")moveServiceDeck(-1);
  if(deckOpen&&e.key==="Enter")proceedServiceOption();
});

applyServiceFilters();
</script>
