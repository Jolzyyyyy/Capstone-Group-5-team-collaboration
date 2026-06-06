<style>
:root{
  --pf-orange:#ff5a1f;
  --pf-orange-dark:#e84c12;
  --pf-black:#101010;
  --pf-text:#171717;
  --pf-muted:#737373;
  --pf-line:#ece8e3;
  --pf-bg:#fbfaf8;
  --pf-card:#ffffff;
  --pf-soft:#fff6ef;
  --pf-soft-2:#fffaf6;
  --pf-green:#16a34a;
  --pf-shadow:0 18px 42px rgba(18,18,18,.055);
  --pf-shadow-soft:0 8px 24px rgba(18,18,18,.035);
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
#checkout{
  font-family:'Poppins',Arial,sans-serif;
  background:
    radial-gradient(circle at 18% 8%,rgba(255,90,31,.035),transparent 26%),
    linear-gradient(180deg,#fff,#fbfaf8 42%,#fff 100%);
  color:var(--pf-text);
  padding:24px 0 54px;
}
#checkout button,
#checkout input,
#checkout select,
#checkout textarea{font-family:'Poppins',Arial,sans-serif}
#checkout button{cursor:pointer;transition:.22s ease}
#checkout a{text-decoration:none;color:inherit}
.pfy-page{width:min(1420px,calc(100% - 80px));margin:0 auto;padding:10px 0 48px}
.pfy-breadcrumb{display:flex;align-items:center;gap:10px;margin:0 0 14px;color:#777;font-size:11px;font-weight:500}
.pfy-breadcrumb i{font-size:9px;color:#aaa}
.pfy-breadcrumb a:hover,
.pfy-breadcrumb strong{color:var(--pf-orange);text-decoration:underline;text-underline-offset:3px}
.pfy-page-head{margin-bottom:18px}
.pfy-page-head h1{margin:0;color:#111;font-size:28px;font-weight:900;letter-spacing:-.8px}
.pfy-page-head p{margin:6px 0 0;color:#686868;font-size:13px}
.pfy-main-grid{display:grid;grid-template-columns:minmax(0,1fr) 390px;gap:38px;align-items:start}
.pfy-left{min-width:0}
.pfy-stepper{
  min-height:84px;
  background:var(--pf-card);
  border:1px solid var(--pf-line);
  border-radius:12px;
  box-shadow:var(--pf-shadow-soft);
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:0;
  margin-bottom:20px;
  overflow:hidden;
}
.pfy-step{position:relative;display:flex;align-items:center;gap:14px;padding:0 26px;background:#fff}
.pfy-step:after{content:"";position:absolute;right:0;top:50%;width:76px;border-top:1px solid #e5e1dc;transform:translate(50%,-50%)}
.pfy-step:last-child:after{display:none}
.pfy-step-no{
  width:40px;
  height:40px;
  border-radius:50%;
  background:#f4f4f4;
  color:#111;
  display:grid;
  place-items:center;
  font-size:14px;
  font-weight:900;
  flex:0 0 auto;
  z-index:2;
}
.pfy-step.is-active .pfy-step-no,
.pfy-step.is-done .pfy-step-no{
  background:var(--pf-orange);
  color:#fff;
  box-shadow:0 10px 18px rgba(255,90,31,.22);
}
.pfy-step-copy{position:relative;z-index:2;line-height:1.15}
.pfy-step-copy strong{display:block;font-size:12px;font-weight:900;color:#111}
.pfy-step-copy small{display:block;margin-top:4px;color:#747474;font-size:10px;font-weight:500}
.pfy-card{
  background:rgba(255,255,255,.98);
  border:1px solid var(--pf-line);
  border-radius:12px;
  box-shadow:var(--pf-shadow-soft);
  margin-bottom:18px;
  overflow:hidden;
}
.pfy-card-head{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:22px 24px 8px}
.pfy-card-title{display:flex;align-items:center;gap:13px;min-width:0}
.pfy-card-icon{
  width:34px;
  height:34px;
  border-radius:50%;
  background:#fff0e8;
  color:var(--pf-orange);
  display:grid;
  place-items:center;
  font-size:15px;
  flex:0 0 auto;
}
.pfy-card-title h2{margin:0;color:#111;font-size:16px;font-weight:900;line-height:1.2}
.pfy-card-title p{margin:4px 0 0;color:#777;font-size:11.5px}
.pfy-card-body{padding:12px 24px 22px}
.pfy-field-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.pfy-field-grid.two{grid-template-columns:1.15fr 1fr}
.pfy-field-grid.four{grid-template-columns:1fr 1fr 1fr 1fr}
.pfy-field{position:relative;min-width:0}
.pfy-field label{
  position:absolute;
  left:14px;
  top:8px;
  color:#777;
  font-size:9.5px;
  font-weight:700;
  z-index:2;
  pointer-events:none;
}
.pfy-field label b{color:var(--pf-orange)}
.pfy-field input,
.pfy-field select{
  width:100%;
  height:48px;
  border:1px solid #e4e1dc;
  border-radius:8px;
  background:#fff;
  color:#111;
  outline:0;
  padding:18px 13px 5px;
  font-size:12px;
  font-weight:600;
  box-shadow:0 1px 0 rgba(0,0,0,.012);
}
.pfy-field select{appearance:auto}
.pfy-field input:focus,
.pfy-field select:focus,
.pfy-note-box textarea:focus,
.pfy-promo input:focus{
  border-color:var(--pf-orange);
  box-shadow:0 0 0 4px rgba(255,90,31,.08);
}
.pfy-checkline{margin-top:14px;display:flex;align-items:center;gap:9px;color:#666;font-size:11.5px;font-weight:500}
.pfy-checkline input,
.pfy-section-control input{width:16px;height:16px;accent-color:var(--pf-orange)}
.pfy-section-control{display:flex;align-items:center;gap:9px;color:#555;font-size:11.5px;font-weight:500;white-space:nowrap}
.pfy-radio-stack{display:grid;gap:10px}
.pfy-delivery-option{
  position:relative;
  display:grid;
  grid-template-columns:26px 1fr auto;
  align-items:center;
  gap:10px;
  min-height:54px;
  border:1px solid #e6e3de;
  border-radius:8px;
  padding:10px 14px;
  background:#fff;
  cursor:pointer;
  transition:.22s ease;
}
.pfy-delivery-option:hover,
.pfy-delivery-option.is-selected{
  border-color:#111827;
  background:rgba(17,24,39,.10);
  box-shadow:none;
}
.pfy-delivery-option input{width:16px;height:16px;accent-color:var(--pf-orange)}
.pfy-delivery-copy strong{display:flex;align-items:center;gap:10px;font-size:12px;font-weight:900;color:#111;line-height:1.2}
.pfy-delivery-copy strong em{font-style:normal;padding:3px 8px;border-radius:999px;background:#f5f5f5;color:#666;font-size:10px;font-weight:600}
.pfy-delivery-copy small{display:block;margin-top:3px;color:#777;font-size:10.5px}
.pfy-delivery-price{font-size:13px;font-weight:900;color:#111}
.pfy-note-box{position:relative}
.pfy-note-box textarea{
  width:100%;
  height:72px;
  resize:none;
  border:1px solid #e4e1dc;
  border-radius:8px;
  outline:0;
  background:#fff;
  padding:13px 14px;
  color:#111;
  font-size:12px;
}
.pfy-note-box small{position:absolute;right:13px;bottom:11px;color:#aaa;font-size:10px;font-weight:700}
.pfy-payment-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.pfy-pay-option{
  min-height:58px;
  border:1px solid #e6e3de;
  border-radius:9px;
  background:#fff;
  display:grid;
  grid-template-columns:24px 34px 1fr;
  align-items:center;
  gap:8px;
  padding:8px 10px;
  cursor:pointer;
  transition:.22s ease;
}
.pfy-pay-option input{width:15px;height:15px;accent-color:var(--pf-orange)}
.pfy-pay-option i{width:30px;height:30px;border-radius:7px;background:#f6f6f6;color:#111;display:grid;place-items:center}
.pfy-pay-option span{line-height:1.15}
.pfy-pay-option strong{display:block;color:#111;font-size:11px;font-weight:900}
.pfy-pay-option small{display:block;margin-top:2px;color:#777;font-size:9.2px}
.pfy-pay-option:hover,
.pfy-pay-option.is-selected{border-color:#111827;background:rgba(17,24,39,.10);box-shadow:none}
.pfy-secure-note{margin-top:13px;color:#555;font-size:11px;display:flex;align-items:center;gap:8px}
.pfy-secure-note i{color:#111}
.pfy-sidebar{position:sticky;top:92px}
.pfy-summary{
  background:#fff;
  border:1px solid var(--pf-line);
  border-radius:12px;
  box-shadow:var(--pf-shadow);
  padding:22px;
}
.pfy-summary-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.pfy-summary-title{display:flex;align-items:center;gap:11px}
.pfy-summary-title i{width:32px;height:32px;border-radius:50%;display:grid;place-items:center;background:#fff0e8;color:var(--pf-orange)}
.pfy-summary h3{margin:0;color:#111;font-size:16px;font-weight:900}
.pfy-item-count{color:#555;font-size:12px;font-weight:600}
.pfy-order-items{display:grid;gap:14px}
.pfy-order-item{display:grid;grid-template-columns:64px 1fr auto;gap:12px;align-items:start}
.pfy-order-img{width:64px;height:64px;border-radius:7px;border:1px solid #eee;background:#f4f4f4;object-fit:cover}
.pfy-order-placeholder{width:64px;height:64px;border-radius:7px;border:1px solid #eee;background:linear-gradient(135deg,#f8f8f8,#e8e8e8);display:grid;place-items:center;color:var(--pf-orange);font-size:22px}
.pfy-order-info h4{margin:0 0 4px;color:#111;font-size:12.5px;line-height:1.25;font-weight:900}
.pfy-order-info p{margin:2px 0;color:#555;font-size:10.5px;line-height:1.35}
.pfy-order-price{padding-top:3px;text-align:right;color:#111;font-size:12.5px;font-weight:900;white-space:nowrap}
.pfy-summary-line{height:1px;background:#eeeeee;margin:20px 0}
.pfy-promo{display:grid;grid-template-columns:1fr 84px;gap:10px;margin-bottom:18px}
.pfy-promo input{height:42px;border:1px solid #e4e1dc;border-radius:7px;outline:0;padding:0 12px;color:#111;font-size:11px}
.pfy-promo button{height:42px;border:1px solid #ffd0b8;background:#fff8f3;color:var(--pf-orange);border-radius:7px;font-size:11px;font-weight:900}
.pfy-promo button:hover{background:var(--pf-orange);color:#fff;border-color:var(--pf-orange)}
.pfy-total-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:13px;color:#111;font-size:12px}
.pfy-total-row span{font-weight:600}
.pfy-total-row strong{font-weight:900}
.pfy-total-row.discount strong{color:var(--pf-green)}
.pfy-grand-total{
  margin:18px -4px 0;
  padding:20px 14px;
  border-radius:10px;
  background:linear-gradient(180deg,#fff8f1,#fff4ea);
  display:flex;
  align-items:center;
  justify-content:space-between;
}
.pfy-grand-total span{font-size:15px;font-weight:900;color:#111}
.pfy-grand-total strong{font-size:25px;font-weight:900;color:#111}
.pfy-reward{display:flex;align-items:center;gap:9px;margin:12px 0 16px;color:#777;font-size:11px;font-weight:500}
.pfy-reward i{color:#f4a100}
.pfy-place-order{
  width:100%;
  height:50px;
  border:0;
  border-radius:7px;
  background:var(--pf-orange);
  color:#fff;
  font-size:16px;
  font-weight:900;
  box-shadow:0 15px 28px rgba(255,90,31,.2);
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
}
.pfy-place-order:hover{background:var(--pf-orange-dark);transform:translateY(-1px)}
.pfy-place-order:disabled{background:#cfcfcf;box-shadow:none;transform:none;cursor:not-allowed}
.pfy-terms{margin:13px 6px 0;text-align:center;color:#777;font-size:10.5px;line-height:1.5}
.pfy-terms span{color:var(--pf-orange);font-weight:900}
.pfy-secure-card{
  margin-top:18px;
  background:#fff;
  border:1px solid var(--pf-line);
  border-radius:12px;
  box-shadow:var(--pf-shadow-soft);
  padding:22px;
}
.pfy-secure-wrap{display:grid;grid-template-columns:42px 1fr;gap:14px;align-items:center}
.pfy-secure-icon{width:42px;height:42px;border-radius:50%;border:2px solid #16a34a;color:#16a34a;display:grid;place-items:center;font-size:21px}
.pfy-secure-card h4{margin:0 0 5px;color:#111;font-size:13px;font-weight:900}
.pfy-secure-card p{margin:0;color:#666;font-size:10.5px;line-height:1.5}
.pfy-payment-logos{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:20px;color:#1950a3;font-size:17px;font-weight:900}
.pfy-payment-logos span{font-size:12px;letter-spacing:-.3px}
.pfy-payment-logos .mc{color:#e63900}
.pfy-payment-logos .jcb{color:#059669}
.pfy-payment-logos .gcash{color:#2688df}
.pfy-payment-logos .maya{color:#2cae58}
.pfy-empty{background:#fff;border:1px dashed #ffd0b8;border-radius:12px;padding:34px 24px;text-align:center;color:#555}
.pfy-empty i{font-size:45px;color:var(--pf-orange);margin-bottom:12px}
.pfy-empty h2{margin:0 0 8px;color:#111;font-size:22px}
.pfy-empty p{margin:0 0 18px;font-size:13px}
.pfy-empty a{display:inline-flex;align-items:center;justify-content:center;height:42px;padding:0 18px;border-radius:7px;background:var(--pf-orange);color:#fff;font-size:12px;font-weight:900}
.pfy-toast{
  position:fixed;
  left:50%;
  top:118px;
  z-index:3000;
  width:min(520px,calc(100% - 32px));
  padding:16px 20px;
  border-radius:14px;
  background:#151515;
  color:#fff;
  font-size:12.5px;
  line-height:1.45;
  border:1px solid rgba(255,255,255,.12);
  box-shadow:0 22px 60px rgba(0,0,0,.26);
  transform:translate(-50%,-22px) scale(.98);
  opacity:0;
  pointer-events:none;
  transition:opacity .22s ease,transform .22s ease,background .22s ease,border-color .22s ease;
  text-align:center;
  font-weight:700;
}
.pfy-toast.show{transform:translate(-50%,0) scale(1);opacity:1}
.pfy-toast.is-success{background:#0f5132;border-color:rgba(34,197,94,.36)}
.pfy-toast.is-error{background:#7f1d1d;border-color:rgba(248,113,113,.38)}
.pfy-toast.is-info{background:#151515;border-color:rgba(255,255,255,.12)}
.pfy-success{
  display:none;
  background:#fff;
  border:1px solid #cfead8;
  border-radius:12px;
  box-shadow:var(--pf-shadow);
  padding:28px;
  margin-bottom:20px;
}
.pfy-success.show{display:block}
.pfy-success h2{margin:0 0 8px;color:#111;font-size:22px}
.pfy-success p{margin:0 0 16px;color:#666;font-size:13px}
.pfy-success strong{color:var(--pf-green)}
.pfy-success-actions{display:flex;gap:10px;flex-wrap:wrap}
.pfy-success-actions a,
.pfy-success-actions button{height:40px;padding:0 16px;border-radius:7px;font-size:12px;font-weight:900}
.pfy-success-actions a{display:inline-flex;align-items:center;background:var(--pf-orange);color:#fff}
.pfy-success-actions button{border:1px solid #e2e2e2;background:#fff;color:#111}
@media(max-width:1180px){
  .pfy-page{width:calc(100% - 32px)}
  .pfy-main-grid{grid-template-columns:1fr}
  .pfy-sidebar{position:static}
  .pfy-payment-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:760px){
  #checkout{padding-top:16px}
  .pfy-page{width:calc(100% - 24px)}
  .pfy-stepper{height:auto;grid-template-columns:1fr 1fr}
  .pfy-step{min-height:72px;padding:0 14px}
  .pfy-step:after{display:none}
  .pfy-field-grid,
  .pfy-field-grid.two,
  .pfy-field-grid.four{grid-template-columns:1fr}
  .pfy-card-head{align-items:flex-start;flex-direction:column}
  .pfy-payment-grid{grid-template-columns:1fr}
  .pfy-main-grid{gap:18px}
  .pfy-order-item{grid-template-columns:54px 1fr}
  .pfy-order-price{grid-column:2;text-align:left}
  .pfy-order-img,
  .pfy-order-placeholder{width:54px;height:54px}
  .pfy-summary{padding:18px}
  .pfy-grand-total strong{font-size:21px}
}
/* Customer dashboard style alignment */
:root{--pf-orange:#ff7a00;--pf-orange-dark:#111827;--pf-green:#16a34a;--pf-line:#111827;--pf-hover:rgba(17,24,39,.10);--pf-hover-strong:rgba(17,24,39,.16);--pf-body:'Inter',system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;--pf-head:'Playfair Display',Georgia,serif;--pf-title:'Poppins',system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif}
#checkout{padding:12px 0 30px;background:#fff;font-family:var(--pf-body)!important;font-weight:400;letter-spacing:0;color:#111827}
#checkout button,#checkout input,#checkout select,#checkout textarea{font-family:var(--pf-body)!important;letter-spacing:0}
.pfy-page{width:min(1490px,calc(100% - 30px));padding:10px 0 30px}
.pfy-page-head{margin-bottom:14px}
.pfy-page-head h1{font-family:var(--pf-head)!important;font-size:30px;font-weight:700!important;letter-spacing:0!important;line-height:1.08}
.pfy-page-head p,.pfy-breadcrumb,.pfy-field input,.pfy-field select,.pfy-note-box textarea,.pfy-total-row,.pfy-terms,.pfy-secure-card p,.pfy-order-info p{font-family:var(--pf-body)!important;font-weight:400;letter-spacing:0}
.pfy-card-title h2,.pfy-summary h3,.pfy-secure-card h4,.pfy-empty h2,.pfy-success h2{font-family:var(--pf-title)!important;font-size:14.5px!important;font-weight:600!important;letter-spacing:.018em!important;line-height:1.35}
.pfy-main-grid{gap:18px;grid-template-columns:minmax(0,1fr) 360px}
.pfy-stepper,.pfy-card,.pfy-summary,.pfy-secure-card,.pfy-success{border:1px solid var(--pf-line)!important;border-radius:14px;background:#fff;box-shadow:0 10px 26px rgba(15,23,42,.045);transition:background .18s ease,box-shadow .18s ease,border-color .18s ease}
.pfy-card{margin-bottom:18px}
.pfy-card:hover,.pfy-summary:hover,.pfy-secure-card:hover,.pfy-success:hover{background:var(--pf-hover)!important;border-color:var(--pf-line)!important;box-shadow:0 18px 42px rgba(17,24,39,.14)}
.pfy-card-head{padding:16px 18px 7px}
.pfy-card-body{padding:10px 18px 16px}
.pfy-card-icon,.pfy-summary-title i,.pfy-step.is-active .pfy-step-no,.pfy-step.is-done .pfy-step-no{background:var(--pf-orange)!important;color:#111827!important}
.pfy-field label{font-family:var(--pf-body)!important;font-weight:600;letter-spacing:0}
.pfy-field input,.pfy-field select,.pfy-note-box textarea,.pfy-promo input{border:1px solid #dfe3ea;border-radius:9px;background:#fff;color:#111827;font-weight:400}
.pfy-field input:focus,.pfy-field select:focus,.pfy-note-box textarea:focus,.pfy-promo input:focus{border-color:#111827!important;box-shadow:0 0 0 3px rgba(17,24,39,.10)!important}
.pfy-checkline input,.pfy-section-control input{accent-color:var(--pf-green)}
.pfy-delivery-option,.pfy-pay-option,.pfy-empty{border:1px solid #dfe3ea;transition:background .18s ease,border-color .18s ease,box-shadow .18s ease,transform .18s ease}
.pfy-delivery-option:hover,.pfy-delivery-option.is-selected,.pfy-pay-option:hover,.pfy-pay-option.is-selected,.pfy-empty:hover{background:var(--pf-hover)!important;border-color:#111827!important;box-shadow:none!important;transform:none!important}
.pfy-delivery-option input,.pfy-pay-option input{accent-color:var(--pf-orange)}
.pfy-place-order,.pfy-promo button,.pfy-empty a,.pfy-success-actions a,.pfy-success-actions button{height:42px;min-width:132px;border:0!important;border-radius:10px;background:var(--pf-orange)!important;color:#111827!important;font-family:var(--pf-title)!important;font-size:12px!important;font-weight:600!important;letter-spacing:.014em!important;box-shadow:none!important;display:inline-flex;align-items:center;justify-content:center;gap:8px}
.pfy-place-order{width:100%;font-size:13px!important}
.pfy-place-order:hover,.pfy-promo button:hover,.pfy-empty a:hover,.pfy-success-actions a:hover,.pfy-success-actions button:hover{background:#111827!important;color:#fff!important;transform:none!important}
.pfy-place-order:disabled{background:#cfcfcf!important;color:#fff!important;cursor:not-allowed}
.pfy-grand-total{background:#fff6ef;border-radius:10px}
.pfy-grand-total strong,.pfy-total-row.discount strong,.pfy-success strong,.pfy-secure-icon{color:var(--pf-green)!important}
.pfy-secure-icon{border-color:var(--pf-green)!important}
.pfy-toast{top:96px!important;font-family:var(--pf-body)!important;font-weight:700;border-radius:14px;background:#111827!important}
.pfy-toast.is-success{background:#111827!important;border-color:rgba(255,255,255,.12)}
.pfy-toast.is-error{background:#7f1d1d!important}
@media(max-width:1180px){.pfy-page{width:calc(100% - 32px)}.pfy-main-grid{grid-template-columns:1fr}}
@media(max-width:760px){.pfy-page{width:calc(100% - 24px)}}
.pfy-breadcrumb{font-size:13px!important;font-weight:500!important;gap:12px!important;line-height:1.2}
.pfy-page-head h1,.pfy-success h2{font-size:30px!important}
.pfy-main-grid{grid-template-columns:minmax(0,1fr) 430px!important;gap:18px!important}
.pfy-card,.pfy-summary,.pfy-secure-card,.pfy-stepper,.pfy-success{box-shadow:none!important}
.pfy-card:hover,.pfy-summary:hover,.pfy-secure-card:hover,.pfy-success:hover,.pfy-delivery-option:hover,.pfy-delivery-option.is-selected,.pfy-pay-option:hover,.pfy-pay-option.is-selected,.pfy-empty:hover{box-shadow:none!important}
.pfy-card-icon,.pfy-summary-title i,.pfy-pay-option i,.pfy-secure-icon{background:transparent!important;border:0!important;color:#111827!important;border-radius:0!important}
.pfy-card-icon{width:28px;height:28px;font-size:17px}
.pfy-summary-title i{width:24px;height:24px}
.pfy-pay-option i{width:24px;height:24px}
.pfy-field label b{display:none!important}
.pfy-field label:after{content:":";color:#111827}
.pfy-promo{grid-template-columns:minmax(0,1fr) 112px!important;align-items:center;gap:8px!important;margin-bottom:16px}
.pfy-promo input{width:100%;height:36px!important;min-width:0;padding:0 12px!important}
.pfy-promo button{height:36px!important;min-width:112px!important;border-radius:9px!important}
.pfy-place-order{height:42px!important;box-shadow:none!important}
.pfy-pay-option input{accent-color:#2563eb!important}
.pfy-pay-option.is-selected input{accent-color:#2563eb!important}
.pfy-step.is-active .pfy-step-no,.pfy-step.is-done .pfy-step-no{box-shadow:none!important}
.pfy-card-title h2,.pfy-summary h3,.pfy-secure-card h4,.pfy-empty h2,.pfy-success h2{font-size:14.5px!important}
.pfy-card-title p,.pfy-field input,.pfy-field select,.pfy-checkline,.pfy-section-control,.pfy-delivery-copy strong,.pfy-delivery-copy small,.pfy-delivery-price,.pfy-pay-option strong,.pfy-pay-option small,.pfy-order-info h4,.pfy-order-info p,.pfy-order-price,.pfy-item-count,.pfy-total-row,.pfy-reward,.pfy-terms,.pfy-secure-card p,.pfy-note-box textarea{font-size:12px!important;line-height:1.4!important}
.pfy-field label{font-size:11px!important}
.pfy-breadcrumb strong{color:#ff7a00!important;border-bottom:3px solid #ff7a00;text-decoration:none!important;padding-bottom:3px}
.pfy-breadcrumb #checkoutCategoryCrumb,.pfy-breadcrumb #checkoutServiceCrumb{color:#111827;font-weight:600}
.pfy-step.is-active .pfy-step-no,.pfy-step.is-done .pfy-step-no{background:transparent!important;color:#ff7a00!important;border:1px solid #ff7a00!important}
.pfy-step.is-active .pfy-step-copy strong,.pfy-step.is-done .pfy-step-copy strong{color:#ff7a00!important}
.pfy-step.is-active .pfy-step-copy small,.pfy-step.is-done .pfy-step-copy small{color:#ff7a00!important}
.pfy-step.is-done:after{border-top-color:#ff7a00!important}
.pfy-delivery-option,.pfy-pay-option{border-color:#dfe3ea!important;background:#fff!important}
.pfy-delivery-option:hover,.pfy-delivery-option.is-selected,.pfy-pay-option:hover,.pfy-pay-option.is-selected{border-color:#111827!important;background:rgba(17,24,39,.10)!important}
.pfy-delivery-option.is-selected .pfy-delivery-copy strong,.pfy-delivery-option.is-selected .pfy-delivery-price,.pfy-pay-option.is-selected strong{color:#111827!important}
.pfy-delivery-option input,.pfy-pay-option input{accent-color:#16a34a!important}
.pfy-card-icon,.pfy-summary-title i,.pfy-secure-icon{color:#ff7a00!important}
.pfy-card-title .fa-location-dot,.pfy-card-title .fa-truck,.pfy-card-title .fa-credit-card,.pfy-card-title .fa-clipboard,.pfy-card-title .fa-user{color:#ff7a00!important}
.pfy-pay-option i{color:#111827!important;background:transparent!important}
.pfy-grand-total strong{color:#16a34a!important}
@media(min-width:1181px){
#checkout{padding:40px 18px 70px 100px}
.pfy-page{width:min(1270px,calc(100% - 118px));max-width:1270px;margin:0 18px 0 0;padding:0}
.pfy-main-grid{grid-template-columns:minmax(0,1fr) 430px!important;gap:32px!important}
}
@media(max-width:1180px){.pfy-main-grid{grid-template-columns:1fr!important}}

#checkout .pfy-toast{
  background:transparent!important;
  color:#fff!important;
  border:0!important;
  box-shadow:none!important;
  border-radius:0!important;
  padding:0!important;
  width:auto!important;
  max-width:min(440px,calc(100vw - 32px))!important;
  font-family:var(--pf-body)!important;
  font-size:12px!important;
  font-weight:400!important;
  line-height:1.45!important;
  text-shadow:0 1px 12px rgba(0,0,0,.68)!important;
}
#checkout .pfy-toast.is-success,
#checkout .pfy-toast.is-info,
#checkout .pfy-toast.is-error{
  background:transparent!important;
  border-color:transparent!important;
}
</style>

<section id="checkout" class="section checkout-section" data-section-id="checkout" data-page="checkout">
<main class="pfy-page">
  <div class="pfy-breadcrumb"><a href="/" onclick="jumpTo('home');return false;">Home</a><i class="fa-solid fa-chevron-right"></i><a href="/services" onclick="jumpTo('products');return false;">Services</a><span id="checkoutCategoryCrumbWrap" hidden><i class="fa-solid fa-chevron-right"></i><span id="checkoutCategoryCrumb">Selected Service</span></span><span id="checkoutServiceCrumbWrap" hidden><i class="fa-solid fa-chevron-right"></i><span id="checkoutServiceCrumb">Service Option</span></span><i class="fa-solid fa-chevron-right"></i><strong>Checkout</strong></div>
  <div class="pfy-page-head">
    <h1>Checkout</h1>
    <p>Complete your order in a few simple steps.</p>
  </div>

  <section class="pfy-success" id="successBox" aria-live="polite">
    <h2><i class="fa-solid fa-circle-check" style="color:#16a34a"></i> Payment successful!</h2>
    <p>Your order reference is <strong id="successRef">PFY-ORDER</strong>. Your payment was completed and your order is confirmed.</p>
    <div class="pfy-success-actions">
      <a href="/services" onclick="jumpTo('products');return false;">Back to Services</a>
      <button type="button" onclick="window.print()">Print Confirmation</button>
    </div>
  </section>

  <div class="pfy-main-grid" id="checkoutGrid">
    <section class="pfy-left">
      <div class="pfy-stepper" aria-label="Checkout progress">
        <div class="pfy-step is-active" data-step="1"><span class="pfy-step-no"><i class="fa-solid fa-cart-shopping"></i></span><span class="pfy-step-copy"><strong>Cart</strong><small>Review items</small></span></div>
        <div class="pfy-step" data-step="2"><span class="pfy-step-no">2</span><span class="pfy-step-copy"><strong>Shipping</strong><small>Delivery details</small></span></div>
        <div class="pfy-step" data-step="3"><span class="pfy-step-no">3</span><span class="pfy-step-copy"><strong>Payment</strong><small>Secure payment</small></span></div>
        <div class="pfy-step" data-step="4"><span class="pfy-step-no">4</span><span class="pfy-step-copy"><strong>Review</strong><small>Confirm order</small></span></div>
      </div>

      <section class="pfy-card">
        <div class="pfy-card-head">
          <div class="pfy-card-title"><span class="pfy-card-icon"><i class="fa-solid fa-user"></i></span><div><h2>Customer Information</h2><p>We'll use this information to contact you about your order.</p></div></div>
        </div>
        <div class="pfy-card-body">
          <div class="pfy-field-grid">
            <div class="pfy-field"><label for="fullName">Full Name</label><input type="text" id="fullName" value="{{ old('fullName', Auth::check() ? Auth::user()->name : '') }}" autocomplete="name" required></div>
            <div class="pfy-field"><label for="email">Email Address</label><input type="email" id="email" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" autocomplete="email" required></div>
            <div class="pfy-field"><label for="phone">Phone Number</label><input type="tel" id="phone" value="{{ old('phone', '') }}" autocomplete="tel" required></div>
          </div>
          <label class="pfy-checkline"><input type="checkbox" id="statusUpdates" checked>Keep me updated on order status and exclusive offers</label>
        </div>
      </section>

      <section class="pfy-card">
        <div class="pfy-card-head">
          <div class="pfy-card-title"><span class="pfy-card-icon"><i class="fa-solid fa-location-dot"></i></span><div><h2>Shipping Address</h2><p>Where should we deliver your order?</p></div></div>
          <label class="pfy-section-control"><input type="checkbox" id="differentAddress">Ship to a different address</label>
        </div>
        <div class="pfy-card-body">
          <div class="pfy-field-grid two">
            <div class="pfy-field"><label for="street">Street Address</label><input type="text" id="street" value="{{ old('street', '') }}" autocomplete="street-address" required></div>
            <div class="pfy-field"><label for="apartment">Apartment, suite, etc. (optional)</label><input type="text" id="apartment" value="{{ old('apartment', '') }}"></div>
          </div>
          <div class="pfy-field-grid four" style="margin-top:14px">
            <div class="pfy-field"><label for="city">City / Town</label><input type="text" id="city" value="{{ old('city', '') }}" required></div>
            <div class="pfy-field"><label for="province">State / Province</label><input type="text" id="province" value="{{ old('province', '') }}" required></div>
            <div class="pfy-field"><label for="postal">Postal Code</label><input type="text" id="postal" value="{{ old('postal', '') }}" required></div>
            <div class="pfy-field"><label for="country">Country</label><select id="country"><option value="Philippines" selected>Philippines</option></select></div>
          </div>
        </div>
      </section>

      <section class="pfy-card">
        <div class="pfy-card-head">
          <div class="pfy-card-title"><span class="pfy-card-icon"><i class="fa-solid fa-truck"></i></span><div><h2>Delivery Method</h2><p>Choose how you would like to receive your order.</p></div></div>
        </div>
        <div class="pfy-card-body">
          <div class="pfy-radio-stack">
            <label class="pfy-delivery-option is-selected" data-radio-wrap>
              <input type="radio" name="shipping" value="standard" data-cost="150" checked>
              <span class="pfy-delivery-copy"><strong>Standard Delivery <em>3-5 business days</em></strong><small>Reliable delivery to your doorstep.</small></span>
              <span class="pfy-delivery-price">₱150.00</span>
            </label>
            <label class="pfy-delivery-option" data-radio-wrap>
              <input type="radio" name="shipping" value="express" data-cost="350">
              <span class="pfy-delivery-copy"><strong>Express Delivery <em>1-2 business days</em></strong><small>Faster delivery for urgent orders.</small></span>
              <span class="pfy-delivery-price">₱350.00</span>
            </label>
          </div>
        </div>
      </section>

      <section class="pfy-card">
        <div class="pfy-card-head">
          <div class="pfy-card-title"><span class="pfy-card-icon"><i class="fa-regular fa-clipboard"></i></span><div><h2>Order Notes (Optional)</h2><p>Add any special instructions or notes for your order.</p></div></div>
        </div>
        <div class="pfy-card-body">
          <div class="pfy-note-box"><textarea id="notes" maxlength="250" placeholder="E.g., Please handle with care, deliver during office hours, etc."></textarea><small id="noteCount">0/250</small></div>
        </div>
      </section>

      <section class="pfy-card">
        <div class="pfy-card-head">
          <div class="pfy-card-title"><span class="pfy-card-icon"><i class="fa-regular fa-credit-card"></i></span><div><h2>Payment Method</h2><p>All transactions are secure and encrypted.</p></div></div>
        </div>
        <div class="pfy-card-body">
          <div class="pfy-payment-grid">
            <label class="pfy-pay-option is-selected" data-radio-wrap><input type="radio" name="payment" value="card" checked><i class="fa-regular fa-credit-card"></i><span><strong>Credit / Debit Card</strong><small>Visa, Mastercard, JCB</small></span></label>
            <label class="pfy-pay-option" data-radio-wrap><input type="radio" name="payment" value="gcash"><i class="fa-solid fa-mobile-screen-button"></i><span><strong>GCash</strong><small>Pay using GCash</small></span></label>
            <label class="pfy-pay-option" data-radio-wrap><input type="radio" name="payment" value="maya"><i class="fa-solid fa-wallet"></i><span><strong>Maya</strong><small>Pay using Maya Checkout</small></span></label>
            <label class="pfy-pay-option" data-radio-wrap><input type="radio" name="payment" value="bank"><i class="fa-solid fa-building-columns"></i><span><strong>Bank Transfer</strong><small>Direct bank transfer</small></span></label>
          </div>
          <div class="pfy-secure-note"><i class="fa-solid fa-lock"></i>Your payment information is secure and will never be stored.</div>
        </div>
      </section>
    </section>

    <aside class="pfy-sidebar">
      <section class="pfy-summary">
        <div class="pfy-summary-head"><div class="pfy-summary-title"><i class="fa-regular fa-rectangle-list"></i><h3>Order Summary</h3></div><span class="pfy-item-count" id="summaryItemCount">0 Items</span></div>
        <div id="emptyState" class="pfy-empty" style="display:none"><i class="fa-solid fa-cart-shopping"></i><h2>Your checkout is empty</h2><p>Please go back to Services and choose a printing service first.</p><a href="/services" onclick="jumpTo('products');return false;">Back to Services</a></div>
        <div id="orderContent">
          <div class="pfy-order-items" id="orderItems"></div>
          <div class="pfy-summary-line"></div>
          <div class="pfy-promo"><input type="text" id="promoCode" placeholder="Enter promo code"><button type="button" onclick="applyPromo()">Apply</button></div>
          <div class="pfy-total-row"><span>Subtotal</span><strong id="subtotal">₱0.00</strong></div>
          <div class="pfy-total-row discount"><span>Discount</span><strong id="discount">- ₱0.00</strong></div>
          <div class="pfy-total-row"><span id="shippingLabel">Shipping (Standard Delivery)</span><strong id="shippingCost">₱150.00</strong></div>
          <div class="pfy-grand-total"><span>Total</span><strong id="total">₱0.00</strong></div>
          <div class="pfy-reward"><i class="fa-regular fa-gem"></i><span id="rewardText">You'll earn 0 reward points with this order!</span></div>
          <button class="pfy-place-order" id="placeOrderBtn" type="button" onclick="placeOrder()">Place Order <i class="fa-solid fa-lock"></i></button>
          <p class="pfy-terms">By placing your order, you agree to our <span>Terms of Service</span> and <span>Privacy Policy</span>.</p>
        </div>
      </section>
      <section class="pfy-secure-card">
        <div class="pfy-secure-wrap"><span class="pfy-secure-icon"><i class="fa-solid fa-shield-halved"></i></span><div><h4>Safe &amp; Secure Checkout</h4><p>Your information and payment are protected with industry-standard encryption.</p></div></div>
        <div class="pfy-payment-logos"><span>VISA</span><span class="mc">● ●</span><span class="jcb">JCB</span><span class="gcash">GCash</span><span class="maya">Maya</span></div>
      </section>
    </aside>
  </div>
</main>

<div class="pfy-toast" id="toast" role="status" aria-live="polite"></div>
</section>

<script>

(function(){
"use strict";
const state={items:[],promoCode:"",totals:{subtotal:0,discount:0,shipping:150,tax:0,total:0}};
const els={orderItems:document.getElementById("orderItems"),summaryItemCount:document.getElementById("summaryItemCount"),emptyState:document.getElementById("emptyState"),orderContent:document.getElementById("orderContent"),subtotal:document.getElementById("subtotal"),discount:document.getElementById("discount"),shippingCost:document.getElementById("shippingCost"),shippingLabel:document.getElementById("shippingLabel"),tax:document.getElementById("tax"),total:document.getElementById("total"),rewardText:document.getElementById("rewardText"),headerCartCount:document.getElementById("headerCartCount")||document.getElementById("cartBadge"),toast:document.getElementById("toast"),noteCount:document.getElementById("noteCount"),notes:document.getElementById("notes"),successBox:document.getElementById("successBox"),successRef:document.getElementById("successRef"),checkoutGrid:document.getElementById("checkoutGrid"),categoryCrumb:document.getElementById("checkoutCategoryCrumb"),categoryCrumbWrap:document.getElementById("checkoutCategoryCrumbWrap"),serviceCrumb:document.getElementById("checkoutServiceCrumb"),serviceCrumbWrap:document.getElementById("checkoutServiceCrumbWrap")};
function safeJson(key,fallback){try{return JSON.parse(localStorage.getItem(key)||fallback)}catch(e){return JSON.parse(fallback)}}
function saveJson(key,value){localStorage.setItem(key,JSON.stringify(value))}
function peso(value){return "₱"+Number(value||0).toLocaleString("en-PH",{minimumFractionDigits:2,maximumFractionDigits:2})}
function money(value){return Number(value||0).toFixed(2)}
function escapeHtml(value){return String(value??"").replace(/[&<>"']/g,function(m){return {"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[m]})}
function normalizeNumber(value){return Number(String(value||0).replace(/[^0-9.]/g,""))||0}
function showToast(message,type){
  if(!els.toast)return;
  els.toast.textContent=message;
  els.toast.classList.remove("is-success","is-error","is-info");
  els.toast.classList.add(type==="success"?"is-success":type==="error"?"is-error":"is-info","show");
  clearTimeout(showToast.timer);
  showToast.timer=setTimeout(function(){els.toast.classList.remove("show")},3600);
}
function makeOrderReference(){return "PFY-"+new Date().getFullYear()+"-"+Date.now().toString(36).toUpperCase().slice(-6)+"-"+Math.random().toString(36).slice(2,5).toUpperCase()}
function sourceItems(){
  const checkoutItems=safeJson("printifyCheckoutItems","[]");
  const active=safeJson("printifyActiveCheckout","null");
  const cartItems=safeJson("printifyCartItems","[]");
  const oldCartItems=safeJson("cartItems","[]");
  if(Array.isArray(checkoutItems)&&checkoutItems.length)return checkoutItems;
  if(active&&typeof active==="object")return [active];
  if(Array.isArray(cartItems)&&cartItems.length)return cartItems;
  if(Array.isArray(oldCartItems)&&oldCartItems.length)return oldCartItems;
  return [];
}
function firstMeta(item){
  if(Array.isArray(item.meta))return item.meta;
  const meta=[];
  if(item.category)meta.push(item.category);
  if(item.paperSize||item.colorVariation)meta.push([item.paperSize,item.colorVariation].filter(Boolean).join(" - "));
  if(item.serviceOption)meta.push(item.serviceOption);
  if(item.fileName)meta.push("File: "+item.fileName);
  return meta.filter(Boolean);
}
function normalizeCheckoutItem(item,index){
  const raw=item&&typeof item==="object"?item:{};
  const qty=Math.max(1,parseInt(raw.qty||raw.quantity||1,10)||1);
  const lineTotal=normalizeNumber(raw.total||raw.lineTotal||raw.amountTotal);
  const rawPrice=normalizeNumber(raw.price||raw.unitPrice||raw.unit_price||raw.amount);
  const price=rawPrice || (lineTotal&&qty?lineTotal/qty:0);
  const name=raw.name||raw.serviceName||raw.title||raw.summaryTitle||"Print Item";
  const meta=firstMeta(raw);
  const image=raw.image||raw.img||raw.thumbnail||raw.previewImage||"";
  return {id:String(raw.id||"checkout-item-"+index),name:String(name),qty:qty,price:Number(money(price)),lineTotal:Number(money(lineTotal||price*qty)),image:image,meta:meta,raw:raw,fileName:raw.fileName||((raw.fileMeta&&raw.fileMeta.name)||"")};
}
function loadItems(){state.items=sourceItems().map(normalizeCheckoutItem).filter(function(item){return item.name&&item.qty>0});}
function itemCount(){return state.items.reduce(function(sum,item){return sum+item.qty},0)}
function selectedShipping(){const checked=document.querySelector('input[name="shipping"]:checked');return {name:checked&&checked.value==="express"?"Express Delivery":"Standard Delivery",cost:checked?normalizeNumber(checked.dataset.cost):150}}
function selectedPayment(){const checked=document.querySelector('input[name="payment"]:checked');return checked?checked.value:"card"}
function calculateTotals(){
  const subtotal=state.items.reduce(function(sum,item){return sum+item.lineTotal},0);
  const code=state.promoCode.toUpperCase();
  let discount=0;
  if(code==="SAVE10"||code==="DISCOUNT10")discount=subtotal*.10;
  if(code==="PRINTIFY50")discount=50;
  discount=Math.min(discount,subtotal);
  const shipping=selectedShipping().cost;
  const tax=0;
  const total=subtotal-discount+shipping;
  state.totals={subtotal:subtotal,discount:discount,shipping:shipping,tax:tax,total:total};
}
function imageMarkup(item){
  if(item.image)return '<img class="pfy-order-img" src="'+escapeHtml(item.image)+'" alt="'+escapeHtml(item.name)+'">';
  return '<div class="pfy-order-placeholder"><i class="fa-regular fa-file-lines"></i></div>';
}
function renderItems(){
  const placeOrderButton=document.getElementById("placeOrderBtn");
  if(!state.items.length){
    if(els.emptyState)els.emptyState.style.display="block";
    if(els.orderContent)els.orderContent.style.display="none";
    if(placeOrderButton)placeOrderButton.disabled=true;
    return;
  }
  if(els.emptyState)els.emptyState.style.display="none";
  if(els.orderContent)els.orderContent.style.display="block";
  if(placeOrderButton)placeOrderButton.disabled=false;
  if(!els.orderItems)return;
  els.orderItems.innerHTML=state.items.map(function(item){
    const meta=item.meta.slice(0,3).map(function(line){return '<p>'+escapeHtml(line)+'</p>'}).join("");
    return '<article class="pfy-order-item">'+imageMarkup(item)+'<div class="pfy-order-info"><h4>'+escapeHtml(item.name)+'</h4><p>Quantity: '+item.qty+' pcs</p>'+meta+'</div><div class="pfy-order-price">'+peso(item.lineTotal)+'</div></article>';
  }).join("");
}
function renderCheckoutBreadcrumb(){
  const first=state.items[0]||{};
  const raw=first.raw&&typeof first.raw==="object"?first.raw:{};
  const category=raw.categoryTitle||raw.category||first.category||(Array.isArray(first.meta)?first.meta[0]:"");
  const service=raw.serviceName||raw.title||first.name||"";
  const serviceKey=raw.serviceKey||raw.slug||first.serviceKey||"text-only";
  if(els.categoryCrumb&&els.categoryCrumbWrap){
    els.categoryCrumb.textContent=category||"Selected Service";
    els.categoryCrumbWrap.hidden=!category;
    els.categoryCrumb.style.cursor="pointer";
    els.categoryCrumb.onclick=function(){if(typeof jumpTo==="function")jumpTo("products",{updateUrl:true});else window.location.href="/services"};
  }
  if(els.serviceCrumb&&els.serviceCrumbWrap){
    els.serviceCrumb.textContent=service||"Service Option";
    els.serviceCrumbWrap.hidden=!service;
    els.serviceCrumb.style.cursor="pointer";
    els.serviceCrumb.onclick=function(){if(typeof window.openPrintifyServiceDetail==="function")window.openPrintifyServiceDetail(serviceKey,true);else window.location.href="/service-details?service="+encodeURIComponent(serviceKey)};
  }
}
function renderTotals(){
  calculateTotals();
  const count=itemCount();
  if(els.summaryItemCount)els.summaryItemCount.textContent=count+" "+(count===1?"Item":"Items");
  if(els.headerCartCount)els.headerCartCount.textContent=String(count);
  if(els.subtotal)els.subtotal.textContent=peso(state.totals.subtotal);
  if(els.discount)els.discount.textContent="- "+peso(state.totals.discount);
  if(els.shippingCost)els.shippingCost.textContent=peso(state.totals.shipping);
  if(els.shippingLabel)els.shippingLabel.textContent="Shipping ("+selectedShipping().name+")";
  if(els.tax)els.tax.textContent=peso(state.totals.tax);
  if(els.total)els.total.textContent=peso(state.totals.total);
  if(els.rewardText)els.rewardText.textContent="You'll earn "+Math.max(0,Math.floor(state.totals.total/100))+" reward points with this order!";
}
function renderAll(){renderItems();renderCheckoutBreadcrumb();renderTotals();syncCheckoutStep()}
function updateStep(step){document.querySelectorAll(".pfy-step").forEach(function(node){const current=Number(node.dataset.step);node.classList.toggle("is-active",current===step);node.classList.toggle("is-done",current<step)})}
function hasValues(ids){return ids.every(function(id){const input=document.getElementById(id);return input&&input.value.trim()})}
function syncCheckoutStep(){
  if(!state.items.length){updateStep(1);return}
  const customerDone=hasValues(["fullName","email","phone"]);
  const shippingDone=hasValues(["street","city","province","postal"]);
  if(customerDone&&shippingDone){updateStep(3);return}
  if(customerDone){updateStep(2);return}
  updateStep(1);
}
function updateRadioCards(){document.querySelectorAll("[data-radio-wrap]").forEach(function(label){const input=label.querySelector('input[type="radio"]');label.classList.toggle("is-selected",Boolean(input&&input.checked))})}
function validateForm(){
  const required=["fullName","email","phone","street","city","province","postal"];
  for(const id of required){const input=document.getElementById(id);if(input&&!input.value.trim()){input.focus();showToast("Please complete all required checkout fields.");return false}}
  if(!state.items.length){showToast("Checkout is empty. Please add a service first.");return false}
  return true;
}
function buildCompletedOrder(){
  return {reference:makeOrderReference(),items:state.items.map(function(item){return item.raw&&Object.keys(item.raw).length?item.raw:item}),totals:state.totals,customer:{fullName:document.getElementById("fullName").value.trim(),email:document.getElementById("email").value.trim(),phone:document.getElementById("phone").value.trim()},shippingAddress:{street:document.getElementById("street").value.trim(),apartment:document.getElementById("apartment").value.trim(),city:document.getElementById("city").value.trim(),province:document.getElementById("province").value.trim(),postal:document.getElementById("postal").value.trim(),country:document.getElementById("country").value},delivery:selectedShipping(),payment:selectedPayment(),notes:els.notes?els.notes.value.trim():"",promoCode:state.promoCode,createdAt:new Date().toISOString(),status:"placed"};
}
function csrfToken(){const meta=document.querySelector('meta[name="csrf-token"]');return meta?meta.getAttribute("content"):""}
function numericId(value){const parsed=parseInt(String(value??"").replace(/[^0-9-]/g,""),10);return Number.isFinite(parsed)?parsed:0}
function checkoutPayloadItems(){
  const first=state.items[0]||{};
  const raw=first.raw&&typeof first.raw==="object"?first.raw:{};
  const names=state.items.map(function(item){return item.name}).filter(Boolean).join(", ");
  return [{
    name:names?("Printify Checkout - "+names).slice(0,180):"Printify Checkout Order",
    qty:1,
    unit_price:state.totals.total,
    price:state.totals.total,
    service_code:"checkout-"+Date.now(),
    service_id:numericId(raw.service_id||raw.serviceId),
    variation_id:numericId(raw.variation_id||raw.variationId),
    service_item_id:raw.service_item_id||raw.serviceItemId||"",
    category:"Checkout Total",
    variation_label:selectedShipping().name+" / "+selectedPayment(),
    unit:"order",
    image_path:raw.image_path||raw.image||""
  }];
}
async function syncCheckoutSession(){
  const response=await fetch("{{ route('cart.sync') }}",{
    method:"POST",
    headers:{"Content-Type":"application/json","Accept":"application/json","X-CSRF-TOKEN":csrfToken()},
    body:JSON.stringify({items:checkoutPayloadItems()})
  });
  if(!response.ok){
    const body=await response.text();
    throw new Error(body||"Unable to prepare checkout session.");
  }
}
async function startPaymentCheckout(){
  const response=await fetch("{{ route('payment.start') }}",{
    method:"POST",
    headers:{"Content-Type":"application/json","Accept":"application/json","X-CSRF-TOKEN":csrfToken()},
    body:JSON.stringify({payment_method:selectedPayment()})
  });
  const data=await response.json().catch(function(){return {}});
  if(!response.ok||!data.redirect_url){
    throw new Error(data.message||"Payment provider did not return a checkout link.");
  }
  return data.redirect_url;
}
window.applyPromo=function(){
  const code=document.getElementById("promoCode").value.trim().toUpperCase();
  if(!code){state.promoCode="";renderTotals();return}
  if(!["SAVE10","DISCOUNT10","PRINTIFY50"].includes(code)){showToast("Invalid promo code. Try SAVE10 or PRINTIFY50.","error");return}
  state.promoCode=code;localStorage.setItem("printifyPromoCode",code);renderTotals();showToast("Promo code applied.","success");
};
window.placeOrder=async function(){
  if(!validateForm()){syncCheckoutStep();return}
  updateStep(4);
  calculateTotals();
  const order=buildCompletedOrder();
  const orders=safeJson("printifyOrders","[]");
  orders.push(order);
  saveJson("printifyOrders",orders);
  saveJson("printifyLastPlacedOrder",order);
  const button=document.getElementById("placeOrderBtn");
  if(button){button.disabled=true;button.innerHTML='Processing <i class="fa-solid fa-spinner fa-spin"></i>'}
  try{
    await syncCheckoutSession();
    showToast("Opening secure payment checkout...");
    const redirectUrl=await startPaymentCheckout();
    window.location.href=redirectUrl;
  }catch(error){
    if(button){button.disabled=false;button.innerHTML='Place Order <i class="fa-solid fa-lock"></i>'}
    showToast(error&&error.message?error.message:"Payment checkout failed to start. Please try again.","error");
    console.error("Checkout payment start failed:",error);
  }
};
window.refreshPrintifyCheckout=function(){
  loadItems();
  state.promoCode=(localStorage.getItem("printifyPromoCode")||"").toUpperCase();
  const promo=document.getElementById("promoCode");
  if(state.promoCode&&promo)promo.value=state.promoCode;
  if(els.successBox)els.successBox.classList.remove("show");
  if(els.checkoutGrid)els.checkoutGrid.style.display="";
  updateRadioCards();
  renderAll();
  applyPaymentReturnState();
};
function applyPaymentReturnState(){
  const params=new URLSearchParams(window.location.search);
  const status=(params.get("payment")||"").toLowerCase();
  if(status==="success"){
    const last=safeJson("printifyLastPlacedOrder","{}");
    const ref=params.get("ref")||last.reference||"PAYMENT-SUCCESS";
    if(els.successRef)els.successRef.textContent=ref;
    if(els.successBox)els.successBox.classList.add("show");
    if(els.checkoutGrid)els.checkoutGrid.style.display="none";
    localStorage.removeItem("printifyCheckoutItems");
    localStorage.removeItem("printifyActiveCheckout");
    localStorage.removeItem("printifyCheckoutTotals");
    showToast("Payment successful. Your order is confirmed.","success");
    return;
  }
  if(status==="cancel"){
    showToast("Payment was cancelled. You can choose PayMongo or Maya and try again.","error");
  }
}
document.querySelectorAll('input[name="shipping"], input[name="payment"]').forEach(function(input){input.addEventListener("change",function(){updateRadioCards();renderTotals();syncCheckoutStep()})});
["fullName","email","phone","street","city","province","postal"].forEach(function(id){const node=document.getElementById(id);if(node){node.addEventListener("focus",function(){updateStep(id==="fullName"||id==="email"||id==="phone"?1:2)});node.addEventListener("input",syncCheckoutStep);node.addEventListener("change",syncCheckoutStep);}});
if(els.notes)els.notes.addEventListener("input",function(){els.noteCount.textContent=els.notes.value.length+"/250"});
document.addEventListener("printify:checkout-opened",window.refreshPrintifyCheckout);
function openCheckoutFromHash(){
  if((location.hash||"").toLowerCase()!=="#checkout" && !/\/checkout\/?$/.test(location.pathname.toLowerCase()))return;
  if(typeof window.jumpTo==="function"){
    window.jumpTo("checkout");
  }else{
    const section=document.getElementById("checkout");
    if(section){
      section.classList.add("active");
      section.style.display="block";
    }
  }
  window.refreshPrintifyCheckout();
}
window.addEventListener("hashchange",openCheckoutFromHash);
window.addEventListener("load",function(){setTimeout(openCheckoutFromHash,0)});
window.refreshPrintifyCheckout();
setTimeout(openCheckoutFromHash,0);
})();

</script>
