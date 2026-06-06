<x-app-layout>
@once
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&family=Poppins:wght@500;600;700&display=swap">
@endonce

<style>
:root{
    --sp-orange:#ff7a00;
    --sp-orange-soft:#fff3e6;
    --sp-orange-soft2:#fff8f1;
    --sp-green:#16a34a;
    --sp-green-soft:#eaf8ef;
    --sp-red:#ef4444;
    --sp-red-soft:#fff1f1;
    --sp-blue:#2563eb;
    --sp-purple:#7c3aed;
    --sp-ink:#111827;
    --sp-muted:#6b7280;
    --sp-soft:#f7f9fc;
    --sp-line:#e5e7eb;
    --sp-line-dark:#d8dee8;
    --sp-shadow:0 15px 35px rgba(15,23,42,.07);
    --sp-shadow-soft:0 8px 24px rgba(15,23,42,.045);
    --sp-radius:16px;
}
.security-page{
    min-height:calc(100vh - 70px);
    background:#fff;
    color:var(--sp-ink);
    font-family:'Inter',system-ui,sans-serif;
    padding:0 24px 42px;
}
.security-wrap{max-width:1320px;margin:0 auto;}
.security-top{
    display:flex;align-items:flex-start;justify-content:space-between;gap:18px;
    padding-top:8px;margin-bottom:18px;
}
.security-title-box{display:flex;gap:14px;align-items:flex-start;}
.security-title-box:before{content:'';width:17px;height:4px;border-radius:999px;background:var(--sp-orange);margin-top:18px;flex:none;}
.security-title{margin:0;font-family:'Playfair Display',Georgia,serif;font-size:42px;line-height:1.05;font-weight:700;letter-spacing:-.025em;color:#111827;}
.security-subtitle{margin:8px 0 0;font-size:13px;color:var(--sp-muted);line-height:1.5;}
.security-date{
    display:inline-flex;align-items:center;gap:10px;height:46px;padding:0 16px;
    border:1px solid var(--sp-line);border-radius:12px;background:#fff;box-shadow:var(--sp-shadow-soft);
    font-size:13px;font-weight:700;white-space:nowrap;color:#111827;
}
.security-date i{font-size:16px;color:#111827;}
.security-tabs{
    display:grid;grid-template-columns:repeat(5,1fr);max-width:620px;height:43px;
    border:1px solid var(--sp-line-dark);border-radius:10px;background:#fff;overflow:hidden;margin-bottom:18px;
}
.security-tab{
    border:0;background:#fff;color:#111827;font-size:12px;font-weight:800;cursor:pointer;position:relative;transition:.18s;
}
.security-tab:hover{background:#fff8f1;color:var(--sp-orange);}
.security-tab.active{color:var(--sp-orange);background:#fff;}
.security-tab.active:after{content:'';position:absolute;left:0;right:0;bottom:0;height:3px;background:var(--sp-orange);}
.sp-panel{display:none;animation:spFade .18s ease;}
.sp-panel.active{display:block;}
@keyframes spFade{from{opacity:.4;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}
.sp-layout{display:grid;grid-template-columns:minmax(0,1fr) 330px;gap:18px;align-items:start;}
.sp-main,.sp-side{display:grid;gap:16px;align-content:start;}
.sp-grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;}
.sp-grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;}
.sp-grid-4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;}
.sp-card{
    background:#fff;border:1px solid var(--sp-line-dark);border-radius:var(--sp-radius);
    box-shadow:var(--sp-shadow-soft);overflow:hidden;
}
.sp-card-pad{padding:20px;}
.sp-card-title{margin:0;font-family:'Poppins',system-ui,sans-serif;font-size:18px;font-weight:700;line-height:1.25;color:#111827;}
.sp-card-title.sm{font-size:16px;}
.sp-card-desc{margin:6px 0 0;color:var(--sp-muted);font-size:13px;line-height:1.45;}
.sp-head-row{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;}
.sp-icon{
    width:44px;height:44px;border-radius:14px;display:grid;place-items:center;flex:none;
    background:var(--sp-orange-soft);color:var(--sp-orange);font-size:19px;
}
.sp-icon.green{background:var(--sp-green-soft);color:var(--sp-green);}
.sp-icon.blue{background:#eef4ff;color:var(--sp-blue);}
.sp-icon.purple{background:#f3efff;color:var(--sp-purple);}
.sp-icon.red{background:var(--sp-red-soft);color:var(--sp-red);}
.sp-hero{
    border:1px solid #ffd8b0;border-radius:var(--sp-radius);background:linear-gradient(95deg,#fff7ee 0%,#fff 55%,#eefaf3 100%);
    box-shadow:var(--sp-shadow-soft);padding:22px 26px;display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:center;
}
.sp-hero-left{display:flex;align-items:center;gap:22px;}
.sp-shield-big{width:88px;height:88px;border-radius:50%;background:#fff3e6;border:1px solid #ffd8b0;display:grid;place-items:center;color:var(--sp-orange);font-size:38px;box-shadow:0 10px 25px rgba(255,122,0,.12);}
.sp-hero h2{margin:0;font-family:'Poppins',system-ui,sans-serif;font-size:22px;font-weight:800;}
.sp-chips{display:flex;flex-wrap:wrap;gap:10px;margin-top:14px;}
.sp-chip{height:28px;display:inline-flex;align-items:center;gap:7px;border-radius:999px;background:var(--sp-green-soft);color:var(--sp-green);padding:0 12px;font-size:12px;font-weight:800;}
.sp-score{border-left:1px solid var(--sp-line-dark);padding-left:28px;}
.sp-score strong{font-size:34px;color:var(--sp-green);line-height:1;}
.sp-score span{font-size:20px;color:#111827;font-weight:700;}
.sp-meter{width:100%;height:10px;border-radius:999px;background:#e7edf3;overflow:hidden;margin:12px 0 7px;}
.sp-meter i{display:block;height:100%;width:90%;background:linear-gradient(90deg,#22c55e,#16a34a);border-radius:inherit;}
.sp-link{border:0;background:transparent;color:var(--sp-orange);font-weight:800;font-size:12px;padding:0;display:inline-flex;align-items:center;gap:8px;cursor:pointer;text-decoration:none;}
.sp-btn{
    min-height:38px;border-radius:10px;border:1px solid var(--sp-orange);background:var(--sp-orange);color:#fff;
    display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:0 18px;font-size:12px;font-weight:800;cursor:pointer;text-decoration:none;transition:.18s;
}
.sp-btn:hover{background:#111827;border-color:#111827;color:#fff;}
.sp-btn.outline{background:#fff;color:var(--sp-orange);}
.sp-btn.soft{background:#fff;color:#111827;border-color:var(--sp-line-dark);}
.sp-btn.red{background:var(--sp-red);border-color:var(--sp-red);}
.sp-btn.red-outline{background:#fff;color:var(--sp-red);border-color:#fecaca;}
.sp-btn.block{width:100%;}
.sp-status{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:6px 10px;font-size:11px;font-weight:800;white-space:nowrap;}
.sp-status.green{background:var(--sp-green-soft);color:var(--sp-green);}
.sp-status.gray{background:#f3f4f6;color:#64748b;}
.sp-status.orange{background:#fff3e6;color:var(--sp-orange);}
.sp-status.red{background:#fff1f1;color:var(--sp-red);}
.sp-row{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:13px 0;border-bottom:1px solid #eef1f5;}
.sp-row:last-child{border-bottom:0;}
.sp-row-left{display:flex;align-items:center;gap:12px;min-width:0;}
.sp-row strong,.sp-mini-title{display:block;font-size:13px;font-weight:800;color:#111827;}
.sp-row small,.sp-mini-desc{display:block;margin-top:4px;font-size:11.5px;color:var(--sp-muted);line-height:1.35;}
.sp-table{width:100%;border-collapse:separate;border-spacing:0;margin-top:12px;border:1px solid var(--sp-line);border-radius:12px;overflow:hidden;}
.sp-table th,.sp-table td{padding:12px 14px;text-align:left;border-bottom:1px solid var(--sp-line);font-size:12px;vertical-align:middle;}
.sp-table th{font-size:10px;text-transform:uppercase;letter-spacing:.045em;color:#4b5563;background:#fbfcfe;font-weight:800;}
.sp-table tr:last-child td{border-bottom:0;}
.sp-table td{color:#111827;}
.sp-table small{display:block;color:var(--sp-muted);font-size:10.5px;margin-top:2px;}
.sp-device{display:flex;align-items:center;gap:10px;}
.sp-list{display:grid;gap:10px;margin-top:16px;}
.sp-setting-row{display:flex;align-items:center;justify-content:space-between;gap:14px;border:1px solid var(--sp-line);background:#fff;border-radius:12px;padding:13px 14px;}
.sp-switch{position:relative;display:inline-block;width:44px;height:25px;flex:none;}
.sp-switch input{opacity:0;width:0;height:0;}
.sp-slider{position:absolute;cursor:pointer;inset:0;background:#cbd5e1;border-radius:999px;transition:.18s;}
.sp-slider:before{content:'';position:absolute;width:19px;height:19px;left:3px;top:3px;border-radius:50%;background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.15);transition:.18s;}
.sp-switch input:checked+.sp-slider{background:var(--sp-green);}
.sp-switch input:checked+.sp-slider:before{transform:translateX(19px);}
.sp-field{display:grid;gap:7px;margin-top:14px;}
.sp-field label{font-size:12px;font-weight:800;color:#111827;}
.sp-input-wrap{position:relative;}
.sp-input{height:44px;width:100%;border:1px solid var(--sp-line-dark);border-radius:10px;background:#fff;padding:0 44px 0 13px;font-size:13px;color:#111827;outline:none;}
.sp-input:focus{border-color:var(--sp-orange);box-shadow:0 0 0 4px rgba(255,122,0,.1);}
.sp-eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);border:0;background:transparent;color:#64748b;cursor:pointer;}
.sp-strength{display:flex;align-items:center;gap:7px;margin-top:8px;}
.sp-strength i{height:4px;flex:1;border-radius:999px;background:var(--sp-green);}
.sp-strength i.off{background:#e5e7eb;}
.sp-info-band{border:1px solid var(--sp-line);border-radius:12px;padding:14px;background:#fbfcfe;display:flex;align-items:center;justify-content:space-between;gap:14px;}
.sp-check-list{display:grid;gap:10px;margin-top:14px;}
.sp-check{display:flex;align-items:center;gap:10px;font-size:12px;color:#111827;}
.sp-check i{color:var(--sp-green);}
.sp-danger{border-color:#fecaca;background:linear-gradient(180deg,#fff7f7,#fff);}
.sp-danger .sp-card-title{color:#991b1b;}
.sp-danger-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:14px;}
.sp-danger-box{border:1px solid #fee2e2;border-radius:14px;padding:16px;background:#fff;display:flex;align-items:center;justify-content:space-between;gap:14px;}
.sp-toast{position:fixed;left:50%;top:95px;z-index:9999;transform:translate(-50%,-12px);opacity:0;pointer-events:none;background:#111827;color:#fff;padding:13px 16px;border-radius:12px;font-weight:800;font-size:12px;box-shadow:0 18px 50px rgba(17,24,39,.25);transition:.2s;}
.sp-toast.show{opacity:1;transform:translate(-50%,0);}
@media(max-width:1180px){.sp-layout{grid-template-columns:1fr}.sp-side{grid-template-columns:repeat(2,minmax(0,1fr));}.sp-grid-3,.sp-grid-4{grid-template-columns:repeat(2,minmax(0,1fr));}.sp-hero{grid-template-columns:1fr}.sp-score{border-left:0;border-top:1px solid var(--sp-line-dark);padding:18px 0 0;}}
@media(max-width:760px){.security-page{padding:0 12px 30px}.security-top{display:grid}.security-tabs{max-width:none;overflow:auto}.sp-grid-2,.sp-grid-3,.sp-grid-4,.sp-side,.sp-danger-row{grid-template-columns:1fr}.sp-table{display:block;overflow-x:auto}.sp-hero-left{align-items:flex-start}.security-title{font-size:34px}.sp-danger-box{display:grid}.sp-btn{width:100%;}.security-date{width:max-content}.sp-card-pad{padding:16px}}

.security-page{padding:0 0 42px;background:#fff;font-family:'Inter',system-ui,sans-serif;letter-spacing:0}
.security-wrap{max-width:none;margin:0}
.security-top{padding-top:0;margin-bottom:18px}
.security-title{font-family:'Playfair Display',Georgia,serif}
.sp-card-title,.security-tab,.sp-row strong,.sp-mini-title{font-family:'Poppins',system-ui,sans-serif;font-weight:600;letter-spacing:0}
.sp-card-desc,.sp-row small,.sp-mini-desc,.security-subtitle{font-family:'Inter',system-ui,sans-serif;font-weight:400;letter-spacing:0}
.sp-card,.sp-hero,.sp-info-band,.sp-setting-row,.sp-danger-box,.sp-table,.security-tabs,.security-date{border-color:#111827}
.sp-card,.sp-hero,.sp-setting-row,.sp-danger-box,.sp-info-band{transition:background .18s,border-color .18s,box-shadow .18s,transform .18s}
.sp-card:hover,.sp-hero:hover,.sp-setting-row:hover,.sp-danger-box:hover,.sp-info-band:hover{background:#fff8f1;border-color:#111827;box-shadow:0 16px 34px rgba(17,24,39,.08)}
.sp-clicked{background:#fff3e6!important;border-color:#111827!important}
.sp-card-pad{padding:18px}
.sp-main,.sp-side{gap:14px}.sp-grid-2,.sp-grid-3,.sp-grid-4{gap:14px}.sp-layout{gap:18px}
.sp-btn,.sp-btn.outline,.sp-btn.soft,.sp-btn.red,.sp-btn.red-outline{min-width:138px;height:42px;min-height:42px;border-radius:10px;border:1px solid var(--sp-orange);background:var(--sp-orange);color:#111827;font-family:'Poppins',system-ui,sans-serif;font-size:12px;font-weight:600;padding:0 18px}
.sp-btn:hover,.sp-btn.outline:hover,.sp-btn.soft:hover,.sp-btn.red:hover,.sp-btn.red-outline:hover{background:#111827;border-color:#111827;color:#fff}
.sp-btn.block{width:100%}
.sp-link{min-height:34px;border-radius:9px;padding:0 10px;color:#111827;font-family:'Poppins',system-ui,sans-serif;font-weight:600;transition:background .18s,color .18s}
.sp-link:hover{background:#111827;color:#fff}
.security-tab:hover{background:#fff8f1;color:var(--sp-orange)}
.security-tab.active{color:var(--sp-orange)}
.sp-switch input:checked+.sp-slider{background:var(--sp-green)}
.sp-toast{left:50%;top:92px;transform:translate(-50%,-12px);background:#111827;color:#fff;border:1px solid rgba(255,255,255,.08)}
.sp-toast.show{transform:translate(-50%,0)}
.security-tabs{
    display:flex;
    align-items:center;
    gap:42px;
    max-width:620px;
    width:100%;
    height:auto;
    margin:0 0 18px;
    border:0;
    border-bottom:1px solid #dfe3ea;
    border-radius:0;
    background:transparent;
    overflow-x:auto;
    overflow-y:hidden;
    scrollbar-width:none;
}
.security-tabs::-webkit-scrollbar{display:none}
.security-tab{
    flex:0 0 auto;
    min-width:0;
    height:43px;
    padding:0 0 12px;
    border:0;
    border-radius:0;
    background:transparent;
    color:#111827;
    font-family:'Poppins',system-ui,sans-serif;
    font-size:10.5px;
    font-weight:700;
    letter-spacing:.04em;
    text-transform:uppercase;
}
.security-tab:hover{
    background:transparent;
    color:var(--sp-orange);
}
.security-tab.active{
    background:transparent;
    color:var(--sp-orange);
}
.security-tab.active:after{
    left:0;
    right:0;
    bottom:-1px;
    height:3px;
    border-radius:999px;
    background:var(--sp-orange);
}
.security-page{padding-bottom:32px}
.security-top{align-items:center;margin-bottom:12px}
.security-title-box{gap:12px}
.security-title-box:before{width:16px;height:4px;margin-top:14px}
.security-title{font-size:34px;line-height:1.08;letter-spacing:-.01em}
.security-subtitle{margin-top:5px;font-size:11px;line-height:1.35}
.security-date{
    height:34px;
    padding:0 12px;
    border-radius:10px;
    border-color:#dfe3ea;
    font-size:11px;
    font-weight:700;
    box-shadow:0 8px 18px rgba(15,23,42,.045);
}
.security-date i{font-size:14px}
.security-tabs{gap:26px;max-width:560px;margin-bottom:14px}
.security-tab{height:38px;padding-bottom:10px}
.sp-layout{grid-template-columns:minmax(0,.95fr) 300px;gap:14px}
.sp-main,.sp-side{gap:11px}
.sp-grid-2,.sp-grid-3,.sp-grid-4{gap:10px}
.sp-card{border-radius:12px}
.sp-card-pad{padding:12px!important}
.sp-card-title{font-size:15px;line-height:1.2}
.sp-card-title.sm{font-size:13px}
.sp-card-desc{font-size:10.5px;line-height:1.32;margin-top:4px}
.sp-head-row{gap:10px}
.sp-icon{width:32px;height:32px;border-radius:10px;font-size:14px}
.sp-shield-big{width:58px;height:58px;font-size:24px}
.sp-hero{padding:14px 16px;grid-template-columns:minmax(0,1fr) 210px;gap:14px;border-radius:12px}
.sp-hero-left{gap:14px}
.sp-hero h2{font-size:17px}
.sp-chips{gap:7px;margin-top:10px}
.sp-chip{height:23px;padding:0 9px;font-size:10px}
.sp-score{padding-left:16px}
.sp-score strong{font-size:25px}
.sp-score span{font-size:15px}
.sp-meter{height:7px;margin:8px 0 5px}
.sp-row{gap:10px;padding:8px 0}
.sp-row-left{gap:9px}
.sp-row strong,.sp-mini-title{font-size:11px}
.sp-row small,.sp-mini-desc{font-size:9.7px;margin-top:2px;line-height:1.25}
.sp-list{gap:6px;margin-top:10px}
.sp-setting-row{gap:10px;border-radius:10px;padding:9px 10px}
.sp-info-band{border-radius:10px;padding:10px;gap:10px}
.sp-danger-row{gap:10px;margin-top:10px}
.sp-danger-box{border-radius:11px;padding:10px;gap:10px}
.sp-table{margin-top:9px;border-radius:10px}
.sp-table th,.sp-table td{padding:8px 10px;font-size:10.5px}
.sp-table th{font-size:8.8px}
.sp-table small{font-size:9px}
.sp-device{gap:8px}
.sp-btn,.sp-btn.outline,.sp-btn.soft,.sp-btn.red,.sp-btn.red-outline{
    min-width:112px;
    height:34px;
    min-height:34px;
    border-radius:9px;
    padding:0 12px;
    font-size:10.5px;
}
.sp-link{min-height:28px;font-size:10px;padding:0 8px}
.sp-status{padding:5px 8px;font-size:9.5px}
.sp-switch{width:36px;height:20px}
.sp-slider:before{width:15px;height:15px;left:3px;top:2.5px}
.sp-switch input:checked+.sp-slider:before{transform:translateX(15px)}
.sp-field{gap:5px;margin-top:10px}
.sp-field label{font-size:10.5px}
.sp-input{height:36px;border-radius:9px;font-size:11px;padding-left:10px}
.sp-strength{gap:5px;margin-top:6px}
.sp-check-list{gap:7px;margin-top:10px}
.sp-check{gap:7px;font-size:10.5px}
.sp-side .sp-card-pad{padding:11px!important}
.sp-side .sp-list{gap:5px;margin-top:9px}
.sp-side .sp-row{padding:7px 0}
.sp-side h3[style*="margin:0"]{font-size:14px}
.sp-side [style*="width:86px"]{
    width:62px!important;
    height:62px!important;
    border-width:7px!important;
    font-size:17px!important;
}
.security-wrap{max-width:1240px;margin:0 auto}
.security-top{padding-top:0;margin-bottom:10px}
.security-date{
    height:32px;
    padding:0 11px;
    gap:7px;
    border:1px solid var(--sp-line-dark);
    border-radius:10px;
    background:#fff;
    color:#111827;
    font-family:'Inter',system-ui,sans-serif;
    font-size:10.5px;
    font-weight:800;
}
.security-date [data-lucide]{width:14px;height:14px;stroke-width:2.2;color:#111827}
.security-tabs{
    gap:24px;
    max-width:520px;
    margin-bottom:12px;
    border-bottom-color:#dfe3ea;
}
.security-tab{
    height:34px;
    padding:0 0 9px;
    font-size:10px;
    font-weight:800;
    letter-spacing:.055em;
    text-transform:uppercase;
}
.sp-layout{grid-template-columns:minmax(0,.88fr) 280px;gap:12px}
.sp-main,.sp-side{gap:9px}
.sp-grid-2,.sp-grid-3,.sp-grid-4{gap:9px}
.sp-card,.sp-hero,.sp-info-band,.sp-setting-row,.sp-danger-box,.sp-table{border-radius:10px}
.sp-card-pad{padding:10px!important}
.sp-side .sp-card-pad{padding:9px!important}
.sp-card-title{font-size:14px;line-height:1.16}
.sp-card-title.sm{font-size:12px}
.sp-card-desc{font-size:9.7px;line-height:1.26;margin-top:3px}
.sp-head-row{gap:8px}
.sp-icon{
    width:28px;
    height:28px;
    border-radius:9px;
    font-size:12px;
}
.sp-shield-big{
    width:50px;
    height:50px;
    border-width:1px;
    font-size:21px;
    box-shadow:0 7px 16px rgba(255,122,0,.10);
}
.sp-hero{
    padding:11px 13px;
    grid-template-columns:minmax(0,1fr) 190px;
    gap:11px;
}
.sp-hero-left{gap:11px}
.sp-hero h2{font-size:15px}
.sp-chips{gap:5px;margin-top:7px}
.sp-chip{height:21px;padding:0 7px;font-size:9px}
.sp-score{padding-left:12px}
.sp-score strong{font-size:22px}
.sp-score span{font-size:12px}
.sp-meter{height:5px;margin:6px 0 4px}
.sp-row{gap:8px;padding:6px 0}
.sp-row-left{gap:7px}
.sp-row strong,.sp-mini-title{font-size:10px}
.sp-row small,.sp-mini-desc{font-size:8.8px;margin-top:1px;line-height:1.22}
.sp-list{gap:4px;margin-top:7px}
.sp-setting-row{gap:8px;padding:7px 8px}
.sp-info-band{padding:8px;gap:8px}
.sp-danger-row{gap:8px;margin-top:8px}
.sp-danger-box{padding:8px;gap:8px}
.sp-table{margin-top:7px}
.sp-table th,.sp-table td{padding:6px 8px;font-size:9.5px}
.sp-table th{font-size:8px;letter-spacing:.04em}
.sp-table small{font-size:8.3px}
.sp-device{gap:6px}
.sp-btn,.sp-btn.outline,.sp-btn.soft,.sp-btn.red,.sp-btn.red-outline{
    min-width:96px;
    height:30px;
    min-height:30px;
    border-radius:8px;
    padding:0 10px;
    font-size:9.5px;
}
.sp-link{min-height:24px;font-size:9px;padding:0 6px}
.sp-status{padding:4px 7px;font-size:8.8px}
.sp-switch{width:34px;height:18px}
.sp-slider:before{width:13px;height:13px;left:3px;top:2.5px}
.sp-switch input:checked+.sp-slider:before{transform:translateX(15px)}
.sp-field{gap:4px;margin-top:8px}
.sp-field label{font-size:9.5px}
.sp-input{height:32px;border-radius:8px;font-size:10px;padding-left:9px}
.sp-strength{gap:4px;margin-top:5px}
.sp-strength i{height:3px}
.sp-check-list{gap:5px;margin-top:8px}
.sp-check{gap:6px;font-size:9.5px}
.sp-side .sp-list{gap:4px;margin-top:7px}
.sp-side .sp-row{padding:5px 0}
.sp-side h3[style*="margin:0"]{font-size:12px}
.sp-side [style*="width:86px"],
.sp-side [style*="width:94px"],
[style*="width:86px"],
[style*="width:94px"]{
    width:52px!important;
    height:52px!important;
    border-width:6px!important;
    font-size:15px!important;
}
[style*="width:220px"]{width:170px!important}
[style*="margin-top:18px"]{margin-top:10px!important}
[style*="margin-top:16px"]{margin-top:9px!important}
[style*="margin-top:14px"]{margin-top:8px!important}
[style*="margin:16px 0 18px"]{margin:9px 0 10px!important}
[style*="font-size:32px"]{font-size:22px!important}
[style*="font-size:12px"]{font-size:9.5px!important}
@media(max-width:760px){.security-page{padding:0 0 30px}.sp-card-pad{padding:16px}.sp-btn{width:100%}}

/* Final Security & Privacy dashboard-aligned sizing */
.security-page{
    padding:0 0 70px!important;
    background:#fff!important;
    font-family:'Inter',system-ui,sans-serif!important;
    letter-spacing:0!important;
}
.security-wrap{
    max-width:none!important;
    margin:0!important;
}
.security-top{
    padding-top:0!important;
    margin-bottom:18px!important;
    gap:18px!important;
}
.security-title-box{gap:14px!important}
.security-title-box:before{
    width:18px!important;
    height:4px!important;
    margin-top:17px!important;
}
.security-title{
    font-family:'Playfair Display',Georgia,serif!important;
    font-size:42px!important;
    line-height:1.06!important;
    font-weight:700!important;
    letter-spacing:0!important;
}
.security-subtitle{
    margin-top:7px!important;
    font-family:'Inter',system-ui,sans-serif!important;
    font-size:13px!important;
    line-height:1.45!important;
    font-weight:400!important;
}
.security-date{
    height:42px!important;
    padding:0 16px!important;
    border:1px solid #111827!important;
    border-radius:10px!important;
    font-size:12px!important;
    box-shadow:none!important;
}
.security-tabs{
    max-width:620px!important;
    height:42px!important;
    gap:30px!important;
    margin-bottom:20px!important;
}
.security-tab{
    height:42px!important;
    padding:0 0 10px!important;
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:11px!important;
    font-weight:600!important;
    letter-spacing:0!important;
}
.sp-layout{
    grid-template-columns:minmax(0,1fr) 330px!important;
    gap:24px!important;
}
.sp-main,.sp-side{gap:18px!important}
.sp-grid-2,.sp-grid-3,.sp-grid-4{gap:18px!important}
.sp-card,.sp-hero,.sp-info-band,.sp-setting-row,.sp-danger-box,.sp-table{
    border:1px solid #111827!important;
    border-radius:12px!important;
    box-shadow:none!important;
}
.sp-card:hover,.sp-hero:hover,.sp-info-band:hover,.sp-setting-row:hover,.sp-danger-box:hover{
    background:rgba(17,24,39,.10)!important;
    border-color:#111827!important;
    box-shadow:none!important;
    transform:none!important;
}
.sp-card-pad{padding:18px!important}
.sp-side .sp-card-pad{padding:16px!important}
.sp-card-title{
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:17px!important;
    line-height:1.22!important;
    font-weight:600!important;
    letter-spacing:0!important;
}
.sp-card-title.sm{font-size:15px!important}
.sp-card-desc,.sp-row small,.sp-mini-desc,.sp-muted{
    font-family:'Inter',system-ui,sans-serif!important;
    font-size:12px!important;
    line-height:1.45!important;
    font-weight:400!important;
    letter-spacing:0!important;
}
.sp-row strong,.sp-mini-title{
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:12px!important;
    font-weight:600!important;
    letter-spacing:0!important;
}
.sp-row{padding:10px 0!important}
.sp-list{gap:8px!important}
.sp-icon{
    width:36px!important;
    height:36px!important;
    border-radius:10px!important;
    font-size:15px!important;
    background:transparent!important;
    box-shadow:none!important;
}
.sp-shield-big{
    width:62px!important;
    height:62px!important;
    font-size:25px!important;
}
.sp-hero{
    padding:18px!important;
    grid-template-columns:minmax(0,1fr) 230px!important;
    gap:18px!important;
}
.sp-btn,.sp-link{
    height:38px!important;
    min-width:116px!important;
    border:0!important;
    border-radius:10px!important;
    background:#ff7a00!important;
    color:#111827!important;
    box-shadow:none!important;
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:11px!important;
    font-weight:600!important;
    letter-spacing:0!important;
}
.sp-btn:hover,.sp-link:hover{
    background:#111827!important;
    color:#fff!important;
    box-shadow:none!important;
    transform:none!important;
}
.sp-status.green,.sp-check,.sp-check i{color:#16a34a!important}
.security-page [style*="font-size:32px"]{font-size:28px!important}
.security-page [style*="font-size:12px"]{font-size:12px!important}
.security-page [style*="width:86px"],
.security-page [style*="width:94px"]{
    width:78px!important;
    height:78px!important;
    border-width:8px!important;
    font-size:20px!important;
}
.security-page [style*="width:220px"]{width:210px!important}
.security-page [style*="margin-top:18px"]{margin-top:16px!important}
.security-page [style*="margin-top:16px"]{margin-top:14px!important}
.security-page [style*="margin-top:14px"]{margin-top:12px!important}
.security-page [style*="margin:16px 0 18px"]{margin:14px 0 16px!important}
@media(max-width:760px){
    .security-page{padding:0 0 34px!important}
    .security-title{font-size:34px!important}
    .security-top{display:grid!important}
    .sp-layout,.sp-grid-2,.sp-grid-3,.sp-grid-4{grid-template-columns:1fr!important}
    .sp-card-pad{padding:16px!important}
}

/* Final readability pass for Security & Privacy */
.security-title{font-size:44px!important}
.security-subtitle{font-size:13.5px!important}
.security-tab{font-size:12px!important}
.sp-layout{gap:28px!important}
.sp-main,.sp-side{gap:20px!important}
.sp-grid-2,.sp-grid-3,.sp-grid-4{gap:20px!important}
.sp-card-pad{padding:20px!important}
.sp-side .sp-card-pad{padding:18px!important}
.sp-card-title{font-size:18px!important}
.sp-card-title.sm{font-size:16px!important}
.sp-card-desc,.sp-row small,.sp-mini-desc,.sp-muted{
    font-size:13px!important;
    line-height:1.5!important;
}
.sp-row strong,.sp-mini-title{
    font-size:13px!important;
}
.sp-row{padding:11px 0!important}
.sp-check{font-size:12px!important}
.sp-btn,.sp-link{
    height:40px!important;
    min-width:124px!important;
}
.security-page [style*="font-size:12px"]{font-size:12.5px!important}
@media(max-width:760px){
    .security-title{font-size:35px!important}
    .sp-card-title{font-size:17px!important}
    .sp-card-title.sm{font-size:15px!important}
}


/* =========================================================
   SCREENSHOT MATCH + PANTAY ENDPOINT LOCK
   - Same left/right endpoint for every card group
   - Mixed white/orange buttons with one black hover color
   - Black borders only, cleaner flat dashboard look
   - Right sidebar and main column align from top to bottom
   ========================================================= */
.security-page{
    --sp-border:#111827;
    --sp-gap:28px;
    padding:0 28px 64px!important;
    background:#fff!important;
}
.security-wrap{
    width:100%!important;
    max-width:1540px!important;
    margin:0 auto!important;
}
.security-top{
    display:flex!important;
    align-items:flex-start!important;
    justify-content:space-between!important;
    gap:24px!important;
    padding-top:0!important;
    margin-bottom:18px!important;
}
.security-title-box{
    display:flex!important;
    align-items:flex-start!important;
    gap:16px!important;
}
.security-title-box:before{
    content:'\f3ed'!important;
    font-family:'Font Awesome 6 Free'!important;
    font-weight:900!important;
    width:42px!important;
    height:42px!important;
    margin-top:2px!important;
    border-radius:12px!important;
    background:transparent!important;
    color:var(--sp-orange)!important;
    display:grid!important;
    place-items:center!important;
    font-size:34px!important;
    line-height:1!important;
}
.security-title{
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:42px!important;
    font-weight:800!important;
    line-height:1.05!important;
    letter-spacing:-.035em!important;
    color:#050505!important;
}
.security-subtitle{
    margin-top:7px!important;
    font-size:13px!important;
    color:#111827!important;
}
.security-date{
    height:52px!important;
    min-width:218px!important;
    justify-content:center!important;
    border:1px solid var(--sp-border)!important;
    border-radius:8px!important;
    box-shadow:none!important;
    font-size:13px!important;
    background:#fff!important;
}
.security-tabs{
    width:620px!important;
    max-width:100%!important;
    height:45px!important;
    display:flex!important;
    align-items:flex-end!important;
    gap:42px!important;
    margin:0 0 20px 58px!important;
    border:0!important;
    border-bottom:0!important;
    background:transparent!important;
    overflow:visible!important;
}
.security-tab{
    height:45px!important;
    padding:0 0 12px!important;
    border:0!important;
    background:transparent!important;
    color:#050505!important;
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:12px!important;
    font-weight:800!important;
    letter-spacing:0!important;
    text-transform:uppercase!important;
}
.security-tab:hover,.security-tab.active{color:var(--sp-orange)!important;background:transparent!important;}
.security-tab.active:after{
    left:0!important;
    right:0!important;
    bottom:2px!important;
    height:3px!important;
    border-radius:999px!important;
    background:var(--sp-orange)!important;
}
.sp-layout{
    display:grid!important;
    grid-template-columns:minmax(0,1fr) clamp(340px,24vw,420px)!important;
    gap:var(--sp-gap)!important;
    align-items:start!important;
}
.sp-main,.sp-side{
    display:grid!important;
    gap:18px!important;
    align-content:start!important;
    min-width:0!important;
}
.sp-grid-2,.sp-grid-3,.sp-grid-4{
    display:grid!important;
    gap:18px!important;
    align-items:stretch!important;
}
.sp-grid-2{grid-template-columns:repeat(2,minmax(0,1fr))!important;}
.sp-grid-3{grid-template-columns:repeat(3,minmax(0,1fr))!important;}
.sp-grid-4{grid-template-columns:repeat(4,minmax(0,1fr))!important;}
.sp-card,.sp-hero,.sp-info-band,.sp-setting-row,.sp-danger-box,.sp-table{
    border:1px solid var(--sp-border)!important;
    border-radius:10px!important;
    box-shadow:none!important;
    background:#fff!important;
}
.sp-card{
    width:100%!important;
    height:100%!important;
    overflow:hidden!important;
}
.sp-card-pad{
    padding:18px 20px!important;
    height:100%!important;
}
.sp-grid-2>.sp-card .sp-card-pad,
.sp-grid-3>.sp-card .sp-card-pad,
.sp-grid-4>.sp-card .sp-card-pad{
    display:flex!important;
    flex-direction:column!important;
}
.sp-grid-2>.sp-card .sp-card-pad>.sp-btn.block:last-child,
.sp-grid-3>.sp-card .sp-card-pad>.sp-btn.block:last-child,
.sp-grid-4>.sp-card .sp-card-pad>.sp-btn.block:last-child{
    margin-top:auto!important;
}
.sp-card:hover,.sp-hero:hover,.sp-info-band:hover,.sp-setting-row:hover,.sp-danger-box:hover{
    background:#fff!important;
    border-color:var(--sp-border)!important;
    box-shadow:none!important;
    transform:none!important;
}
.sp-card-title{
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:18px!important;
    font-weight:800!important;
    line-height:1.22!important;
    color:#050505!important;
}
.sp-card-title.sm{font-size:16px!important;}
.sp-card-desc,.sp-row small,.sp-mini-desc{
    font-size:12.5px!important;
    line-height:1.42!important;
    color:#111827!important;
}
.sp-row strong,.sp-mini-title{font-size:13px!important;font-weight:800!important;color:#050505!important;}
.sp-row{padding:12px 0!important;border-bottom:1px solid #d9dee8!important;}
.sp-row:last-child{border-bottom:0!important;}
.sp-hero{
    padding:18px 24px!important;
    grid-template-columns:minmax(0,1fr) 285px!important;
    gap:24px!important;
}
.sp-hero-left{gap:22px!important;}
.sp-shield-big{
    width:84px!important;
    height:84px!important;
    border:1px solid #ffd6ad!important;
    background:#fff8f1!important;
    color:var(--sp-orange)!important;
    font-size:36px!important;
}
.sp-score{border-left:1px solid #d9dee8!important;padding-left:28px!important;}
.sp-score strong{font-size:30px!important;color:var(--sp-green)!important;}
.sp-meter{height:8px!important;}
.sp-icon{
    width:38px!important;
    height:38px!important;
    border-radius:8px!important;
    background:transparent!important;
    color:var(--sp-orange)!important;
    font-size:20px!important;
}
.sp-icon.green{color:var(--sp-green)!important;background:transparent!important;}
.sp-icon.blue{color:var(--sp-blue)!important;background:transparent!important;}
.sp-icon.purple{color:var(--sp-purple)!important;background:transparent!important;}
.sp-icon.red{color:var(--sp-red)!important;background:transparent!important;}
.sp-head-row{align-items:center!important;}
.sp-list{gap:0!important;margin-top:14px!important;}
.sp-info-band{padding:14px 16px!important;}
.sp-setting-row{padding:14px 16px!important;}
.sp-table{
    width:100%!important;
    margin-top:14px!important;
    border-collapse:separate!important;
    border-spacing:0!important;
}
.sp-table th,.sp-table td{
    padding:12px 14px!important;
    font-size:12px!important;
    border-bottom:1px solid #d9dee8!important;
}
.sp-table th{font-size:10px!important;background:#fff!important;color:#374151!important;}
.sp-table tr:last-child td{border-bottom:0!important;}
.sp-btn,.sp-btn.red{
    min-width:132px!important;
    height:38px!important;
    min-height:38px!important;
    border:1px solid var(--sp-orange)!important;
    border-radius:7px!important;
    background:var(--sp-orange)!important;
    color:#050505!important;
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:12px!important;
    font-weight:800!important;
    padding:0 16px!important;
    box-shadow:none!important;
}
.sp-btn.outline,.sp-btn.soft,.sp-btn.red-outline{
    background:#fff!important;
    color:#050505!important;
    border-color:var(--sp-border)!important;
}
.sp-btn.block{width:100%!important;min-width:0!important;}
.sp-btn:hover,.sp-btn.outline:hover,.sp-btn.soft:hover,.sp-btn.red:hover,.sp-btn.red-outline:hover,
.sp-link:hover{
    background:#111827!important;
    border-color:#111827!important;
    color:#fff!important;
}
.sp-link{
    min-height:38px!important;
    border:1px solid var(--sp-border)!important;
    border-radius:7px!important;
    padding:0 14px!important;
    background:#fff!important;
    color:#050505!important;
    font-family:'Poppins',system-ui,sans-serif!important;
    font-size:12px!important;
    font-weight:800!important;
    text-decoration:none!important;
}
.sp-status{font-size:11px!important;font-weight:800!important;}
.sp-status.green{background:var(--sp-green-soft)!important;color:var(--sp-green)!important;}
.sp-status.gray{background:#f3f4f6!important;color:#64748b!important;}
.sp-check,.sp-check i{font-size:12px!important;color:var(--sp-green)!important;}
.sp-side .sp-card-pad{padding:18px 20px!important;}
.sp-side .sp-card{height:auto!important;}
.sp-side [style*="width:86px"],.sp-side [style*="width:94px"],
.security-page [style*="width:86px"],.security-page [style*="width:94px"]{
    width:78px!important;
    height:78px!important;
    border-width:8px!important;
    font-size:22px!important;
}
.sp-danger{border-color:#ef4444!important;background:#fffafa!important;}
.sp-danger-box{border-color:#ef4444!important;}
.sp-danger .sp-card-title{color:#b91c1c!important;}
.sp-field label{font-size:12px!important;font-weight:800!important;}
.sp-input{height:40px!important;border:1px solid #111827!important;border-radius:7px!important;}
.sp-switch{width:42px!important;height:24px!important;}
.sp-switch input:checked+.sp-slider{background:var(--sp-green)!important;}
.sp-slider:before{width:18px!important;height:18px!important;left:3px!important;top:3px!important;}
.sp-switch input:checked+.sp-slider:before{transform:translateX(18px)!important;}
@media(max-width:1180px){
    .security-page{padding:0 18px 44px!important;}
    .security-tabs{margin-left:0!important;}
    .sp-layout{grid-template-columns:1fr!important;}
    .sp-side{grid-template-columns:repeat(2,minmax(0,1fr))!important;}
}
@media(max-width:760px){
    .security-top{display:grid!important;}
    .security-title-box:before{width:34px!important;height:34px!important;font-size:28px!important;}
    .security-title{font-size:32px!important;}
    .security-tabs{gap:24px!important;overflow-x:auto!important;}
    .sp-grid-2,.sp-grid-3,.sp-grid-4,.sp-side{grid-template-columns:1fr!important;}
    .sp-hero{grid-template-columns:1fr!important;}
    .sp-score{border-left:0!important;border-top:1px solid #d9dee8!important;padding:16px 0 0!important;}
}

/* Balanced security section: dashboard spacing, less heavy boxes */
.security-top{margin-bottom:14px!important}
.security-tabs{margin-bottom:16px!important}
.sp-layout{
    grid-template-columns:minmax(0,1fr) 320px!important;
    gap:20px!important;
}
.sp-main,.sp-side{gap:16px!important}
.sp-grid-2,.sp-grid-3,.sp-grid-4{gap:16px!important}
.sp-card-pad{padding:16px!important}
.sp-side .sp-card-pad{padding:14px!important}
.sp-hero{padding:16px!important;gap:16px!important}
.sp-card-title{font-size:17px!important}
.sp-card-title.sm{font-size:15px!important}
.sp-card-desc,.sp-row small,.sp-mini-desc,.sp-muted{font-size:12.5px!important}
.sp-row strong,.sp-mini-title{font-size:12.5px!important}
.sp-row{padding:9px 0!important}
.sp-list{gap:7px!important}
.sp-icon{background:transparent!important}
.sp-icon.red,.security-page .fa-shield-halved,.security-page .fa-shield,.security-page .fa-lock,.security-page .fa-key{color:#dc2626!important}
.security-page .fa-envelope,.security-page .fa-at,.security-page .fa-id-card{color:#111827!important}
.security-page .fa-mobile-screen,.security-page .fa-download{color:#2563eb!important}
.security-page .fa-location-dot{color:#16a34a!important}
.security-page .fa-circle-check,.security-page .sp-status.green{color:#16a34a!important}
.sp-btn.soft,.sp-link{
    background:#fff!important;
    color:#111827!important;
    border:0!important;
    box-shadow:none!important;
}
.sp-btn.soft:hover,.sp-link:hover{
    background:#111827!important;
    color:#fff!important;
}
/* Match Settings overview scale exactly */
.security-page{
    padding:0 0 34px!important;
}
.security-wrap{
    width:100%!important;
    max-width:none!important;
}
.security-top{
    margin:0 0 12px!important;
    align-items:flex-start!important;
}
.security-title-box:before{
    width:17px!important;
    height:4px!important;
    margin-top:18px!important;
}
.security-title{
    font-size:40px!important;
    line-height:1.2!important;
    letter-spacing:0!important;
}
.security-subtitle{
    margin:6px 0 0!important;
    font-size:12px!important;
    line-height:1.4!important;
}
.security-tabs{
    display:flex!important;
    gap:26px!important;
    height:auto!important;
    max-width:none!important;
    border:0!important;
    border-bottom:1px solid #dfe3ea!important;
    border-radius:0!important;
    margin:0 0 16px!important;
    overflow-x:auto!important;
}
.security-tab{
    height:auto!important;
    padding:11px 0 12px!important;
    background:transparent!important;
    font-size:10.5px!important;
    font-weight:700!important;
    letter-spacing:0!important;
}
.security-date{
    height:36px!important;
    padding:0 13px!important;
    font-size:11px!important;
    border-radius:10px!important;
}
.sp-layout{
    grid-template-columns:minmax(0,1fr) 320px!important;
    gap:18px!important;
}
.sp-main,.sp-side{gap:16px!important}
.sp-grid-2,.sp-grid-3,.sp-grid-4{gap:16px!important}
.sp-card,.sp-hero,.sp-info-band,.sp-setting-row,.sp-danger-box,.sp-table{
    border-radius:14px!important;
}
.sp-card-pad{
    padding:15px 17px!important;
}
.sp-side .sp-card-pad{
    padding:15px 17px!important;
}
.sp-hero{
    padding:15px 17px!important;
    gap:14px!important;
    grid-template-columns:minmax(0,1fr) 220px!important;
}
.sp-card-title{
    font-size:14.5px!important;
    line-height:1.35!important;
}
.sp-card-title.sm{
    font-size:13px!important;
}
.sp-card-desc,.sp-row small,.sp-mini-desc,.sp-muted,.security-page p{
    font-size:10.5px!important;
    line-height:1.42!important;
}
.sp-row strong,.sp-mini-title{
    font-size:11px!important;
}
.sp-row{
    padding:7px 0!important;
}
.sp-list{
    gap:7px!important;
}
.sp-setting-row{
    gap:10px!important;
    padding:9px 10px!important;
    border-radius:10px!important;
}
.sp-icon{
    width:28px!important;
    height:28px!important;
    border-radius:9px!important;
    font-size:12.5px!important;
}
.sp-shield-big{
    width:50px!important;
    height:50px!important;
    font-size:21px!important;
}
.sp-btn,.sp-link{
    height:38px!important;
    min-width:128px!important;
    font-size:11px!important;
}
.security-page [style*="width:86px"],
.security-page [style*="width:94px"]{
    width:62px!important;
    height:62px!important;
    border-width:7px!important;
    font-size:17px!important;
}
.security-page [style*="font-size:32px"]{font-size:22px!important}
.security-page [style*="font-size:12px"]{font-size:10.5px!important}
.security-wrap{max-width:1490px!important;margin:0 auto!important}
.security-top{display:flex!important;align-items:flex-start!important;justify-content:space-between!important;gap:18px!important;margin:0 0 16px!important}
.security-title-box{gap:10px!important;align-items:flex-start!important}.security-title-box:before{width:18px!important;height:4px!important;margin-top:8px!important;border-radius:999px!important;background:var(--sp-orange)!important;flex:0 0 auto!important}
.security-title{font-size:40px!important;line-height:1.2!important;letter-spacing:-.02em!important}
.security-subtitle{margin:4px 0 0!important;font-size:12px!important;line-height:1.45!important;color:#6b7280!important}
.security-date{height:42px!important;min-width:178px!important;padding:0 15px!important;border:1px solid #111827!important;border-radius:8px!important;background:#fff!important;color:#111827!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;gap:8px!important;font-size:12px!important;font-weight:700!important;line-height:1!important;white-space:nowrap!important;box-shadow:none!important}
.security-date i,.security-date [data-lucide]{width:16px!important;height:16px!important;color:#111827!important}
.sp-layout,.sp-settings-grid,.sp-grid-2,.sp-grid-3,.sp-grid-4{align-items:stretch!important}
@media(max-width:760px){.security-top{display:grid!important}.security-date{width:100%!important}}
.security-page{padding:0 0 44px!important;background:#fff!important}
.security-wrap{max-width:1490px!important;margin:0 auto!important}
.security-top{display:flex!important;align-items:flex-start!important;justify-content:space-between!important;gap:18px!important;margin:0 0 14px!important}
.security-title-box{display:flex!important;align-items:flex-start!important;gap:10px!important}
.security-title-box:before{content:"\f3ed"!important;font-family:"Font Awesome 6 Free"!important;font-weight:900!important;width:34px!important;height:34px!important;margin-top:4px!important;border-radius:50%!important;background:transparent!important;color:#ff7a00!important;display:grid!important;place-items:center!important;font-size:28px!important;line-height:1!important;flex:0 0 auto!important}
.security-title{font-family:'Playfair Display',Georgia,serif!important;font-size:40px!important;font-weight:700!important;line-height:1.1!important;letter-spacing:0!important;color:#111827!important;margin:0!important}
.security-subtitle{margin:4px 0 0!important;font:400 12px/1.45 'Inter',system-ui,sans-serif!important;color:#111827!important}
.security-date{height:42px!important;min-width:178px!important;border:1px solid #111827!important;border-radius:8px!important;background:#fff!important;box-shadow:none!important}
.security-tabs{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important;margin:0 0 22px!important;padding:0!important;gap:30px!important}
.security-tab{height:34px!important;border:0!important;border-radius:0!important;background:transparent!important;color:#111827!important;padding:0!important;font:600 11px/1 'Poppins',system-ui,sans-serif!important}
.security-tab.active{color:#ff7a00!important;border-bottom:3px solid #ff7a00!important;background:transparent!important}
.sp-layout{grid-template-columns:minmax(0,1fr) 350px!important;gap:28px!important;align-items:start!important}
.sp-main,.sp-side{gap:18px!important}
.sp-hero,.sp-main>.sp-card,.sp-main>.sp-grid-2>.sp-card,.sp-main>.sp-grid-3>.sp-card,.sp-main>.sp-grid-4>.sp-card,.sp-table{border:1px solid #111827!important;border-radius:8px!important;background:#fff!important;box-shadow:none!important}
.sp-hero:hover,.sp-main>.sp-card:hover,.sp-main>.sp-grid-2>.sp-card:hover,.sp-main>.sp-grid-3>.sp-card:hover,.sp-main>.sp-grid-4>.sp-card:hover{background:#fff!important;box-shadow:none!important}
.sp-side>.sp-card{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important}
.sp-side>.sp-card:hover{background:transparent!important;box-shadow:none!important}
.sp-card-pad{padding:18px!important}
.sp-side .sp-card-pad{padding:0 0 18px!important}
.sp-card-title{font-family:'Poppins',system-ui,sans-serif!important;font-size:17px!important;font-weight:600!important;letter-spacing:0!important}
.sp-card-title.sm{font-size:15px!important}
.sp-card-desc,.sp-row small,.sp-mini-desc,.security-page p{font-family:'Inter',system-ui,sans-serif!important;font-size:12px!important;font-weight:400!important;letter-spacing:0!important}
.sp-btn{border:0!important;border-radius:8px!important;background:#ff7a00!important;color:#111827!important;box-shadow:none!important}
.sp-btn:hover,.sp-btn:focus{background:#111827!important;color:#fff!important;box-shadow:none!important}
.sp-btn.outline,.sp-link{background:#fff!important;border:0!important;color:#111827!important;box-shadow:none!important}
.sp-btn.outline:hover,.sp-link:hover{background:#111827!important;color:#fff!important}
@media(max-width:1180px){.sp-layout{grid-template-columns:1fr!important}.sp-side{grid-template-columns:repeat(2,minmax(0,1fr))!important}}
@media(max-width:760px){.security-page{padding:0 18px 44px!important}.security-top{display:grid!important}.security-title{font-size:32px!important}.sp-layout,.sp-side{grid-template-columns:1fr!important}.security-tabs{overflow:auto!important;gap:22px!important}}

/* Final Security & Privacy box treatment based on the reference screens */
#overview-panel .sp-main>.sp-hero{border:1px solid #111827!important;border-radius:8px!important;background:#fff!important;box-shadow:none!important;padding:18px 20px!important}
#overview-panel .sp-main>.sp-card-title.sm{margin:18px 0 0!important;padding-left:2px!important;font:600 16px/1.25 'Poppins',system-ui,sans-serif!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type,#overview-panel .sp-main>.sp-grid-2:first-of-type{display:grid!important;grid-template-columns:1fr!important;gap:0!important;border:1px solid #111827!important;border-radius:8px!important;background:#fff!important;overflow:hidden!important;box-shadow:none!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type>.sp-card,#overview-panel .sp-main>.sp-grid-2:first-of-type>.sp-card{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important;border-bottom:1px solid #e5e7eb!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type>.sp-card:last-child,#overview-panel .sp-main>.sp-grid-2:first-of-type>.sp-card:last-child{border-bottom:0!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type .sp-card-pad,#overview-panel .sp-main>.sp-grid-2:first-of-type .sp-card-pad{min-height:0!important;padding:14px 18px!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type .sp-card-pad{display:grid!important;grid-template-columns:42px minmax(0,1fr) auto!important;align-items:center!important;column-gap:14px!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type .sp-head-row{grid-row:1/span 4!important;margin:0!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type .sp-card-title{margin:0!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type .sp-card-desc,#overview-panel .sp-main>.sp-grid-3:first-of-type p{margin:2px 0!important}
#overview-panel .sp-main>.sp-grid-3:first-of-type .sp-btn{grid-column:3!important;grid-row:1/span 4!important;align-self:center!important;min-width:150px!important}
#overview-panel .sp-main>.sp-grid-4:first-of-type{display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:0!important;border:1px solid #111827!important;border-radius:8px!important;background:#111827!important;overflow:hidden!important}
#overview-panel .sp-main>.sp-grid-4:first-of-type>.sp-card{border:0!important;border-radius:0!important;border-right:1px solid rgba(255,255,255,.12)!important;background:transparent!important;color:#fff!important;box-shadow:none!important}
#overview-panel .sp-main>.sp-grid-4:first-of-type>.sp-card:last-child{border-right:0!important}
#overview-panel .sp-main>.sp-grid-4:first-of-type .sp-card-title,#overview-panel .sp-main>.sp-grid-4:first-of-type .sp-card-desc,#overview-panel .sp-main>.sp-grid-4:first-of-type strong{color:#fff!important}
#password-panel .sp-main>.sp-card:nth-of-type(1),#password-panel .sp-main>.sp-card:nth-of-type(2){border:1px solid #111827!important;background:#fff!important;box-shadow:none!important}
#password-panel .sp-main>.sp-card:nth-of-type(1){border-radius:8px 8px 0 0!important;border-bottom:0!important;margin-bottom:0!important}
#password-panel .sp-main>.sp-card:nth-of-type(2){border-radius:0 0 8px 8px!important;border-top:1px solid #e5e7eb!important;margin-top:0!important}
#password-panel .sp-main>.sp-grid-2{display:grid!important;grid-template-columns:1fr 1fr!important;gap:0!important;margin-top:18px!important;border:1px solid #111827!important;border-bottom:0!important;border-radius:8px 8px 0 0!important;background:#111827!important;overflow:hidden!important}
#password-panel .sp-main>.sp-grid-2>.sp-card,#password-panel .sp-main>.sp-grid-2+.sp-card{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important;color:#fff!important}
#password-panel .sp-main>.sp-grid-2>.sp-card:first-child{border-right:1px solid rgba(255,255,255,.12)!important}
#password-panel .sp-main>.sp-grid-2+.sp-card{margin-top:0!important;border:1px solid #111827!important;border-top:1px solid rgba(255,255,255,.12)!important;border-radius:0 0 8px 8px!important;background:#111827!important}
#password-panel .sp-main>.sp-grid-2 .sp-card-title,#password-panel .sp-main>.sp-grid-2 .sp-card-desc,#password-panel .sp-main>.sp-grid-2 strong,#password-panel .sp-main>.sp-grid-2 small,#password-panel .sp-main>.sp-grid-2+.sp-card .sp-card-title,#password-panel .sp-main>.sp-grid-2+.sp-card .sp-card-desc,#password-panel .sp-main>.sp-grid-2+.sp-card strong,#password-panel .sp-main>.sp-grid-2+.sp-card small{color:#fff!important}
#password-panel .sp-main>.sp-grid-2 .sp-row,#password-panel .sp-main>.sp-grid-2+.sp-card .sp-row{border-color:rgba(255,255,255,.12)!important}
#overview-panel .sp-side>.sp-card,#password-panel .sp-side>.sp-card{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important}
</style>

<div class="security-page">
    <div class="security-wrap">
        <div class="security-top">
            <div class="security-title-box">
                <div>
                    <h1 class="security-title">Security &amp; Privacy</h1>
                    <p class="security-subtitle">Manage your account security, privacy settings, and data protection.</p>
                </div>
            </div>
            <div class="security-date"><i data-lucide="calendar-days"></i><span>Today is {{ now()->format('M d, Y') }}</span></div>
        </div>

        <div class="security-tabs" role="tablist" aria-label="Security and Privacy tabs">
            <button type="button" class="security-tab active" data-tab="overview">OVERVIEW</button>
            <button type="button" class="security-tab" data-tab="password">PASSWORD</button>
            <button type="button" class="security-tab" data-tab="twofa">2FA</button>
            <button type="button" class="security-tab" data-tab="devices">DEVICES</button>
            <button type="button" class="security-tab" data-tab="privacy">PRIVACY</button>
        </div>

        <!-- OVERVIEW TAB -->
        <section class="sp-panel active" id="overview-panel">
            <div class="sp-layout">
                <main class="sp-main">
                    <section class="sp-hero">
                        <div class="sp-hero-left">
                            <div class="sp-shield-big"><i class="fa-solid fa-shield-halved"></i></div>
                            <div>
                                <h2>Your Account is Secure</h2>
                                <p class="sp-card-desc">Great job! You have strong security settings in place.</p>
                                <div class="sp-chips">
                                    <span class="sp-chip"><i class="fa-solid fa-check"></i> Strong Password</span>
                                    <span class="sp-chip"><i class="fa-solid fa-check"></i> 2FA Enabled</span>
                                    <span class="sp-chip"><i class="fa-solid fa-check"></i> Email Verified</span>
                                </div>
                            </div>
                        </div>
                        <div class="sp-score">
                            <p class="sp-card-desc">Security Score</p>
                            <strong>90</strong><span>/100</span>
                            <div class="sp-meter"><i></i></div>
                            <button type="button" class="sp-link" onclick="showSpToast('Security recommendations opened.')">View Security Recommendations <i class="fa-solid fa-chevron-right"></i></button>
                        </div>
                    </section>

                    <h3 class="sp-card-title sm"><i class="fa-solid fa-lock" style="color:var(--sp-orange);margin-right:8px"></i>Account Protection &amp; Device Access</h3>
                    <div class="sp-grid-3">
                        <section class="sp-card"><div class="sp-card-pad">
                            <div class="sp-head-row"><span class="sp-icon"><i class="fa-solid fa-key"></i></span></div>
                            <h3 class="sp-card-title sm" style="margin-top:12px">Password</h3>
                            <p class="sp-card-desc">Keep your password strong and unique to protect your account.</p>
                            <p style="margin:16px 0 18px;font-size:12px;color:#111827">Last changed<br><b>May 25, 2026</b></p>
                            <button type="button" class="sp-btn block" data-tab-jump="password">Change Password</button>
                        </div></section>
                        <section class="sp-card"><div class="sp-card-pad">
                            <div class="sp-head-row"><span class="sp-icon blue"><i class="fa-solid fa-lock"></i></span></div>
                            <h3 class="sp-card-title sm" style="margin-top:12px">Two-Factor Authentication</h3>
                            <p class="sp-card-desc">Add an extra layer of security to your account.</p>
                            <div style="margin:14px 0"><span class="sp-status green">Enabled</span></div>
                            <p class="sp-card-desc"><b>Method:</b> Authenticator App</p>
                            <button type="button" class="sp-btn outline block" style="margin-top:18px" data-tab-jump="twofa">Manage 2FA</button>
                        </div></section>
                        <section class="sp-card"><div class="sp-card-pad">
                            <div class="sp-head-row"><span class="sp-icon green"><i class="fa-regular fa-envelope"></i></span></div>
                            <h3 class="sp-card-title sm" style="margin-top:12px">Email Verification</h3>
                            <p class="sp-card-desc">Your email is verified. We’ll use this to secure your account.</p>
                            <div style="margin:14px 0"><span class="sp-status green"><i class="fa-solid fa-check"></i> Verified</span></div>
                            <p class="sp-card-desc">allaeyra8@gmail.com</p>
                            <button type="button" class="sp-btn outline block" style="margin-top:18px">Update Email</button>
                        </div></section>
                    </div>

                    <h3 class="sp-card-title sm"><i class="fa-solid fa-desktop" style="color:var(--sp-orange);margin-right:8px"></i>Device Access</h3>
                    <div class="sp-grid-2">
                        <section class="sp-card"><div class="sp-card-pad">
                            <div class="sp-head-row"><div><h3 class="sp-card-title sm">Login Activity</h3><p class="sp-card-desc">Review your recent login activity.</p></div><button type="button" class="sp-link" data-tab-jump="devices">View All <i class="fa-solid fa-chevron-right"></i></button></div>
                            <div class="sp-list">
                                <div class="sp-row"><div><b>Quezon City, Philippines</b><small>Chrome on Windows</small></div><span class="sp-status green">Current Session</span></div>
                                <div class="sp-row"><div><b>Quezon City, Philippines</b><small>Safari on iPhone</small></div><small>May 31, 2026 10:43 AM</small></div>
                                <div class="sp-row"><div><b>Makati City, Philippines</b><small>Chrome on Windows</small></div><small>May 29, 2026 09:15 PM</small></div>
                            </div>
                        </div></section>
                        <section class="sp-card"><div class="sp-card-pad">
                            <h3 class="sp-card-title sm">Active Sessions</h3><p class="sp-card-desc">You are currently signed in on these devices.</p>
                            <div class="sp-list">
                                <div class="sp-row"><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-brands fa-windows"></i></span><div><strong>Windows • Chrome</strong><small>Quezon City • Jun 02, 2026 11:24 AM</small></div></div><span class="sp-status green">Current Device</span></div>
                                <div class="sp-row"><div class="sp-row-left"><span class="sp-icon"><i class="fa-solid fa-mobile-screen"></i></span><div><strong>iPhone • Safari</strong><small>May 31, 2026 10:43 AM</small></div></div><button type="button" class="sp-btn red-outline">Sign Out</button></div>
                            </div>
                        </div></section>
                    </div>

                    <h3 class="sp-card-title sm"><i class="fa-solid fa-shield-halved" style="color:var(--sp-orange);margin-right:8px"></i>Privacy &amp; Data Controls</h3>
                    <div class="sp-grid-4">
                        <section class="sp-card"><div class="sp-card-pad"><span class="sp-icon purple"><i class="fa-regular fa-user"></i></span><h3 class="sp-card-title sm" style="margin-top:12px">Profile Visibility</h3><p class="sp-card-desc">Control who can see your profile information.</p><button class="sp-btn outline block" style="margin-top:16px" data-tab-jump="privacy">Manage Visibility</button></div></section>
                        <section class="sp-card"><div class="sp-card-pad"><span class="sp-icon green"><i class="fa-solid fa-bullhorn"></i></span><h3 class="sp-card-title sm" style="margin-top:12px">Marketing Preferences</h3><p class="sp-card-desc">Choose how you want to hear from us.</p><button class="sp-btn outline block" style="margin-top:16px" data-tab-jump="privacy">Update Preferences</button></div></section>
                        <section class="sp-card"><div class="sp-card-pad"><span class="sp-icon blue"><i class="fa-solid fa-cookie-bite"></i></span><h3 class="sp-card-title sm" style="margin-top:12px">Cookies &amp; Data</h3><p class="sp-card-desc">Manage cookies and similar technologies.</p><button class="sp-btn outline block" style="margin-top:16px" data-tab-jump="privacy">Manage Cookies</button></div></section>
                        <section class="sp-card"><div class="sp-card-pad"><span class="sp-icon"><i class="fa-solid fa-download"></i></span><h3 class="sp-card-title sm" style="margin-top:12px">Download Your Data</h3><p class="sp-card-desc">Request a copy of your account data.</p><button class="sp-btn outline block" style="margin-top:16px" data-tab-jump="privacy">Request Export</button></div></section>
                    </div>

                    <section class="sp-card sp-danger"><div class="sp-card-pad">
                        <h3 class="sp-card-title sm"><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</h3>
                        <p class="sp-card-desc">These actions are permanent and cannot be undone.</p>
                        <div class="sp-danger-row">
                            <div class="sp-danger-box"><div class="sp-row-left"><span class="sp-icon red"><i class="fa-solid fa-user-xmark"></i></span><div><strong>Deactivate Account</strong><small>Temporarily deactivate your account.</small></div></div><button class="sp-btn red-outline">Deactivate Account</button></div>
                            <div class="sp-danger-box"><div class="sp-row-left"><span class="sp-icon red"><i class="fa-regular fa-trash-can"></i></span><div><strong>Delete Account</strong><small>Permanently delete your account and data.</small></div></div><button class="sp-btn red">Delete My Account</button></div>
                        </div>
                    </div></section>
                </main>
                <aside class="sp-side">
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Account Protection</h3><p class="sp-card-desc">Your account security status.</p><div class="sp-row-left" style="margin-top:16px"><div style="width:86px;height:86px;border-radius:50%;border:10px solid var(--sp-green);display:grid;place-items:center;color:var(--sp-green);font-size:22px"><i class="fa-solid fa-shield-halved"></i></div><div><h3 style="margin:0">Strong</h3><p class="sp-card-desc">Your account is well protected.</p></div></div><div class="sp-list"><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Strong password</span><b style="color:var(--sp-green);font-size:12px">Good</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Two-factor authentication</span><b style="color:var(--sp-green);font-size:12px">Enabled</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Recovery email</span><b style="color:var(--sp-green);font-size:12px">Verified</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Active sessions</span><b style="color:var(--sp-green);font-size:12px">3 devices</b></div></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Account Verification</h3><p class="sp-card-desc">Your identity verification status.</p><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><i class="fa-regular fa-envelope"></i><div><strong>Email Address</strong><small>allaeyra8@gmail.com</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-mobile-screen"></i><div><strong>Phone Number</strong><small>+63 945 346 46</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-regular fa-id-card"></i><div><strong>Identity Verification</strong><small>Verified on May 25, 2026</small></div></div><span class="sp-status green">Verified</span></div><button class="sp-link">Learn more about verification <i class="fa-solid fa-arrow-up-right-from-square"></i></button></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Data &amp; Privacy Actions</h3><p class="sp-card-desc">Manage and export your data.</p><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-solid fa-download"></i></span><div><strong>Download My Data</strong><small>Request a copy of your data</small></div></div><button class="sp-btn soft">Request</button></div><div class="sp-row"><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-regular fa-clock"></i></span><div><strong>Data Retention</strong><small>Manage how long we keep your data</small></div></div><i class="fa-solid fa-chevron-right"></i></div></div></div></section>
                </aside>
            </div>
        </section>

        <!-- PASSWORD TAB -->
        <section class="sp-panel" id="password-panel">
            <div class="sp-layout">
                <main class="sp-main">
                    <section class="sp-card"><div class="sp-card-pad">
                        <div class="sp-head-row"><div class="sp-row-left"><span class="sp-icon"><i class="fa-solid fa-lock"></i></span><div><h2 class="sp-card-title">Password Management</h2><p class="sp-card-desc">A strong password helps keep your account safe and secure.</p></div></div><button type="button" class="sp-btn soft"><i class="fa-regular fa-lightbulb"></i> Password Tips</button></div>
                        <div class="sp-info-band" style="margin-top:18px"><div><small>Password strength</small><br><b style="color:var(--sp-green)">Strong</b></div><div class="sp-strength" style="width:220px"><i></i><i></i><i></i><i></i><i class="off"></i></div><div><small>Last changed</small><br><b>May 31, 2026 12:56 PM</b></div></div>
                    </div></section>

                    <section class="sp-card"><div class="sp-card-pad">
                        <div class="sp-row-left"><span class="sp-icon"><i class="fa-solid fa-key"></i></span><div><h2 class="sp-card-title">Change Password</h2><p class="sp-card-desc">Use a strong password that you don’t use on other websites.</p></div></div>
                        <form method="POST" action="{{ route('password.change') }}">
                            @csrf
                            @method('put')
                            <div class="sp-field"><label for="current_password">Current Password</label><div class="sp-input-wrap"><input id="current_password" class="sp-input" type="password" name="current_password" value="••••••••••••" autocomplete="current-password"><button class="sp-eye" type="button" data-toggle-pass><i class="fa-regular fa-eye"></i></button></div></div>
                            <div class="sp-field"><label for="password">New Password</label><div class="sp-input-wrap"><input id="password" class="sp-input" type="password" name="password" value="••••••••••" autocomplete="new-password"><button class="sp-eye" type="button" data-toggle-pass><i class="fa-regular fa-eye"></i></button></div><div class="sp-strength"><i></i><i></i><i></i><i></i><i class="off"></i><span style="font-size:12px;color:var(--sp-green);font-weight:800">Strong</span></div><p class="sp-card-desc">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</p></div>
                            <div class="sp-field"><label for="password_confirmation">Confirm New Password</label><div class="sp-input-wrap"><input id="password_confirmation" class="sp-input" type="password" name="password_confirmation" value="••••••••••" autocomplete="new-password"><button class="sp-eye" type="button" data-toggle-pass><i class="fa-regular fa-eye"></i></button></div></div>
                            <div style="display:flex;justify-content:flex-end;margin-top:18px"><button type="submit" class="sp-btn">Update Password</button></div>
                        </form>
                    </div></section>

                    <div class="sp-grid-2">
                        <section class="sp-card"><div class="sp-card-pad"><div class="sp-row-left"><span class="sp-icon"><i class="fa-solid fa-shield-halved"></i></span><div><h3 class="sp-card-title sm">Password Requirements</h3><p class="sp-card-desc">Your password must include:</p></div></div><div class="sp-check-list"><span class="sp-check"><i class="fa-solid fa-circle-check"></i> At least 8 characters long</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i> At least one uppercase letter (A–Z)</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i> At least one lowercase letter (a–z)</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i> At least one number (0–9)</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i> At least one special character (!@#$%^&amp;*)</span></div></div></section>
                        <section class="sp-card"><div class="sp-card-pad"><div class="sp-row-left"><span class="sp-icon"><i class="fa-solid fa-arrows-rotate"></i></span><div><h3 class="sp-card-title sm">Recovery Options</h3><p class="sp-card-desc">Use these options to recover your account.</p></div></div><div class="sp-row"><div class="sp-row-left"><span class="sp-icon"><i class="fa-regular fa-envelope"></i></span><div><strong>Recovery Email</strong><small>allaeyra8@gmail.com</small></div></div><span class="sp-status green">Verified</span><button class="sp-btn soft">Edit</button></div><div class="sp-row"><div class="sp-row-left"><span class="sp-icon"><i class="fa-solid fa-mobile-screen"></i></span><div><strong>Recovery Phone</strong><small>+63 945 346 46</small></div></div><span class="sp-status green">Verified</span><button class="sp-btn soft">Edit</button></div></div></section>
                    </div>

                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-shield" style="color:var(--sp-orange);margin-right:8px"></i>Password Best Practices</h3><div class="sp-grid-4" style="margin-top:14px"><span class="sp-check" style="color:#6b7280"><i style="color:var(--sp-orange)" class="fa-solid fa-circle"></i> Use a unique password for your account</span><span class="sp-check" style="color:#6b7280"><i style="color:var(--sp-orange)" class="fa-solid fa-circle"></i> Avoid using personal information</span><span class="sp-check" style="color:#6b7280"><i style="color:var(--sp-orange)" class="fa-solid fa-circle"></i> Change your password regularly</span><span class="sp-check" style="color:#6b7280"><i style="color:var(--sp-orange)" class="fa-solid fa-circle"></i> Don’t share your password with anyone</span></div></div></section>
                </main>
                <aside class="sp-side">
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Password Health</h3><div class="sp-row-left" style="margin-top:18px"><div style="width:94px;height:94px;border-radius:50%;border:12px solid var(--sp-green);display:grid;place-items:center;color:var(--sp-green);font-size:26px;font-weight:800">94<br><small style="font-size:12px;color:#111827">/100</small></div><div><h3 style="margin:0;color:var(--sp-green)">Excellent</h3><p class="sp-card-desc">Great job! Your password is strong and secure.</p></div></div><div class="sp-list"><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Strong password</span><b style="color:var(--sp-green);font-size:12px">Yes</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Recently updated</span><b style="color:var(--sp-green);font-size:12px">Yes</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Unique password</span><b style="color:var(--sp-green);font-size:12px">Yes</b></div></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Recovery Status</h3><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-regular fa-envelope"></i></span><div><strong>Recovery Email</strong><small>allaeyra8@gmail.com</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-solid fa-mobile-screen"></i></span><div><strong>Recovery Phone</strong><small>+63 945 346 46</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-regular fa-calendar"></i></span><div><strong>Last Reset</strong><small>May 31, 2026 12:56 PM</small></div></div></div><button class="sp-link">Manage recovery options <i class="fa-solid fa-chevron-right"></i></button></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Account Verification</h3><p class="sp-card-desc">Your identity and contact details.</p><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><i class="fa-regular fa-envelope"></i><div><strong>Email Address</strong><small>allieyra8@gmail.com</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-mobile-screen"></i><div><strong>Phone Number</strong><small>+63 945 346 46</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-regular fa-id-card"></i><div><strong>Identity Verification</strong><small>Verified on May 25, 2026</small></div></div><span class="sp-status green">Verified</span></div><button class="sp-link">View all verification details <i class="fa-solid fa-chevron-right"></i></button></div></div></section>
                </aside>
            </div>
        </section>

        <!-- 2FA TAB -->
        <section class="sp-panel" id="twofa-panel">
            <div class="sp-layout">
                <main class="sp-main">
                    <section class="sp-card"><div class="sp-card-pad"><div class="sp-head-row"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-solid fa-lock"></i></span><div><h2 class="sp-card-title">Two-Factor Authentication</h2><p class="sp-card-desc">Add an extra layer of security to your account by requiring a second verification when you sign in.</p></div></div><span class="sp-status green"><i class="fa-solid fa-circle-check"></i> Enabled</span></div><div style="display:flex;justify-content:flex-end;margin-top:12px"><p class="sp-card-desc"><i class="fa-solid fa-mobile-screen"></i> Active method<br><b style="color:#111827;font-size:14px">Authenticator App</b></p></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-shield-halved" style="color:var(--sp-orange);margin-right:8px"></i>2FA Method</h3><p class="sp-card-desc">Your primary method for two-factor authentication.</p><div class="sp-info-band" style="margin-top:16px"><div class="sp-row-left"><span class="sp-icon soft"><i class="fa-solid fa-mobile-screen-button"></i></span><div><strong>Authenticator App <span class="sp-status green">Primary</span></strong><small>Generate time-based codes using your authenticator app.</small></div></div><div><small>Setup date</small><br><b>May 25, 2026</b></div><button class="sp-btn outline">Manage 2FA</button></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-key" style="color:var(--sp-orange);margin-right:8px"></i>Backup Codes</h3><p class="sp-card-desc">Use backup codes to sign in if you lose access to your authenticator app.</p><div class="sp-info-band" style="margin-top:16px"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-regular fa-rectangle-list"></i></span><div><strong>8 unused codes left</strong><small>Each code can only be used once.</small></div></div><div style="display:flex;gap:10px"><button class="sp-btn soft">View Codes</button><button class="sp-btn">Regenerate</button></div></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-users" style="color:var(--sp-orange);margin-right:8px"></i>Recovery Methods</h3><p class="sp-card-desc">These options can be used to recover your account if you lose access to your 2FA method.</p><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-solid fa-mobile-screen"></i></span><div><strong>Recovery Phone</strong><small>+63 912 ••• ••• 46</small></div></div><span class="sp-status green">Verified</span><button class="sp-btn soft">Edit</button></div><div class="sp-row"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-regular fa-envelope"></i></span><div><strong>Recovery Email</strong><small>allieyra8@gmail.com</small></div></div><span class="sp-status green">Verified</span><button class="sp-btn soft">Edit</button></div></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-laptop" style="color:var(--sp-orange);margin-right:8px"></i>Trusted Devices</h3><p class="sp-card-desc">Devices where you’ve chosen to trust 2FA for a limited time.</p><table class="sp-table"><thead><tr><th>Device</th><th>Browser</th><th>Location</th><th>Added On</th><th>Action</th></tr></thead><tbody><tr><td><div class="sp-device"><i class="fa-solid fa-laptop"></i><div><b>Windows • Chrome</b><small>Windows 11</small></div></div></td><td>Chrome 125</td><td>Quezon City, Metro Manila<small>Philippines</small></td><td>Jun 02, 2026 11:24 AM</td><td><button class="sp-btn red-outline">Remove</button></td></tr><tr><td><div class="sp-device"><i class="fa-solid fa-mobile-screen"></i><div><b>iPhone • Safari</b><small>iOS 17.5</small></div></div></td><td>Safari</td><td>Quezon City, Metro Manila<small>Philippines</small></td><td>Jun 01, 2026 8:16 PM</td><td><button class="sp-btn red-outline">Remove</button></td></tr><tr><td><div class="sp-device"><i class="fa-solid fa-desktop"></i><div><b>MacOS • Chrome</b><small>macOS 14.4</small></div></div></td><td>Chrome 124</td><td>Manila, Metro Manila<small>Philippines</small></td><td>May 31, 2026 4:03 PM</td><td><button class="sp-btn red-outline">Remove</button></td></tr></tbody></table></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-circle-info" style="color:var(--sp-orange);margin-right:8px"></i>Why Enable 2FA?</h3><div class="sp-grid-4" style="margin-top:14px"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Reduces unauthorized access risk</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Protects your account</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Keeps orders secure</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Recommended by security experts</span></div></div></section>
                </main>
                <aside class="sp-side"><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Protection Status</h3><p class="sp-card-desc">Your account is well protected.</p><div class="sp-row-left" style="margin-top:16px"><div style="width:86px;height:86px;border-radius:50%;border:10px solid var(--sp-green);display:grid;place-items:center;color:var(--sp-green);font-size:22px"><i class="fa-solid fa-shield-halved"></i></div><div><h3 style="margin:0">Strong</h3><p class="sp-card-desc">Your account is protected.</p></div></div><div class="sp-list"><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Strong password</span><b style="color:var(--sp-green);font-size:12px">Good</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Two-factor authentication</span><b style="color:var(--sp-green);font-size:12px">Enabled</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Recovery email</span><b style="color:var(--sp-green);font-size:12px">Verified</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Recovery phone</span><b style="color:var(--sp-green);font-size:12px">Verified</b></div></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Account Verification</h3><p class="sp-card-desc">Your identity and contact details.</p><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><i class="fa-regular fa-envelope"></i><div><strong>Email Address</strong><small>allieyra8@gmail.com</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-mobile-screen"></i><div><strong>Phone Number</strong><small>+63 945 346 46</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-regular fa-id-card"></i><div><strong>Identity Verification</strong><small>Verified on May 25, 2026</small></div></div><span class="sp-status green">Verified</span></div><button class="sp-link">View all verification details <i class="fa-solid fa-chevron-right"></i></button></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">2FA Help</h3><p class="sp-card-desc">Need help with two-factor authentication?</p><div class="sp-list"><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-solid fa-lock-open"></i></span><div><strong>Lost your authenticator?</strong><small>Use a backup code or recovery method.</small></div></div><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-regular fa-circle-question"></i></span><div><strong>Can’t access recovery options?</strong><small>Contact our support team for assistance.</small></div></div><button class="sp-link">Go to Help Center <i class="fa-solid fa-arrow-up-right-from-square"></i></button></div></div></section></aside>
            </div>
        </section>

        <!-- DEVICES TAB -->
        <section class="sp-panel" id="devices-panel">
            <div class="sp-layout">
                <main class="sp-main">
                    <section class="sp-card"><div class="sp-card-pad"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-solid fa-desktop"></i></span><div><h2 class="sp-card-title">Active Devices &amp; Sessions</h2><p class="sp-card-desc">These are the devices currently signed in to your account.</p></div></div><div class="sp-grid-3" style="margin-top:18px"><div><h2 style="margin:0;font-size:32px">3</h2><b>Active Devices</b><p class="sp-card-desc">Your account is secure.</p></div><span class="sp-check"><i class="fa-solid fa-circle-check"></i> All devices are verified</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i> Secure sessions</span><span class="sp-check"><i class="fa-solid fa-circle-check"></i> Regular monitoring</span></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-display" style="color:var(--sp-orange);margin-right:8px"></i>Active Sessions / Devices</h3><p class="sp-card-desc">Manage the devices that are currently signed in to your account.</p><table class="sp-table"><thead><tr><th>Device</th><th>Browser</th><th>Location</th><th>Last Active</th><th>Status</th><th>Action</th></tr></thead><tbody><tr><td><div class="sp-device"><i class="fa-solid fa-laptop"></i><div><b>Windows • Chrome</b><small>Windows 11</small></div></div></td><td><i class="fa-brands fa-chrome" style="color:#16a34a"></i> Chrome 125.0</td><td>Quezon City, Metro Manila<small>Philippines</small></td><td>Just now</td><td><span class="sp-status green">This device</span></td><td>—</td></tr><tr><td><div class="sp-device"><i class="fa-solid fa-mobile-screen"></i><div><b>iPhone • Safari</b><small>iOS 17.5</small></div></div></td><td><i class="fa-brands fa-safari" style="color:#2563eb"></i> Safari 17.5</td><td>Quezon City, Metro Manila<small>Philippines</small></td><td>Jun 01, 2026 8:16 PM</td><td><span class="sp-status green">Active</span></td><td><button class="sp-btn outline">Sign Out</button></td></tr><tr><td><div class="sp-device"><i class="fa-solid fa-laptop"></i><div><b>MacOS • Chrome</b><small>macOS 14.4</small></div></div></td><td><i class="fa-brands fa-chrome" style="color:#16a34a"></i> Chrome 124.0</td><td>Manila, Metro Manila<small>Philippines</small></td><td>May 31, 2026 4:03 PM</td><td><span class="sp-status green">Active</span></td><td><button class="sp-btn outline">Sign Out</button></td></tr></tbody></table><div class="sp-head-row" style="margin-top:14px"><p class="sp-card-desc"><i class="fa-solid fa-shield-halved"></i> Didn’t recognize a device? Sign out of unfamiliar sessions and change your password.</p><button class="sp-btn">Sign Out All Other Sessions</button></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-chart-line" style="color:var(--sp-orange);margin-right:8px"></i>Recent Login Activity</h3><p class="sp-card-desc">Review your most recent account access.</p><table class="sp-table"><thead><tr><th>Date &amp; Time</th><th>Device / Browser</th><th>Location</th><th>IP Address</th><th>Status</th></tr></thead><tbody><tr><td>Jun 02, 2026 11:24 AM</td><td>Windows • Chrome</td><td>Quezon City, Metro Manila</td><td>112.201.45.67</td><td><span class="sp-status green">Current</span></td></tr><tr><td>Jun 01, 2026 8:16 PM</td><td>iPhone • Safari</td><td>Quezon City, Metro Manila</td><td>112.201.45.67</td><td><span class="sp-status green">Success</span></td></tr><tr><td>May 31, 2026 4:03 PM</td><td>MacOS • Chrome</td><td>Manila, Metro Manila</td><td>112.200.12.24</td><td><span class="sp-status green">Success</span></td></tr><tr><td>May 30, 2026 9:11 AM</td><td>Android • Chrome</td><td>Cebu City, Cebu</td><td>112.198.77.19</td><td><span class="sp-status green">Success</span></td></tr></tbody></table></div></section>
                    <div class="sp-grid-2"><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-gear" style="color:var(--sp-orange);margin-right:8px"></i>Device Security Settings</h3><div class="sp-list"><div class="sp-setting-row"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-regular fa-clock"></i></span><div><strong>Session timeout</strong><small>Automatically sign out inactive sessions.</small></div></div><button class="sp-btn soft">30 days <i class="fa-solid fa-chevron-down"></i></button></div><div class="sp-setting-row"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-regular fa-bell"></i></span><div><strong>New device alerts</strong><small>Email me when a new device signs in.</small></div></div><label class="sp-switch"><input checked type="checkbox"><span class="sp-slider"></span></label></div><div class="sp-setting-row"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-solid fa-shield-halved"></i></span><div><strong>Remember trusted devices</strong><small>Skip 2FA on trusted devices for 30 days.</small></div></div><label class="sp-switch"><input checked type="checkbox"><span class="sp-slider"></span></label></div></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-display" style="color:var(--sp-orange);margin-right:8px"></i>Manage Devices</h3><p class="sp-card-desc">Take control of your active sessions and devices.</p><div class="sp-list"><div class="sp-setting-row"><div><strong>Review and remove devices</strong><small>See a full list of devices and sign out any no longer used.</small></div><i class="fa-solid fa-chevron-right"></i></div><div class="sp-setting-row"><div><strong>Sign out all other sessions</strong><small>Immediately sign out all devices except this one.</small></div><i class="fa-solid fa-chevron-right"></i></div><button class="sp-btn block">Sign Out All Other Sessions</button></div></div></section></div>
                </main>
                <aside class="sp-side"><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Device Summary</h3><div class="sp-list"><div><small>Total active devices</small><h2 style="color:var(--sp-green);margin:4px 0">3</h2></div><div><small>Current device</small><br><b>Windows • Chrome</b></div><div><small>Last login location</small><br><b>Quezon City, Metro Manila<br>Philippines</b></div><button class="sp-link">View full login history <i class="fa-solid fa-chevron-right"></i></button></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Protection Status</h3><p class="sp-card-desc">Your account is well protected.</p><div class="sp-row-left" style="margin-top:16px"><div style="width:86px;height:86px;border-radius:50%;border:10px solid var(--sp-green);display:grid;place-items:center;color:var(--sp-green);font-size:22px"><i class="fa-solid fa-shield-halved"></i></div><div><h3 style="margin:0">Strong</h3><p class="sp-card-desc">Your account is protected.</p></div></div><div class="sp-list"><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Strong password</span><b style="color:var(--sp-green);font-size:12px">Good</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Two-factor authentication</span><b style="color:var(--sp-green);font-size:12px">Enabled</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Recovery email</span><b style="color:var(--sp-green);font-size:12px">Verified</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Recovery phone</span><b style="color:var(--sp-green);font-size:12px">Verified</b></div></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Safety Reminder</h3><p class="sp-card-desc">Follow these tips to keep your account secure.</p><div class="sp-list"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-solid fa-lock"></i></span><div><strong>Always log out</strong><small>Log out of shared or public devices.</small></div></div><div class="sp-row-left"><span class="sp-icon"><i class="fa-regular fa-eye"></i></span><div><strong>Review unknown logins</strong><small>Check login activity regularly.</small></div></div><div class="sp-row-left"><span class="sp-icon blue"><i class="fa-regular fa-envelope"></i></span><div><strong>Enable alerts</strong><small>Keep new device alerts on.</small></div></div></div></div></section></aside>
            </div>
        </section>

        <!-- PRIVACY TAB -->
        <section class="sp-panel" id="privacy-panel">
            <div class="sp-layout">
                <main class="sp-main">
                    <section class="sp-card"><div class="sp-card-pad"><div class="sp-head-row"><div class="sp-row-left"><span class="sp-icon green"><i class="fa-solid fa-shield-halved"></i></span><div><h2 class="sp-card-title">Privacy Controls</h2><p class="sp-card-desc">You’re in control of how your personal data is used and shared. Adjust your privacy settings to match your preferences.</p></div></div><div style="text-align:right"><span class="sp-status green"><i class="fa-solid fa-circle-check"></i> Well Managed</span><p class="sp-card-desc">Last reviewed: May 31, 2026</p></div></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-regular fa-user" style="color:var(--sp-orange);margin-right:8px"></i>Data &amp; Privacy Settings</h3><p class="sp-card-desc">Manage how your data is used and who can see it.</p><div class="sp-list"><div class="sp-setting-row"><div class="sp-row-left"><i class="fa-regular fa-user"></i><div><strong>Profile Visibility</strong><small>Allow others to see your profile information.</small></div></div><label class="sp-switch"><input checked type="checkbox"><span class="sp-slider"></span></label></div><div class="sp-setting-row"><div class="sp-row-left"><i class="fa-solid fa-bag-shopping"></i><div><strong>Order Visibility</strong><small>Allow others to view your orders and purchase history.</small></div></div><label class="sp-switch"><input checked type="checkbox"><span class="sp-slider"></span></label></div><div class="sp-setting-row"><div class="sp-row-left"><i class="fa-solid fa-wand-magic-sparkles"></i><div><strong>Personalized Recommendations</strong><small>Show product recommendations based on your activity.</small></div></div><label class="sp-switch"><input type="checkbox"><span class="sp-slider"></span></label></div><div class="sp-setting-row"><div class="sp-row-left"><i class="fa-regular fa-envelope"></i><div><strong>Marketing Communications</strong><small>Receive promotional emails and updates from PrintifyCo.</small></div></div><label class="sp-switch"><input type="checkbox"><span class="sp-slider"></span></label></div></div></div></section>
                    <section class="sp-card"><div class="sp-card-pad"><div class="sp-head-row"><div><h3 class="sp-card-title sm"><i class="fa-solid fa-cookie-bite" style="color:var(--sp-orange);margin-right:8px"></i>Cookie Preferences</h3><p class="sp-card-desc">Manage how we use cookies to improve your experience.</p></div><button class="sp-btn outline">Manage Cookies</button></div><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-cookie"></i><div><strong>Essential Cookies</strong><small>Necessary for the website to function properly.</small></div></div><b style="font-size:12px;color:#64748b">Always Active</b></div><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-chart-simple"></i><div><strong>Analytics Cookies</strong><small>Help us understand how you use our website.</small></div></div><span class="sp-status green">Enabled</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-bullhorn"></i><div><strong>Marketing Cookies</strong><small>Used to deliver personalized ads and content.</small></div></div><span class="sp-status gray">Disabled</span></div></div></div></section>
                    <div class="sp-grid-2"><section class="sp-card"><div class="sp-card-pad"><div class="sp-row-left"><span class="sp-icon"><i class="fa-solid fa-download"></i></span><div><h3 class="sp-card-title sm">Download My Data</h3><p class="sp-card-desc">Request a copy of your personal data that we have collected and stored.</p></div></div><button class="sp-btn outline block" style="margin-top:16px">Request Data Export</button></div></section><section class="sp-card"><div class="sp-card-pad"><div class="sp-row-left"><span class="sp-icon"><i class="fa-regular fa-clock"></i></span><div><h3 class="sp-card-title sm">Data Retention</h3><p class="sp-card-desc">Review how long we keep your data and your rights related to retention.</p></div></div><button class="sp-btn outline block" style="margin-top:16px">Review Policy</button></div></section></div>
                    <section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm"><i class="fa-solid fa-scale-balanced" style="color:var(--sp-orange);margin-right:8px"></i>Privacy Actions</h3><p class="sp-card-desc">Take control of your privacy and manage your data rights.</p><div class="sp-grid-3" style="margin-top:14px"><div class="sp-setting-row"><div class="sp-row-left"><i class="fa-regular fa-file-lines"></i><div><strong>Request account data</strong><small>Get a copy of the data we hold about you.</small></div></div><i class="fa-solid fa-chevron-right"></i></div><div class="sp-setting-row"><div class="sp-row-left"><i class="fa-solid fa-shield-halved"></i><div><strong>Update consent preferences</strong><small>Review and update consent for processing.</small></div></div><i class="fa-solid fa-chevron-right"></i></div><div class="sp-setting-row"><div class="sp-row-left"><i class="fa-solid fa-user-xmark"></i><div><strong>Request account deactivation</strong><small>Temporarily deactivate your account.</small></div></div><i class="fa-solid fa-chevron-right"></i></div></div></div></section>
                </main>
                <aside class="sp-side"><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Privacy Summary</h3><div class="sp-row-left" style="margin-top:16px"><div style="width:86px;height:86px;border-radius:50%;border:10px solid var(--sp-green);display:grid;place-items:center;color:var(--sp-green);font-size:24px"><i class="fa-solid fa-lock"></i></div><div><h3 style="margin:0">Well Managed</h3><p class="sp-card-desc">Your settings are up to date.</p></div></div><div class="sp-list"><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Privacy settings optimized</span><b style="color:var(--sp-green);font-size:12px">Good</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Consents updated</span><b style="color:var(--sp-green);font-size:12px">Good</b></div><div class="sp-row"><span class="sp-check"><i class="fa-solid fa-circle-check"></i>Data protection enabled</span><b style="color:var(--sp-green);font-size:12px">Good</b></div></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Consent Status</h3><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><i class="fa-regular fa-envelope"></i><div><strong>Email</strong><small>Transactional emails</small></div></div><span class="sp-status green">Allowed</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-bullhorn"></i><div><strong>Marketing</strong><small>Promotions and offers</small></div></div><span class="sp-status gray">Opted Out</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-users"></i><div><strong>Data Sharing</strong><small>Third-party sharing</small></div></div><span class="sp-status green">Not Shared</span></div><button class="sp-link">Manage consents <i class="fa-solid fa-chevron-right"></i></button></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Account Verification</h3><p class="sp-card-desc">Verified contact details help secure your data.</p><div class="sp-list"><div class="sp-row"><div class="sp-row-left"><i class="fa-regular fa-envelope"></i><div><strong>Email Address</strong><small>allaeyra8@gmail.com</small></div></div><span class="sp-status green">Verified</span></div><div class="sp-row"><div class="sp-row-left"><i class="fa-solid fa-mobile-screen"></i><div><strong>Phone Number</strong><small>+63 945 346 46</small></div></div><span class="sp-status green">Verified</span></div></div></div></section><section class="sp-card"><div class="sp-card-pad"><h3 class="sp-card-title sm">Data Protection Note</h3><p class="sp-card-desc">We use industry-standard security measures to protect your personal data. You can manage your privacy preferences at any time.</p><button class="sp-link" style="margin-top:16px">Learn more about our privacy practices <i class="fa-solid fa-arrow-up-right-from-square"></i></button></div></section></aside>
            </div>
        </section>
    </div>
    <div id="spToast" class="sp-toast">Updated.</div>
</div>

<script>
(function(){
    const securitySaveRoute = @json(Route::has('settings.save') ? route('settings.save') : '');
    const securityCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || @json(csrf_token());
    const tabs = document.querySelectorAll('.security-tab');
    const panels = document.querySelectorAll('.sp-panel');
    async function persistSecuritySetting(key, value, label){
        localStorage.setItem('printify_security_' + key, String(value));
        if(securitySaveRoute){
            try{
                const response = await fetch(securitySaveRoute, {
                    method:'POST',
                    headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':securityCsrf},
                    body:JSON.stringify({group:'security_privacy', key, value})
                });
                if(response.ok){
                    showSpToast((label || 'Security setting') + ' saved successfully.');
                    return;
                }
            }catch(error){
                console.warn('Security setting backend sync skipped.', error);
            }
        }
        showSpToast((label || 'Security setting') + ' saved locally.');
    }
    function activateTab(name){
        tabs.forEach(btn => btn.classList.toggle('active', btn.dataset.tab === name));
        panels.forEach(panel => panel.classList.toggle('active', panel.id === name + '-panel'));
        history.replaceState(null, '', window.location.pathname + '#' + name);
        window.scrollTo({top:0, behavior:'smooth'});
    }
    tabs.forEach(btn => btn.addEventListener('click', () => activateTab(btn.dataset.tab)));
    document.querySelectorAll('[data-tab-jump]').forEach(btn => btn.addEventListener('click', () => activateTab(btn.dataset.tabJump)));
    const hash = (window.location.hash || '').replace('#','');
    if(['overview','password','twofa','devices','privacy'].includes(hash)) activateTab(hash);

    document.querySelectorAll('[data-toggle-pass]').forEach(btn => {
        btn.addEventListener('click', function(){
            const input = this.closest('.sp-input-wrap').querySelector('input');
            input.type = input.type === 'password' ? 'text' : 'password';
            this.innerHTML = input.type === 'password' ? '<i class="fa-regular fa-eye"></i>' : '<i class="fa-regular fa-eye-slash"></i>';
        });
    });

    window.showSpToast = function(message){
        const toast = document.getElementById('spToast');
        if(!toast) return;
        toast.textContent = message || 'Updated.';
        toast.classList.add('show');
        clearTimeout(window.spToastTimer);
        window.spToastTimer = setTimeout(() => toast.classList.remove('show'), 2200);
    }

    document.querySelectorAll('.sp-switch input').forEach((input, index) => {
        const key = 'printify_security_switch_' + index;
        const saved = localStorage.getItem(key);
        if(saved !== null) input.checked = saved === '1';
        input.addEventListener('change', () => {
            localStorage.setItem(key, input.checked ? '1' : '0');
            persistSecuritySetting('switch_' + index, input.checked ? 'enabled' : 'disabled', input.checked ? 'Preference enabled' : 'Preference disabled');
        });
    });

    document.querySelectorAll('.sp-btn,.sp-link,.sp-setting-row').forEach(el => {
        el.addEventListener('click', event => {
            const target = event.currentTarget;
            if(target.matches('[data-tab-jump],[data-toggle-pass]') || target.type === 'submit' || target.closest('form')) return;
            const label = (target.textContent || 'Security setting').replace(/\s+/g,' ').trim();
            if(target.classList.contains('sp-setting-row')) target.classList.add('sp-clicked');
            persistSecuritySetting('action_' + label.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,''), new Date().toISOString(), label || 'Security setting');
            setTimeout(() => target.classList.remove('sp-clicked'), 180);
        });
    });
})();
</script>
</x-app-layout>
