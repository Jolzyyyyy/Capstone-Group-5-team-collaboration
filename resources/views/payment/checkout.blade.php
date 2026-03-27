<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout</title>

    <style>
        :root{
            --bg:#f6f7fb;
            --card:#ffffff;
            --muted:#6b7280;
            --text:#111827;
            --line:#e5e7eb;
            --soft:#f3f4f6;
            --soft2:#f9fafb;
            --shadow: 0 12px 40px rgba(0,0,0,.08);
            --shadow2: 0 6px 18px rgba(0,0,0,.06);
            --radius: 16px;
            --radius-sm: 12px;

            --brand:#2563eb;
            --success:#16a34a;
            --danger:#b00020;

            --promo:#ec4899;

            /* Shopee-like payment method */
            --pm-red:#ef4444;
            --pm-border:#ececec;
            --pm-gray:#d1d5db;
        }

        *{ box-sizing:border-box; }

        body {
            font-family: Arial, sans-serif;
            background: radial-gradient(900px 420px at 20% -10%, rgba(37,99,235,.12), transparent 60%),
                        radial-gradient(900px 420px at 80% -10%, rgba(22,163,74,.10), transparent 60%),
                        var(--bg);
            color: var(--text);
            margin: 0;
        }

        /* ✅ MAS PA-LEFT at PA-RIGHT yung cards */
        .wrap {
            max-width: 1480px;
            width: calc(100% - 32px);
            margin: 44px auto;
            padding: 0 0 44px;
            text-align: left;
        }

        /* =========================
           ✅ FIXED HEADER POSITIONING (ITO YUNG NAGPABAGO)
           - Pinipilit natin na ang header ay same width ng .grid (600 + 600 + 130 = 1330px)
           - Para: Checkout title nasa gitna ng SHIPPING column
                   Steps nasa gitna ng ORDER SUMMARY column
           ========================= */
        .header-row{
            width: 1330px !important;         /* 600 + 600 + 130 */
            max-width: 100% !important;
            margin: 0 auto 14px !important;   /* center the whole header block */
            display: grid !important;         /* override yung dating flex behavior */
            grid-template-columns: 600px 600px !important;
            column-gap: 130px !important;
            align-items: end !important;
        }
        /* Checkout block (left column) */
        .header-row > div:first-child{
            justify-self: center !important;  /* gitna ng 600px left column */
            text-align: center !important;
            transform: translateX(18px);      /* konting usog pa-right (pwede 0-30px) */
        }
        /* Steps block (right column) */
        .header-row > .steps{
            justify-self: center !important;  /* gitna ng 600px right column */
            justify-content: center !important;
        }

        .page-title{
            font-size: 34px;
            margin: 0 0 6px;
            font-weight: 900;
            letter-spacing: .2px;
        }
        .page-subtitle{
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            font-weight: 400;
        }

        .steps{
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
            justify-content:flex-end;
        }
        .step{
            display:flex;
            align-items:center;
            gap:8px;
            font-size:12px;
            color: var(--muted);
            white-space:nowrap;
            font-weight: 600;
        }
        .step .bubble{
            width:22px; height:22px;
            border-radius:999px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            border:1px solid var(--line);
            background: #fff;
            color:#374151;
        }
        .step.active{ color: var(--brand); }
        .step.active .bubble{
            border-color: rgba(37,99,235,.35);
            background: rgba(37,99,235,.10);
            color: var(--brand);
        }
        .step .arrow{
            color:#9ca3af;
            margin-left: 2px;
            margin-right: 2px;
        }

        .msg {
            margin: 10px 0 18px;
            max-width: 1180px;
        }
        .error {
            color: var(--danger);
            background:#fff;
            border:1px solid #f2b8c6;
            padding:12px 14px;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow2);
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 12px;
        }
        .empty {
            color: #555;
            background:#fff;
            border:1px solid var(--line);
            padding:12px 14px;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow2);
            font-size: 12px;
            font-weight: 600;
        }

        /* ✅ MAS MALAKING DISTANCE BETWEEN SHIPPING and ORDER SUMMARY */
        .grid{
            display: grid;
            grid-template-columns: 600px 600px; /* ✅ mas maliit */
            justify-content: center;
            column-gap: 130px;
            row-gap: 34px;
            align-items: stretch;
        }

        @media (max-width: 1160px){
            .grid{
                grid-template-columns: 1fr;
                column-gap: 0;
            }
            .steps{ justify-content:flex-start; }
            .wrap{
                width: calc(100% - 28px);
            }

            /* ✅ Mobile: stack header */
            .header-row{
                width: 100% !important;
                grid-template-columns: 1fr !important;
                column-gap: 0 !important;
                row-gap: 10px !important;
                justify-items: start !important;
                margin: 0 0 14px !important;
            }
            .header-row > div:first-child{
                transform: none !important;
                text-align: left !important;
                justify-self: start !important;
            }
            .header-row > .steps{
                justify-self: start !important;
                justify-content: flex-start !important;
            }
        }

        .card {
            height: 100%;
            background: var(--card);
            border: 1px solid rgba(229,231,235,.9);
            border-radius: var(--radius);
            padding: 12px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            display:flex;
            flex-direction:column;
        }
        .card::before{
            content:"";
            position:absolute;
            inset:0;
            pointer-events:none;
            background: linear-gradient(180deg, rgba(37,99,235,.06), transparent 45%);
            opacity:.9;
        }
        .card > *{ position:relative; }

        .card-title{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 10px;
            margin-bottom: 6px;
        }
        .card-title h3{
            margin:0;
            font-size: 14px;
            font-weight: 900;
            letter-spacing:.2px;
        }
        .hint{
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--soft2);
            border: 1px solid var(--line);
            color: #374151;
            font-size: 11px;
            white-space: nowrap;
            font-weight: 700;
        }
        .dot{
            width:8px; height:8px; border-radius:999px;
            background: var(--success);
            box-shadow: 0 0 0 3px rgba(22,163,74,.15);
        }

        .secure-badge{ display:none !important; }

        .section-label{
            font-size: 10px;
            color: var(--muted);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .07em;
            margin: 10px 0 8px;
        }

        .form-grid{
            display:grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .form-grid-2{
            display:grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        @media (max-width: 520px){
            .form-grid, .form-grid-2{ grid-template-columns: 1fr; }
        }

        label{
            display:block;
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 6px;
            font-weight: 800;
        }

        input[type="text"], input[type="email"], input[type="tel"], textarea, select{
            width: 100%;
            padding: 9px 10px;
            border:1px solid var(--line);
            border-radius: 12px;
            background:#fff;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
            font-size: 12px;
            font-weight: 400;
        }
        textarea{ min-height: 38px; resize: vertical; }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        textarea:focus,
        select:focus{
            border-color:#cbd5e1;
            box-shadow: none;
            outline: none;
        }

        input, select, textarea, button{ outline: none; }
        input:focus-visible, select:focus-visible, textarea:focus-visible, button:focus-visible{ outline: none; }

        .divider{
            height:1px;
            background: var(--line);
            margin: 10px 0;
        }
        .muted{
            color: var(--muted);
            font-size: 11px;
            line-height: 1.4;
            font-weight: 400;
        }

        .pin-row{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            margin-top: 8px;
        }
        .pin-btn{
            border:1px solid var(--line);
            background: var(--soft2);
            color:#374151;
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            cursor:pointer;
            display:inline-flex;
            align-items:center;
            gap:8px;
            white-space:nowrap;
        }
        .pin-btn:hover{ opacity:.92; }
        .pin-ic{
            width:10px; height:10px; border-radius:999px;
            background: var(--brand);
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }

        .contact-scope input,
        .contact-scope select,
        .address-scope input,
        .address-scope select{
            border-radius: 8px !important;
        }

        /* =========================
           ORDER SUMMARY (padding/gaps + qty stepper)
           ========================= */
        .order-shell{
            background: var(--soft2);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 10px;
            margin-bottom: 8px;
        }

        .order-item{
            display:flex;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 0;
            background: transparent;
            border: 0;
            box-shadow: none;
            margin-bottom: 0;
            align-items:center;
        }
        .order-item + .order-item{
            border-top: 1px solid var(--line);
        }

        .thumb{
            width: 52px;
            height: 52px;
            border-radius: 0;
            border:1px solid var(--line);
            background: linear-gradient(180deg, var(--soft2), var(--soft));
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            flex: 0 0 auto;
        }
        .thumb img{
            width:100%;
            height:100%;
            object-fit: cover;
            display:block;
        }
        .thumb .ph{
            font-size: 11px;
            color: var(--muted);
            font-weight: 700;
        }

        .item-main{
            flex: 1 1 auto;
            min-width: 0;
        }
        .item-name{
            font-weight: 900;
            font-size: 12px;
            margin: 0 0 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .item-meta{
            display:flex;
            gap:10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: 11px;
            font-weight: 400;
        }
        .pill-mini{
            display:inline;
            padding: 0;
            border-radius: 0;
            border: 0;
            background: transparent;
            color:#374151;
            font-size: 11px;
            font-weight: 400;
        }

        .item-right{
            text-align:right;
            flex: 0 0 auto;
            min-width: 148px;
            padding-left: 10px;
        }

        .qty-wrap{
            display:flex;
            align-items:center;
            justify-content:flex-end;
            gap:8px;
        }
        .qty-btn{
            width: 22px;
            height: 22px;
            border-radius: 6px;
            border: 1px solid var(--line);
            background: #fff;
            color:#111827;
            font-weight: 900;
            font-size: 13px;
            line-height: 1;
            cursor:pointer;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }
        .qty-btn:hover{ background: rgba(0,0,0,.02); }
        .qty-btn:active{ transform: translateY(1px); }
        .qty-num{
            min-width: 20px;
            text-align:center;
            font-weight: 800;
            font-size: 12px;
            color:#111827;
        }

        .line-total{
            font-weight: 800;
            font-size: 12px;
            margin-top: 6px;
            display:block;
            padding-right: 2px;
        }

        /* === PAYMENT METHOD (Shopee-like) === */
        .pm-wrap{
            margin-top: 10px;
            background:#fff;
            border:1px solid rgba(229,231,235,.95);
            border-radius: 14px;
            overflow:hidden;
        }

        .pm-title{
            padding: 10px 12px 8px;
            font-weight: 900;
            font-size: 14px;
            color:#111;
        }
        .pm-row{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            padding: 10px 12px;
            border-top: 1px solid var(--pm-border);
            background:#fff;
            cursor:pointer;
            user-select:none;
        }
        .pm-row:first-of-type{ border-top: 0; }
        .pm-row:hover{ background: rgba(0,0,0,.012); }

        .pm-left{
            display:flex;
            align-items:center;
            gap:12px;
            min-width:0;
        }
        .pm-text{ min-width:0; }

        .pm-name{
            font-size: 15px;
            font-weight: 900;
            line-height: 1.15;
            color:#111;
            margin: 0;
        }
        .pm-sub{
            margin: 5px 0 0;
            font-size: 11px;
            font-weight: 400;
            color:#9ca3af;
            line-height: 1.2;
        }

        .pm-icon{
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background:#f2f3f5;
            border:1px solid #e9e9e9;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight: 900;
            color:#111;
            flex: 0 0 auto;
            font-size: 14px;
        }
        .pm-icon.plus{ font-size: 22px; font-weight: 700; }
        .pm-icon.gcash{ color:#2563eb; }
        .pm-icon.maya{ color:#16a34a; }
        .pm-icon.bank{ color:#111; }
        .pm-icon.grab{ color:#16a34a; }

        .pm-right{
            display:flex;
            align-items:center;
            gap:10px;
            flex: 0 0 auto;
        }

        .pm-radio{
            width: 28px;
            height: 28px;
            border-radius: 999px;
            border: 4px solid var(--pm-gray);
            background:#fff;
            position: relative;
        }
        .pm-radio::after{
            content:"";
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: var(--pm-red);
            position:absolute;
            top:50%;
            left:50%;
            transform: translate(-50%,-50%) scale(0);
            transition: transform .12s ease;
        }
        .pm-row.is-active .pm-radio{
            border-color: rgba(239,68,68,.30);
        }
        .pm-row.is-active .pm-radio::after{
            transform: translate(-50%,-50%) scale(1);
        }

        .pm-chevron{
            font-size: 22px;
            color:#9ca3af;
            line-height: 1;
            font-weight: 900;
        }

        .pm-badge{
            display:inline-flex;
            align-items:center;
            margin-top: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(236,72,153,.12);
            color: var(--promo);
            font-weight: 800;
            font-size: 12px;
        }

        .pm-logos{
            margin-top: 8px;
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }
        .logo{
            width: 46px;
            height: 28px;
            border-radius: 10px;
            border:1px solid #e9e9e9;
            background:#f7f7f8;
            position:relative;
            overflow:hidden;
        }
        .logo.mc::before{ content:""; position:absolute; inset:0; background: linear-gradient(90deg,#ff5a5f 0 50%, #f59e0b 50% 100%); opacity:.9; }
        .logo.visa::before{ content:"VISA"; position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-weight:900; color:#1d4ed8; font-size:12px; }
        .logo.amex::before{ content:"AMEX"; position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-weight:900; color:#0284c7; font-size:12px; }
        .logo.up::before{ content:"UP"; position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-weight:900; color:#111; font-size:12px; }

        .pm-divider{ height:1px; background: var(--pm-border); }

        .pm-mini-icons{
            display:flex;
            gap:8px;
            align-items:center;
        }
        .mini{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            height: 24px;
            padding: 0 10px;
            border-radius: 10px;
            border:1px solid #e9e9e9;
            background:#f7f7f8;
            font-weight: 800;
            font-size: 11px;
            color:#111;
        }
        .mini.maya{ color:#16a34a; }

        .pm-viewall{
            font-size: 15px;
            font-weight: 900;
            color:#111;
        }

        .pm-more{ display:none; }
        .pm-more.show{ display:block; }

        .list-card{
            margin-top: 10px;
            background:#fff;
            border:1px solid rgba(229,231,235,.9);
            border-radius: 14px;
            overflow:hidden;
        }
        .list-row{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding: 10px 12px;
            border-top: 1px solid var(--line);
        }
        .list-row:first-child{ border-top: 0; }
        .list-left{
            font-weight: 900;
            color:#111827;
            font-size: 12px;
        }
        .list-right{
            display:flex;
            align-items:center;
            gap:8px;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 400;
            flex: 1 1 auto;
            justify-content:flex-end;
        }
        .chev{ display:none !important; }

        .viewall{
            color:#6b7280;
            background: var(--soft2);
            border:1px solid var(--line);
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;
        }
        .viewall:hover{ opacity:.9; }

        .list-input{
            max-width: 240px;
            width: 100%;
            border-radius: 8px;
        }
        .list-card select{
            border-radius: 8px !important;
        }

        @media (max-width: 980px){
            .list-input{ max-width: 100%; }
        }

        .pay-details{
            margin-top: 10px;
            background:#fff;
            border:1px solid rgba(229,231,235,.9);
            border-radius: 14px;
            padding: 12px;
        }
        .pay-details h4{
            margin: 0 0 10px;
            font-size: 13px;
            font-weight: 900;
        }
        .rowline{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            padding: 6px 0;
            font-size: 12px;
            color:#374151;
        }
        .rowline .k{ color:#6b7280; font-weight: 600; }
        .rowline .v{ font-weight: 900; }

        .neg .v{ color:#dc2626; }

        .total-line{
            border-top: 1px solid var(--line);
            margin-top: 8px;
            padding-top: 10px;
        }
        .total-line .k{ color:#111827; font-weight: 900; }
        .total-line .v{ font-size: 13px; }

        .bottom-actions{
            margin-top: 12px;
            display:flex;
            justify-content:center;
        }
        .pay-card{
            width: 100%;
            max-width: 1180px;
            background: var(--card);
            border:1px solid rgba(229,231,235,.9);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 12px 14px;
            display:flex;
            align-items:center;
            justify-content:flex-end;
            gap:12px;
            flex-wrap:wrap;
        }

        .btn {
            background: linear-gradient(180deg, #111827, #0b1220);
            color: #fff;
            border: 0;
            padding: 11px 18px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 900;
            letter-spacing: .2px;
            box-shadow: 0 10px 24px rgba(17,24,39,.18);
        }
        .btn:hover{ transform: translateY(-1px); }
        .btn:active{ transform: translateY(0); }

        .powered{
            display:flex;
            align-items:center;
            gap:10px;
            justify-content:center;
            margin-top: 8px;
            color: var(--muted);
            font-size: 11px;
        }
        .powered .badge{ opacity:.95; }

        .pay-right{ display:none !important; }

        .sr-only{
            position:absolute !important;
            width:1px !important;
            height:1px !important;
            padding:0 !important;
            margin:-1px !important;
            overflow:hidden !important;
            clip:rect(0,0,0,0) !important;
            white-space:nowrap !important;
            border:0 !important;
        }
    </style>
</head>

<body>
<div class="wrap">

    <div class="header-row">
        <div>
            <h1 class="page-title">Checkout</h1>
            <p class="page-subtitle">Review your order then click Pay Now.</p>
        </div>

        {{-- Stepper UI --}}
        <div class="steps" aria-label="Checkout steps">
            <div class="step active">
                <span class="bubble">1</span>
                <span>Shipping</span>
            </div>
            <span class="arrow">→</span>
            <div class="step">
                <span class="bubble">2</span>
                <span>Payment</span>
            </div>
            <span class="arrow">→</span>
            <div class="step">
                <span class="bubble">3</span>
                <span>Finish</span>
            </div>
        </div>
    </div>

    <div class="msg">
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        {{-- ✅ show validation errors (no layout change) --}}
        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        @endif

        @if(!empty($emptyMessage))
            <div class="empty">{{ $emptyMessage }}</div>
        @endif
    </div>

    <div class="grid">

        {{-- LEFT: SHIPPING --}}
        <div class="card">
            <div class="card-title">
                <h3>Shipping</h3>
                <span class="badge secure-badge"><span class="dot"></span> Secure checkout</span>
            </div>

            <div class="section-label">Contact Information</div>

            <div class="contact-scope">
                <div class="form-grid">
                    <div>
                        <label>Email</label>
                        <input type="email" placeholder="example@gmail.com">
                    </div>
                    <div>
                        <label>Phone Number</label>
                        <input type="tel" placeholder="Enter your phone number">
                    </div>
                </div>
            </div>

            <p class="muted" style="margin:6px 0 0;">We will send order update to this email</p>

            <div class="divider"></div>

            <div class="section-label">Address Selection</div>

            <div class="address-scope">

                <div class="form-grid-2">
                    <div>
                        <label>First Name</label>
                        <input type="text" placeholder="Enter your first name">
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" placeholder="Enter your last name">
                    </div>
                </div>

                <div style="height:10px;"></div>

                <div class="form-grid-2">
                    <div>
                        <label>Region</label>
                        <select>
                            <option>Select Region</option>
                            <option>NCR</option>
                            <option>Region IV-A</option>
                            <option>Region III</option>
                        </select>
                    </div>
                    <div>
                        <label>Province</label>
                        <select>
                            <option>Select Province</option>
                            <option>Laguna</option>
                            <option>Cavite</option>
                            <option>Rizal</option>
                        </select>
                    </div>
                </div>

                <div style="height:10px;"></div>

                <div class="form-grid-2">
                    <div>
                        <label>City</label>
                        <select>
                            <option>Select City</option>
                            <option>San Pedro</option>
                            <option>Biñan</option>
                            <option>Calamba</option>
                        </select>
                    </div>
                    <div>
                        <label>Barangay</label>
                        <select>
                            <option>Select Barangay</option>
                            <option>Barangay 1</option>
                            <option>Barangay 2</option>
                            <option>Barangay 3</option>
                        </select>
                    </div>
                </div>

                <div style="height:10px;"></div>

                <div class="form-grid-2">
                    <div>
                        <label>Postal Code</label>
                        <input type="text" placeholder="Postal Code">
                    </div>
                    <div>
                        <label>Street Name, Building, House No.</label>
                        <input type="text" placeholder="Street Name, Building, House No.">
                    </div>
                </div>

            </div>

            <div class="pin-row">
                <p class="muted" style="margin:0;">Confirm your location for smoother delivery.</p>
                <button type="button" class="pin-btn">
                    <span class="pin-ic"></span>
                    View Map Pin
                </button>
            </div>

            <div class="divider"></div>

            <div class="card-title" style="margin-bottom:0;">
                <h3 style="font-size:13px;">Payment</h3>
                <div class="hint">Choose on Order Summary</div>
            </div>
            <p class="muted" style="margin:6px 0 0;">
                You will be redirected to PayMongo to complete the payment securely.
            </p>
        </div>

        {{-- RIGHT: ORDER SUMMARY + THE ONLY PAYMENT METHOD --}}
        <div class="card">
            <div class="card-title">
                <h3>Order Summary</h3>
                <div class="hint" style="display:none;">Cart summary</div>
            </div>

            @if(empty($cartItems))
                <p class="empty">No items to checkout.</p>
            @else
                @php
                    $shippingSubtotal = 0;
                    $shippingDiscount = 0;
                    $merchSubtotal = $cartTotal;
                    $totalPayment = $merchSubtotal + $shippingSubtotal - $shippingDiscount;
                @endphp

                <div class="order-shell">
                    @foreach($cartItems as $index => $item)
                        @php
                            $price = $item['price'];
                            $qty = $item['qty'];
                            $lineTotal = $price * $qty;
                            $img = $item['image'] ?? ($item['img'] ?? null);
                        @endphp

                        <div class="order-item">
                            <div class="thumb">
                                @if($img)
                                    <img src="{{ $img }}" alt="Item">
                                @else
                                    <span class="ph">IMG</span>
                                @endif
                            </div>

                            <div class="item-main">
                                <p class="item-name">{{ $item['name'] }}</p>
                                <div class="item-meta">
                                    <span class="pill-mini">₱{{ number_format($price, 2) }}</span>
                                </div>
                            </div>

                            <div class="item-right">
                                <div class="qty-wrap">
                                    <button type="button" class="qty-btn" data-action="dec" data-index="{{ $index }}">−</button>
                                    <span class="qty-num" id="qtyNum{{ $index }}">{{ $qty }}</span>
                                    <button type="button" class="qty-btn" data-action="inc" data-index="{{ $index }}">+</button>
                                </div>

                                <span class="line-total" id="lineTotal{{ $index }}">₱{{ number_format($lineTotal, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('payment.pay') }}" id="payForm">
                    @csrf

                    @foreach($cartItems as $index => $item)
                        <input type="hidden" name="cart_items[{{ $index }}][name]" value="{{ $item['name'] }}">
                        <input type="hidden" name="cart_items[{{ $index }}][price]" value="{{ $item['price'] }}" id="price{{ $index }}">
                        <input type="hidden" name="cart_items[{{ $index }}][qty]" value="{{ $item['qty'] }}" id="qtyInput{{ $index }}">
                    @endforeach

                    <select id="realPaymentMethod" name="payment_method" class="sr-only" required>
                        <option value="gcash">GCash</option>
                        <option value="card">Card</option>
                        <option value="grab_pay">GrabPay</option>
                        <option value="paymaya">PayMaya</option>
                        <option value="bank">Online Banking</option>
                    </select>

                    <div class="pm-wrap" aria-label="Payment method">
                        <div class="pm-title">Payment method</div>

                        <div class="pm-row is-active" data-method="gcash" role="button" tabindex="0">
                            <div class="pm-left">
                                <div class="pm-icon gcash">G</div>
                                <div class="pm-text">
                                    <p class="pm-name">GCash</p>
                                    <span class="pm-badge">₱20.00 off ›</span>
                                </div>
                            </div>
                            <div class="pm-right">
                                <span class="pm-radio"></span>
                            </div>
                        </div>

                        <div class="pm-row pm-link" data-method="card" role="button" tabindex="0">
                            <div class="pm-left">
                                <div class="pm-icon plus">+</div>
                                <div class="pm-text">
                                    <p class="pm-name">Add credit/debit card</p>
                                    <div class="pm-logos" aria-label="Card logos">
                                        <span class="logo mc"></span>
                                        <span class="logo visa"></span>
                                        <span class="logo amex"></span>
                                        <span class="logo up"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pm-right">
                                <span class="pm-chevron">›</span>
                            </div>
                        </div>

                        <div class="pm-divider"></div>

                        <div class="pm-row pm-view" id="pmViewAll" role="button" tabindex="0">
                            <div class="pm-left">
                                <div class="pm-mini-icons">
                                    <span class="mini maya">maya</span>
                                    <span class="mini bank">🏦</span>
                                </div>
                            </div>
                            <div class="pm-right">
                                <span class="pm-viewall">View all options</span>
                                <span class="pm-chevron">›</span>
                            </div>
                        </div>

                        <div class="pm-more" id="pmMore">
                            <div class="pm-row" data-method="paymaya" role="button" tabindex="0">
                                <div class="pm-left">
                                    <div class="pm-icon maya">M</div>
                                    <div class="pm-text">
                                        <p class="pm-name">Maya</p>
                                        <p class="pm-sub">Pay using Maya wallet</p>
                                    </div>
                                </div>
                                <div class="pm-right"><span class="pm-radio"></span></div>
                            </div>

                            <div class="pm-row" data-method="grab_pay" role="button" tabindex="0">
                                <div class="pm-left">
                                    <div class="pm-icon grab">Gr</div>
                                    <div class="pm-text">
                                        <p class="pm-name">GrabPay</p>
                                        <p class="pm-sub">Pay via GrabPay</p>
                                    </div>
                                </div>
                                <div class="pm-right"><span class="pm-radio"></span></div>
                            </div>

                            <div class="pm-row" data-method="bank" role="button" tabindex="0">
                                <div class="pm-left">
                                    <div class="pm-icon bank">🏦</div>
                                    <div class="pm-text">
                                        <p class="pm-name">Online Banking</p>
                                        <p class="pm-sub">Choose your bank on redirect</p>
                                    </div>
                                </div>
                                <div class="pm-right"><span class="pm-radio"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="list-card">
                        <div class="list-row">
                            <div class="list-left">Shop Voucher</div>
                            <div class="list-right">
                                <input class="list-input" type="text" placeholder="Select or enter code" name="voucher_code">
                                <span class="chev">›</span>
                            </div>
                        </div>

                        <div class="list-row">
                            <div class="list-left">Message for Seller</div>
                            <div class="list-right">
                                <input class="list-input" type="text" placeholder="Please leave a message" name="seller_message">
                                <span class="chev">›</span>
                            </div>
                        </div>

                        <div class="list-row">
                            <div class="list-left">E-receipt</div>
                            <div class="list-right">
                                <select style="max-width:240px;" name="e_receipt">
                                    <option value="request">Request Now</option>
                                    <option value="email">Email Receipt</option>
                                    <option value="none">No Receipt</option>
                                </select>
                                <span class="chev">›</span>
                            </div>
                        </div>

                        <div class="list-row">
                            <div class="list-left">Shipping Option</div>
                            <div class="list-right">
                                <select style="max-width:240px;" name="shipping_option">
                                    <option value="standard">Standard Delivery</option>
                                    <option value="express">Express Delivery</option>
                                    <option value="pickup">Pickup</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pay-details">
                        <h4>Payment Details</h4>

                        <div class="rowline">
                            <span class="k">Merchandise Subtotal</span>
                            <span class="v">₱{{ number_format($merchSubtotal, 2) }}</span>
                        </div>

                        <div class="rowline">
                            <span class="k">Shipping Subtotal</span>
                            <span class="v">₱{{ number_format($shippingSubtotal, 2) }}</span>
                        </div>

                        <div class="rowline neg">
                            <span class="k">Shipping Discount Subtotal</span>
                            <span class="v">-₱{{ number_format($shippingDiscount, 2) }}</span>
                        </div>

                        <div class="total-line">
                            <div class="rowline">
                                <span class="k">Total Payment</span>
                                <span class="v">₱{{ number_format($totalPayment, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bottom-actions">
                        <div class="pay-card">
                            <div class="pay-right"></div>
                            <button type="submit" class="btn">Pay Now</button>
                        </div>
                    </div>

                    <div class="powered">
                        <span class="muted">You will be redirected to PayMongo to complete payment.</span>
                        <span class="badge">Powered by PayMongo</span>
                    </div>
                </form>
            @endif
        </div>

    </div>

</div>

<script>
(function(){
    const realSelect = document.getElementById('realPaymentMethod');
    const rows = document.querySelectorAll('.pm-row[data-method]');
    const viewAllBtn = document.getElementById('pmViewAll');
    const more = document.getElementById('pmMore');

    if(realSelect){
        realSelect.value = 'gcash';
    }

    function setActive(row){
        rows.forEach(r => r.classList.remove('is-active'));
        row.classList.add('is-active');

        const method = row.getAttribute('data-method');
        if(realSelect){
            const exists = Array.from(realSelect.options).some(o => o.value === method);
            if(exists){
                realSelect.value = method;
            }
        }
    }

    rows.forEach(row => {
        row.addEventListener('click', () => setActive(row));
        row.addEventListener('keydown', (e) => {
            if(e.key === 'Enter' || e.key === ' '){
                e.preventDefault();
                setActive(row);
            }
        });
    });

    if(viewAllBtn && more){
        viewAllBtn.addEventListener('click', () => more.classList.toggle('show'));
        viewAllBtn.addEventListener('keydown', (e) => {
            if(e.key === 'Enter' || e.key === ' '){
                e.preventDefault();
                more.classList.toggle('show');
            }
        });
    }

    function formatPHP(num){
        try{
            return '₱' + Number(num).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2});
        }catch(e){
            return '₱' + Number(num).toFixed(2);
        }
    }

    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const idx = btn.getAttribute('data-index');
            const action = btn.getAttribute('data-action');

            const qtyInput = document.getElementById('qtyInput' + idx);
            const qtyNum = document.getElementById('qtyNum' + idx);
            const priceEl = document.getElementById('price' + idx);
            const lineTotalEl = document.getElementById('lineTotal' + idx);

            if(!qtyInput || !qtyNum || !priceEl || !lineTotalEl) return;

            let qty = parseInt(qtyInput.value || '1', 10);
            const price = parseFloat(priceEl.value || '0');

            if(action === 'inc') qty += 1;
            if(action === 'dec') qty = Math.max(1, qty - 1);

            qtyInput.value = qty;
            qtyNum.textContent = qty;

            const lineTotal = price * qty;
            lineTotalEl.textContent = formatPHP(lineTotal);
        });
    });

})();
</script>

</body>
</html>