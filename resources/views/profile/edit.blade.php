<x-app-layout>
@php
    $user = Auth::user();
    $firstName = $user->first_name ?? $user->name ?? '';
    $lastName = $user->last_name ?? '';
    $photo = $user->profile_photo_url ?? $user->profile_photo ?? $user->avatar ?? null;
    if (!$photo && !empty($user->profile_photo_path)) $photo = asset('storage/' . $user->profile_photo_path);
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&family=Poppins:wght@500;600;700&display=swap');
[x-cloak]{display:none!important}
:root{
    --p-bg:#ffffff;--p-card:#fff;--p-ink:#111827;--p-muted:#6b7280;--p-line:#edf0f4;--p-line2:#dfe3ea;
    --p-orange:#ff7a00;--p-orange-soft:#fff3e6;--p-green:#16a34a;--p-green-soft:#eaf8ef;
    --p-purple:#7c3aed;--p-purple-soft:#f2ebff;--p-yellow:#f59e0b;--p-yellow-soft:#fff7df;--p-complete:#2563eb;--p-complete-soft:#eff6ff;
    --p-shadow:0 12px 30px rgba(15,23,42,.07);--p-shadow2:0 18px 42px rgba(15,23,42,.11);
    --p-radius:14px;--btn-h:42px;--btn-r:10px;--font:'Inter',system-ui,sans-serif;
    --head:'Playfair Display',Georgia,serif;--title:'Poppins',system-ui,sans-serif;
}
*{box-sizing:border-box}
.profile-page{min-height:calc(100vh - 70px);padding:0 0 34px;background:var(--p-bg);color:var(--p-ink);font-family:var(--font);overflow-x:hidden;-webkit-font-smoothing:antialiased}
.profile-page button,.profile-page input{font-family:var(--font)}
.profile-shell{max-width:1490px;margin:0 auto;padding:0}
.profile-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin:0 0 16px}
.profile-title-wrap{display:flex;align-items:flex-start;gap:10px}
.profile-title-wrap:before{content:'';width:18px;height:4px;margin-top:8px;border-radius:999px;background:var(--p-orange);flex:0 0 auto}
.profile-title{margin:0 0 3px;font-family:var(--head);font-size:40px;font-weight:700;line-height:1.2;color:#111827;letter-spacing:-.02em}
.profile-sub{margin:0;color:var(--p-muted);font-size:12px;font-weight:400;line-height:1.45}
.profile-date{height:42px;min-width:178px;padding:0 15px;border:1px solid #111827;border-radius:8px;background:#fff;color:#111827;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:12px;font-weight:700;line-height:1;white-space:nowrap}
.profile-date svg{color:#111827}
.profile-grid{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:18px;align-items:start}
.profile-left,.profile-right{display:grid;gap:18px;align-content:start}
.profile-left{border:1px solid #111827;border-radius:8px;background:#fff;padding:18px;gap:0}
.prof-card{background:var(--p-card);border:1px solid #111827;border-radius:8px;box-shadow:none;transition:background .18s ease,border-color .18s ease,transform .18s ease}
.prof-card:hover{background:#fff;border-color:#111827;transform:none}
.prof-hero{min-height:138px;padding:0 0 18px;display:flex;align-items:center;justify-content:space-between;gap:18px;border:0;border-radius:0;border-bottom:1px solid #111827}
.prof-main{display:flex;align-items:center;gap:18px;min-width:0}
.prof-avatar-wrap{width:108px;height:108px;position:relative;flex:0 0 auto}
.prof-avatar{width:108px;height:108px;border-radius:50%;overflow:hidden;display:grid;place-items:center;background:radial-gradient(circle at 32% 25%,#ff9a37,#ff5a00 54%,#e04800);border:4px solid #fff;outline:3px solid #ff6a14;box-shadow:0 18px 34px rgba(255,122,0,.22);color:#fff;font-size:44px;font-weight:800;letter-spacing:-.08em}
.prof-avatar img{width:100%;height:100%;object-fit:cover;display:block}
.prof-avatar-initials{transform:translateX(-2px)}
.prof-camera-btn{position:absolute;right:-2px;bottom:5px;width:36px;height:36px;border-radius:50%;border:3px solid #fff;background:var(--p-orange);color:#fff;display:grid;place-items:center;cursor:pointer;box-shadow:none;transition:.18s ease}
.prof-camera-btn:hover,.prof-camera-btn:focus{background:#111827;color:#fff;transform:translateY(-1px)}
.prof-online{position:absolute;right:-10px;top:80px;width:14px;height:14px;border-radius:50%;background:#22c55e;border:4px solid #fff;box-shadow:0 0 0 1px rgba(22,163,74,.08);pointer-events:none;z-index:3}
.prof-name{margin:0;font-family:var(--head);font-size:34px;font-weight:700;line-height:1.02;color:#111827;letter-spacing:-.028em}
.prof-role{margin:7px 0 8px;color:#111827;font-family:var(--title);font-size:12px;font-weight:600;letter-spacing:.16em;text-transform:uppercase}
.prof-loc{display:flex;align-items:center;gap:6px;margin:0;color:#4b5563;font-size:13px;font-weight:500}
.prof-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
.prof-chip{display:inline-flex;align-items:center;gap:6px;min-height:27px;padding:5px 11px;border-radius:999px;background:var(--p-green-soft);color:var(--p-green);font-size:12px;font-weight:700}
.prof-actions{width:184px;display:grid;gap:10px;flex:0 0 auto}
.prof-btn,.prof-mini,.prof-link{height:var(--btn-h);min-width:132px;padding:0 17px;border:0!important;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;gap:8px;background:#fff;color:#000!important;font-size:12px;font-weight:600;letter-spacing:0;line-height:1;white-space:nowrap;cursor:pointer;box-shadow:none!important;transition:background .18s ease,color .18s ease;transform:none!important}
.prof-btn svg,.prof-mini svg,.prof-link svg{flex:0 0 auto}
.prof-primary{background:var(--p-orange)!important;color:#111827!important}
.prof-link-primary{background:var(--p-orange)!important;color:#111827!important}
.prof-btn:hover,.prof-btn:focus,.prof-mini:hover,.prof-mini:focus,.prof-link:hover,.prof-link:focus{background:#111827!important;color:#fff!important}
.prof-btn:hover svg,.prof-mini:hover svg,.prof-link:hover svg{color:#fff}
.prof-btn:disabled{opacity:.58;cursor:not-allowed}
.prof-btn:disabled:hover{background:var(--p-orange)!important;border-color:transparent!important;color:#fff!important}
.prof-mini{min-width:74px}
.prof-link{width:100%;margin-top:10px;justify-content:space-between}
.prof-link span:first-child{color:inherit}
.prof-link span:last-child{font-size:18px;line-height:1}
.prof-info,.prof-widget{padding:18px;background:#fff}
.profile-left .prof-info{border:0;border-radius:0;padding:18px 0 0;box-shadow:none}
.profile-left .prof-info:hover,.profile-left .prof-hero:hover{background:#fff}
.profile-right .prof-widget{border:0;border-radius:0;background:transparent;padding:0;box-shadow:none}
.profile-right .prof-widget:hover{background:transparent}
.profile-right .prof-complete,.profile-right .prof-progress,.profile-right .prof-suggest{display:none!important}
.profile-right .prof-widget:first-child{display:none!important}
.profile-right .prof-widget:nth-child(3){display:none!important}
.prof-head{min-height:42px;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin-bottom:12px}
.prof-title-wrap{display:flex;align-items:flex-start;gap:10px}
.prof-title-wrap:before{content:'';width:18px;height:4px;margin-top:8px;border-radius:999px;background:var(--p-orange);box-shadow:none;flex:0 0 auto}
.prof-card-title,.prof-widget-title{margin:0;color:#111827;font-family:var(--title);font-size:14.5px;font-weight:600;letter-spacing:.022em;line-height:1.35}
.prof-widget-title{display:flex;align-items:center;gap:9px;margin-bottom:12px}
.prof-widget-title svg{color:var(--p-orange);flex:0 0 auto}
.prof-subtitle{margin:4px 0 0;color:#4b5563;font-size:11px;font-weight:400;line-height:1.45}
.prof-section{padding:16px 0;border-top:1px solid var(--p-line)}
.prof-section:first-of-type{border-top:0;padding-top:4px}
.prof-section-title{display:flex;align-items:center;gap:9px;margin:0 0 12px;color:#111827;font-family:var(--title);font-size:14.5px;font-weight:600;letter-spacing:.022em}
.prof-section-title svg{color:var(--p-orange);flex:0 0 auto}
.prof-fields{display:grid;grid-template-columns:1fr 1fr;column-gap:42px}
.prof-field{display:grid;grid-template-columns:142px minmax(0,1fr);align-items:center;gap:12px;min-height:38px;padding:4px 0;border-bottom:1px dashed #e5e7eb}
.prof-field:nth-last-child(-n+2){border-bottom-color:transparent}
.prof-label{color:#111827;font-family:var(--font);font-size:12px;font-weight:600;letter-spacing:.003em}
.prof-value{color:#263244;font-size:13px;font-weight:400;line-height:1.35;word-break:break-word}
.prof-input{width:100%;height:36px;border:1px solid #d9dee8;border-radius:9px;background:#fff;color:#111827;padding:0 11px;font-size:13px;font-weight:500;outline:0;transition:.18s ease}
.prof-input:focus{border-color:var(--p-orange);box-shadow:0 0 0 4px rgba(255,122,0,.11)}
.prof-badge{display:inline-flex;align-items:center;justify-content:center;min-height:24px;padding:4px 10px;border-radius:999px;background:var(--p-green-soft);color:var(--p-green);font-size:11px;font-weight:700}
.prof-savebar{position:sticky;bottom:18px;z-index:10;margin-top:10px;padding:12px;border:1px solid #111827;border-radius:8px;background:rgba(255,255,255,.94);box-shadow:none;backdrop-filter:blur(14px);display:flex;align-items:center;justify-content:space-between;gap:12px}
.prof-save-text{margin:0;color:#4b5563;font-size:12px;font-weight:500;line-height:1.35}
.prof-save-actions{display:flex;gap:10px;align-items:center;justify-content:flex-end}
.prof-complete{display:grid;grid-template-columns:66px 1fr;gap:12px;align-items:center}
.prof-ring{--v:88;width:66px;height:66px;border-radius:50%;display:grid;place-items:center;background:conic-gradient(var(--p-complete) calc(var(--v)*1%),#edf0f4 0);position:relative}
.prof-ring:before{content:'';position:absolute;width:48px;height:48px;border-radius:50%;background:#fff}
.prof-ring span{position:relative;color:#111827;font-size:14px;font-weight:800}
.prof-widget-copy{margin:0;color:#4b5563;font-size:12px;line-height:1.45}
.prof-progress{height:7px;margin:12px 0;border-radius:999px;background:#edf0f4;overflow:hidden}
.prof-progress span{display:block;width:88%;height:100%;border-radius:999px;background:var(--p-complete)}
.prof-suggest{margin-top:10px;padding:12px;border-radius:13px;background:var(--p-orange-soft);border:1px dashed rgba(255,122,0,.42);color:#7c4a28;font-size:12px;font-weight:500;line-height:1.55}
.prof-pref,.prof-act{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:7px 0;color:#4b5563;font-size:12px;font-weight:500;line-height:1.35}
.prof-pref-label{color:#263244;font-size:12px;font-weight:500}
.prof-pref-side{display:inline-flex;align-items:center;gap:0;flex:0 0 auto}
.prof-toggle{width:39px;height:22px;padding:3px;border:0;border-radius:999px;background:#d1d5db;display:inline-flex;align-items:center;justify-content:flex-start;cursor:pointer;box-shadow:inset 0 1px 3px rgba(15,23,42,.12);transition:.18s ease}
.prof-toggle.is-off{background:#d1d5db;box-shadow:inset 0 1px 3px rgba(15,23,42,.12)}
.prof-toggle.is-on{background:var(--p-green);box-shadow:none}
.prof-toggle-knob{width:16px;height:16px;border-radius:999px;background:#fff;color:var(--p-green);display:grid;place-items:center;box-shadow:0 2px 5px rgba(0,0,0,.14);transform:translateX(0);transition:.18s ease}
.prof-toggle-knob svg{display:none}
.prof-toggle.is-on .prof-toggle-knob{transform:translateX(17px);color:var(--p-green)}
.prof-pref-state{display:none!important;min-width:22px;text-align:right;font-size:11px;font-weight:800}
.prof-pref-state.on{color:var(--p-green)}
.prof-pref-state.off{color:var(--p-green)}
.prof-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.prof-stat{min-height:72px;border:1px solid #111827;border-radius:8px;padding:11px;display:flex;align-items:center;gap:11px;background:#fff;transition:.18s ease}
.prof-stat:hover{background:#fff3e6;border-color:#111827}
.prof-ico{width:38px;height:38px;border-radius:13px;display:grid;place-items:center;flex:0 0 auto}
.ico-orange{background:var(--p-orange-soft);color:var(--p-orange)}
.ico-green{background:var(--p-green-soft);color:var(--p-green)}
.ico-purple{background:var(--p-purple-soft);color:var(--p-purple)}
.ico-yellow{background:var(--p-yellow-soft);color:var(--p-yellow)}
.prof-num{display:block;color:#111827;font-size:16px;font-weight:800;line-height:1}
.prof-small{display:block;margin-top:4px;color:#6b7280;font-size:11px;font-weight:500;line-height:1.15}
.prof-act{align-items:flex-start;border-bottom:1px solid #eef0f4;padding:8px 0}
.prof-act:last-of-type{border-bottom:0}
.prof-act-main{display:flex;gap:8px;min-width:0}
.prof-dot2{width:17px;height:17px;border-radius:50%;border:1px solid #9ca3af;display:grid;place-items:center;flex:0 0 auto}
.prof-dot2:after{content:'';width:5px;height:5px;border-radius:50%;background:#6b7280}
.prof-act-text{font-size:12px;font-weight:500;color:#374151}
.prof-time{font-size:11px;font-weight:500;color:#7c8797;white-space:nowrap}
.prof-toast{position:fixed;left:50%;top:88px;right:auto;bottom:auto;z-index:100000;min-width:260px;max-width:420px;border:1px solid #111827;border-radius:10px;padding:12px 15px;background:#111827;color:#fff;box-shadow:none;display:flex;align-items:center;justify-content:center;gap:10px;font-family:var(--title);font-size:12px;font-weight:600;letter-spacing:0;transform:translateX(-50%)}
.prof-modal{position:fixed;inset:0;z-index:80;background:rgba(15,23,42,.56);display:grid;place-items:center;padding:20px}
.prof-crop{width:min(640px,100%);background:#fff;border-radius:8px;border:1px solid #111827;box-shadow:none;overflow:hidden}
.prof-crop-head{padding:18px 20px;border-bottom:1px solid #eef0f4;display:flex;align-items:flex-start;justify-content:space-between;gap:16px}
.prof-crop-head h3{margin:0;font-family:var(--title);font-size:18px;font-weight:600;color:#111827;letter-spacing:.005em}
.prof-crop-sub{margin:3px 0 0;color:#6b7280;font-size:12px;font-weight:400;line-height:1.45}
.prof-x{border:1px solid var(--p-line);background:#fff;border-radius:11px;width:36px;height:36px;cursor:pointer;color:#111827;display:grid;place-items:center;transition:background .18s ease,border-color .18s ease,color .18s ease}
.prof-x:hover{background:#111827;border-color:#111827;color:#fff}
.prof-stage{height:390px;background:#090d16;position:relative;overflow:hidden;display:grid;place-items:center;touch-action:none;user-select:none;cursor:grab}
.prof-stage.is-dragging{cursor:grabbing}
.prof-stage:before{content:'';position:absolute;inset:0;background:linear-gradient(rgba(255,255,255,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.045) 1px,transparent 1px),radial-gradient(circle at center,rgba(255,255,255,.05),transparent 58%);background-size:24px 24px,24px 24px,100% 100%;pointer-events:none}
.prof-stage:after{content:'';position:absolute;width:var(--crop);height:var(--crop);border-radius:50%;box-shadow:0 0 0 999px rgba(0,0,0,.54);border:3px solid #fff;pointer-events:none}
.prof-stage img{position:absolute;left:50%;top:50%;width:var(--w);height:var(--h);max-width:none;max-height:none;user-select:none;pointer-events:none;transform:translate(-50%,-50%) translate(var(--x),var(--y)) scale(var(--z));transform-origin:center;will-change:transform}
.prof-stage-tip{position:absolute;left:50%;bottom:18px;transform:translateX(-50%);z-index:2;padding:7px 12px;border-radius:999px;background:rgba(255,255,255,.92);color:#111827;font-size:11px;font-weight:600;letter-spacing:.01em;box-shadow:0 8px 20px rgba(0,0,0,.16);pointer-events:none}
.prof-crop-body{padding:16px 20px;background:#fff}
.prof-crop-toolrow{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:14px}
.prof-preview-wrap{display:flex;align-items:center;gap:10px;min-width:0}
.prof-preview{width:58px;height:58px;border-radius:50%;overflow:hidden;position:relative;background:#f3f4f6;border:2px solid #fff;box-shadow:0 8px 22px rgba(15,23,42,.14);flex:0 0 auto}
.prof-preview img{position:absolute;left:50%;top:50%;width:var(--pw);height:var(--ph);max-width:none;max-height:none;transform:translate(-50%,-50%) translate(var(--px),var(--py)) scale(var(--pz));transform-origin:center;pointer-events:none;user-select:none}
.prof-crop-help{min-width:0}
.prof-crop-help strong{display:block;color:#111827;font-family:var(--title);font-size:13px;font-weight:600;letter-spacing:.006em;line-height:1.2}
.prof-crop-help span{display:block;margin-top:3px;color:#6b7280;font-size:11px;font-weight:400;line-height:1.35}
.prof-zoom-pct{min-width:48px;text-align:right;color:#111827;font-size:12px;font-weight:700}
.prof-zoom-row{display:grid;grid-template-columns:38px minmax(0,1fr) 38px;align-items:center;gap:10px}
.prof-icon-btn{width:38px;height:38px;min-width:0;padding:0;border:1px solid var(--p-line2);border-radius:11px;background:#fff;color:#111827;display:grid;place-items:center;cursor:pointer;font-size:18px;font-weight:700;box-shadow:0 6px 16px rgba(15,23,42,.04);transition:background .18s ease,border-color .18s ease,color .18s ease,box-shadow .18s ease}
.prof-icon-btn:hover,.prof-icon-btn:focus{background:#111827;border-color:#111827;color:#fff;box-shadow:0 12px 24px rgba(17,24,39,.20)}
.prof-range-wrap{min-width:0}
.prof-range-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:5px}
.prof-range{width:100%;accent-color:var(--p-orange);display:block}
.prof-controls{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:13px}
.prof-crop-actions{display:flex;justify-content:flex-end;gap:10px;padding:15px 20px;border-top:1px solid #eef0f4;background:#fff}
@media(max-width:1180px){.profile-grid{grid-template-columns:1fr}
.profile-right{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media(max-width:760px){.profile-shell{padding:0 12px 18px}
.profile-head{display:grid}.profile-date{width:100%}
.prof-hero,.prof-main{flex-direction:column;align-items:flex-start}
.prof-actions{width:100%;grid-template-columns:1fr 1fr}
.prof-fields,.profile-right{grid-template-columns:1fr}
.prof-field{grid-template-columns:1fr;gap:4px;padding:8px 0}
.prof-savebar{position:static;flex-direction:column;align-items:stretch}
.prof-save-actions{display:grid;grid-template-columns:1fr 1fr}
.prof-btn{width:100%;min-width:0}
}
@media(max-width:480px){.prof-actions,.prof-save-actions,.prof-stats,.prof-controls{grid-template-columns:1fr}
.prof-stage{height:300px}
.prof-name{font-size:32px}
}
.profile-page .profile-grid{align-items:stretch!important}
.profile-page .profile-left{border:1px solid #111827!important;border-radius:8px!important;background:#fff!important;padding:18px!important;gap:0!important}
.profile-page .profile-left>.prof-card{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important}
.profile-page .profile-left>.prof-hero{padding:0 0 18px!important;border-bottom:1px solid #111827!important}
.profile-page .profile-left>.prof-info{padding:18px 0 0!important}
.profile-page .profile-left>.prof-card:hover{background:transparent!important;box-shadow:none!important}
.profile-page .profile-right{border:0!important;background:transparent!important;box-shadow:none!important;align-content:start!important}
.profile-page .profile-right>.prof-widget{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important;padding:0!important}
.profile-page .profile-right>.prof-widget:hover{border:0!important;background:transparent!important;box-shadow:none!important}
.profile-page .profile-right>.prof-widget:first-child,.profile-page .profile-right>.prof-widget:nth-child(3){display:none!important}
.profile-page .profile-right .prof-pref,.profile-page .profile-right .prof-act{border-bottom:1px solid #eef1f5!important}
.profile-page .profile-right .prof-link{width:100%!important}

</style>

<div class="profile-page" x-data="printifyProfile()" x-init="init()">
    <div class="profile-shell">
<div class="profile-head">
<div class="profile-title-wrap">
<div>
<h1 class="profile-title">My Profile</h1>
<p class="profile-sub">Manage your customer account, photo, and communication preferences.</p>
</div>
</div>
<div class="profile-date">
<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
<path d="M8 2v4M16 2v4M3 10h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
<path d="M5 5h14a2 2 0 0 1 2 2v14H3V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
</svg>
<span>Today is {{ now()->format('M d, Y') }}</span>
</div>
</div>
<div class="profile-grid">
<main class="profile-left">
        <section class="prof-card prof-hero">
<div class="prof-main">
<div class="prof-avatar-wrap">
            <div class="prof-avatar">
<template x-if="photo">
<img :src="photo" alt="Profile photo">
</template>
<template x-if="!photo">
<span class="prof-avatar-initials" x-text="initials">
</span>
</template>
</div>
            <button class="prof-camera-btn" type="button" title="Change profile photo" aria-label="Change profile photo" @click="$refs.profilePhotoInput.click()">
<svg width="17" height="17" viewBox="0 0 24 24" fill="none">
<path d="M9 6l1.5-2h3L15 6h3a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V9a3 3 0 0 1 3-3h3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
<circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="2"/>
</svg>
</button>
            <span class="prof-online">
</span>
<input x-ref="profilePhotoInput" id="profilePhoto" type="file" accept="image/*" hidden @change="openCrop($event)">
        </div>
<div>
<h1 class="prof-name" x-text="fullName">
</h1>
<p class="prof-role">Customer Account</p>
<p class="prof-loc">
<svg width="14" height="14" viewBox="0 0 24 24" fill="none">
<path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z" stroke="currentColor" stroke-width="2"/>
<circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
</svg>
<span x-text="p.city">
</span>
</p>
<div class="prof-chips">
<span class="prof-chip">✓ Verified Email</span>
<span class="prof-chip">● Active Account</span>
</div>
</div>
</div>
        <div class="prof-actions">
<button class="prof-btn prof-light" type="button" @click="$refs.profilePhotoInput.click()">
<svg width="15" height="15" viewBox="0 0 24 24" fill="none">
<path d="M12 16V4m0 0 4 4m-4-4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
</svg>Upload Photo</button>
<button class="prof-btn prof-primary" type="button" @click="editAll()">
<svg width="15" height="15" viewBox="0 0 24 24" fill="none">
<path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
<path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
</svg>
<span x-text="editing ? 'Editing Profile' : 'Edit Profile'">
</span>
</button>
</div>
</section>

        <section class="prof-card prof-info">
<div class="prof-head">
<div class="prof-title-wrap">
<div>
<h2 class="prof-card-title">Profile Information</h2>
<p class="prof-subtitle">Personal Information, Address Details, and Account Details are combined in one clean box.</p>
</div>
</div>
<button class="prof-mini prof-primary" type="button" @click="editAll()" x-show="!editing">
<svg width="14" height="14" viewBox="0 0 24 24" fill="none">
<path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
<path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
</svg>Edit</button>
</div>
        <div class="prof-section">
<h3 class="prof-section-title">
<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
<path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
<circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
</svg>Personal Information</h3>
<div class="prof-fields">
<template x-for="f in personal" :key="f.k">
<div class="prof-field">
<span class="prof-label" x-text="f.l">
</span>
<template x-if="!editing">
<span class="prof-value" x-text="p[f.k]">
</span>
</template>
<template x-if="editing">
<input class="prof-input" :type="f.t || 'text'" x-model="p[f.k]" @input="markDirty()">
</template>
</div>
</template>
</div>
</div>
        <div class="prof-section">
<h3 class="prof-section-title">
<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
<path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z" stroke="currentColor" stroke-width="2"/>
<circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
</svg>Address Details</h3>
<div class="prof-fields">
<template x-for="f in address" :key="f.k">
<div class="prof-field">
<span class="prof-label" x-text="f.l">
</span>
<template x-if="!editing">
<span class="prof-value" x-text="p[f.k]">
</span>
</template>
<template x-if="editing">
<input class="prof-input" type="text" x-model="p[f.k]" @input="markDirty()">
</template>
</div>
</template>
</div>
</div>
        <div class="prof-section">
<h3 class="prof-section-title">
<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2"/>
<path d="m9 12 2 2 4-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
</svg>Account Details</h3>
<div class="prof-fields">
<div class="prof-field">
<span class="prof-label">Username</span>
<span class="prof-value" x-text="p.email">
</span>
</div>
<div class="prof-field">
<span class="prof-label">Last Login</span>
<span class="prof-value" x-text="p.lastLogin">
</span>
</div>
<div class="prof-field">
<span class="prof-label">Member Since</span>
<span class="prof-value" x-text="p.memberSince">
</span>
</div>
<div class="prof-field">
<span class="prof-label">Two-Factor Auth</span>
<span class="prof-badge">Enabled</span>
</div>
<div class="prof-field">
<span class="prof-label">Account Type</span>
<span class="prof-value">Customer</span>
</div>
<div class="prof-field">
<span class="prof-label">Account Status</span>
<span class="prof-badge">Active</span>
</div>
</div>
</div>
        <div class="prof-savebar" x-show="editing || dirty || photoDirty" x-transition x-cloak>
<p class="prof-save-text" x-text="photoDirty && !dirty ? 'Profile photo preview is ready. Save Changes will update the photo only.' : (dirty ? 'You have unsaved changes. Click Save Changes to apply them.' : 'Edit mode is active. Change any field to enable saving.')">
</p>
<div class="prof-save-actions">
<button class="prof-btn prof-light" type="button" @click="cancel()" :disabled="saving">Cancel</button>
<button class="prof-btn prof-primary" type="button" @click="save()" :disabled="saving || (!dirty && !photoDirty)">
<span x-text="saving ? 'Saving...' : 'Save Changes'">
</span>
</button>
</div>
</div>
        </section>
</main>

        <aside class="profile-right">
<section class="prof-card prof-widget">
<div class="prof-complete">
<div class="prof-ring">
<span>88%</span>
</div>
<div>
<h3 class="prof-widget-title" style="margin-bottom:4px">Profile Completion</h3>
<p class="prof-widget-copy">Great job! Complete your profile to get the most out of your account.</p>
</div>
</div>
<div class="prof-progress">
<span>
</span>
</div>
<button class="prof-link" type="button" @click="suggest = !suggest">
<span>View Suggestions</span>
<span>›</span>
</button>
<div class="prof-suggest" x-show="suggest" x-transition x-cloak>Add alternate contact number and verify your address to reach 100% completion.</div>
</section>
        <section class="prof-card prof-widget">
<h3 class="prof-widget-title">
<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
<path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z" stroke="currentColor" stroke-width="2"/>
</svg>Communication Preferences</h3>
<template x-for="pr in prefs" :key="pr.k">
<div class="prof-pref">
<span class="prof-pref-label" x-text="pr.l">
</span>
<div class="prof-pref-side">
<button type="button" class="prof-toggle" :class="pr.on ? 'is-on' : 'is-off'" :aria-pressed="pr.on.toString()" @click="togglePref(pr)">
<span class="prof-toggle-knob">
<template x-if="pr.on">
<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
<path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
</template>
<template x-if="!pr.on">
<svg width="13" height="13" viewBox="0 0 24 24" fill="none">
<path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
</svg>
</template>
</span>
</button>
<span class="prof-pref-state" :class="pr.on ? 'on' : 'off'" x-text="pr.on ? 'On' : 'Off'">
</span>
</div>
</div>
</template>
<button class="prof-link prof-link-primary" type="button" @click="toastMsg('Preferences can be edited here. Click Save Changes after toggling.')">
<span>Manage Preferences</span>
<span>›</span>
</button>
</section>
        <section class="prof-card prof-widget">
<h3 class="prof-widget-title">
<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
<path d="M4 19V9M10 19V5M16 19v-7M22 19H2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
</svg>Quick Stats</h3>
<div class="prof-stats">
<template x-for="s in stats" :key="s.l">
<div class="prof-stat">
<span class="prof-ico" :class="s.c" x-html="s.i">
</span>
<span>
<span class="prof-num" x-text="s.n">
</span>
<span class="prof-small" x-text="s.l">
</span>
</span>
</div>
</template>
</div>
</section>
        <section class="prof-card prof-widget">
<h3 class="prof-widget-title">
<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
<path d="M12 8v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
<path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-width="2"/>
</svg>Recent Activity</h3>
<template x-for="a in shownActs" :key="a.t">
<div class="prof-act">
<div class="prof-act-main">
<span class="prof-dot2">
</span>
<span class="prof-act-text" x-text="a.t">
</span>
</div>
<span class="prof-time" x-text="a.d">
</span>
</div>
</template>
<button class="prof-link prof-link-primary" type="button" @click="allActs = true" x-show="!allActs" x-transition>
<span>View All Activity</span>
<span>›</span>
</button>
<button class="prof-link" type="button" @click="allActs = false" x-show="allActs" x-transition x-cloak>
<span>Show Less Activity</span>
<span>›</span>
</button>
</section>
</aside>
    </div>
</div>

    <div class="prof-modal" x-show="crop.open" x-transition x-cloak>
<div class="prof-crop">
<div class="prof-crop-head">
<div>
<h3>Adjust Profile Photo</h3>
<p class="prof-crop-sub">Drag to reposition, use the zoom control, then apply your circular profile photo.</p>
</div>
<button class="prof-x" type="button" @click="closeCrop()" aria-label="Close photo cropper">
<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
<path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
</svg>
</button>
</div>
<div class="prof-stage" :class="crop.drag ? 'is-dragging' : ''" :style="'--x:' + crop.x + 'px; --y:' + crop.y + 'px; --z:' + crop.z + '; --w:' + crop.baseW + 'px; --h:' + crop.baseH + 'px; --crop:' + crop.cropSize + 'px;'" @wheel.prevent="wheelZoom($event)" @pointerdown.prevent="dragStart($event)">
<img :src="crop.src" alt="Crop preview" draggable="false">
<div class="prof-stage-tip">Drag photo to reposition</div>
</div>
<div class="prof-crop-body">
<div class="prof-crop-toolrow">
<div class="prof-preview-wrap">
<div class="prof-preview" :style="previewVars()">
<img :src="crop.src" alt="Circular preview" draggable="false">
</div>
<div class="prof-crop-help">
<strong>Live profile preview</strong>
<span>This is how your avatar will look after applying.</span>
</div>
</div>
<div class="prof-zoom-pct" x-text="Math.round(crop.z * 100) + '%'">
</div>
</div>
<div class="prof-zoom-row">
<button class="prof-icon-btn" type="button" @click="zoomBy(-0.08)" aria-label="Zoom out">−</button>
<div class="prof-range-wrap">
<div class="prof-range-head">
<label class="prof-label">Zoom</label>
<span class="prof-crop-sub" x-text="crop.z > 1 ? 'Enhanced crop mode' : 'Original fit'">
</span>
</div>
<input class="prof-range" type="range" min="1" max="2.8" step="0.01" x-model.number="crop.z">
</div>
<button class="prof-icon-btn" type="button" @click="zoomBy(0.08)" aria-label="Zoom in">+</button>
</div>
<div class="prof-controls">
<button class="prof-btn prof-light" type="button" @click="resetCrop()">Reset</button>
<button class="prof-btn prof-light" type="button" @click="centerCrop()">Center</button>
<button class="prof-btn prof-light" type="button" @click="crop.y -= 10">Move Up</button>
<button class="prof-btn prof-light" type="button" @click="crop.y += 10">Move Down</button>
<button class="prof-btn prof-light" type="button" @click="crop.x -= 10">Move Left</button>
<button class="prof-btn prof-light" type="button" @click="crop.x += 10">Move Right</button>
<button class="prof-btn prof-light" type="button" @click="zoomBy(-0.08)">Zoom Out</button>
<button class="prof-btn prof-light" type="button" @click="zoomBy(0.08)">Zoom In</button>
</div>
</div>
<div class="prof-crop-actions">
<button class="prof-btn prof-light" type="button" @click="closeCrop()">Cancel</button>
<button class="prof-btn prof-primary" type="button" @click="applyCrop()">Apply Photo</button>
</div>
</div>
</div>
    <div class="prof-toast" x-show="toast.show" x-transition x-cloak>
<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
<path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<span x-text="toast.text">
</span>
</div>
</div>

<script>
function printifyProfile(){return{
csrf:@json(csrf_token()),updateUrl:@json(Route::has('profile.update')?route('profile.update'):url()->current()),saving:false,editing:false,dirty:false,photoDirty:false,suggest:false,allActs:false,photo:@json($photo),saved:null,toast:{show:false,text:''},to:null,
crop:{open:false,src:'',x:0,y:0,z:1,file:null,drag:false,sx:0,sy:0,ox:0,oy:0,naturalW:0,naturalH:0,baseW:270,baseH:270,cropSize:270},
p:{firstName:@json($firstName),lastName:@json($lastName),email:@json($user->email ?? ''),phone:@json($user->phone ?? ''),birthdate:@json($user->birthdate ?? ''),gender:@json($user->gender ?? ''),street:@json($user->street ?? ''),barangay:@json($user->barangay ?? ''),region:@json($user->region ?? ''),city:@json($user->city ?? ''),postalCode:@json($user->postal_code ?? ''),memberSince:@json(optional($user->created_at)->format('F d, Y') ?? ''),lastLogin:'Current session'},
personal:[{k:'firstName',l:'First Name'},{k:'lastName',l:'Last Name'},{k:'email',l:'Email Address',t:'email'},{k:'phone',l:'Phone Number'},{k:'birthdate',l:'Birthdate'},{k:'gender',l:'Gender'}],address:[{k:'street',l:'Street Address'},{k:'region',l:'Region'},{k:'barangay',l:'Barangay'},{k:'city',l:'City'},{k:'postalCode',l:'Postal Code'}],prefs:[{k:'email',l:'Email Notifications',on:true},{k:'sms',l:'SMS Notifications',on:true},{k:'marketing',l:'Marketing Updates',on:false},{k:'orders',l:'Order Updates',on:true}],
stats:[{n:24,l:'Total Orders',c:'ico-orange',i:'<svg width="19" height="19" viewBox="0 0 24 24" fill="none"><path d="M6 7h12l1 14H5L6 7Z" stroke="currentColor" stroke-width="2"/><path d="M9 7a3 3 0 0 1 6 0" stroke="currentColor" stroke-width="2"/></svg>'},{n:18,l:'Completed Orders',c:'ico-green',i:'<svg width="19" height="19" viewBox="0 0 24 24" fill="none"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5" stroke="currentColor" stroke-width="2"/></svg>'},{n:7,l:'Saved Designs',c:'ico-purple',i:'<svg width="19" height="19" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z" stroke="currentColor" stroke-width="2"/><path d="M14 2v6h6M9 15l2 2 4-5" stroke="currentColor" stroke-width="2"/></svg>'},{n:3,l:'Pending Jobs',c:'ico-yellow',i:'<svg width="19" height="19" viewBox="0 0 24 24" fill="none"><path d="M12 8v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-width="2"/></svg>'}],
acts:[{t:'Order #PRN-2026-0054 completed',d:'May 31, 2026 11:24 AM'},{t:'Uploaded design for Order #PRN-2026-0055',d:'May 31, 2026 10:43 AM'},{t:'Updated profile information',d:'May 31, 2026 10:15 AM'},{t:'Changed communication preference settings',d:'May 30, 2026 6:10 PM'},{t:'Logged in successfully',d:'May 30, 2026 5:58 PM'}],
init(){this.saved=this.clone({p:this.p,photo:this.photo,prefs:this.prefs});this.syncHeader();window.addEventListener('pointermove',e=>this.dragMove(e));window.addEventListener('pointerup',()=>this.dragEnd());window.addEventListener('pointercancel',()=>this.dragEnd())},
get fullName(){return `${this.p.firstName} ${this.p.lastName}`.trim()},get initials(){return ((this.p.firstName?.[0]||'E')+(this.p.lastName?.[0]||'A')).toUpperCase()},get shownActs(){return this.allActs?this.acts:this.acts.slice(0,3)},clone(v){return JSON.parse(JSON.stringify(v))},editAll(){this.editing=true;this.toastMsg('Edit mode enabled. Save Changes will appear after edits.')},markDirty(){this.dirty=true},markPhotoDirty(){this.photoDirty=true},togglePref(pr){pr.on=!pr.on;this.editing=true;this.markDirty();this.toastMsg(`${pr.l} turned ${pr.on?'On':'Off'}. Click Save Changes to keep it.`)},cancel(){let d=this.clone(this.saved);this.p=d.p;this.photo=d.photo;this.prefs=d.prefs;this.editing=false;this.dirty=false;this.photoDirty=false;this.syncHeader();this.toastMsg('Changes cancelled.')},
profilePayload(source=this.p,prefs=this.prefs){return{first_name:source.firstName,last_name:source.lastName,name:`${source.firstName||''} ${source.lastName||''}`.trim(),email:source.email,phone:source.phone,birthdate:source.birthdate,gender:source.gender,street:source.street,barangay:source.barangay,region:source.region,city:source.city,postal_code:source.postalCode,preferences:prefs}},
async save(){this.saving=true;let wasPhotoOnly=this.photoDirty&&!this.dirty,base=this.dirty?this.profilePayload(this.p,this.prefs):this.profilePayload(this.saved.p,this.saved.prefs);let payload={...base};if(this.photoDirty)payload.photo_base64=this.photo;let serverSaved=false;try{let r=await fetch(this.updateUrl,{method:'PATCH',headers:{'X-CSRF-TOKEN':this.csrf,'Accept':'application/json','Content-Type':'application/json'},body:JSON.stringify(payload)});serverSaved=r.ok;if(!r.ok)throw new Error('Profile save failed')}catch(e){this.saving=false;this.toastMsg('Profile was not saved. Please check the details and try again.');return}this.saved=this.clone({p:this.p,photo:this.photo,prefs:this.prefs});this.editing=false;this.dirty=false;this.photoDirty=false;this.saving=false;this.syncHeader();this.toastMsg(serverSaved?(wasPhotoOnly?'Profile photo updated successfully.':'Profile updated successfully.'):'Profile saved successfully.')},
openCrop(e){let f=e.target.files[0];e.target.value='';if(!f)return;if(!f.type.startsWith('image/')){this.toastMsg('Please upload a valid image file.');return}let src=URL.createObjectURL(f),img=new Image();img.onload=()=>{let cropSize=286,aspect=img.naturalWidth/img.naturalHeight,baseW=cropSize,baseH=cropSize;if(aspect>=1){baseW=cropSize*aspect}else{baseH=cropSize/aspect}this.crop={open:true,src,x:0,y:0,z:1,file:f,drag:false,sx:0,sy:0,ox:0,oy:0,naturalW:img.naturalWidth,naturalH:img.naturalHeight,baseW,baseH,cropSize};this.toastMsg('Drag, zoom, preview, then apply the profile photo.')};img.src=src},closeCrop(){this.crop.open=false;this.crop.drag=false},resetCrop(){this.crop.x=0;this.crop.y=0;this.crop.z=1},centerCrop(){this.crop.x=0;this.crop.y=0},clampZoom(v){return Math.max(1,Math.min(2.8,Number(v.toFixed(2))))},zoomBy(v){this.crop.z=this.clampZoom(this.crop.z+v)},wheelZoom(e){this.zoomBy((e.deltaY < 0 ? 0.06 : -0.06))},previewVars(){let preview=58,scale=preview/(this.crop.cropSize||286);return `--pw:${this.crop.baseW*scale}px; --ph:${this.crop.baseH*scale}px; --px:${this.crop.x*scale}px; --py:${this.crop.y*scale}px; --pz:${this.crop.z};`},dragStart(e){if(!this.crop.open)return;this.crop.drag=true;this.crop.sx=e.clientX;this.crop.sy=e.clientY;this.crop.ox=this.crop.x;this.crop.oy=this.crop.y;if(e.currentTarget&&e.currentTarget.setPointerCapture){try{e.currentTarget.setPointerCapture(e.pointerId)}catch(err){}}},dragMove(e){if(!this.crop.drag||!e)return;this.crop.x=this.crop.ox+(e.clientX-this.crop.sx);this.crop.y=this.crop.oy+(e.clientY-this.crop.sy)},dragEnd(){this.crop.drag=false},
applyCrop(){let img=new Image();img.onload=()=>{let c=document.createElement('canvas'),ctx=c.getContext('2d'),out=512,crop=this.crop.cropSize||286,f=out/crop;c.width=out;c.height=out;ctx.fillStyle='#fff';ctx.fillRect(0,0,out,out);ctx.save();ctx.beginPath();ctx.arc(out/2,out/2,out/2,0,Math.PI*2);ctx.clip();let w=this.crop.baseW*this.crop.z*f,h=this.crop.baseH*this.crop.z*f,x=out/2-w/2+this.crop.x*f,y=out/2-h/2+this.crop.y*f;ctx.drawImage(img,x,y,w,h);ctx.restore();this.photo=c.toDataURL('image/png');this.crop.open=false;this.crop.drag=false;this.markPhotoDirty();this.syncHeader();this.toastMsg('Photo preview applied. Save Changes updates photo only.')};img.src=this.crop.src},
syncHeader(){let d={name:this.fullName,photo:this.photo,initials:this.initials};window.dispatchEvent(new CustomEvent('printify-profile-updated',{detail:d}));document.querySelectorAll('[data-profile-name],.header-profile-name,.user-name').forEach(el=>el.textContent=d.name);document.querySelectorAll('[data-profile-avatar],.header-profile-img,.profile-avatar-img').forEach(el=>{if(d.photo){if(el.tagName==='IMG')el.src=d.photo;else el.style.backgroundImage=`url(${d.photo})`}});document.querySelectorAll('[data-profile-initials],.header-profile-initials').forEach(el=>el.textContent=d.initials)},toastMsg(t){this.toast.text=t;this.toast.show=true;clearTimeout(this.to);this.to=setTimeout(()=>this.toast.show=false,2400)}
}}
</script></x-app-layout>
