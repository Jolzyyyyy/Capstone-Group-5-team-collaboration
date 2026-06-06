<x-app-layout>
@once
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&family=Poppins:wght@500;600;700&display=swap">
@endonce

@php
    $settingsUser = auth()->user();
    $settingsPhoto = $settingsUser->profile_photo_url ?? $settingsUser->profile_photo ?? null;
    $settingsName = $settingsUser->name ?? '';
    $settingsEmail = $settingsUser->email ?? '';
    $settingsPhone = $settingsUser->phone ?? '';
    $settingsBirthdate = $settingsUser->birthdate ?? '';
    $settingsCompany = $settingsUser->company ?? '';
    $settingsCustomerId = $settingsUser ? 'CUST-'.str_pad((string) $settingsUser->id, 5, '0', STR_PAD_LEFT) : 'Not set';
@endphp

<style>
:root{
--st-bg:#fff;--st-card:#fff;--st-line:#111827;--st-line2:#dfe3ea;--st-text:#111827;--st-muted:#6b7280;
--st-soft:#9a9a9a;--st-orange:#ff7a00;--st-orange2:#ff7a00;--st-orange3:#fff3e6;--st-green:#16a34a;
--st-green-bg:#eef8f2;--st-danger:#d74343;--st-gray-hover:#f4f4f4;--st-gray-active:#e4e4e4;--st-gray-dark:#d2d2d2;--st-shadow:0 10px 26px rgba(35,25,12,.045);--st-radius:14px
}
.st-page{min-height:calc(100vh - 70px);background:#fff!important;padding:0 0 34px;color:var(--st-text);font-family:'Inter',system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;font-weight:400;letter-spacing:0}
.st-wrap{width:100%;max-width:1490px;margin:0 auto;background:#fff!important}
.st-top{margin:0 0 12px}
.st-title{margin:0;font-family:'Playfair Display',Georgia,serif;font-size:40px;line-height:1.2;font-weight:700;text-transform:none;letter-spacing:-.02em;color:#111827}
.st-subtitle{margin:6px 0 0;font-size:12px;color:#666;font-weight:400}
.st-tabs{display:flex;gap:26px;align-items:center;border-bottom:1px solid var(--st-line2);margin:0 0 16px;overflow-x:auto;scrollbar-width:none}
.st-tabs::-webkit-scrollbar{display:none}
.st-tab{appearance:none;border:0;background:transparent;position:relative;padding:11px 0 12px;color:#252525;text-transform:uppercase;font-size:10.5px;font-weight:700;letter-spacing:.04em;cursor:pointer;white-space:nowrap;transition:.18s ease}
.st-tab:hover,.st-tab.active{color:var(--st-orange2)}
.st-tab.active:after{content:"";position:absolute;left:0;right:0;bottom:-1px;height:3px;background:var(--st-orange);border-radius:99px}
.st-panel{display:none}
.st-panel.active{display:block}
.st-grid{display:grid;grid-template-columns:minmax(0,1.26fr) minmax(360px,.74fr);gap:17px;align-items:start}
.st-stack{display:flex;flex-direction:column;gap:16px}
.st-right-slim{width:96%;max-width:650px}
.st-card{background:var(--st-card);border:1px solid #111827;border-radius:var(--st-radius);box-shadow:var(--st-shadow);overflow:hidden;transition:background .18s ease,border-color .18s ease,box-shadow .18s ease,transform .18s ease}
.st-card:hover{background:rgba(17,24,39,.10);border-color:#111827;box-shadow:0 18px 42px rgba(15,23,42,.11);transform:none}
.st-body{padding:15px 17px}
.st-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:13px}
.st-card-title{margin:0;font-family:'Poppins',system-ui,sans-serif;font-size:14.5px;font-weight:600;letter-spacing:.022em;text-transform:none;color:#111827;line-height:1.35}
.st-card-desc{margin:4px 0 0;color:#7b7b7b;font-size:10.5px;font-weight:400}
.st-btn{appearance:none;border:1px solid var(--st-orange);background:var(--st-orange);color:#000;border-radius:10px;height:42px;min-width:132px;padding:0 17px;font-size:12px;font-weight:700;letter-spacing:.014em;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:8px;transition:.18s ease;white-space:nowrap}
.st-btn:hover,.st-btn:focus-visible{background:#111827;border-color:#111827;color:#fff;box-shadow:0 12px 24px rgba(17,24,39,.20);transform:none;outline:none}.st-btn:active,.st-btn.is-clicked{background:#111827;border-color:#111827;color:#fff;box-shadow:none;transform:none}
.st-link{border:0;background:transparent;color:var(--st-orange2);font-size:10px;font-weight:650;cursor:pointer;padding:0}
.st-link:hover,.st-link:focus-visible{background:var(--st-gray-hover);color:#222;text-decoration:none;outline:none}.st-link:active,.st-link.is-clicked{background:var(--st-gray-active);color:#111}
.st-ico{width:18px;height:18px;color:#555;display:inline-flex;align-items:center;justify-content:center;flex:0 0 18px}
.st-ico i{font-size:14px}
.st-orange-ico{width:28px;height:28px;border-radius:9px;background:var(--st-orange3);color:var(--st-orange2);border:1px solid #f3dfcb;display:flex;align-items:center;justify-content:center;flex:0 0 auto}
.st-orange-ico i{font-size:12.5px}
.st-badge{display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:4px 8px;font-size:8.5px;font-weight:650;line-height:1}
.st-badge.green{background:var(--st-green-bg);border:1px solid #d5ecdd;color:var(--st-green)}
.st-badge.orange{background:#fff6ed;border:1px solid #efd3b3;color:var(--st-orange2)}
.st-profile{display:grid;grid-template-columns:minmax(0,1fr) minmax(285px,.8fr);gap:16px}
.st-profile-left{display:flex;align-items:center;gap:18px}
.st-avatar-wrap{position:relative;flex:0 0 auto}
.st-avatar{width:108px;height:108px;border-radius:50%;object-fit:cover;border:4px solid #fff;outline:3px solid var(--st-orange);box-shadow:0 18px 34px rgba(255,122,0,.22);background:radial-gradient(circle at 32% 25%,#ff9a37,#ff5a00 54%,#e04800)}
.st-camera{position:absolute;right:-2px;bottom:5px;width:36px;height:36px;border-radius:50%;background:var(--st-orange);border:3px solid #fff;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:none;cursor:pointer}
.st-camera:hover,.st-camera:focus-visible{background:#111827;color:#fff;border-color:#fff;outline:none}.st-camera:active,.st-camera.is-clicked{background:#111827;color:#fff}
.st-name-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.st-name{margin:0;font-size:15px;font-weight:750}
.st-info{margin-top:6px;color:#737373;font-size:11px;font-weight:400}
.st-info strong{color:#333;font-weight:600}
.st-copy{border:0;background:transparent;color:#888;margin-left:5px;padding:0;cursor:pointer}
.st-copy:hover{color:var(--st-orange2)}
.st-profile-right{border-left:1px solid var(--st-line);padding-left:17px;display:flex;flex-direction:column;justify-content:center;gap:14px}
.st-detail{display:flex;justify-content:space-between;gap:12px;align-items:flex-start}
.st-detail-left{display:flex;gap:10px}
.st-label{margin:0;color:#858585;font-size:10.5px;font-weight:500}
.st-value{margin:2px 0 0;color:#333;font-size:11px;font-weight:600}
.st-address-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
.st-box{position:relative;background:#fff;border:1px solid var(--st-line);border-radius:13px;padding:13px;min-height:105px;transition:.18s ease}
.st-box:hover{border-color:#e2e2e2;box-shadow:none}
.st-box-title{margin:7px 0 0;font-size:11px;font-weight:700}
.st-box-text{margin:7px 0 0;color:#696969;font-size:10px;line-height:1.48;font-weight:400}
.st-kebab{position:absolute;right:9px;top:9px;border:0;background:transparent;color:#8b8b8b;cursor:pointer;width:22px;height:22px}
.st-kebab:hover,.st-kebab:focus-visible{background:var(--st-gray-hover);border-radius:8px;color:#222;outline:none}.st-kebab:active,.st-kebab.is-clicked{background:var(--st-gray-active);color:#111;border-radius:8px}
.st-add-box{border:1px dashed #ded9d3;background:#fff;border-radius:13px;min-height:105px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:9px;font-size:10.5px;font-weight:650;color:#222;cursor:pointer;transition:.18s ease}
.st-add-box:hover,.st-add-box:focus-visible{border-color:var(--st-gray-dark);background:var(--st-gray-hover);color:#222;outline:none;transform:none}.st-add-box:active,.st-add-box.is-clicked{background:var(--st-gray-active);border-color:#c8c8c8;color:#111;transform:none}
.st-add-circle{width:28px;height:28px;border-radius:50%;border:1px solid #ded9d3;background:#fff;display:flex;align-items:center;justify-content:center;color:#222}
.st-add-box:hover .st-add-circle,.st-add-box:focus-visible .st-add-circle{border-color:var(--st-gray-dark);color:#222;background:#fff}
.st-two{display:grid;grid-template-columns:minmax(0,.98fr) minmax(0,1.02fr);gap:16px}
.st-list{display:flex;flex-direction:column;gap:9px}
.st-pay-item{border:1px solid var(--st-line);background:#fff;border-radius:12px;min-height:52px;padding:9px 10px;display:flex;align-items:center;justify-content:space-between;gap:9px;transition:.18s ease;min-width:0}
.st-pay-item:hover{border-color:var(--st-gray-dark);background:var(--st-gray-hover)}
.st-pay-left{display:flex;align-items:center;gap:9px;min-width:0;overflow:hidden}
.st-card-logo{width:43px;height:29px;border:1px solid var(--st-line);border-radius:7px;background:#fff;display:flex;align-items:center;justify-content:center;flex:0 0 43px}
.st-card-logo.visa{background:#2157f3;border-color:#2157f3;color:#fff}
.st-card-logo.master i:first-child{color:#e6483f;margin-right:-7px}
.st-card-logo.master i:last-child{color:#f4a51c}
.st-card-logo.paypal{color:#1d64ca}
.st-card-logo i{font-size:24px}
.st-pay-text{min-width:0;overflow:hidden}
.st-pay-title{margin:0;color:#222;font-size:10.5px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.st-pay-sub{margin:3px 0 0;color:#858585;font-size:9.4px;font-weight:400;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.st-mini{border-radius:999px;border:1px solid #d6efdf;background:var(--st-green-bg);color:var(--st-green);padding:3px 7px;font-size:8px;font-weight:650;white-space:nowrap;flex:0 0 auto}
.st-mini.orange{border-color:#f0d4b4;background:#fff7ef;color:var(--st-orange2)}
.st-pay-bottom{display:grid;grid-template-columns:minmax(0,1fr) 118px;gap:9px}
.st-add-payment{border:1px dashed #ded9d3;background:#fff;border-radius:12px;min-height:63px;padding:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;color:#202020;font-size:9.2px;font-weight:650;line-height:1.2;cursor:pointer;transition:.18s ease;text-align:center;white-space:normal}
.st-add-payment:hover,.st-add-payment:focus-visible{border-color:var(--st-gray-dark);background:var(--st-gray-hover);color:#222;outline:none;transform:none}.st-add-payment:active,.st-add-payment.is-clicked{background:var(--st-gray-active);border-color:#c8c8c8;color:#111;transform:none}
.st-notif-item{display:flex;align-items:center;justify-content:space-between;gap:11px;padding:4.5px 0}
.st-notif-left{display:flex;gap:9px;min-width:0}
.st-notif-title{margin:0;color:#222;font-size:10.5px;font-weight:700}
.st-notif-sub{margin:3px 0 0;color:#858585;font-size:9.2px;font-weight:400;line-height:1.25}
.st-comm-item{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:7px 0;border-bottom:1px solid #f0f1f3;transition:.18s}
.st-comm-item:last-child{border-bottom:0}.st-comm-item:hover{background:rgba(17,24,39,.10);margin-left:-8px;margin-right:-8px;padding-left:8px;padding-right:8px;border-radius:10px}
.st-comm-left{display:flex;align-items:flex-start;gap:10px;min-width:0}.st-comm-title{margin:0;color:#111827;font-family:'Poppins',system-ui,sans-serif;font-size:11px;font-weight:600;letter-spacing:.018em}.st-comm-sub{margin:3px 0 0;color:#858585;font-size:9.7px;font-weight:400;line-height:1.35}
.st-switch{position:relative;width:36px;height:20px;flex:0 0 auto}
.st-switch input{display:none}
.st-slider{position:absolute;inset:0;border-radius:99px;background:#ddd;cursor:pointer;transition:.18s}
.st-slider:before{content:"";position:absolute;width:15px;height:15px;border-radius:50%;left:3px;top:2.5px;background:#fff;box-shadow:0 2px 5px rgba(0,0,0,.16);transition:.18s}
.st-switch input:checked+.st-slider{background:var(--st-green)}
.st-switch input:checked+.st-slider:before{transform:translateX(15px)}
.st-manage{margin-top:9px;width:100%;border:0;background:transparent;text-align:left;color:#222;font-size:9.5px;font-weight:650;cursor:pointer}
.st-manage:hover{color:var(--st-orange2)}
.st-quick-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:9px}
.st-quick{border:1px solid var(--st-line);background:#fff;border-radius:11px;padding:9px 9px;display:flex;align-items:center;justify-content:space-between;gap:8px;text-align:left;min-height:59px;cursor:pointer;transition:.18s ease;min-width:0}
.st-quick:hover,.st-quick:focus-visible{background:var(--st-gray-hover);border-color:var(--st-gray-dark);box-shadow:none;transform:none;outline:none}.st-quick:active,.st-quick.is-clicked{background:var(--st-gray-active);border-color:#c8c8c8;color:#111;box-shadow:none;transform:none}
.st-quick-left{display:flex;align-items:flex-start;gap:8px;min-width:0}
.st-quick-title{margin:0;color:#222;font-size:10px;font-weight:700;line-height:1.15}
.st-quick-sub{margin:3px 0 0;color:#898989;font-size:8.5px;font-weight:400;line-height:1.25}
.st-chev{color:#777;font-size:12px;flex:0 0 auto}
.st-quick:hover .st-chev,.st-quick:hover .st-orange-ico,.st-quick:focus-visible .st-chev,.st-quick:focus-visible .st-orange-ico{color:#222}
.st-pref-row,.st-activity-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:11px 0;border-bottom:1px solid #f1f1f1}
.st-pref-row:last-child,.st-activity-row:last-child{border-bottom:0}
.st-pref-left,.st-activity-left{display:flex;align-items:center;gap:10px}
.st-pref-label,.st-act-label{font-size:10.5px;font-weight:650;color:#222}
.st-select{border:0;background:transparent;color:#5f5f5f;font-size:10.3px;font-weight:400;outline:none;min-width:150px;text-align:right;cursor:pointer}
.st-act-val{text-align:right;color:#626262;font-size:10.4px;font-weight:400}
.st-privacy-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:11px}
.st-privacy{border:1px solid var(--st-line);border-radius:13px;background:#fff;padding:12px;display:flex;align-items:center;justify-content:space-between;gap:9px;text-align:left;cursor:pointer;transition:.18s ease}
.st-privacy:hover,.st-privacy:focus-visible{background:var(--st-gray-hover);border-color:var(--st-gray-dark);transform:none;outline:none}.st-privacy:active,.st-privacy.is-clicked{background:var(--st-gray-active);border-color:#c8c8c8;transform:none}
.st-privacy-left{display:flex;align-items:flex-start;gap:10px}
.st-privacy-title{margin:0;font-size:10.5px;font-weight:700;color:#222}
.st-privacy-sub{margin:3px 0 0;font-size:8.8px;font-weight:400;color:#858585}
.st-settings-grid{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:18px;align-items:start}
.st-setting-main,.st-setting-side{display:flex;flex-direction:column;gap:16px}
.st-sec-row{display:grid;grid-template-columns:52px minmax(0,1fr) auto;align-items:center;gap:16px;padding:17px 18px;border-bottom:1px solid #e8ebf0;background:#fff;transition:.18s ease}
.st-sec-row:last-child{border-bottom:0}
.st-sec-row:hover,.st-privacy-panel-row:hover,.st-setting-action:hover{background:rgba(17,24,39,.10)}
.st-sec-row .st-orange-ico{width:40px;height:40px;border-radius:14px}
.st-sec-title{margin:0;font-family:'Poppins',system-ui,sans-serif;font-size:13px;font-weight:600;color:#111827}
.st-sec-sub{margin:4px 0 0;font-size:10.5px;color:#6b7280;font-weight:400;line-height:1.35}
.st-outline-btn{appearance:none;border:1px solid var(--st-orange);background:#fff;color:var(--st-orange);border-radius:10px;height:38px;min-width:128px;padding:0 15px;font-family:'Poppins',system-ui,sans-serif;font-size:11px;font-weight:600;cursor:pointer;transition:.18s ease}
.st-outline-btn:hover,.st-outline-btn:focus-visible{background:#111827;border-color:#111827;color:#fff;outline:none}
.st-score-ring{width:142px;height:142px;margin:10px auto 14px;border-radius:50%;background:conic-gradient(var(--st-orange) 0 331deg,#e5e7eb 331deg 360deg);display:grid;place-items:center}
.st-score-inner{width:106px;height:106px;border-radius:50%;background:#fff;display:grid;place-items:center;text-align:center}
.st-score-inner strong{font-family:'Poppins',system-ui,sans-serif;font-size:30px;line-height:1;color:#111827}
.st-score-inner span{font-size:11px;font-weight:700;color:var(--st-green)}
.st-check-line{display:flex;align-items:center;gap:10px;font-size:11px;color:#334155;padding:6px 0}
.st-check-line i{color:var(--st-green)}
.st-side-list{display:flex;flex-direction:column;gap:9px}
.st-side-row{display:flex;align-items:center;justify-content:space-between;gap:12px;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;background:#fff}
.st-side-row:hover{background:rgba(17,24,39,.08)}
.st-table-lite{border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;margin-top:12px}
.st-table-lite-row{display:grid;grid-template-columns:minmax(0,1.2fr) minmax(0,.9fr) minmax(0,1fr);gap:12px;align-items:center;padding:10px 12px;border-bottom:1px solid #edf0f4;font-size:10.5px;color:#475569}
.st-table-lite-row:last-child{border-bottom:0}
.st-table-lite-row strong{color:#111827;font-weight:650}
.st-tip-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-top:13px}
.st-tip{display:flex;gap:10px;align-items:flex-start;border:1px solid #e5e7eb;border-radius:12px;padding:11px;background:#fff}
.st-privacy-panel-row{display:grid;grid-template-columns:220px minmax(0,1fr);gap:18px;align-items:center;padding:17px;border-bottom:1px solid #e8ebf0;background:#fff;transition:.18s ease}
.st-privacy-panel-row:last-child{border-bottom:0}
.st-privacy-control{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:8px 0}
.st-privacy-control select{height:38px;border:1px solid #dfe3ea;border-radius:10px;background:#fff;padding:0 12px;font-size:11px;font-weight:650;min-width:140px}
.st-status-pill{border-radius:999px;padding:5px 9px;font-size:9px;font-weight:700;background:#edf8f1;color:var(--st-green);white-space:nowrap}
.st-status-pill.gray{background:#f2f4f7;color:#64748b}
.st-wide-actions{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.st-summary-strip{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;border:1px solid #e5e7eb;border-radius:12px;padding:12px;background:#fff}
.st-summary-item{display:flex;align-items:center;gap:9px;border-right:1px solid #eef1f5;padding-right:8px}
.st-summary-item:last-child{border-right:0}
.st-setting-action{display:flex;align-items:center;justify-content:space-between;gap:12px;border-bottom:1px solid #eef1f5;padding:12px 0;cursor:pointer;transition:.18s ease}
.st-setting-action:last-child{border-bottom:0}
.st-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px}
.st-field{display:flex;flex-direction:column;gap:6px}
.st-field label{font-size:9.5px;text-transform:uppercase;letter-spacing:.05em;color:#777;font-weight:650}
.st-input,.st-textarea{width:100%;border:1px solid var(--st-line2);background:#fff;border-radius:11px;padding:10px 11px;color:#222;font-size:11.5px;font-weight:400;outline:none}
.st-input:focus,.st-textarea:focus{border-color:var(--st-gray-dark);box-shadow:0 0 0 3px rgba(0,0,0,.06)}
.st-textarea{min-height:100px;resize:vertical}
.st-actions{display:flex;justify-content:flex-end;gap:9px;margin-top:16px}
.st-modal{position:fixed;inset:0;z-index:9999;display:none}
.st-modal.active{display:block}
.st-modal-bg{position:absolute;inset:0;background:rgba(0,0,0,.33);backdrop-filter:blur(4px)}
.st-modal-shell{position:relative;min-height:100vh;padding:20px;display:flex;align-items:center;justify-content:center}
.st-modal-card{width:100%;max-width:545px;background:#fff;border-radius:18px;border:1px solid var(--st-line);box-shadow:0 20px 60px rgba(0,0,0,.16);overflow:hidden}
.st-modal-head{padding:15px 17px;border-bottom:1px solid #f1f1f1;display:flex;align-items:flex-start;justify-content:space-between;gap:10px}
.st-modal-title{margin:0;font-size:12px;font-weight:750;text-transform:uppercase;letter-spacing:.06em}
.st-modal-desc{margin:4px 0 0;color:#868686;font-size:10.5px;font-weight:400}
.st-close{width:33px;height:33px;border-radius:50%;border:1px solid var(--st-line);background:#fff;color:#666;cursor:pointer;font-size:18px}
.st-close:hover,.st-close:focus-visible{background:var(--st-gray-hover);color:#222;border-color:var(--st-gray-dark);outline:none}.st-close:active,.st-close.is-clicked{background:var(--st-gray-active);color:#111}
.st-modal-body{padding:17px}
.st-toast{position:fixed;left:50%;top:110px;background:#242424;color:#fff;border-radius:12px;padding:12px 15px;font-size:12px;font-weight:700;box-shadow:0 18px 50px rgba(17,24,39,.24);opacity:0;transform:translate(-50%,-12px);pointer-events:none;transition:.2s;z-index:10000}
.st-toast.show{opacity:1;transform:translate(-50%,0)}
.st-footer{margin:16px 0 0;text-align:center;color:#bfbfbf;font-size:8.5px;font-weight:650;letter-spacing:.16em;text-transform:uppercase}
.st-page button,.st-page select,.st-page input,.st-page textarea{font-family:inherit}
.st-page button,.st-page .st-select{transform:none!important}
.st-tab,.st-btn,.st-link,.st-camera,.st-kebab,.st-add-box,.st-add-payment,.st-quick,.st-privacy,.st-close,.st-select{transition:background-color .16s ease,border-color .16s ease,color .16s ease,box-shadow .16s ease}
.st-tab:hover,.st-tab:focus-visible{background:var(--st-gray-hover);color:#222;outline:none}
.st-tab:active,.st-tab.is-clicked{background:var(--st-gray-active);color:#111;transform:none}
.st-tab.active:hover{color:var(--st-orange2)}
.st-link{border-radius:8px;min-height:24px;padding:0 7px;display:inline-flex;align-items:center;justify-content:center}
.st-pref-row{border-radius:10px;padding-left:8px;padding-right:8px;transition:background-color .16s ease,border-color .16s ease}
.st-pref-row:hover,.st-pref-row:focus-within,.st-pref-row.is-clicked{background:var(--st-gray-hover)}
.st-pref-row:active{background:var(--st-gray-active)}
.st-right-slim .st-body{padding:13px 15px}
.st-right-slim .st-head{margin-bottom:9px}
.st-right-slim .st-card-desc{margin-top:2px;line-height:1.35}
.st-right-slim .st-comm-item{padding:5.5px 0}
.st-right-slim .st-comm-left{gap:8px}
.st-right-slim .st-comm-sub{margin-top:2px;font-size:9.2px;line-height:1.25}
.st-right-slim .st-pref-row,.st-right-slim .st-activity-row{padding-top:7px;padding-bottom:7px}
.st-right-slim .st-orange-ico{width:27px;height:27px}
.st-select{border-radius:8px;padding:5px 7px}
.st-select:hover,.st-select:focus{background:var(--st-gray-hover);color:#222}
.st-orange-ico{transition:background-color .16s ease,border-color .16s ease,color .16s ease}
.st-quick:hover .st-orange-ico,.st-privacy:hover .st-orange-ico,.st-pref-row:hover .st-orange-ico{background:#fff;border-color:var(--st-gray-dark);color:#222}
.st-clickable-cover{background:var(--st-gray-active)!important;border-color:#c8c8c8!important;color:#111!important;transform:none!important;box-shadow:none!important}

@media(max-width:1320px){.st-grid{grid-template-columns:minmax(0,1.15fr) minmax(350px,.85fr)}.st-right-slim{width:100%}}
@media(max-width:1260px){.st-grid,.st-profile,.st-two,.st-settings-grid{grid-template-columns:1fr}.st-profile-right{border-left:0;border-top:1px solid var(--st-line);padding-left:0;padding-top:14px}.st-quick-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.st-right-slim{max-width:none}}
@media(max-width:860px){.st-page{padding:16px 12px 28px}.st-address-grid,.st-privacy-grid,.st-form-grid,.st-quick-grid,.st-tip-grid,.st-summary-strip,.st-wide-actions{grid-template-columns:1fr}.st-profile-left{align-items:flex-start;flex-direction:column}.st-select{min-width:120px}.st-tabs{gap:20px}.st-pay-bottom{grid-template-columns:1fr}.st-sec-row,.st-privacy-panel-row{grid-template-columns:1fr}.st-outline-btn,.st-btn{width:100%}}
.st-top{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin:0 0 16px}
.st-title-wrap{display:flex;align-items:flex-start;gap:10px}.st-title-wrap:before{content:'';width:18px;height:4px;margin-top:8px;border-radius:999px;background:var(--st-orange);flex:0 0 auto}
.st-date{height:42px;min-width:178px;padding:0 15px;border:1px solid #111827;border-radius:8px;background:#fff;color:#111827;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:12px;font-weight:700;line-height:1;white-space:nowrap}
.st-date i{font-size:15px;color:#111827}.st-wrap{max-width:1490px!important}.st-grid,.st-settings-grid{align-items:stretch}
@media(max-width:860px){.st-top{display:grid}.st-date{width:100%}}

/* Settings UI alignment update */
.st-page{padding:0 38px 38px}
.st-wrap{max-width:1480px!important}
.st-card,.st-main-box{border:1px solid #111827;border-radius:14px;background:#fff;box-shadow:none}
.st-card:hover,.st-main-box:hover{background:rgba(17,24,39,.09);box-shadow:none;border-color:#111827}
.st-plain{border:0!important;background:transparent!important;box-shadow:none!important;overflow:visible!important}
.st-plain:hover{background:transparent!important;box-shadow:none!important}
.st-plain .st-body{padding:0}
.st-section-line{height:1px;background:#111827;margin:17px 0}
.st-soft-line{height:1px;background:#e5e7eb;margin:10px 0}
.st-no-box-list{display:flex;flex-direction:column;gap:9px}
.st-no-box-item{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid #e8ebf0}
.st-no-box-item:last-child{border-bottom:0}
.st-stat-row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
.st-stat-tile{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #edf0f4}
.st-action-list{display:flex;flex-direction:column;gap:0}
.st-channel-row{display:grid;grid-template-columns:minmax(0,1.2fr) 84px 84px 84px 34px;gap:10px;align-items:center;padding:10px 0;border-bottom:1px solid #e8ebf0}
.st-channel-row:last-child{border-bottom:0}
.st-channel-head{font-size:9px;text-transform:uppercase;color:#64748b;font-weight:700;text-align:center}
.st-inline-pill{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:3px 7px;background:#edf8f1;color:var(--st-green);font-size:8px;font-weight:700}
.st-setting-side .st-plain .st-body{padding:0 0 10px}
.st-setting-side .st-plain{padding:0}
#panel-overview .st-stack>.st-card:nth-of-type(1) .st-body{padding-bottom:17px}
#panel-overview .st-two{border:1px solid #111827;border-radius:14px;padding:15px;background:#fff}
#panel-overview .st-two>.st-card{border:0!important;background:transparent!important;box-shadow:none!important}
#panel-overview .st-two>.st-card .st-body{padding:0}
#panel-overview>.st-grid{grid-template-columns:minmax(0,1.15fr) minmax(350px,.85fr)!important;gap:17px!important;align-items:start!important}
#panel-overview>.st-grid>.st-stack{min-width:0;width:100%;display:flex!important;flex-direction:column!important;gap:16px!important}
#panel-overview .overview-profile-box,
#panel-overview .overview-payment-box{border:1px solid #111827!important;border-radius:14px!important;background:#fff!important;box-shadow:none!important;overflow:hidden!important}
#panel-overview .overview-profile-box:hover,
#panel-overview .overview-payment-box:hover{background:#fff!important}
#panel-overview .overview-profile-box .st-section-line{margin-left:-17px;margin-right:-17px}
#panel-overview .overview-payment-box{display:grid!important;grid-template-columns:minmax(0,.96fr) minmax(0,1.04fr)!important;gap:0!important;padding:15px!important}
#panel-overview .overview-payment-box>.st-card{border:0!important;background:transparent!important;box-shadow:none!important;border-radius:0!important}
#panel-overview .overview-payment-box>.st-card:first-child{border-right:1px solid #e5e7eb!important;padding-right:15px}
#panel-overview .overview-payment-box>.st-card:last-child{padding-left:15px}
#panel-overview .overview-payment-box>.st-card .st-body{padding:0!important}
#panel-overview .overview-plain-panel{border:0!important;background:transparent!important;box-shadow:none!important;border-radius:0!important;overflow:visible!important}
#panel-overview .overview-plain-panel:hover{background:transparent!important}
#panel-overview .overview-plain-panel>.st-body{padding:0!important}
#panel-overview .overview-quick-panel .st-head{margin-bottom:10px}
#panel-overview .overview-quick-panel .st-quick-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:8px!important}
#panel-overview .overview-quick-panel .st-quick,
#panel-overview .overview-privacy-panel .st-privacy{border:0!important;border-bottom:1px solid #e8ebf0!important;border-radius:0!important;background:transparent!important;min-height:52px!important;padding:8px 0!important}
#panel-overview .overview-quick-panel .st-quick:hover,
#panel-overview .overview-privacy-panel .st-privacy:hover{background:rgba(17,24,39,.08)!important;border-radius:10px!important;padding-left:8px!important;padding-right:8px!important}
#panel-overview .overview-comm-panel{max-width:none!important;width:100%!important}
#panel-overview .overview-activity-panel{max-width:none!important;width:100%!important}
#panel-overview .overview-prefs-panel{max-width:none!important;width:100%!important}
#panel-overview .overview-prefs-panel .st-pref-row,
#panel-overview .overview-activity-panel .st-activity-row{border-bottom:1px solid #e8ebf0!important;border-radius:0!important;background:transparent!important;padding-left:0!important;padding-right:0!important}
#panel-profile .st-profile-grid{grid-template-columns:minmax(0,1fr) 340px!important;gap:18px!important}
#panel-profile .st-profile-side .st-card{border:0!important;background:transparent!important;box-shadow:none!important;border-radius:0!important}
#panel-profile .st-profile-side .st-card>.st-body{padding:0!important}
#panel-security .st-settings-grid,
#panel-notifications .st-notif-layout{grid-template-columns:minmax(0,1fr) 300px!important;gap:16px!important;align-items:start!important}
#panel-security .st-setting-side,
#panel-notifications .st-notif-side{gap:14px!important}
#panel-profile .st-profile-grid{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:18px;align-items:start}
#panel-profile .st-profile-side{display:flex;flex-direction:column;gap:16px}
#panel-profile .st-card-title,#panel-security .st-card-title,#panel-notifications .st-card-title,#panel-payments .st-card-title{font-family:'Poppins',system-ui,sans-serif;font-weight:600}
#panel-profile .st-card:first-child,#panel-profile .st-connected-card,#panel-security .st-setting-main>.st-card:first-child,#panel-security .st-login-tips-box,#panel-notifications .st-card:first-child,#panel-payments .st-billing-box{border-color:#111827}
#panel-security .st-setting-side>.st-card{border:0!important;background:transparent!important;box-shadow:none!important}
#panel-security .st-setting-side>.st-card .st-body{padding:0}
#panel-security .st-setting-side .st-side-row{border:0!important;border-bottom:1px solid #e8ebf0!important;border-radius:0!important;background:transparent!important;padding-left:0;padding-right:0}
#panel-security .st-tip{border:0;border-bottom:1px solid #e8ebf0;border-radius:0;background:transparent}
#panel-notifications .st-notif-layout,#panel-payments .st-pay-layout{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:18px;align-items:start}
#panel-notifications .st-notif-side,#panel-payments .st-pay-side{display:flex;flex-direction:column;gap:16px}
#panel-notifications .st-notif-side>.st-card,#panel-payments .st-pay-side>.st-card{border:0!important;background:transparent!important;box-shadow:none!important}
#panel-notifications .st-notif-side>.st-card .st-body,#panel-payments .st-pay-side>.st-card .st-body{padding:0}
.st-btn,.st-outline-btn{border:0!important;background:var(--st-orange)!important;color:#000!important;height:42px;min-width:132px}
.st-btn:hover,.st-outline-btn:hover,.st-btn:focus-visible,.st-outline-btn:focus-visible{background:#111827!important;color:#fff!important}
.st-switch input:checked+.st-slider{background:#16a34a!important}
@media(max-width:1260px){#panel-profile .st-profile-grid,#panel-notifications .st-notif-layout,#panel-payments .st-pay-layout{grid-template-columns:1fr}.st-channel-row{grid-template-columns:minmax(0,1fr) 56px 56px 56px 24px}}
@media(max-width:860px){.st-page{padding:16px 14px 30px}.st-stat-row{grid-template-columns:1fr}.st-channel-row{grid-template-columns:1fr 1fr 1fr}.st-channel-head{text-align:left}}

/* Overview uses the original two-column boxed stack. */
#panel-overview>.st-grid{display:grid!important;grid-template-columns:minmax(0,1.15fr) minmax(350px,.85fr)!important;gap:17px!important;align-items:start!important}
#panel-overview>.st-grid>.st-stack{display:flex!important;flex-direction:column!important;gap:16px!important}
#panel-overview .overview-profile-box,#panel-overview .overview-payment-box,#panel-overview .overview-privacy-panel,#panel-overview .overview-quick-panel,#panel-overview .overview-comm-panel,#panel-overview .overview-prefs-panel,#panel-overview .overview-activity-panel{grid-column:auto!important;grid-row:auto!important}
#panel-overview .overview-profile-box{border:1px solid #111827!important;border-radius:14px!important;background:#fff!important}
#panel-overview .overview-payment-box{border:1px solid #111827!important;border-radius:14px!important;background:#fff!important;display:grid!important;grid-template-columns:1fr 1fr!important;gap:0!important;padding:15px!important}
#panel-overview .overview-payment-box>.st-card{border:0!important;background:transparent!important;box-shadow:none!important}
#panel-overview .overview-payment-box>.st-card:first-child{border-right:1px solid #e5e7eb!important;padding-right:15px!important}
#panel-overview .overview-payment-box>.st-card:last-child{padding-left:15px!important}
#panel-overview .overview-plain-panel{border:0!important;background:transparent!important;box-shadow:none!important;overflow:visible!important}
#panel-overview .overview-plain-panel>.st-body{padding:0!important}
#panel-overview .overview-quick-panel .st-quick-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:8px!important}
#panel-overview .overview-quick-panel .st-quick,
#panel-overview .overview-privacy-panel .st-privacy{border:0!important;border-bottom:1px solid #e8ebf0!important;border-radius:0!important;background:transparent!important;padding:8px 0!important;min-height:50px!important}
#panel-overview .overview-comm-panel .st-comm-item,
#panel-overview .overview-prefs-panel .st-pref-row,
#panel-overview .overview-activity-panel .st-activity-row{padding:8px 0!important;border-bottom:1px solid #e8ebf0!important}
#panel-overview .overview-profile-box .st-address-grid{grid-template-columns:repeat(3,minmax(0,1fr))!important}
#panel-overview .overview-profile-box .st-box,
#panel-overview .overview-profile-box .st-add-box{min-height:104px!important}
#panel-security .st-sec-row{padding:11px 14px!important;grid-template-columns:44px minmax(0,1fr) auto!important}
#panel-security .st-sec-row .st-orange-ico{width:34px!important;height:34px!important;border-radius:11px!important}
#panel-security .st-setting-side .st-score-ring,
#panel-notifications .st-notif-side .st-score-ring{width:98px!important;height:98px!important;margin:8px auto!important}
#panel-security .st-setting-side .st-score-inner,
#panel-notifications .st-notif-side .st-score-inner{width:74px!important;height:74px!important}
#panel-security .st-setting-side .st-score-inner strong,
#panel-notifications .st-notif-side .st-score-inner strong{font-size:22px!important}
#panel-security .st-setting-side .st-check-line,
#panel-notifications .st-notif-side .st-no-box-item,
#panel-notifications .st-notif-side .st-setting-action{padding:6px 0!important;font-size:10.5px!important}
#panel-security .st-login-tips-box .st-body{padding:12px 14px!important}
#panel-security .st-tip-grid{gap:8px!important}
#panel-security .st-tip{padding:8px!important}
#panel-notifications .st-card:first-child .st-body{padding:12px 14px!important}
#panel-notifications .st-summary-strip{gap:6px!important}
#panel-notifications .st-summary-item{gap:7px!important}
#panel-notifications .st-channel-row{padding:7px 0!important;grid-template-columns:minmax(0,1.2fr) 58px 58px 58px 24px!important}
#panel-notifications .st-channel-row .st-orange-ico{width:30px!important;height:30px!important}
#panel-notifications .st-channel-row .st-sec-title{font-size:11px!important}
#panel-notifications .st-channel-row .st-sec-sub{font-size:9px!important;margin-top:2px!important}
.st-profile-hero{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(360px,.95fr);gap:16px;align-items:center}
.st-profile-hero-main{display:flex;align-items:center;gap:15px;min-width:0}
.st-profile-hero-meta{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;border-left:1px solid #e5e7eb;padding-left:16px}
.st-profile-mini{display:flex;align-items:flex-start;gap:8px;min-width:0}
.st-profile-mini .st-orange-ico{width:26px;height:26px;border-radius:8px}
.st-profile-mini strong{display:block;font-size:10.5px;font-weight:700;color:#111827}
.st-profile-mini span{display:block;font-size:9.5px;color:#6b7280;margin-top:2px}
.st-profile-block-title{display:flex;align-items:center;gap:8px;margin:14px 0 10px}
.st-profile-block-title .st-orange-ico{width:24px;height:24px}
#panel-profile .st-form-grid{gap:10px!important}
#panel-profile .st-field label{font-size:8.8px!important}
#panel-profile .st-input{height:38px!important;padding:8px 10px!important;font-size:10.5px!important}
#panel-profile .st-summary-strip .st-summary-item{padding:8px 10px;border:1px solid #e5e7eb;border-radius:10px}
@media(max-width:1260px){.st-profile-hero{grid-template-columns:1fr}.st-profile-hero-meta{border-left:0;border-top:1px solid #e5e7eb;padding-left:0;padding-top:12px}}
#panel-overview .overview-profile-box .st-body{padding:13px 15px!important}
#panel-overview .overview-profile-box .st-address-grid{gap:9px!important}
#panel-overview .overview-profile-box .st-box,
#panel-overview .overview-profile-box .st-add-box{min-height:86px!important;padding:10px!important;border-radius:11px!important}
#panel-overview .overview-profile-box .st-box-text{font-size:9px!important;line-height:1.35!important;margin-top:5px!important}
#panel-overview .overview-profile-box .st-box-title{font-size:10px!important;margin-top:5px!important}
#panel-overview .overview-profile-box .st-add-circle{width:24px!important;height:24px!important}
#panel-overview .overview-profile-box .st-section-line{margin-top:12px!important;margin-bottom:12px!important}
#panel-overview .overview-payment-box{padding:12px!important}
#panel-overview .overview-payment-box .st-list{gap:7px!important}
#panel-overview .overview-payment-box .st-pay-item{min-height:44px!important;padding:7px 9px!important}
#panel-overview .overview-payment-box .st-card-logo{width:38px!important;height:25px!important}
#panel-overview .overview-payment-box .st-notif-item{padding:3px 0!important}
#panel-profile .st-profile-side{gap:10px!important}
#panel-profile .st-profile-side .st-head{margin-bottom:6px!important}
#panel-profile .st-profile-side .st-no-box-list{gap:3px!important}
#panel-profile .st-profile-side .st-no-box-item{padding:5px 0!important}
#panel-profile .st-profile-side .st-btn{height:36px!important}
#panel-profile .st-profile-side .st-score-ring{width:88px!important;height:88px!important;margin:6px auto!important}
#panel-profile .st-profile-side .st-score-inner{width:66px!important;height:66px!important}
#panel-profile .st-profile-side .st-score-inner strong{font-size:20px!important}
#panel-security .st-setting-side{gap:8px!important}
#panel-security .st-setting-side .st-card-title,
#panel-notifications .st-notif-side .st-card-title{font-size:13px!important}
#panel-security .st-setting-side .st-card-desc,
#panel-notifications .st-notif-side .st-card-desc{font-size:9.5px!important;line-height:1.25!important}
#panel-security .st-setting-side .st-side-list,
#panel-notifications .st-notif-side .st-no-box-list{gap:3px!important;margin-top:7px!important}
#panel-security .st-setting-side .st-side-row,
#panel-security .st-setting-side .st-setting-action,
#panel-notifications .st-notif-side .st-setting-action,
#panel-notifications .st-notif-side .st-no-box-item{padding:5px 0!important}
#panel-security .st-setting-side .st-btn,
#panel-notifications .st-notif-side .st-btn{height:36px!important;margin-top:8px!important}
#panel-notifications .st-notif-side{gap:8px!important}
#panel-notifications .st-card:first-child .st-body{padding:10px 12px!important}
#panel-notifications .st-channel-row{padding:5px 0!important}
#panel-notifications .st-summary-strip{margin-bottom:8px!important}
@media(min-width:981px){#panel-overview>.st-grid{display:grid!important;grid-template-columns:minmax(0,1.15fr) minmax(350px,.85fr)!important;gap:17px!important;align-items:start!important}#panel-overview>.st-grid>.st-stack{display:flex!important;flex-direction:column!important;gap:16px!important}#panel-overview .overview-profile-box,#panel-overview .overview-payment-box,#panel-overview .overview-privacy-panel,#panel-overview .overview-quick-panel,#panel-overview .overview-comm-panel,#panel-overview .overview-prefs-panel,#panel-overview .overview-activity-panel{grid-column:auto!important;grid-row:auto!important}}
@media(max-width:980px){#panel-overview>.st-grid{grid-template-columns:1fr!important}#panel-overview .overview-payment-box{grid-template-columns:1fr!important}}
#panel-overview .overview-privacy-panel,
#panel-overview .overview-quick-panel,
#panel-overview .overview-comm-panel,
#panel-overview .overview-prefs-panel,
#panel-overview .overview-activity-panel{border:1px solid #111827!important;border-radius:14px!important;background:#fff!important;box-shadow:none!important;overflow:hidden!important}
#panel-overview .overview-privacy-panel>.st-body,
#panel-overview .overview-quick-panel>.st-body,
#panel-overview .overview-comm-panel>.st-body,
#panel-overview .overview-prefs-panel>.st-body,
#panel-overview .overview-activity-panel>.st-body{padding:13px 15px!important}
#panel-overview .overview-quick-panel .st-quick,
#panel-overview .overview-privacy-panel .st-privacy{border:1px solid #111827!important;border-radius:11px!important;background:#fff!important;padding:9px!important;min-height:56px!important}
#panel-overview .overview-payment-box{padding:0!important;gap:16px!important}
#panel-overview .overview-payment-box>.st-card .st-body{padding:13px 15px!important}
#panel-profile .st-profile-grid,
#panel-security .st-settings-grid,
#panel-notifications .st-notif-layout{grid-template-columns:minmax(0,1fr) 300px!important;gap:16px!important;align-items:start!important}
#panel-profile .st-profile-side,
#panel-security .st-setting-side,
#panel-notifications .st-notif-side{gap:8px!important}
#panel-profile .st-profile-side .st-card-title,
#panel-security .st-setting-side .st-card-title,
#panel-notifications .st-notif-side .st-card-title{font-size:13px!important}
#panel-profile .st-profile-side .st-card-desc,
#panel-security .st-setting-side .st-card-desc,
#panel-notifications .st-notif-side .st-card-desc{font-size:9.5px!important;line-height:1.25!important}
.st-page{padding:0 0 34px!important}
.st-wrap{max-width:1490px!important;margin:0 auto!important;width:100%!important}
.st-top{margin-top:0!important}
#panel-overview>.st-grid{grid-template-columns:minmax(0,1.2fr) minmax(360px,.8fr)!important}
#panel-overview>.st-grid{display:grid!important;grid-template-columns:minmax(0,1.15fr) minmax(350px,.85fr)!important;gap:17px!important;align-items:start!important}
#panel-overview>.st-grid>.st-stack{display:flex!important;flex-direction:column!important;gap:16px!important;min-width:0!important;width:100%!important}
#panel-overview .overview-profile-box,
#panel-overview .overview-payment-box,
#panel-overview .overview-privacy-panel,
#panel-overview .overview-quick-panel,
#panel-overview .overview-comm-panel,
#panel-overview .overview-prefs-panel,
#panel-overview .overview-activity-panel{grid-column:auto!important;grid-row:auto!important}
@media(max-width:760px){#panel-overview>.st-grid{grid-template-columns:1fr!important}}

/* Settings tab grouping: Notifications style applied to all remaining tabs */
.st-section-layout{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:16px;align-items:start}
.st-section-main,.st-section-side{display:flex;flex-direction:column;gap:12px;min-width:0}
.st-main-group{border:1px solid #111827!important;border-radius:14px!important;background:#fff!important;box-shadow:none!important;overflow:hidden!important}
.st-main-group:hover{background:#fff!important;box-shadow:none!important;border-color:#111827!important}
.st-main-group>.st-body{padding:13px 15px!important}
.st-plain-panel{border:0!important;background:transparent!important;box-shadow:none!important;border-radius:0!important;overflow:visible!important}
.st-plain-panel:hover{background:transparent!important;box-shadow:none!important}
.st-plain-panel>.st-body{padding:0!important}
.st-line-row{display:grid;grid-template-columns:38px minmax(0,1fr) auto;gap:11px;align-items:center;padding:9px 0;border-bottom:1px solid #e8ebf0;background:transparent}
.st-line-row:last-child{border-bottom:0}
.st-line-row:hover{background:rgba(17,24,39,.08);margin-left:-8px;margin-right:-8px;padding-left:8px;padding-right:8px;border-radius:10px}
.st-row-actions{display:flex;align-items:center;gap:7px;justify-content:flex-end;flex-wrap:wrap}
.st-mini-tabs{display:flex;align-items:center;gap:26px;border-bottom:1px solid #e8ebf0;margin:2px 0 8px;padding-bottom:8px}
.st-mini-tabs span{font-size:10px;font-weight:700;color:#64748b}
.st-mini-tabs .active{color:#111827}
.st-map-mini{height:72px;border-radius:10px;background:linear-gradient(135deg,#eaf8ef,#eef4ff);display:flex;align-items:center;justify-content:center;color:var(--st-green);font-size:24px;min-width:190px}
.st-tip-inline{display:flex;align-items:center;gap:9px;border-radius:10px;background:#f3f8ff;color:#2563eb;font-size:10.5px;padding:9px 10px;margin-top:9px}
.st-right-metric{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid #e8ebf0;font-size:11px}
.st-right-metric:last-child{border-bottom:0}
.st-section-layout .st-section-side .st-score-ring{width:98px!important;height:98px!important;margin:8px auto!important}
.st-section-layout .st-section-side .st-score-inner{width:74px!important;height:74px!important}
.st-section-layout .st-section-side .st-score-inner strong{font-size:22px!important}
.st-section-layout .st-section-side .st-card-title{font-size:13px!important}
.st-section-layout .st-section-side .st-card-desc{font-size:9.5px!important;line-height:1.25!important}
#panel-payments .st-billing-box{border:1px solid #111827!important;border-radius:14px!important;background:#fff!important}
#panel-payments .st-pay-side>.st-card{border:0!important;background:transparent!important;box-shadow:none!important}
#panel-payments .st-pay-side>.st-card>.st-body{padding:0!important}
#panel-addresses .st-address-grid{grid-template-columns:1fr!important;gap:0!important}
#panel-addresses .st-address-row{grid-template-columns:38px minmax(0,1fr) minmax(190px,auto)}
#panel-preferences .st-section-main .st-plain-panel,
#panel-privacy .st-section-main .st-plain-panel{border:0!important;background:transparent!important}
#panel-privacy .st-privacy-panel-row{padding:12px 0!important;grid-template-columns:210px minmax(0,1fr)!important}
#panel-privacy .st-privacy-panel-row:first-child{padding-top:0!important}
#panel-privacy .st-privacy-panel-row:last-child{padding-bottom:0!important}
@media(max-width:1260px){.st-section-layout,#panel-payments .st-pay-layout{grid-template-columns:1fr!important}.st-map-mini{min-width:0}}
@media(max-width:860px){.st-section-layout{gap:14px}.st-mini-tabs{gap:14px;flex-wrap:wrap}.st-line-row,#panel-addresses .st-address-row{grid-template-columns:1fr}.st-row-actions{justify-content:flex-start}.st-map-mini{width:100%}#panel-privacy .st-privacy-panel-row{grid-template-columns:1fr!important}}

/* Keep Overview bottom panels in the right column, matching the original Settings UI */
@media(min-width:1261px){
#panel-overview>.st-grid{display:grid!important;grid-template-columns:minmax(0,1fr) 540px!important;gap:18px!important;align-items:start!important}
#panel-overview>.st-grid>.st-stack:first-child{grid-column:1!important;grid-row:1!important;min-width:0!important;width:100%!important}
#panel-overview>.st-grid>.st-stack:nth-child(2){grid-column:2!important;grid-row:1!important;min-width:0!important;width:100%!important;max-width:540px!important;align-self:start!important}
#panel-overview .overview-quick-panel,
#panel-overview .overview-comm-panel,
#panel-overview .overview-prefs-panel,
#panel-overview .overview-activity-panel{width:100%!important;max-width:none!important}
#panel-overview .overview-quick-panel .st-quick-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}
}
@media(max-width:1260px){
#panel-overview>.st-grid{grid-template-columns:1fr!important}
#panel-overview>.st-grid>.st-stack:nth-child(2){max-width:none!important;width:100%!important}
}
</style>

<div class="st-page">
<div class="st-wrap">
<div class="st-top">
<div class="st-title-wrap"><div><h1 class="st-title">Settings</h1>
<p class="st-subtitle">Manage your account preferences, profile, and security settings.</p></div></div>
<div class="st-date"><i class="fa-regular fa-calendar-days"></i><span>Today is {{ now()->format('M d, Y') }}</span></div>
</div>

<div class="st-tabs">
<button class="st-tab active" data-tab="overview">Overview</button>
<button class="st-tab" data-tab="profile">Profile</button>
<button class="st-tab" data-tab="security">Security</button>
<button class="st-tab" data-tab="notifications">Notifications</button>
<button class="st-tab" data-tab="payments">Payments</button>
<button class="st-tab" data-tab="addresses">Addresses</button>
<button class="st-tab" data-tab="preferences">Preferences</button>
<button class="st-tab" data-tab="privacy">Privacy</button>
</div>

<section id="panel-overview" class="st-panel active">
<div class="st-grid">
<div class="st-stack">

<div class="st-card overview-profile-box">
<div class="st-body">
<div class="st-head">
<h2 class="st-card-title">Profile Summary</h2>
<button class="st-btn" type="button" data-modal-open="profileModal">Edit Profile</button>
</div>
<div class="st-profile">
<div class="st-profile-left">
<div class="st-avatar-wrap">
<img class="st-avatar" src="{{ $settingsPhoto ?: 'https://i.pravatar.cc/220?img=47' }}" alt="Profile">
<button class="st-camera" type="button" aria-label="Change photo" onclick="openPhotoPicker()"><i class="fa-solid fa-camera"></i></button><input id="settingsPhotoInput" type="file" accept="image/*" hidden>
</div>
<div>
<div class="st-name-row">
<h3 id="profileDisplayName" class="st-name">{{ $settingsName ?: 'Not set' }}</h3>
<span class="st-badge green"><i class="fa-solid fa-check"></i> Verified</span>
</div>
<div id="profileDisplayEmail" class="st-info">{{ $settingsEmail ?: 'Not set' }}</div>
<div class="st-info">Customer ID: <strong>{{ $settingsCustomerId }}</strong><button class="st-copy" type="button" onclick="copySettingsText(@js($settingsCustomerId))"><i class="fa-regular fa-copy"></i></button></div>
<div class="st-info">Member since {{ optional($settingsUser->created_at)->format('F j, Y') ?: 'Not set' }}</div>
<span class="st-badge orange">Premium Member</span>
</div>
</div>
<div class="st-profile-right">
<div class="st-detail">
<div class="st-detail-left"><span class="st-ico"><i class="fa-solid fa-phone"></i></span><div><p class="st-label">Phone Number</p><p id="profileDisplayPhone" class="st-value">{{ $settingsPhone ?: 'Not set' }}</p></div></div>
<button class="st-link" type="button" data-modal-open="profileModal">Edit</button>
</div>
<div class="st-detail">
<div class="st-detail-left"><span class="st-ico"><i class="fa-regular fa-calendar"></i></span><div><p class="st-label">Date of Birth</p><p id="profileDisplayBirth" class="st-value">{{ $settingsBirthdate ?: 'Not set' }}</p></div></div>
<button class="st-link" type="button" data-modal-open="profileModal">Edit</button>
</div>
<div class="st-detail">
<div class="st-detail-left"><span class="st-ico"><i class="fa-regular fa-building"></i></span><div><p class="st-label">Company (Optional)</p><p id="profileDisplayCompany" class="st-value">{{ $settingsCompany ?: 'Not set' }}</p></div></div>
<button class="st-link" type="button" data-modal-open="profileModal">Edit</button>
</div>
</div>
</div>
</div>
<div class="st-section-line"></div>
<div class="st-head">
<h2 class="st-card-title">Address Book</h2>
<button class="st-btn" type="button" data-modal-open="addressModal">Manage Addresses</button>
</div>
<div class="st-address-grid">
<div class="st-box">
<button class="st-kebab" type="button" onclick="openAddressActions('Home Address')" aria-label="Home address actions"><i class="fa-solid fa-ellipsis-vertical"></i></button>
<span class="st-mini orange">Primary</span>
<h3 class="st-box-title">Home Address</h3>
<p class="st-box-text">123 Printify Avenue<br>Makati City, Metro Manila 1200<br>Philippines<br>+63 912 345 6789</p>
</div>
<div class="st-box">
<button class="st-kebab" type="button" onclick="openAddressActions('Work')" aria-label="Work address actions"><i class="fa-solid fa-ellipsis-vertical"></i></button>
<h3 class="st-box-title">Work</h3>
<p class="st-box-text">45 Timog Avenue<br>Quezon City, Metro Manila 1103<br>Philippines<br>+63 912 345 6789</p>
</div>
<button class="st-add-box" type="button" data-modal-open="addressModal"><span class="st-add-circle"><i class="fa-solid fa-plus"></i></span>Add New Address</button>
</div>
</div>
</div>

<div class="st-two overview-payment-box">
<div class="st-card">
<div class="st-body">
<h2 class="st-card-title">Saved Payment Methods</h2>
<div class="st-list" style="margin-top:12px">
<div class="st-pay-item">
<div class="st-pay-left"><div class="st-card-logo visa"><i class="fa-brands fa-cc-visa"></i></div><div class="st-pay-text"><p class="st-pay-title">Visa ending in 4242</p><p class="st-pay-sub">Expires 12/27 · {{ $settingsName ?: 'Account holder not set' }}</p></div></div>
<span class="st-mini orange">Primary</span>
</div>
<div class="st-pay-item">
<div class="st-pay-left"><div class="st-card-logo master"><i class="fa-solid fa-circle"></i><i class="fa-solid fa-circle"></i></div><div class="st-pay-text"><p class="st-pay-title">Mastercard ending in 8888</p><p class="st-pay-sub">Expires 08/26 · {{ $settingsName ?: 'Account holder not set' }}</p></div></div>
<button class="st-kebab" style="position:static;flex:0 0 auto" type="button" onclick="openPaymentActions('Mastercard ending in 8888')" aria-label="Payment method actions"><i class="fa-solid fa-ellipsis-vertical"></i></button>
</div>
<div class="st-pay-bottom">
<div class="st-pay-item">
<div class="st-pay-left"><div class="st-card-logo paypal"><i class="fa-brands fa-paypal"></i></div><div class="st-pay-text"><p class="st-pay-title">PayPal</p><p class="st-pay-sub">{{ $settingsEmail ?: 'Email not set' }}</p></div></div>
<span class="st-mini">Verified</span>
</div>
<button class="st-add-payment" type="button" data-modal-open="paymentModal"><span class="st-add-circle" style="width:24px;height:24px"><i class="fa-solid fa-plus"></i></span><span>Add New<br>Payment Method</span></button>
</div>
</div>
</div>
</div>

<div class="st-card">
<div class="st-body">
<h2 class="st-card-title">Notification Preferences</h2>
<p class="st-card-desc">Manage how you want to be notified.</p>
<div class="st-list" style="margin-top:10px">
@php
$notifs=[
['box','Order Updates','Get notified about order status and delivery',true],
['percent','Promotions & Offers','Receive exclusive deals and promotions',true],
['circle-plus','New Services & Features','Updates about new services and features',false],
['lightbulb','Tips & Resources','Helpful tips and printing guides',true],
['comment-dots','SMS Notifications','Receive important alerts via SMS',false],
];
@endphp
@foreach($notifs as $n)
<div class="st-notif-item">
<div class="st-notif-left"><span class="st-ico"><i class="fa-solid fa-{{ $n[0] }}"></i></span><div><p class="st-notif-title">{{ $n[1] }}</p><p class="st-notif-sub">{{ $n[2] }}</p></div></div>
<label class="st-switch"><input type="checkbox" @checked($n[3]) onchange="toggleSettingMessage(this,'{{ $n[1] }}')"><span class="st-slider"></span></label>
</div>
@endforeach
</div>
<button type="button" class="st-manage" data-tab-jump="notifications">Manage all notification settings <i class="fa-solid fa-arrow-right" style="float:right"></i></button>
</div>
</div>
</div>

<div class="st-card overview-plain-panel overview-privacy-panel">
<div class="st-body">
<h2 class="st-card-title">Privacy Controls</h2>
<p class="st-card-desc">Manage your data and privacy settings.</p>
<div class="st-privacy-grid" style="margin-top:12px">
<button type="button" class="st-privacy" data-tab-jump="privacy"><div class="st-privacy-left"><span class="st-ico"><i class="fa-solid fa-shield-halved"></i></span><div><p class="st-privacy-title">Data Privacy</p><p class="st-privacy-sub">Manage your personal data</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button type="button" class="st-privacy" data-tab-jump="privacy"><div class="st-privacy-left"><span class="st-ico"><i class="fa-solid fa-bullhorn"></i></span><div><p class="st-privacy-title">Marketing Consent</p><p class="st-privacy-sub">Manage marketing permissions</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button type="button" class="st-privacy" onclick="showSettingsToast('Your data export request has been prepared.')"><div class="st-privacy-left"><span class="st-ico"><i class="fa-solid fa-download"></i></span><div><p class="st-privacy-title">Download My Data</p><p class="st-privacy-sub">Request a copy of your data</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
</div>
</div>
</div>
</div>

<div class="st-stack">
<div class="st-card overview-plain-panel overview-quick-panel">
<div class="st-body">
<div class="st-head">
<div><h2 class="st-card-title">Quick Settings</h2><p class="st-card-desc">Manage key account preferences quickly.</p></div>
<button class="st-btn" type="button" data-tab-jump="preferences">View All Settings</button>
</div>
<div class="st-quick-grid">
<button class="st-quick" type="button" data-modal-open="profileModal"><div class="st-quick-left"><span class="st-orange-ico"><i class="fa-regular fa-user"></i></span><div><p class="st-quick-title">Personal Info</p><p class="st-quick-sub">Update your personal details</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button class="st-quick" type="button" data-tab-jump="security"><div class="st-quick-left"><span class="st-orange-ico"><i class="fa-solid fa-lock"></i></span><div><p class="st-quick-title">Login & Security</p><p class="st-quick-sub">Manage password & 2FA</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button class="st-quick" type="button" data-tab-jump="notifications"><div class="st-quick-left"><span class="st-orange-ico"><i class="fa-regular fa-bell"></i></span><div><p class="st-quick-title">Notification Preferences</p><p class="st-quick-sub">Control email & SMS alerts</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button class="st-quick" type="button" data-modal-open="paymentModal"><div class="st-quick-left"><span class="st-orange-ico"><i class="fa-regular fa-credit-card"></i></span><div><p class="st-quick-title">Payment Methods</p><p class="st-quick-sub">Manage saved cards & wallets</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button class="st-quick" type="button" data-modal-open="addressModal"><div class="st-quick-left"><span class="st-orange-ico"><i class="fa-solid fa-truck-fast"></i></span><div><p class="st-quick-title">Shipping / Pickup Preferences</p><p class="st-quick-sub">Set delivery & pickup options</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button class="st-quick" type="button" data-tab-jump="privacy"><div class="st-quick-left"><span class="st-orange-ico"><i class="fa-solid fa-user-shield"></i></span><div><p class="st-quick-title">Privacy & Permissions</p><p class="st-quick-sub">Manage data & visibility</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button class="st-quick" type="button" onclick="openSavedFilesPanel()"><div class="st-quick-left"><span class="st-orange-ico"><i class="fa-regular fa-folder-open"></i></span><div><p class="st-quick-title">Saved Designs / Files</p><p class="st-quick-sub">Access your uploaded files</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
<button class="st-quick" type="button" onclick="openConnectedAccountsPanel()"><div class="st-quick-left"><span class="st-orange-ico"><i class="fa-solid fa-link"></i></span><div><p class="st-quick-title">Connected Accounts</p><p class="st-quick-sub">Link social & other accounts</p></div></div><i class="fa-solid fa-chevron-right st-chev"></i></button>
</div>
</div>
</div>

<div class="st-card st-right-slim overview-plain-panel overview-comm-panel">
<div class="st-body">
<div class="st-head">
<div><h2 class="st-card-title">Communication Preferences</h2><p class="st-card-desc">Choose which customer communication channels stay active.</p></div>
<button class="st-btn" type="button" data-tab-jump="notifications">Manage</button>
</div>
<div class="st-list" style="margin-top:10px">
@php
$commPrefs=[
['envelope','Email Messages','Receive account and order communication via email',true],
['comment-dots','SMS Messages','Receive short updates and urgent order alerts by SMS',true],
['bell','Dashboard Alerts','Show live alerts inside the customer dashboard',true],
['headset','Support Replies','Notify you when support sends a reply',true],
];
@endphp
@foreach($commPrefs as $pref)
<div class="st-comm-item">
<div class="st-comm-left"><span class="st-orange-ico"><i class="fa-solid fa-{{ $pref[0] }}"></i></span><div><p class="st-comm-title">{{ $pref[1] }}</p><p class="st-comm-sub">{{ $pref[2] }}</p></div></div>
<label class="st-switch"><input type="checkbox" @checked($pref[3]) onchange="toggleSettingMessage(this,'{{ $pref[1] }}')"><span class="st-slider"></span></label>
</div>
@endforeach
</div>
</div>
</div>

<div class="st-card st-right-slim overview-plain-panel overview-prefs-panel">
<div class="st-body">
<h2 class="st-card-title">Preferences</h2>
<div style="margin-top:8px">
<div class="st-pref-row"><div class="st-pref-left"><span class="st-orange-ico"><i class="fa-solid fa-globe"></i></span><span class="st-pref-label">Language</span></div><select class="st-select" data-setting-name="Language"><option>English</option><option>Tagalog</option></select></div>
<div class="st-pref-row"><div class="st-pref-left"><span class="st-orange-ico"><i class="fa-solid fa-peso-sign"></i></span><span class="st-pref-label">Currency</span></div><select class="st-select" data-setting-name="Currency"><option>Philippine Peso (PHP)</option><option>US Dollar (USD)</option></select></div>
<div class="st-pref-row"><div class="st-pref-left"><span class="st-orange-ico"><i class="fa-regular fa-sun"></i></span><span class="st-pref-label">Theme</span></div><select class="st-select" data-setting-name="Theme"><option>Light Mode</option><option>Dark Mode</option></select></div>
<div class="st-pref-row"><div class="st-pref-left"><span class="st-orange-ico"><i class="fa-regular fa-calendar-days"></i></span><span class="st-pref-label">Date Format</span></div><select class="st-select" data-setting-name="Date Format"><option>MM/DD/YYYY</option><option>DD/MM/YYYY</option><option>YYYY-MM-DD</option></select></div>
</div>
</div>
</div>

<div class="st-card st-right-slim overview-plain-panel overview-activity-panel">
<div class="st-body">
<div class="st-head">
<div><h2 class="st-card-title">Account Activity</h2><p class="st-card-desc">Review your recent account activity.</p></div>
<button class="st-btn" type="button" onclick="openActivityLogPanel()">View All Activity</button>
</div>
<div>
<div class="st-activity-row"><div class="st-activity-left"><span class="st-ico"><i class="fa-regular fa-clock"></i></span><span class="st-act-label">Last Login</span></div><div class="st-act-val">May 29, 2026&nbsp; 8:15 AM<div style="margin-top:5px"><span class="st-mini">This Device</span></div></div></div>
<div class="st-activity-row"><div class="st-activity-left"><span class="st-ico"><i class="fa-solid fa-location-dot"></i></span><span class="st-act-label">Login Location</span></div><div class="st-act-val">Makati City, Metro Manila, Philippines</div></div>
<div class="st-activity-row"><div class="st-activity-left"><span class="st-ico"><i class="fa-solid fa-laptop"></i></span><span class="st-act-label">Active Sessions</span></div><div class="st-act-val">1 of 3<div style="margin-top:5px"><button class="st-link" type="button" onclick="openSessionManagerPanel()">Manage Sessions</button></div></div></div>
</div>
</div>
</div>
</div>
</div>
</section>

<section id="panel-profile" class="st-panel">
<div class="st-profile-grid">
<div class="st-stack">
<div class="st-card"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Profile Settings</h2><p class="st-card-desc">Update your profile information and public account details.</p></div><button class="st-btn" type="button" data-modal-open="profileModal"><i class="fa-solid fa-pen"></i> Edit Profile</button></div>
<div class="st-profile-hero">
<div class="st-profile-hero-main">
<div class="st-avatar-wrap">
<img class="st-avatar" src="{{ $settingsPhoto ?: 'https://i.pravatar.cc/220?img=47' }}" alt="Profile">
<button class="st-camera" type="button" aria-label="Change photo" onclick="openPhotoPicker()"><i class="fa-solid fa-camera"></i></button>
</div>
<div>
<div class="st-name-row"><h3 class="st-name">{{ $settingsName ?: 'Not set' }}</h3><span class="st-badge green"><i class="fa-solid fa-check"></i> Verified</span></div>
<div class="st-info"><i class="fa-solid fa-location-dot"></i> Quezon City, Metro Manila</div>
<div class="st-info">Customer ID: <strong>{{ $settingsCustomerId }}</strong> <button class="st-copy" type="button" onclick="copySettingsText(@js($settingsCustomerId))"><i class="fa-regular fa-copy"></i></button></div>
<span class="st-badge orange" style="margin-top:8px">Premium Member</span>
</div>
</div>
<div class="st-profile-hero-meta">
<div class="st-profile-mini"><span class="st-orange-ico" style="background:#f8fafc;color:#64748b"><i class="fa-solid fa-phone"></i></span><div><strong>Phone Number</strong><span>{{ $settingsPhone ?: 'Not set' }}</span></div></div>
<div class="st-profile-mini"><span class="st-orange-ico" style="background:#f8fafc;color:#64748b"><i class="fa-regular fa-calendar"></i></span><div><strong>Member Since</strong><span>{{ optional($settingsUser->created_at)->format('M d, Y') ?: 'Not set' }}</span></div></div>
<div class="st-profile-mini"><span class="st-orange-ico" style="background:#f8fafc;color:#64748b"><i class="fa-solid fa-envelope"></i></span><div><strong>Email Address</strong><span>{{ $settingsEmail ?: 'Not set' }}</span></div></div>
<div class="st-profile-mini"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-shield-halved"></i></span><div><strong>Account Status</strong><span style="color:var(--st-green)">Active</span></div></div>
</div>
</div>
<div class="st-section-line"></div>
<div class="st-profile-block-title"><span class="st-orange-ico"><i class="fa-regular fa-user"></i></span><h2 class="st-card-title">Personal Information</h2></div>
<div class="st-form-grid" style="margin-top:14px">
<div class="st-field"><label>Full Name</label><input class="st-input" value="{{ $settingsName }}"></div>
<div class="st-field"><label>Email Address</label><input class="st-input" type="email" value="{{ $settingsEmail }}"></div>
<div class="st-field"><label>Phone Number</label><input class="st-input" value="{{ $settingsPhone }}"></div>
<div class="st-field"><label>Company</label><input class="st-input" value="{{ $settingsCompany }}" placeholder="Optional"></div>
</div>
<div class="st-profile-block-title"><span class="st-orange-ico"><i class="fa-regular fa-address-card"></i></span><h2 class="st-card-title">Account Details</h2></div>
<div class="st-form-grid">
<div class="st-field"><label>Account Type</label><input class="st-input" value="Customer" readonly></div>
<div class="st-field"><label>Two-Factor Authentication</label><input class="st-input" value="Enabled" readonly></div>
</div>
<div class="st-actions"><button type="button" class="st-btn" onclick="saveProfilePanelSettings()">Save Profile</button></div>
</div></div>

<div class="st-card st-connected-card"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Connected Accounts</h2><p class="st-card-desc">Manage connected social and third-party accounts.</p></div><button class="st-btn" type="button" onclick="openConnectedAccountsPanel()">Manage</button></div>
<div class="st-three st-summary-strip" style="grid-template-columns:repeat(3,minmax(0,1fr));border:0;padding:0">
<div class="st-summary-item"><span class="st-orange-ico" style="background:#fff;color:#4285f4"><i class="fa-brands fa-google"></i></span><div><p class="st-sec-title">Google</p><p class="st-sec-sub">{{ $settingsEmail ?: 'Not set' }}</p></div></div>
<div class="st-summary-item"><span class="st-orange-ico" style="background:#eef4ff;color:#1877f2"><i class="fa-brands fa-facebook-f"></i></span><div><p class="st-sec-title">Facebook</p><p class="st-sec-sub">Connected</p></div></div>
<div class="st-summary-item"><span class="st-orange-ico" style="background:#eef4ff;color:#1d64ca"><i class="fa-brands fa-paypal"></i></span><div><p class="st-sec-title">PayPal</p><p class="st-sec-sub">Connected</p></div></div>
</div>
</div></div>
</div>
<div class="st-profile-side">
<div class="st-card st-plain"><div class="st-body">
<h2 class="st-card-title">Profile Completion</h2>
<div class="st-score-ring" style="width:112px;height:112px"><div class="st-score-inner" style="width:82px;height:82px"><div><strong style="font-size:24px">92%</strong><br><span>Complete</span></div></div></div>
<button class="st-btn" type="button" onclick="showSettingsToast('Profile suggestions opened.')">View Suggestions</button>
</div></div>
<div class="st-card st-plain"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Communication Preferences</h2><p class="st-card-desc">Use these channels for order and account updates.</p></div></div>
<div class="st-no-box-list">
@foreach([['Email Notifications',true],['SMS Notifications',true],['Marketing Updates',false],['Order Updates',true]] as $pref)
<div class="st-no-box-item"><span class="st-sec-title">{{ $pref[0] }}</span><label class="st-switch"><input type="checkbox" @checked($pref[1]) onchange="toggleSettingMessage(this,'{{ $pref[0] }}')"><span class="st-slider"></span></label></div>
@endforeach
</div>
<button class="st-btn" style="margin-top:12px;width:100%" type="button" data-tab-jump="notifications">Manage Preferences</button>
</div></div>
<div class="st-card st-plain"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Recent Activity</h2><p class="st-card-desc">Latest profile and account updates.</p></div><button class="st-link" type="button" onclick="openActivityLogPanel()">View All</button></div>
<div class="st-no-box-list">
<div class="st-no-box-item"><div class="st-comm-left"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-check"></i></span><div><p class="st-sec-title">Profile updated</p><p class="st-sec-sub">Today</p></div></div></div>
<div class="st-no-box-item"><div class="st-comm-left"><span class="st-orange-ico" style="background:#eef4ff;color:#2563eb"><i class="fa-solid fa-cloud-arrow-up"></i></span><div><p class="st-sec-title">Design file uploaded</p><p class="st-sec-sub">Recently</p></div></div></div>
</div>
</div></div>
</div>
</div>
</section>

<section id="panel-security" class="st-panel">
<div class="st-settings-grid">
<div class="st-setting-main">
<div class="st-card">
<div class="st-sec-row">
<span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-shield-halved"></i></span>
<div><h2 class="st-card-title">Security Overview</h2><p class="st-card-desc">Review and manage your account security.</p></div>
<span class="st-status-pill"><i class="fa-solid fa-check"></i> Your account is well protected</span>
</div>
<div class="st-sec-row">
<span class="st-orange-ico"><i class="fa-solid fa-lock"></i></span>
<div><p class="st-sec-title">Password</p><p class="st-sec-sub">Last changed 30 days ago</p></div>
<button class="st-outline-btn" type="button" onclick="openPasswordPanel()">Change Password</button>
</div>
<div class="st-sec-row">
<span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-shield-halved"></i></span>
<div><p class="st-sec-title">Two-Factor Authentication</p><p class="st-sec-sub">Add an extra layer of security to your account.</p></div>
<label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Two-factor authentication')"><span class="st-slider"></span></label>
</div>
<div class="st-sec-row">
<span class="st-orange-ico" style="background:#f4efff;color:#7c3aed"><i class="fa-regular fa-clock"></i></span>
<div><p class="st-sec-title">Login Activity</p><p class="st-sec-sub">Review your recent account activity.</p></div>
<button class="st-outline-btn" type="button" onclick="openActivityLogPanel()">View Activity</button>
</div>
<div class="st-sec-row">
<span class="st-orange-ico" style="background:#eef4ff;color:#2563eb"><i class="fa-solid fa-laptop"></i></span>
<div><p class="st-sec-title">Active Sessions / Devices</p><p class="st-sec-sub">Manage devices that are signed in to your account.</p></div>
<button class="st-outline-btn" type="button" onclick="openSessionManagerPanel()">Manage Devices</button>
</div>
<div class="st-sec-row">
<span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-circle-check"></i></span>
<div><p class="st-sec-title">Account Verification</p><p class="st-sec-sub">Your account is verified.</p></div>
<span class="st-status-pill">Verified</span>
</div>
<div class="st-sec-row">
<span class="st-orange-ico"><i class="fa-solid fa-life-ring"></i></span>
<div><p class="st-sec-title">Recovery Options</p><p class="st-sec-sub">Update your recovery information to stay in control.</p></div>
<button class="st-outline-btn" type="button" onclick="openRecoveryPanel()">Manage Recovery</button>
</div>
<div class="st-sec-row">
<span class="st-orange-ico" style="background:#f4efff;color:#7c3aed"><i class="fa-solid fa-display"></i></span>
<div><p class="st-sec-title">Trusted Devices</p><p class="st-sec-sub">Devices that you trust and use to access your account.</p></div>
<button class="st-outline-btn" type="button" onclick="openTrustedDevicesPanel()">Manage Devices</button>
</div>
</div>

<div class="st-card st-login-tips-box"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Recent Login Activity</h2><p class="st-card-desc">Review your most recent account access.</p></div><button class="st-outline-btn" type="button" onclick="openActivityLogPanel()">View All Activity</button></div>
<div class="st-table-lite">
<div class="st-table-lite-row"><strong>Quezon City, Metro Manila, Philippines</strong><span class="st-status-pill">Current</span><span>Windows - Chrome<br>Jun 03, 2026 at 09:41 AM</span></div>
<div class="st-table-lite-row"><strong>Quezon City, Metro Manila, Philippines</strong><span>iPhone - iOS</span><span>Jun 02, 2026 at 10:12 PM</span></div>
<div class="st-table-lite-row"><strong>Makati City, Metro Manila, Philippines</strong><span>MacOS - Safari</span><span>Jun 01, 2026 at 08:05 PM</span></div>
</div>
<button class="st-link" type="button" style="margin:12px auto 0;display:flex" onclick="openActivityLogPanel()">View All Activity</button>
<div class="st-section-line"></div>
<h2 class="st-card-title">Security Tips</h2><p class="st-card-desc">Simple steps to keep your account safe.</p>
<div class="st-tip-grid">
<div class="st-tip"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-lock"></i></span><div><p class="st-sec-title">Use a strong password</p><p class="st-sec-sub">Use letters, numbers, and symbols.</p></div></div>
<div class="st-tip"><span class="st-orange-ico" style="background:#f4efff;color:#7c3aed"><i class="fa-solid fa-shield"></i></span><div><p class="st-sec-title">Enable 2FA</p><p class="st-sec-sub">Add an extra verification layer.</p></div></div>
<div class="st-tip"><span class="st-orange-ico"><i class="fa-solid fa-triangle-exclamation"></i></span><div><p class="st-sec-title">Beware of phishing</p><p class="st-sec-sub">Never share your password or OTP.</p></div></div>
<div class="st-tip"><span class="st-orange-ico" style="background:#eef4ff;color:#2563eb"><i class="fa-solid fa-rotate"></i></span><div><p class="st-sec-title">Keep devices updated</p><p class="st-sec-sub">Use current browsers and OS versions.</p></div></div>
</div>
</div></div>
</div>

<div class="st-setting-side">
<div class="st-card"><div class="st-body">
<h2 class="st-card-title">Security Score</h2>
<div class="st-score-ring"><div class="st-score-inner"><div><strong>92%</strong><br><span>Excellent</span></div></div></div>
<p class="st-card-desc" style="text-align:center">Great job! Your account security is strong.</p>
<div style="margin-top:14px">
<div class="st-check-line"><i class="fa-solid fa-circle-check"></i>Strong password</div>
<div class="st-check-line"><i class="fa-solid fa-circle-check"></i>Two-factor authentication enabled</div>
<div class="st-check-line"><i class="fa-solid fa-circle-check"></i>Recovery information verified</div>
<div class="st-check-line"><i class="fa-solid fa-circle-check"></i>No compromised devices found</div>
</div>
</div></div>
<div class="st-card"><div class="st-body">
<h2 class="st-card-title">Verification Status</h2>
<div class="st-side-list" style="margin-top:12px">
<div class="st-side-row"><span><i class="fa-regular fa-envelope"></i> Email Address</span><span class="st-status-pill">Verified</span></div>
<div class="st-side-row"><span><i class="fa-solid fa-phone"></i> Phone Number</span><span class="st-status-pill">Verified</span></div>
<div class="st-side-row"><span><i class="fa-solid fa-id-card"></i> Identity Verification</span><span class="st-status-pill gray">Not required</span></div>
<button class="st-link" type="button" onclick="showSettingsToast('Verification options opened.')">Manage Verification <i class="fa-solid fa-chevron-right"></i></button>
</div>
</div></div>
<div class="st-card"><div class="st-body">
<h2 class="st-card-title">Need Help?</h2><p class="st-card-desc">We're here to help you stay secure.</p>
<div class="st-side-list" style="margin-top:12px">
<button class="st-setting-action" type="button" onclick="showSettingsToast('Security guide opened.')"><span>How to secure your account</span><i class="fa-solid fa-chevron-right"></i></button>
<button class="st-setting-action" type="button" onclick="openPasswordPanel()"><span>Reset your password</span><i class="fa-solid fa-chevron-right"></i></button>
<button class="st-setting-action" type="button" onclick="showSettingsToast('2FA guide opened.')"><span>Enable two-factor authentication</span><i class="fa-solid fa-chevron-right"></i></button>
<button class="st-setting-action" type="button" onclick="showSettingsToast('Suspicious activity report opened.')"><span>Report suspicious activity</span><i class="fa-solid fa-chevron-right"></i></button>
<button class="st-btn" type="button" onclick="window.location.href='{{ Route::has('help-center') ? route('help-center') : '#' }}'">Visit Help Center</button>
</div>
</div></div>
</div>
</div>
</section>

<section id="panel-notifications" class="st-panel">
<div class="st-notif-layout">
<div class="st-card"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Notification Channels</h2><p class="st-card-desc">Choose how you receive different types of notifications.</p></div><button class="st-btn" type="button" onclick="persistSettingsValue('notifications','all_channels','saved','Notification channels')">Save Changes</button></div>
<div class="st-summary-strip" style="grid-template-columns:repeat(4,minmax(0,1fr));padding:0;border:0">
@foreach([['envelope','Email',$settingsEmail ?: 'Not set'],['comment-dots','SMS',$settingsPhone ?: 'Not set'],['bell','In-App','Enabled'],['paper-plane','Marketing','Receive offers']] as $channel)
<div class="st-summary-item"><span class="st-orange-ico" style="background:#eef4ff;color:#2563eb"><i class="fa-solid fa-{{ $channel[0] }}"></i></span><div><p class="st-sec-title">{{ $channel[1] }}</p><p class="st-sec-sub">{{ $channel[2] }}</p></div><span class="st-inline-pill">On</span></div>
@endforeach
</div>
<div class="st-section-line"></div>
<div class="st-channel-row"><div></div><div class="st-channel-head">Email</div><div class="st-channel-head">SMS</div><div class="st-channel-head">In-App</div><div></div></div>
@php
$channelRows=[
['box','Order Confirmations','Get notified when your order is placed.',true,true,true],
['truck-fast','Order Updates','Receive updates on status changes and progress.',true,true,true],
['route','Shipping & Delivery','Track your shipment and delivery updates.',true,true,true],
['triangle-exclamation','Delivery Exceptions','Be alerted for delays, failures, or delivery issues.',true,false,true],
['credit-card','Payment Confirmations','Receive confirmation after payment.',true,true,true],
['receipt','Invoice & Receipt','Get notified when invoices and receipts are available.',true,false,true],
['shield-halved','Security & Account Alerts','Important updates about your account and security.',true,true,true],
['headset','Support & Messages','Updates about support tickets and replies.',true,false,true],
['gift','Promotions & Offers','Exclusive deals, new products, and special offers.',true,false,false],
];
@endphp
@foreach($channelRows as $row)
<div class="st-channel-row">
<div class="st-comm-left"><span class="st-orange-ico"><i class="fa-solid fa-{{ $row[0] }}"></i></span><div><p class="st-sec-title">{{ $row[1] }}</p><p class="st-sec-sub">{{ $row[2] }}</p></div></div>
@for($i=3;$i<=5;$i++)
<label class="st-switch" style="margin:auto"><input type="checkbox" @checked($row[$i]) onchange="toggleSettingMessage(this,'{{ $row[1] }} channel {{ $i }}')"><span class="st-slider"></span></label>
@endfor
<button class="st-link" type="button" onclick="showSettingsToast('{{ $row[1] }} details opened.')"><i class="fa-solid fa-chevron-down"></i></button>
</div>
@endforeach
</div></div>
<div class="st-notif-side">
<div class="st-card st-plain"><div class="st-body"><h2 class="st-card-title">Notification Summary</h2><p class="st-card-desc">Overview of your current notification settings.</p><div class="st-score-ring" style="width:112px;height:112px"><div class="st-score-inner" style="width:82px;height:82px"><div><strong style="font-size:24px">92%</strong><br><span>All set</span></div></div></div><div class="st-no-box-list"><div class="st-no-box-item"><span>Email Notifications</span><span>24 enabled</span></div><div class="st-no-box-item"><span>SMS Notifications</span><span>12 enabled</span></div><div class="st-no-box-item"><span>In-App Notifications</span><span>18 enabled</span></div><div class="st-no-box-item"><span>Marketing Notifications</span><span>6 enabled</span></div></div></div></div>
<div class="st-card st-plain"><div class="st-body"><h2 class="st-card-title">Unread & Alerts</h2><div class="st-no-box-list"><div class="st-no-box-item"><span>Unread Notifications</span><strong style="color:var(--st-orange)">2</strong></div><div class="st-no-box-item"><span>High Priority Alerts</span><strong style="color:var(--st-orange)">1</strong></div><div class="st-no-box-item"><span>Support Replies</span><strong style="color:var(--st-orange)">1</strong></div></div><button class="st-btn" style="margin-top:12px;width:100%" type="button" onclick="showSettingsToast('All notifications opened.')">View All Notifications</button></div></div>
<div class="st-card st-plain"><div class="st-body"><h2 class="st-card-title">Quick Actions</h2><div class="st-action-list"><button class="st-setting-action" type="button" onclick="persistSettingsValue('notifications','test','sent','Test notification')"><span>Test Notification</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="openSettingsModal('profileModal')"><span>Update Contact Info</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Advanced notification preferences opened.')"><span>Notification Preferences</span><i class="fa-solid fa-chevron-right"></i></button></div></div></div>
<div class="st-card st-plain"><div class="st-body"><h2 class="st-card-title">Need Help?</h2><p class="st-card-desc">Our support team is here to help you.</p><button class="st-btn" style="margin-top:12px;width:100%" type="button" onclick="window.location.href='{{ Route::has('help-center') ? route('help-center') : '#' }}'">Contact Support</button></div></div>
</div>
</div>
</section>

<section id="panel-payments" class="st-panel">
<div class="st-pay-layout">
<div class="st-card st-billing-box"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Billing Address</h2><p class="st-card-desc">Use this address for invoices, receipts, and official billing records.</p></div><button class="st-btn" type="button" data-modal-open="addressModal">Edit Address</button></div>
<div class="st-address-grid">
<div class="st-box"><span class="st-mini orange">Primary</span><h3 class="st-box-title">Home Billing</h3><p class="st-box-text">123 Printify Avenue<br>Makati City, Metro Manila 1200<br>Philippines<br>+63 912 345 6789</p></div>
<div class="st-box"><h3 class="st-box-title">Business Billing</h3><p class="st-box-text">45 Timog Avenue<br>Quezon City, Metro Manila 1103<br>Philippines<br>{{ $settingsEmail ?: 'Email not set' }}</p></div>
<button class="st-add-box" type="button" data-modal-open="addressModal"><span class="st-add-circle"><i class="fa-solid fa-plus"></i></span>Add Billing Address</button>
</div>
<div class="st-section-line"></div>
<div class="st-head"><div><h2 class="st-card-title">Preferred Payment</h2><p class="st-card-desc">Cards and wallets used for checkout and automatic billing.</p></div><button class="st-btn" type="button" data-modal-open="paymentModal">Add Payment Method</button></div>
<div class="st-list">
<div class="st-pay-item"><div class="st-pay-left"><div class="st-card-logo visa"><i class="fa-brands fa-cc-visa"></i></div><div class="st-pay-text"><p class="st-pay-title">Visa ending in 4242</p><p class="st-pay-sub">Primary card · Expires 12/27</p></div></div><span class="st-mini orange">Primary</span></div>
<div class="st-pay-item"><div class="st-pay-left"><div class="st-card-logo paypal"><i class="fa-brands fa-paypal"></i></div><div class="st-pay-text"><p class="st-pay-title">PayPal</p><p class="st-pay-sub">{{ $settingsEmail ?: 'Email not set' }}</p></div></div><span class="st-mini">Verified</span></div>
</div>
<div class="st-section-line"></div>
<div class="st-head"><div><h2 class="st-card-title">Invoice & Billing</h2><p class="st-card-desc">Manage invoices, receipts, and billing preferences.</p></div><button class="st-btn" type="button" onclick="showSettingsToast('Invoice center opened.')">Open Invoices</button></div>
<div class="st-channel-row" style="grid-template-columns:minmax(0,1fr) 140px 110px 110px">
<div class="st-comm-left"><span class="st-orange-ico"><i class="fa-solid fa-file-invoice"></i></span><div><p class="st-sec-title">Monthly Invoice</p><p class="st-sec-sub">Automatically send PDF invoice every month.</p></div></div>
<span class="st-status-pill">Enabled</span><button class="st-link" type="button" onclick="showSettingsToast('Invoice downloaded.')">Download</button><button class="st-link" type="button" onclick="showSettingsToast('Invoice emailed.')">Email</button>
</div>
<div class="st-section-line"></div>
<div class="st-head"><div><h2 class="st-card-title">Billing Activity</h2><p class="st-card-desc">Latest payment and checkout activity.</p></div><button class="st-btn" type="button" onclick="showSettingsToast('Full billing history opened.')">View History</button></div>
<div class="st-table-lite">
<div class="st-table-lite-row"><strong>Document Printing</strong><span>Paid</span><span>Jun 05, 2026<br>PHP 1,200.00</span></div>
<div class="st-table-lite-row"><strong>Photo Services</strong><span>Processing</span><span>Jun 03, 2026<br>PHP 850.00</span></div>
<div class="st-table-lite-row"><strong>Large Format Printing</strong><span>Paid</span><span>May 30, 2026<br>PHP 2,100.00</span></div>
</div>
</div></div>
<div class="st-pay-side">
<div class="st-card st-plain"><div class="st-body"><h2 class="st-card-title">Payment Summary</h2><div class="st-stat-row" style="margin-top:12px"><div class="st-stat-tile"><span class="st-orange-ico"><i class="fa-solid fa-wallet"></i></span><div><p class="st-sec-title">3</p><p class="st-sec-sub">Saved Methods</p></div></div><div class="st-stat-tile"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-check"></i></span><div><p class="st-sec-title">2</p><p class="st-sec-sub">Verified</p></div></div></div></div></div>
<div class="st-card st-plain"><div class="st-body"><h2 class="st-card-title">Secured Payment</h2><div class="st-no-box-list"><div class="st-no-box-item"><span>Encrypted checkout</span><i class="fa-solid fa-circle-check" style="color:var(--st-green)"></i></div><div class="st-no-box-item"><span>Card data protected</span><i class="fa-solid fa-circle-check" style="color:var(--st-green)"></i></div><div class="st-no-box-item"><span>Fraud monitoring</span><i class="fa-solid fa-circle-check" style="color:var(--st-green)"></i></div></div></div></div>
<div class="st-card st-plain"><div class="st-body"><h2 class="st-card-title">Billing Help</h2><div class="st-action-list"><button class="st-setting-action" type="button" onclick="showSettingsToast('Receipt guide opened.')"><span>How to read receipts</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Refund policy opened.')"><span>Refund policy</span><i class="fa-solid fa-chevron-right"></i></button></div></div></div>
<div class="st-card st-plain"><div class="st-body"><h2 class="st-card-title">Need More Help?</h2><p class="st-card-desc">Contact support for billing questions.</p><button class="st-btn" style="margin-top:12px;width:100%" type="button" onclick="window.location.href='{{ Route::has('help-center') ? route('help-center') : '#' }}'">Contact Support</button></div></div>
</div>
</div>
</section>

<section id="panel-addresses" class="st-panel">
<div class="st-section-layout">
<div class="st-section-main">
<div class="st-card st-main-group"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Manage Your Addresses</h2><p class="st-card-desc">Add, edit, and organize shipping, billing, and pickup addresses.</p></div><button class="st-btn" type="button" data-modal-open="addressModal"><i class="fa-solid fa-plus"></i> Add Address</button></div>
<div class="st-mini-tabs"><span class="active">Shipping Addresses <b class="st-mini">2</b></span><span>Billing Address <b class="st-mini">1</b></span><span>Pickup & Branch <b class="st-mini">1</b></span></div>

<div class="st-line-row st-address-row">
<span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-house"></i></span>
<div><p class="st-sec-title">Eyra Mae Alla <span class="st-mini">Default</span></p><p class="st-sec-sub"><i class="fa-solid fa-phone"></i> +63 912 345 6789<br>Blk 6 Lot 8 Ninada St. Litex Rd.<br>Commonwealth, Quezon City, Metro Manila 1121<br>Estimated Delivery: 2-4 business days</p></div>
<div class="st-row-actions"><div class="st-map-mini"><i class="fa-solid fa-location-dot"></i></div><button class="st-outline-btn" type="button" data-modal-open="addressModal"><i class="fa-regular fa-pen-to-square"></i> Edit</button><button class="st-link" type="button" onclick="showSettingsToast('Address duplicated.')">Duplicate</button></div>
</div>
<div class="st-line-row st-address-row">
<span class="st-orange-ico" style="background:#f4efff;color:#7c3aed"><i class="fa-solid fa-building"></i></span>
<div><p class="st-sec-title">Mae Alla <span class="st-mini gray">Work</span></p><p class="st-sec-sub"><i class="fa-solid fa-phone"></i> +63 917 888 2345<br>14F Printify Tower, 32nd St. Corner 9th Ave.<br>Bonifacio Global City, Taguig City, Metro Manila 1634<br>Estimated Delivery: 1-3 business days</p></div>
<div class="st-row-actions"><div class="st-map-mini" style="color:#7c3aed;background:linear-gradient(135deg,#f4efff,#eef4ff)"><i class="fa-solid fa-location-dot"></i></div><button class="st-outline-btn" type="button" data-modal-open="addressModal"><i class="fa-regular fa-pen-to-square"></i> Edit</button><button class="st-link" type="button" onclick="showSettingsToast('Work address duplicated.')">Duplicate</button><button class="st-link" style="color:var(--st-danger)" type="button" onclick="showSettingsToast('Delete confirmation opened.')">Delete</button></div>
</div>
<div class="st-line-row st-address-row">
<span class="st-orange-ico"><i class="fa-solid fa-store"></i></span>
<div><p class="st-sec-title">PrintifyCo. SM North EDSA Branch <span class="st-mini orange">Branch Pickup</span></p><p class="st-sec-sub"><i class="fa-solid fa-phone"></i> +63 2 8356 7890<br>SM City North EDSA, The Block, 2nd Level<br>Epifanio de los Santos Ave., Quezon City, 1105<br>Pick-up Hours: Mon-Sun, 10:00 AM - 9:00 PM</p></div>
<div class="st-row-actions"><div class="st-map-mini" style="color:var(--st-orange);background:linear-gradient(135deg,#fff3e6,#eef4ff)"><i class="fa-solid fa-location-dot"></i></div><button class="st-outline-btn" type="button" data-modal-open="addressModal"><i class="fa-regular fa-pen-to-square"></i> Edit</button><button class="st-link" type="button" onclick="showSettingsToast('Branch duplicated.')">Duplicate</button></div>
</div>
<div class="st-tip-inline"><i class="fa-solid fa-circle-info"></i> Tip: Set a default shipping address to save time during checkout. <button class="st-link" type="button" onclick="showSettingsToast('Address tips opened.')">Learn More</button></div>
</div></div>
</div>

<div class="st-section-side">
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Address Summary</h2><div class="st-no-box-list" style="margin-top:8px"><div class="st-right-metric"><span>Shipping Addresses</span><strong>2</strong></div><div class="st-right-metric"><span>Billing Address</span><strong>1</strong></div><div class="st-right-metric"><span>Pickup / Branch</span><strong>1</strong></div><div class="st-right-metric"><span>Total Saved Addresses</span><strong>4</strong></div></div></div></div>
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Delivery Preferences</h2><div class="st-no-box-list" style="margin-top:8px"><div class="st-right-metric"><span>Preferred Courier</span><strong style="color:var(--st-orange)">J&T Express</strong></div><div class="st-right-metric"><span>Delivery Speed</span><span>Standard</span></div><div class="st-right-metric"><span>Weekend Delivery</span><span class="st-status-pill">Enabled</span></div><div class="st-right-metric"><span>Leave at Door</span><span class="st-status-pill">Enabled</span></div></div><button class="st-btn" style="width:100%;margin-top:10px" type="button" onclick="showSettingsToast('Delivery preferences opened.')">Manage Preferences</button></div></div>
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Quick Tips</h2><div class="st-action-list" style="margin-top:8px"><button class="st-setting-action" type="button" onclick="showSettingsToast('Default address guide opened.')"><span>Set a default address<br><small>Save time at checkout.</small></span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Address update guide opened.')"><span>Keep addresses updated<br><small>Make sure delivery info is current.</small></span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Branch pickup guide opened.')"><span>Use branch pickup<br><small>Pickup at a nearby branch.</small></span><i class="fa-solid fa-chevron-right"></i></button></div></div></div>
</div>
</div>
</section>

<section id="panel-preferences" class="st-panel">
<div class="st-section-layout">
<div class="st-section-main">
<div class="st-card st-main-group"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Region & Localization</h2><p class="st-card-desc">Set your language, currency, time zone, and date format.</p></div><button class="st-btn" type="button" onclick="saveGenericSettings(event,'Localization preferences saved.')">Save Preferences</button></div>
<div class="st-form-grid">
<div class="st-field"><label>Language</label><select class="st-input" onchange="persistSettingsValue('preferences','language',this.value,'Language')"><option>English</option><option>Filipino</option></select></div>
<div class="st-field"><label>Currency</label><select class="st-input" onchange="persistSettingsValue('preferences','currency',this.value,'Currency')"><option>Philippine Peso (PHP)</option><option>US Dollar (USD)</option></select></div>
<div class="st-field"><label>Time Zone</label><select class="st-input" onchange="persistSettingsValue('preferences','timezone',this.value,'Time zone')"><option>(GMT+08:00) Manila</option><option>(GMT+08:00) Singapore</option></select></div>
<div class="st-field"><label>Date Format</label><select class="st-input" onchange="persistSettingsValue('preferences','date_format',this.value,'Date format')"><option>MM/DD/YYYY</option><option>DD/MM/YYYY</option></select></div>
</div>
<div class="st-section-line"></div>
<div class="st-head" style="margin-bottom:8px"><div><h2 class="st-card-title">Appearance & Display</h2><p class="st-card-desc">Choose how the dashboard looks and feels.</p></div></div>
<div class="st-line-row"><span class="st-orange-ico"><i class="fa-solid fa-sun"></i></span><div><p class="st-sec-title">Theme Mode</p><p class="st-sec-sub">Light mode is active.</p></div><select class="st-input" style="max-width:160px" onchange="persistSettingsValue('preferences','theme',this.value,'Theme')"><option>Light</option><option>Dark</option></select></div>
<div class="st-line-row"><span class="st-orange-ico" style="background:#eef4ff;color:#2563eb"><i class="fa-solid fa-palette"></i></span><div><p class="st-sec-title">Accent Color</p><p class="st-sec-sub">Orange is used for primary actions.</p></div><span class="st-status-pill orange">Orange</span></div>
</div></div>

<div class="st-card st-main-group"><div class="st-body">
<div class="st-head"><div><h2 class="st-card-title">Accessibility</h2><p class="st-card-desc">Make controls easier to read and use.</p></div></div>
<div class="st-line-row"><span class="st-orange-ico"><i class="fa-solid fa-eye"></i></span><div><p class="st-sec-title">High Contrast Mode</p><p class="st-sec-sub">Improve contrast for important controls.</p></div><label class="st-switch"><input type="checkbox" onchange="toggleSettingMessage(this,'High contrast mode')"><span class="st-slider"></span></label></div>
<div class="st-line-row"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-keyboard"></i></span><div><p class="st-sec-title">Keyboard Shortcuts</p><p class="st-sec-sub">Enable shortcut keys across the portal.</p></div><label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Keyboard shortcuts')"><span class="st-slider"></span></label></div>
<div class="st-section-line"></div>
<div class="st-head" style="margin-bottom:8px"><div><h2 class="st-card-title">Communication & Reminders</h2><p class="st-card-desc">Control alerts and reminder behavior.</p></div></div>
<div class="st-line-row"><span class="st-orange-ico"><i class="fa-solid fa-bell"></i></span><div><p class="st-sec-title">Order Updates</p><p class="st-sec-sub">Notify me about order and delivery progress.</p></div><label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Order reminders')"><span class="st-slider"></span></label></div>
<div class="st-line-row"><span class="st-orange-ico" style="background:#eef4ff;color:#2563eb"><i class="fa-solid fa-comment-dots"></i></span><div><p class="st-sec-title">Message Reminders</p><p class="st-sec-sub">Remind me when support replies arrive.</p></div><label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Message reminders')"><span class="st-slider"></span></label></div>
</div></div>

<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Dashboard Display</h2><div class="st-action-list"><button class="st-setting-action" type="button" onclick="showSettingsToast('Dashboard display opened.')"><span>Show revenue cards</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Dashboard widgets reordered.')"><span>Manage widgets</span><i class="fa-solid fa-chevron-right"></i></button></div></div></div>
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Order & Design Workspace</h2><div class="st-action-list"><button class="st-setting-action" type="button" onclick="showSettingsToast('Order workspace opened.')"><span>Default order view</span><span>Card View</span></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Design settings opened.')"><span>Design tools mode</span><span>Advanced</span></button></div></div></div>
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Saved & Print</h2><div class="st-action-list"><button class="st-setting-action" type="button" onclick="showSettingsToast('Print output preferences opened.')"><span>Default paper size</span><span>A4</span></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Saved files preferences opened.')"><span>Auto-save designs</span><span class="st-status-pill">Enabled</span></button></div></div></div>
</div>
<div class="st-section-side">
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Your Preferences Summary</h2><div class="st-no-box-list" style="margin-top:8px"><div class="st-right-metric"><span>Language</span><strong>English</strong></div><div class="st-right-metric"><span>Currency</span><strong>PHP</strong></div><div class="st-right-metric"><span>Time Zone</span><strong>Manila</strong></div><div class="st-right-metric"><span>Theme</span><strong>Light</strong></div></div></div></div>
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Quick Personalization</h2><div class="st-action-list" style="margin-top:8px"><button class="st-setting-action" type="button" onclick="showSettingsToast('Dashboard personalization opened.')"><span>Set your dashboard layout</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Color settings opened.')"><span>Choose accent color</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Theme guide opened.')"><span>Try dark mode</span><i class="fa-solid fa-chevron-right"></i></button></div></div></div>
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Need Help?</h2><p class="st-card-desc">Quick access to preference support.</p><button class="st-btn" style="width:100%;margin-top:10px" type="button" onclick="window.location.href='{{ Route::has('help-center') ? route('help-center') : '#' }}'">Visit Help Center</button></div></div>
</div>
</div>
</section>

<section id="panel-privacy" class="st-panel">
<div class="st-section-layout">
<div class="st-section-main">
<div class="st-card st-main-group"><div class="st-body">
<div class="st-privacy-panel-row">
<div class="st-comm-left"><span class="st-orange-ico"><i class="fa-regular fa-user"></i></span><div><h2 class="st-card-title">Profile Visibility</h2><p class="st-card-desc">Control what information is visible to other users.</p></div></div>
<div><div class="st-privacy-control"><div><p class="st-sec-title">Profile Visibility</p><p class="st-sec-sub">Choose who can view your profile information.</p></div><select onchange="persistSettingsValue('privacy','profile_visibility',this.value,'Profile visibility')"><option>Everyone</option><option>Customers only</option><option>Only Me</option></select></div><div class="st-privacy-control"><div><p class="st-sec-title">Show Member Since Date</p><p class="st-sec-sub">Allow others to see when you joined.</p></div><label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Show member since date')"><span class="st-slider"></span></label></div></div>
</div>
<div class="st-privacy-panel-row">
<div class="st-comm-left"><span class="st-orange-ico" style="background:#fff1f2;color:#e11d48"><i class="fa-solid fa-lock"></i></span><div><h2 class="st-card-title">Order Visibility</h2><p class="st-card-desc">Choose who can view your orders and purchase activity.</p></div></div>
<div><div class="st-privacy-control"><div><p class="st-sec-title">Order History Visibility</p><p class="st-sec-sub">Control who can see your past orders.</p></div><select onchange="persistSettingsValue('privacy','order_visibility',this.value,'Order visibility')"><option>Only Me</option><option>Support Team</option><option>Everyone</option></select></div><div class="st-privacy-control"><div><p class="st-sec-title">Show Product Reviews</p><p class="st-sec-sub">Show your product reviews and ratings.</p></div><label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Product reviews visibility')"><span class="st-slider"></span></label></div></div>
</div>
<div class="st-privacy-panel-row">
<div class="st-comm-left"><span class="st-orange-ico"><i class="fa-solid fa-bullhorn"></i></span><div><h2 class="st-card-title">Marketing Consent</h2><p class="st-card-desc">Manage updates, offers, and promo communication.</p></div></div>
<div><div class="st-privacy-control"><div><p class="st-sec-title">Email Marketing</p><p class="st-sec-sub">Receive emails about products and offers.</p></div><label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Email marketing')"><span class="st-slider"></span></label></div><div class="st-privacy-control"><div><p class="st-sec-title">SMS Marketing</p><p class="st-sec-sub">Receive marketing text messages.</p></div><label class="st-switch"><input type="checkbox" onchange="toggleSettingMessage(this,'SMS marketing')"><span class="st-slider"></span></label></div></div>
</div>
<div class="st-privacy-panel-row">
<div class="st-comm-left"><span class="st-orange-ico" style="background:#eef4ff;color:#2563eb"><i class="fa-solid fa-cookie-bite"></i></span><div><h2 class="st-card-title">Cookie Preferences</h2><p class="st-card-desc">Control cookies used to improve your experience.</p></div></div>
<div><div class="st-privacy-control"><div><p class="st-sec-title">Essential Cookies</p><p class="st-sec-sub">Required for core site functionality.</p></div><span class="st-status-pill">Always Active</span></div><div class="st-privacy-control"><div><p class="st-sec-title">Analytics Cookies</p><p class="st-sec-sub">Help us understand how the portal is used.</p></div><label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Analytics cookies')"><span class="st-slider"></span></label></div></div>
</div>
</div></div>

<div class="st-card st-main-group"><div class="st-body">
<div class="st-line-row"><span class="st-orange-ico"><i class="fa-solid fa-wand-magic-sparkles"></i></span><div><p class="st-sec-title">Personalized Recommendations</p><p class="st-sec-sub">Allow personalized product and order suggestions.</p></div><label class="st-switch"><input type="checkbox" checked onchange="toggleSettingMessage(this,'Personalized recommendations')"><span class="st-slider"></span></label></div>
<div class="st-section-line"></div>
<div class="st-line-row"><span class="st-orange-ico" style="background:#eef4ff;color:#2563eb"><i class="fa-solid fa-download"></i></span><div><p class="st-sec-title">Download My Data</p><p class="st-sec-sub">Download a copy of your personal data.</p></div><button class="st-outline-btn" type="button" onclick="requestDataExport()">Request Data Export</button></div>
<div class="st-line-row"><span class="st-orange-ico" style="background:#f4efff;color:#7c3aed"><i class="fa-regular fa-clock"></i></span><div><p class="st-sec-title">Data Retention</p><p class="st-sec-sub">We retain your data only as long as necessary.</p></div><button class="st-outline-btn" type="button" onclick="showSettingsToast('Data retention policy opened.')">View Retention Policy</button></div>
<div class="st-section-line"></div>
<h2 class="st-card-title">Account Verification</h2><p class="st-card-desc">Review your verification and privacy status.</p>
<div class="st-summary-strip" style="margin-top:10px">
<div class="st-summary-item"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-check"></i></span><div><p class="st-sec-title">Email Verified</p><p class="st-sec-sub">{{ $settingsEmail ?: 'Not set' }}</p></div></div>
<div class="st-summary-item"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-phone"></i></span><div><p class="st-sec-title">Phone Verified</p><p class="st-sec-sub">{{ $settingsPhone ?: 'Not set' }}</p></div></div>
<div class="st-summary-item"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-shield"></i></span><div><p class="st-sec-title">Two-Factor Auth</p><p class="st-sec-sub">Enabled</p></div></div>
<div class="st-summary-item"><span class="st-orange-ico" style="background:#eaf8ef;color:var(--st-green)"><i class="fa-solid fa-user-shield"></i></span><div><p class="st-sec-title">Privacy Level</p><p class="st-sec-sub">High</p></div></div>
</div>
</div></div>
</div>

<div class="st-section-side">
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Consent Status</h2><p class="st-card-desc">Overview of your marketing consent.</p><div class="st-no-box-list" style="margin-top:8px"><div class="st-right-metric"><span>Email Marketing</span><span>Subscribed <i class="fa-solid fa-circle-check" style="color:var(--st-green)"></i></span></div><div class="st-right-metric"><span>SMS Marketing</span><span>Not Subscribed <i class="fa-regular fa-circle" style="color:#9ca3af"></i></span></div><div class="st-right-metric"><span>Push Notifications</span><span>Subscribed <i class="fa-solid fa-circle-check" style="color:var(--st-green)"></i></span></div></div><button class="st-btn" style="width:100%;margin-top:10px" type="button" onclick="showSettingsToast('Consent preferences opened.')">Manage Consent</button></div></div>
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Data Protection</h2><p class="st-card-desc">Your data is secure with PrintifyCo.</p><div style="margin-top:8px"><div class="st-check-line"><i class="fa-regular fa-circle-check"></i>Your data is encrypted in transit and at rest.</div><div class="st-check-line"><i class="fa-regular fa-circle-check"></i>We never sell your personal data.</div><div class="st-check-line"><i class="fa-regular fa-circle-check"></i>You can update or delete your data anytime.</div></div><button class="st-outline-btn" style="width:100%;margin-top:10px" type="button" onclick="showSettingsToast('Privacy policy opened.')">View Privacy Policy</button></div></div>
<div class="st-card st-plain-panel"><div class="st-body"><h2 class="st-card-title">Privacy Actions</h2><div class="st-action-list" style="margin-top:8px"><button class="st-setting-action" type="button" onclick="showSettingsToast('Privacy preferences opened.')"><span>Update Privacy Preferences</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Data permissions opened.')"><span>Manage Data & Permissions</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="openDeactivatePanel()"><span>Delete My Account</span><i class="fa-solid fa-chevron-right"></i></button><button class="st-setting-action" type="button" onclick="showSettingsToast('Privacy team contact opened.')"><span>Contact Privacy Team</span><i class="fa-solid fa-chevron-right"></i></button></div></div></div>
</div>
</div>
</section>

<p class="st-footer">Printify & Co. Client Portal v1.0</p>
</div>
</div>

<div id="profileModal" class="st-modal">
<div class="st-modal-bg" data-modal-close></div>
<div class="st-modal-shell"><div class="st-modal-card">
<div class="st-modal-head"><div><h3 class="st-modal-title">Edit Profile</h3><p class="st-modal-desc">Update your personal details.</p></div><button type="button" class="st-close" data-modal-close>×</button></div>
<form class="st-modal-body" onsubmit="saveProfileModal(event)">
<div class="st-field"><label>Full Name</label><input id="profileNameInput" class="st-input" value="{{ $settingsName }}"></div>
<div class="st-field" style="margin-top:12px"><label>Email Address</label><input id="profileEmailInput" class="st-input" type="email" value="{{ $settingsEmail }}"></div>
<div class="st-form-grid" style="margin-top:12px"><div class="st-field"><label>Phone Number</label><input id="profilePhoneInput" class="st-input" value="{{ $settingsPhone }}"></div><div class="st-field"><label>Date of Birth</label><input id="profileBirthInput" class="st-input" value="{{ $settingsBirthdate }}"></div></div>
<div class="st-field" style="margin-top:12px"><label>Company</label><input id="profileCompanyInput" class="st-input" value="{{ $settingsCompany }}" placeholder="Optional"></div>
<div class="st-actions"><button type="button" class="st-btn" data-modal-close>Cancel</button><button type="submit" class="st-btn">Save Changes</button></div>
</form>
</div></div>
</div>

<div id="addressModal" class="st-modal">
<div class="st-modal-bg" data-modal-close></div>
<div class="st-modal-shell"><div class="st-modal-card">
<div class="st-modal-head"><div><h3 class="st-modal-title">Manage Address</h3><p class="st-modal-desc">Add a shipping or pickup address.</p></div><button type="button" class="st-close" data-modal-close>×</button></div>
<form class="st-modal-body" onsubmit="saveGenericSettings(event,'Address saved successfully.')">
<div class="st-field"><label>Address Label</label><input class="st-input" placeholder="Home, Work, Branch, etc."></div>
<div class="st-field" style="margin-top:12px"><label>Complete Address</label><textarea class="st-textarea" placeholder="Street, barangay, city, province, ZIP code"></textarea></div>
<div class="st-actions"><button type="button" class="st-btn" data-modal-close>Cancel</button><button type="submit" class="st-btn">Save Address</button></div>
</form>
</div></div>
</div>

<div id="paymentModal" class="st-modal">
<div class="st-modal-bg" data-modal-close></div>
<div class="st-modal-shell"><div class="st-modal-card">
<div class="st-modal-head"><div><h3 class="st-modal-title">Add Payment Method</h3><p class="st-modal-desc">Add a card or digital wallet.</p></div><button type="button" class="st-close" data-modal-close>×</button></div>
<form class="st-modal-body" onsubmit="saveGenericSettings(event,'Payment method saved successfully.')">
<div class="st-field"><label>Cardholder Name</label><input class="st-input" placeholder="{{ $settingsName ?: 'Cardholder name' }}"></div>
<div class="st-field" style="margin-top:12px"><label>Card Number</label><input class="st-input" placeholder="0000 0000 0000 0000"></div>
<div class="st-form-grid" style="margin-top:12px"><div class="st-field"><label>Expiry</label><input class="st-input" placeholder="MM/YY"></div><div class="st-field"><label>CVC</label><input class="st-input" placeholder="123"></div></div>
<div class="st-actions"><button type="button" class="st-btn" data-modal-close>Cancel</button><button type="submit" class="st-btn">Save Payment</button></div>
</form>
</div></div>
</div>


<div id="settingsUtilityModal" class="st-modal">
<div class="st-modal-bg" data-modal-close></div>
<div class="st-modal-shell"><div class="st-modal-card">
<div class="st-modal-head"><div><h3 id="settingsUtilityTitle" class="st-modal-title">Settings</h3><p id="settingsUtilityDesc" class="st-modal-desc">Manage account settings.</p></div><button type="button" class="st-close" data-modal-close>×</button></div>
<div id="settingsUtilityBody" class="st-modal-body"></div>
</div></div>
</div>

<div id="settingsToast" class="st-toast">Settings updated.</div>

<script>
const settingsSaveRoute=@json(Route::has('settings.save') ? route('settings.save') : '');
const settingsProfileRoute=@json(Route::has('profile.update') ? route('profile.update') : '');
const settingsDisplayEmail=@json($settingsEmail ?: 'Not set');
const settingsDisplayPhone=@json($settingsPhone ?: 'Not set');
const settingsCsrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || @json(csrf_token());
document.addEventListener('DOMContentLoaded',function(){
const tabs=document.querySelectorAll('.st-tab'),panels=document.querySelectorAll('.st-panel'),jumps=document.querySelectorAll('[data-tab-jump]'),opens=document.querySelectorAll('[data-modal-open]'),closes=document.querySelectorAll('[data-modal-close]');
function activateTab(name,updateUrl=true){tabs.forEach(b=>b.classList.toggle('active',b.dataset.tab===name));panels.forEach(p=>p.classList.remove('active'));const panel=document.getElementById('panel-'+name);if(panel)panel.classList.add('active');if(updateUrl)history.replaceState(null,'',window.location.pathname+'#'+name);window.scrollTo({top:0,behavior:'smooth'})}
tabs.forEach(b=>b.addEventListener('click',()=>activateTab(b.dataset.tab)));
jumps.forEach(b=>b.addEventListener('click',()=>activateTab(b.dataset.tabJump)));
opens.forEach(b=>b.addEventListener('click',()=>openSettingsModal(b.dataset.modalOpen)));
closes.forEach(b=>b.addEventListener('click',closeAllSettingsModals));
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeAllSettingsModals()});
document.querySelectorAll('.st-select[data-setting-name]').forEach(select=>{
const key='printify_setting_'+select.dataset.settingName.toLowerCase().replace(/\s+/g,'_');
const saved=localStorage.getItem(key);
if(saved){Array.from(select.options).forEach(o=>{if(o.value===saved||o.text===saved)select.value=o.value})}
select.addEventListener('change',()=>{localStorage.setItem(key,select.value);persistSettingsValue('preferences',key,select.value,select.dataset.settingName)});
});
document.querySelectorAll('button,.st-select').forEach(el=>{
el.addEventListener('click',()=>{el.classList.add('is-clicked','st-clickable-cover');setTimeout(()=>el.classList.remove('is-clicked','st-clickable-cover'),180)})
});
document.querySelectorAll('.st-pref-row').forEach(row=>{
row.addEventListener('click',e=>{if(e.target.tagName.toLowerCase()==='select')return;const s=row.querySelector('select');if(s){s.focus();row.classList.add('is-clicked');setTimeout(()=>row.classList.remove('is-clicked'),180)}})
});
const initialTab=(window.location.hash||'').replace('#','');if(initialTab&&document.getElementById('panel-'+initialTab))activateTab(initialTab,false);
window.activateSettingsTab=activateTab;
});
function openSettingsModal(id){const m=document.getElementById(id);if(!m)return;m.classList.add('active');document.body.style.overflow='hidden'}
function closeAllSettingsModals(){document.querySelectorAll('.st-modal').forEach(m=>m.classList.remove('active'));document.body.style.overflow=''}
function splitSettingsName(name){const parts=(name||'').trim().split(/\s+/).filter(Boolean);return{first_name:parts.shift()||'',last_name:parts.join(' ')}}
function setProfileDisplays(data){const fallback='Not set';profileDisplayName.textContent=data.name||fallback;profileDisplayEmail.textContent=data.email||fallback;profileDisplayPhone.textContent=data.phone||fallback;profileDisplayBirth.textContent=data.birthdate||fallback;profileDisplayCompany.textContent=data.company||fallback}
async function saveSettingsProfilePayload(payload){if(!settingsProfileRoute){showSettingsToast('Profile save route is unavailable.');return false}try{const response=await fetch(settingsProfileRoute,{method:'PATCH',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':settingsCsrf},body:JSON.stringify(payload)});if(!response.ok)throw new Error('Profile save failed');return true}catch(e){console.warn('Profile save failed.',e);showSettingsToast('Profile was not saved. Please check the details.');return false}}
async function saveProfileModal(e){e.preventDefault();const n=profileNameInput.value.trim(),em=profileEmailInput.value.trim(),p=profilePhoneInput.value.trim(),b=profileBirthInput.value.trim(),c=profileCompanyInput.value.trim(),parts=splitSettingsName(n);const payload={name:n,first_name:parts.first_name,last_name:parts.last_name,email:em,phone:p,birthdate:b,company:c};if(!await saveSettingsProfilePayload(payload))return;setProfileDisplays(payload);const panelInputs=document.querySelectorAll('#panel-profile .st-input');if(panelInputs.length){panelInputs[0].value=n;panelInputs[1].value=em;panelInputs[2].value=p;panelInputs[3].value=c}window.dispatchEvent(new CustomEvent('printify-profile-updated',{detail:{name:n,initials:n? n.split(/\s+/).map(part=>part[0]).join('').slice(0,2).toUpperCase():''}}));closeAllSettingsModals();showSettingsToast('Profile updated successfully.')}
async function saveProfilePanelSettings(){const inputs=document.querySelectorAll('#panel-profile .st-input');if(!inputs.length)return;const n=inputs[0].value.trim(),em=inputs[1].value.trim(),p=inputs[2].value.trim(),c=inputs[3].value.trim(),b=profileBirthInput.value.trim(),parts=splitSettingsName(n);const payload={name:n,first_name:parts.first_name,last_name:parts.last_name,email:em,phone:p,birthdate:b,company:c};if(!await saveSettingsProfilePayload(payload))return;profileNameInput.value=n;profileEmailInput.value=em;profilePhoneInput.value=p;profileCompanyInput.value=c;setProfileDisplays(payload);window.dispatchEvent(new CustomEvent('printify-profile-updated',{detail:{name:n,initials:n? n.split(/\s+/).map(part=>part[0]).join('').slice(0,2).toUpperCase():''}}));showSettingsToast('Profile settings saved.')}
function saveGenericSettings(e,msg){e.preventDefault();closeAllSettingsModals();showSettingsToast(msg||'Settings saved successfully.')}
async function persistSettingsValue(group,key,value,label){
localStorage.setItem('printify_setting_'+group+'_'+key,value);
if(settingsSaveRoute){
try{
const response=await fetch(settingsSaveRoute,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':settingsCsrf},body:JSON.stringify({group,key,value})});
if(response.ok){showSettingsToast((label||'Setting')+' saved successfully.');return}
}catch(e){console.warn('Settings backend sync skipped.',e)}
}
showSettingsToast((label||'Setting')+' saved locally.');
}
function toggleSettingMessage(input,label){const key=label.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'');const value=input.checked?'enabled':'disabled';localStorage.setItem('printify_toggle_'+key,value);persistSettingsValue('toggles',key,value,label)}
function copySettingsText(text){if(navigator.clipboard){navigator.clipboard.writeText(text).then(()=>showSettingsToast('Copied: '+text)).catch(()=>showSettingsToast('Copy failed.'))}else showSettingsToast('Copied: '+text)}
function openPhotoPicker(){const input=document.getElementById('settingsPhotoInput');if(input)input.click()}
document.addEventListener('change',function(e){if(e.target&&e.target.id==='settingsPhotoInput'&&e.target.files&&e.target.files[0]){const reader=new FileReader();reader.onload=function(ev){document.querySelectorAll('.st-avatar').forEach(img=>img.src=ev.target.result);window.dispatchEvent(new CustomEvent('printify-profile-updated',{detail:{photo:ev.target.result}}));showSettingsToast('Profile photo preview updated. Save from My Profile to keep it on every device.')};reader.readAsDataURL(e.target.files[0])}});
function openUtilityModal(title,desc,body){const t=document.getElementById('settingsUtilityTitle'),d=document.getElementById('settingsUtilityDesc'),b=document.getElementById('settingsUtilityBody');if(t)t.textContent=title;if(d)d.textContent=desc;if(b)b.innerHTML=body;openSettingsModal('settingsUtilityModal')}
function openAddressActions(label){openUtilityModal(label+' Actions','Choose what you want to do with this saved address.','<div class="st-list"><button type="button" class="st-btn" onclick="closeAllSettingsModals();openSettingsModal(\'addressModal\')">Edit Address</button><button type="button" class="st-btn" onclick="showSettingsToast(\''+label+' set as primary.\');closeAllSettingsModals()">Set as Primary</button><button type="button" class="st-btn" onclick="showSettingsToast(\''+label+' removed from saved addresses.\');closeAllSettingsModals()">Remove Address</button></div>')}
function openPaymentActions(label){openUtilityModal(label+' Actions','Manage this saved payment method.','<div class="st-list"><button type="button" class="st-btn" onclick="showSettingsToast(\''+label+' set as primary.\');closeAllSettingsModals()">Set as Primary</button><button type="button" class="st-btn" onclick="closeAllSettingsModals();openSettingsModal(\'paymentModal\')">Update Payment</button><button type="button" class="st-btn" onclick="showSettingsToast(\''+label+' removed.\');closeAllSettingsModals()">Remove Payment</button></div>')}
function openSavedFilesPanel(){openUtilityModal('Saved Designs / Files','Access uploaded design files connected to your account.','<div class="st-list"><div class="st-pay-item"><div><p class="st-pay-title">Business Card Layout</p><p class="st-pay-sub">Ready for reorder · PDF</p></div><button type="button" class="st-btn" onclick="showSettingsToast(\'Business Card Layout opened.\')">Open</button></div><div class="st-pay-item"><div><p class="st-pay-title">Sticker Draft</p><p class="st-pay-sub">Last updated recently · PNG</p></div><button type="button" class="st-btn" onclick="showSettingsToast(\'Sticker Draft opened.\')">Open</button></div></div>')}
function openConnectedAccountsPanel(){openUtilityModal('Connected Accounts','Link or review accounts connected to your Printify & Co. profile.','<div class="st-list"><div class="st-pay-item"><div><p class="st-pay-title">Google</p><p class="st-pay-sub">Connected for sign-in.</p></div><span class="st-mini">Connected</span></div><div class="st-pay-item"><div><p class="st-pay-title">Facebook</p><p class="st-pay-sub">Not linked.</p></div><button type="button" class="st-btn" onclick="showSettingsToast(\'Facebook linking started.\')">Connect</button></div></div>')}
function openActivityLogPanel(){openUtilityModal('Account Activity','Recent account activity and security events.','<div class="st-list"><div class="st-activity-row"><div class="st-activity-left"><span class="st-ico"><i class="fa-regular fa-clock"></i></span><span class="st-act-label">Login</span></div><div class="st-act-val">May 29, 2026 8:15 AM</div></div><div class="st-activity-row"><div class="st-activity-left"><span class="st-ico"><i class="fa-solid fa-user-pen"></i></span><span class="st-act-label">Profile Viewed</span></div><div class="st-act-val">Today</div></div></div>')}
function openSessionManagerPanel(){openUtilityModal('Active Sessions','Manage devices currently signed in to your account.','<div class="st-list"><div class="st-pay-item"><div><p class="st-pay-title">Chrome on Windows</p><p class="st-pay-sub">Makati City · Current device</p></div><span class="st-mini">Active</span></div><div class="st-pay-item"><div><p class="st-pay-title">Mobile Browser</p><p class="st-pay-sub">Last active recently</p></div><button type="button" class="st-btn" onclick="showSettingsToast(\'Mobile browser session removed.\')">Remove</button></div></div>')}
function openRecoveryPanel(){openUtilityModal('Recovery Options','Manage recovery email, phone, and backup access.','<div class="st-list"><div class="st-pay-item"><div><p class="st-pay-title">Recovery Email</p><p class="st-pay-sub">'+settingsDisplayEmail+'</p></div><span class="st-mini">Verified</span></div><div class="st-pay-item"><div><p class="st-pay-title">Recovery Phone</p><p class="st-pay-sub">'+settingsDisplayPhone+'</p></div><span class="st-mini">Verified</span></div><button type="button" class="st-btn" onclick="persistSettingsValue(\'security\',\'recovery_reviewed\',new Date().toISOString(),\'Recovery options\');closeAllSettingsModals()">Save Recovery Review</button></div>')}
function openPasswordPanel(){openUtilityModal('Change Password','Update your password securely.','<form onsubmit="saveGenericSettings(event,\'Password updated successfully.\')"><div class="st-field"><label>Current Password</label><input class="st-input" type="password" required></div><div class="st-field" style="margin-top:12px"><label>New Password</label><input class="st-input" type="password" required></div><div class="st-actions"><button type="button" class="st-btn" onclick="closeAllSettingsModals()">Cancel</button><button type="submit" class="st-btn">Save Password</button></div></form>')}
function openTrustedDevicesPanel(){openUtilityModal('Trusted Devices','Review devices with saved login access.','<div class="st-list"><div class="st-pay-item"><div><p class="st-pay-title">Windows Desktop</p><p class="st-pay-sub">Trusted · Current device</p></div><span class="st-mini">Trusted</span></div><div class="st-pay-item"><div><p class="st-pay-title">Android Phone</p><p class="st-pay-sub">Trusted device</p></div><button type="button" class="st-btn" onclick="showSettingsToast(\'Android Phone removed from trusted devices.\')">Remove</button></div></div>')}
function requestDataExport(){localStorage.setItem('printify_data_export_requested_at',new Date().toISOString());openUtilityModal('Data Export Requested','Your downloadable copy request has been recorded.','<div class="st-box" style="min-height:auto"><h3 class="st-box-title" style="margin-top:0">Request submitted</h3><p class="st-box-text">A copy of your account data will be prepared for download once available.</p></div><div class="st-actions"><button type="button" class="st-btn" onclick="closeAllSettingsModals()">Done</button></div>');showSettingsToast('Data download request submitted.')}
function openDeactivatePanel(){openUtilityModal('Deactivate Account','Confirm before deactivating your account.','<div class="st-box" style="min-height:auto"><h3 class="st-box-title" style="margin-top:0;color:var(--st-danger)">Deactivate account?</h3><p class="st-box-text">This will hide your account access until it is restored by support.</p></div><div class="st-actions"><button type="button" class="st-btn" onclick="closeAllSettingsModals()">Cancel</button><button type="button" class="st-btn" onclick="showSettingsToast(\'Deactivate request prepared.\');closeAllSettingsModals()">Continue</button></div>')}
function showSettingsToast(msg){const t=document.getElementById('settingsToast');if(!t)return;t.textContent=msg;t.classList.add('show');clearTimeout(window.settingsToastTimeout);window.settingsToastTimeout=setTimeout(()=>t.classList.remove('show'),2200)}
</script>
</x-app-layout>
