<x-app-layout>
@once
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&family=Poppins:wght@500;600;700&display=swap">
@endonce

<style>
:root{--hc-orange:#ff7a00;--hc-ink:#111827;--hc-muted:#6b7280;--hc-line:#111827;--hc-soft:#f7f7f8;--hc-shadow:0 12px 30px rgba(15,23,42,.07);--hc-shadow2:0 18px 42px rgba(15,23,42,.11);--hc-radius:14px;--hc-green:#16a34a}
.hc-page{min-height:calc(100vh - 70px);background:#fff;color:var(--hc-ink);font-family:'Inter',system-ui,sans-serif;font-weight:400;letter-spacing:0}
.hc-wrap{max-width:1490px;margin:0 auto}
.hc-title{margin:0;font-family:'Playfair Display',Georgia,serif;font-size:40px;font-weight:700;line-height:1.2;letter-spacing:-.02em;color:#111827}.hc-sub{margin:4px 0 0;color:var(--hc-muted);font-size:12px;line-height:1.45}
.hc-grid{display:grid;grid-template-columns:minmax(0,1fr) 350px;gap:18px;align-items:start}.hc-stack{display:grid;gap:18px}.hc-main-stack{display:grid;gap:12px}
.hc-card{background:#fff;border:1px solid #111827;border-radius:var(--hc-radius);box-shadow:var(--hc-shadow);transition:background .18s ease,box-shadow .18s ease,border-color .18s ease;overflow:hidden}.hc-card:hover{background:rgba(17,24,39,.10);box-shadow:var(--hc-shadow2);border-color:#111827}.hc-body{padding:15px 17px}.hc-card-title{margin:0;font-family:'Poppins',system-ui,sans-serif;font-size:14.5px;font-weight:600;letter-spacing:.022em;line-height:1.35;color:#111827}.hc-card-desc{margin:5px 0 0;color:var(--hc-muted);font-size:11.5px;line-height:1.5}
.hc-hero{display:grid;grid-template-columns:44px minmax(0,1fr);gap:13px;align-items:start}.hc-hero-icon,.hc-icon{width:42px;height:42px;border-radius:13px;background:#fff3e6;color:var(--hc-orange);display:grid;place-items:center;flex:0 0 auto}.hc-icon{width:36px;height:36px;border-radius:11px}.hc-icon.green{background:#eaf8ef;color:var(--hc-green)}.hc-icon.purple{background:#f2ebff;color:#7c3aed}.hc-icon.blue{background:#eff6ff;color:#2563eb}
.hc-search-row{display:grid;grid-template-columns:1fr 42px;gap:0;margin-top:14px;border:1px solid #dfe3ea;border-radius:12px;overflow:hidden;background:#fff}.hc-search-row:focus-within{border-color:var(--hc-orange);box-shadow:0 0 0 4px rgba(255,122,0,.11)}.hc-search-input{height:42px;border:0;background:#fff;color:#111827;padding:0 14px;font-size:12px;font-weight:500;outline:0}.hc-search-icon{border:0;background:#fff;color:#64748b;display:grid;place-items:center;cursor:pointer}.hc-chip-row{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}.hc-chip{height:28px;border:1px solid #dfe3ea;border-radius:999px;background:#fff;color:#374151;padding:0 13px;font-size:10.5px;font-weight:600;cursor:pointer;transition:.18s}.hc-chip:hover,.hc-chip:focus{background:#111827;border-color:#111827;color:#fff;outline:0}
.hc-category-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.hc-category{min-height:96px;border:1px solid #dfe3ea;border-radius:12px;background:#fff;padding:13px;display:grid;grid-template-columns:42px minmax(0,1fr) 14px;gap:12px;align-items:center;text-align:left;cursor:pointer;transition:.18s}.hc-category:hover,.hc-category:focus{background:rgba(17,24,39,.10);border-color:#111827;outline:0}.hc-category strong{display:block;font-family:'Poppins',system-ui,sans-serif;font-size:12px;font-weight:600;color:#111827}.hc-category small{display:block;margin-top:4px;color:#6b7280;font-size:10.5px;line-height:1.35}.hc-category em{display:block;margin-top:10px;color:#111827;font-style:normal;font-size:10px;font-weight:700}
.hc-list{display:grid;gap:0}.hc-article,.hc-support,.hc-announcement,.hc-shortcut,.hc-ticket{display:grid;grid-template-columns:auto minmax(0,1fr) auto;gap:12px;align-items:center;border-bottom:1px solid #f0f1f3;padding:10px 0}.hc-article:last-child,.hc-support:last-child,.hc-announcement:last-child,.hc-shortcut:last-child,.hc-ticket:last-child{border-bottom:0}.hc-article button,.hc-support button,.hc-announcement button,.hc-shortcut button{border:0;background:transparent;color:#111827;cursor:pointer;padding:0;text-align:left}.hc-row-title{margin:0;font-family:'Poppins',system-ui,sans-serif;font-size:12px;font-weight:600;color:#111827}.hc-row-sub{margin:3px 0 0;color:#6b7280;font-size:10.5px;line-height:1.35}.hc-tag{display:inline-flex;align-items:center;justify-content:center;min-width:92px;border-radius:999px;background:#fff3e6;color:var(--hc-orange);padding:5px 9px;font-size:9.5px;font-weight:800}.hc-tag.green{background:#eaf8ef;color:var(--hc-green)}.hc-tag.purple{background:#f2ebff;color:#7c3aed}
.hc-btn{height:42px;min-width:132px;border:1px solid var(--hc-orange);border-radius:10px;background:var(--hc-orange);color:#000!important;padding:0 17px;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:12px;font-weight:700;letter-spacing:.014em;cursor:pointer;transition:.18s;text-decoration:none}.hc-btn:hover,.hc-btn:focus{background:#111827!important;border-color:#111827!important;color:#fff!important;box-shadow:0 12px 24px rgba(17,24,39,.20);outline:0}.hc-btn.full{width:100%}.hc-btn.plain{background:#fff;color:#111827!important;border-color:#dfe3ea}.hc-btn.plain:hover,.hc-btn.plain:focus{background:#111827!important;border-color:#111827!important;color:#fff!important}
.hc-shortcut-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px}.hc-main-stack .hc-shortcut-grid{grid-template-columns:repeat(5,minmax(0,1fr));gap:10px}.hc-shortcut{border:1px solid #dfe3ea;border-radius:12px;padding:12px;background:#fff;min-height:64px}.hc-main-stack .hc-shortcut{min-height:58px;padding:10px;gap:8px}.hc-shortcut:hover{background:rgba(17,24,39,.10);border-color:#111827}
.hc-status{display:flex;align-items:center;gap:7px;color:#6b7280;font-size:10.5px;font-weight:600}.hc-dot{width:7px;height:7px;border-radius:50%;background:var(--hc-green)}.hc-link{border:0;background:transparent;color:var(--hc-orange);font-size:10.5px;font-weight:800;cursor:pointer;padding:0}.hc-link:hover{color:#111827}
.hc-modal{position:fixed;inset:0;z-index:10000;display:none;place-items:center;padding:20px;background:rgba(15,23,42,.55)}.hc-modal.active{display:grid}.hc-modal-card{width:min(560px,100%);background:#fff;border:1px solid #111827;border-radius:16px;box-shadow:0 30px 80px rgba(15,23,42,.28);overflow:hidden}.hc-modal-head{display:flex;justify-content:space-between;gap:14px;align-items:flex-start;padding:18px;border-bottom:1px solid #eef0f4}.hc-close{width:34px;height:34px;border:1px solid #dfe3ea;border-radius:10px;background:#fff;color:#111827;cursor:pointer}.hc-close:hover{background:#111827;color:#fff}.hc-field-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.hc-input,.hc-textarea,.hc-select{width:100%;border:1px solid #dfe3ea;border-radius:10px;background:#fff;color:#111827;padding:0 12px;font-size:12px;font-weight:500;outline:0}.hc-input,.hc-select{height:42px}.hc-textarea{min-height:110px;padding:12px;resize:vertical}.hc-input:focus,.hc-textarea:focus,.hc-select:focus{border-color:var(--hc-orange);box-shadow:0 0 0 4px rgba(255,122,0,.11)}
.hc-toast{position:fixed;left:50%;top:110px;z-index:11000;transform:translate(-50%,-12px);opacity:0;pointer-events:none;background:#111827;color:#fff;border-radius:13px;padding:13px 16px;box-shadow:0 18px 50px rgba(17,24,39,.25);font-size:12px;font-weight:700;transition:.18s}.hc-toast.show{opacity:1;transform:translate(-50%,0)}
@media(max-width:1320px){.hc-grid{grid-template-columns:1fr}.hc-stack{grid-template-columns:repeat(2,minmax(0,1fr))}.hc-stack .hc-card:first-child{grid-column:1/-1}.hc-shortcut-grid,.hc-main-stack .hc-shortcut-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:900px){.hc-category-grid,.hc-stack,.hc-field-grid,.hc-shortcut-grid{grid-template-columns:1fr}.hc-title{font-size:34px}}
.hc-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin:0 0 16px}
.hc-title-wrap{display:flex;align-items:flex-start;gap:10px}.hc-title-wrap:before{content:'';width:18px;height:4px;margin-top:8px;border-radius:999px;background:var(--hc-orange);flex:0 0 auto}
.hc-date{height:42px;min-width:178px;padding:0 15px;border:1px solid #111827;border-radius:8px;background:#fff;color:#111827;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:12px;font-weight:700;line-height:1;white-space:nowrap}
.hc-date i{font-size:15px}.hc-grid{align-items:stretch}.hc-main-stack,.hc-stack{height:100%}
.hc-main-stack{gap:0}.hc-main-stack>.hc-card:nth-child(1){border-bottom:0;border-radius:8px 8px 0 0;box-shadow:none}.hc-main-stack>.hc-card:nth-child(2){border-top:0;border-bottom:0;border-radius:0;box-shadow:none}.hc-main-stack>.hc-card:nth-child(3){border-top:0;border-radius:0 0 8px 8px;box-shadow:none}.hc-main-stack>.hc-card:nth-child(4){margin-top:18px;border-color:#111827;border-radius:8px;box-shadow:none}
.hc-main-stack>.hc-card:nth-child(1):hover,.hc-main-stack>.hc-card:nth-child(2):hover,.hc-main-stack>.hc-card:nth-child(3):hover,.hc-main-stack>.hc-card:nth-child(4):hover{background:#fff;box-shadow:none}
.hc-stack>.hc-card{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important;overflow:visible!important}.hc-stack>.hc-card:hover{background:transparent!important;box-shadow:none!important}.hc-stack>.hc-card>.hc-body{padding:0!important}
.hc-stack .hc-support,.hc-stack .hc-announcement,.hc-stack .hc-ticket{padding:12px 0}.hc-card,.hc-category,.hc-shortcut,.hc-modal-card{border-radius:8px}.hc-btn{border:0!important;border-radius:8px}
@media(max-width:900px){.hc-head{display:grid}.hc-date{width:100%}.hc-main-stack{gap:12px}.hc-main-stack>.hc-card:nth-child(1),.hc-main-stack>.hc-card:nth-child(2),.hc-main-stack>.hc-card:nth-child(3),.hc-main-stack>.hc-card:nth-child(4){border:1px solid #111827;border-radius:8px}.hc-stack{gap:18px}}
</style>

<div class="hc-page">
<div class="hc-wrap">
    <div class="hc-head">
        <div class="hc-title-wrap">
            <div>
                <h1 class="hc-title">Help Center</h1>
                <p class="hc-sub">Find answers, get support, and manage your orders with ease.</p>
            </div>
        </div>
        <div class="hc-date"><i class="fa-regular fa-calendar-days"></i><span>Today is {{ now()->format('M d, Y') }}</span></div>
    </div>

    <div class="hc-grid">
        <main class="hc-main-stack">
            <section class="hc-card">
                <div class="hc-body">
                    <div class="hc-hero">
                        <span class="hc-hero-icon"><i class="fa-solid fa-circle-question"></i></span>
                        <div>
                            <h2 class="hc-card-title">How can we help you today?</h2>
                            <p class="hc-card-desc">Search our help articles or browse by category to find the answers you need.</p>
                            <div class="hc-search-row">
                                <input id="helpSearch" class="hc-search-input" type="search" placeholder="Search for help articles, topics, or questions...">
                                <button class="hc-search-icon" type="button" onclick="runHelpSearch()" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                            <div class="hc-chip-row">
                                @foreach(['track order','refund','payment failed','change address','return policy'] as $term)
                                <button type="button" class="hc-chip" onclick="quickHelpSearch('{{ $term }}')">{{ $term }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="hc-card">
                <div class="hc-body">
                    <div class="hc-category-grid" id="categoryGrid">
                        @foreach([
                            ['bag-shopping','Orders & Shipping','Track orders, shipping times, delivery updates and more.','18 articles','Orders & Shipping'],
                            ['credit-card','Payments & Billing','Payment methods, billing issues, invoices and refunds.','16 articles','Payments & Billing'],
                            ['lock','Account & Security','Account settings, login help, security and privacy.','14 articles','Account & Security'],
                            ['pen-to-square','Product Design Help','Design tools, file requirements, templates and tips.','12 articles','Product Design'],
                            ['truck-arrow-right','Returns & Refunds','Return process, eligibility, refund status and more.','10 articles','Returns & Refunds'],
                            ['circle-question','General FAQs','Browse common questions about our services.','20 articles','General FAQs'],
                        ] as $cat)
                        <button class="hc-category" type="button" data-help-item="{{ strtolower($cat[1].' '.$cat[2]) }}" onclick="selectHelpCategory('{{ $cat[4] }}')">
                            <span class="hc-icon {{ $loop->index === 1 ? 'green' : ($loop->index === 2 ? 'purple' : ($loop->index === 3 ? 'blue' : '')) }}"><i class="fa-solid fa-{{ $cat[0] }}"></i></span>
                            <span><strong>{{ $cat[1] }}</strong><small>{{ $cat[2] }}</small><em>{{ $cat[3] }}</em></span>
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="hc-card">
                <div class="hc-body">
                    <h2 class="hc-card-title">Popular Articles</h2>
                    <div class="hc-list" id="articleList">
                        @foreach([
                            ['How do I track my order?','Orders & Shipping',''],
                            ['What payment methods do you accept?','Payments & Billing','green'],
                            ['How do I change or cancel my order?','Orders & Shipping',''],
                            ['How do I start a return or request a refund?','Returns & Refunds',''],
                            ['I forgot my password. How can I reset it?','Account & Security','purple'],
                        ] as $article)
                        <div class="hc-article" data-help-item="{{ strtolower($article[0].' '.$article[1]) }}">
                            <i class="fa-regular fa-file-lines"></i>
                            <button type="button" onclick="openArticle('{{ $article[0] }}')"><p class="hc-row-title">{{ $article[0] }}</p></button>
                            <span class="hc-tag {{ $article[2] }}">{{ $article[1] }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div style="display:flex;justify-content:center;margin-top:12px"><button class="hc-link" type="button" onclick="showAllArticles()">View all articles <i class="fa-solid fa-chevron-right"></i></button></div>
                </div>
            </section>

            <section class="hc-card">
                <div class="hc-body">
                    <h2 class="hc-card-title"><i class="fa-solid fa-cube" style="color:var(--hc-orange);margin-right:8px"></i>Order Support Shortcuts</h2>
                    <p class="hc-card-desc">Quick actions to manage and get help with your orders.</p>
                    <div class="hc-shortcut-grid" style="margin-top:14px">
                        <button class="hc-shortcut" type="button" onclick="location.href='{{ route('orders.my.index') }}'"><i class="fa-solid fa-truck"></i><span><p class="hc-row-title">Track My Order</p><p class="hc-row-sub">Check delivery status</p></span><span></span></button>
                        <button class="hc-shortcut" type="button" onclick="openTicketModal('Change Request')"><i class="fa-solid fa-pen-to-square"></i><span><p class="hc-row-title">Request a Change</p><p class="hc-row-sub">Modify order details</p></span><span></span></button>
                        <button class="hc-shortcut" type="button" onclick="openTicketModal('Report an Issue')"><i class="fa-solid fa-triangle-exclamation"></i><span><p class="hc-row-title">Report an Issue</p><p class="hc-row-sub">Something wrong?</p></span><span></span></button>
                        <button class="hc-shortcut" type="button" onclick="openTicketModal('Return Request')"><i class="fa-solid fa-rotate-left"></i><span><p class="hc-row-title">Start a Return</p><p class="hc-row-sub">Initiate return process</p></span><span></span></button>
                        <button class="hc-shortcut" type="button" onclick="location.href='{{ route('orders.my.index') }}#history'"><i class="fa-regular fa-clock"></i><span><p class="hc-row-title">View Order History</p><p class="hc-row-sub">See past orders</p></span><span></span></button>
                    </div>
                </div>
            </section>
        </main>

        <aside class="hc-stack">
            <section class="hc-card">
                <div class="hc-body">
                    <h2 class="hc-card-title"><i class="fa-solid fa-headset" style="color:var(--hc-orange);margin-right:8px"></i>Get Support</h2>
                    <p class="hc-card-desc">Choose the best way to reach our support team.</p>
                    <div class="hc-list" style="margin-top:12px">
                        <div class="hc-support"><span class="hc-icon"><i class="fa-solid fa-comment-dots"></i></span><button type="button" onclick="startLiveChat()"><p class="hc-row-title">Live Chat</p><p class="hc-row-sub">Chat with our support team in real-time.</p></button><span class="hc-status"><span class="hc-dot"></span>Online <i class="fa-solid fa-chevron-right"></i></span></div>
                        <div class="hc-support"><span class="hc-icon"><i class="fa-solid fa-ticket"></i></span><button type="button" onclick="openTicketModal('Order Concern')"><p class="hc-row-title">Submit a Ticket</p><p class="hc-row-sub">Submit a request and we'll get back to you.</p></button><i class="fa-solid fa-chevron-right"></i></div>
                        <div class="hc-support"><span class="hc-icon"><i class="fa-solid fa-envelope"></i></span><a href="mailto:support@printifyco.com" style="text-decoration:none"><p class="hc-row-title">Email Support</p><p class="hc-row-sub">support@printifyco.com<br>We usually reply within 24h.</p></a><i class="fa-solid fa-chevron-right"></i></div>
                        <div class="hc-support"><span class="hc-icon"><i class="fa-solid fa-phone"></i></span><a href="tel:+639123456789" style="text-decoration:none"><p class="hc-row-title">Call Support</p><p class="hc-row-sub">+63 912 345 6789<br>Mon - Fri, 9:00 AM - 6:00 PM PHT</p></a><i class="fa-solid fa-chevron-right"></i></div>
                    </div>
                </div>
            </section>

            <section class="hc-card">
                <div class="hc-body">
                    <h2 class="hc-card-title"><i class="fa-solid fa-bullhorn" style="margin-right:8px"></i>Recent Announcements</h2>
                    <p class="hc-card-desc">Stay updated with the latest news.</p>
                    <div class="hc-list" style="margin-top:12px">
                        @foreach([
                            ['New Design Editor Update','Try our improved editor with faster performance and new features.','May 29, 2026'],
                            ['Shipping Updates','Updated delivery times for certain areas. Check your orders.','May 27, 2026'],
                            ['Payment System Maintenance','Scheduled maintenance on June 5, 2026 from 1:00 AM - 3:00 AM PHT.','May 25, 2026'],
                        ] as $news)
                        <div class="hc-announcement">
                            <span></span>
                            <button type="button" onclick="openAnnouncement('{{ $news[0] }}')"><p class="hc-row-title">{{ $news[0] }}</p><p class="hc-row-sub">{{ $news[1] }}</p></button>
                            <small style="color:#6b7280;font-size:10px">{{ $news[2] }}</small>
                        </div>
                        @endforeach
                    </div>
                    <div style="display:flex;justify-content:center;margin-top:12px"><button class="hc-link" type="button" onclick="openAnnouncement('All Announcements')">View all announcements <i class="fa-solid fa-chevron-right"></i></button></div>
                </div>
            </section>

            <section class="hc-card">
                <div class="hc-body">
                    <h2 class="hc-card-title">Ticket Activity</h2>
                    <p class="hc-card-desc">Recent support activity saved for this account.</p>
                    <div class="hc-list" id="ticketList" style="margin-top:10px"></div>
                </div>
            </section>
        </aside>
    </div>

</div>

<div id="ticketModal" class="hc-modal" aria-hidden="true">
    <div class="hc-modal-card">
        <div class="hc-modal-head">
            <div><h2 class="hc-card-title">Submit a Ticket</h2><p class="hc-card-desc">This creates a real support ticket in the database.</p></div>
            <button type="button" class="hc-close" onclick="closeTicketModal()">x</button>
        </div>
        <div class="hc-body">
            <form id="supportForm">
                <div class="hc-field-grid">
                    <select id="supportTopic" class="hc-select"><option>Order Concern</option><option>Artwork Upload</option><option>Payment & Billing</option><option>Delivery / Pickup</option><option>Change Request</option><option>Report an Issue</option><option>Return Request</option></select>
                    <input id="supportOrder" class="hc-input" placeholder="Order reference (optional)">
                </div>
                <textarea id="supportMessage" class="hc-textarea" style="margin-top:10px" placeholder="Describe your concern..." required></textarea>
                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:12px;flex-wrap:wrap"><button class="hc-btn plain" type="button" onclick="saveHelpDraft()">Save Draft</button><button class="hc-btn" type="submit">Submit Ticket</button></div>
            </form>
        </div>
    </div>
</div>

<div id="helpToast" class="hc-toast">Saved.</div>
</div>

<script>
const helpKey='printify_help_tickets';
const helpTicketStoreUrl=@json(Route::has('help-center.tickets.store') ? route('help-center.tickets.store') : null);
const helpCsrf=@json(csrf_token());
function helpToast(msg){const t=document.getElementById('helpToast');if(!t)return;t.textContent=msg;t.classList.add('show');clearTimeout(window.helpToastTimer);window.helpToastTimer=setTimeout(()=>t.classList.remove('show'),2400)}
function helpTickets(){try{return JSON.parse(localStorage.getItem(helpKey)||'[]')}catch(e){return []}}
function saveTickets(items){localStorage.setItem(helpKey,JSON.stringify(items))}
function helpField(id){return document.getElementById(id)}
function escapeHelpText(value){return String(value||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]))}
function updateHelpUrl(view){history.replaceState(null,'',window.location.pathname+(view?'#'+view:''))}
function renderTickets(){const box=helpField('ticketList');if(!box)return;const items=helpTickets();box.innerHTML=items.length?items.slice(-3).reverse().map(t=>`<div class="hc-ticket"><i class="fa-regular fa-message"></i><div><p class="hc-row-title">${escapeHelpText(t.ref||t.topic)}</p><p class="hc-row-sub">${escapeHelpText(t.topic)} - ${escapeHelpText((t.message||'').slice(0,62))}</p></div><span class="hc-tag">${escapeHelpText(t.status)}</span></div>`).join(''):'<div class="hc-ticket"><i class="fa-regular fa-message"></i><div><p class="hc-row-title">No tickets yet</p><p class="hc-row-sub">Submit a ticket to see it here.</p></div><span class="hc-tag">Ready</span></div>'}
function openTicketModal(topicValue){const modal=helpField('ticketModal'),topic=helpField('supportTopic'),message=helpField('supportMessage');if(topic&&topicValue)topic.value=topicValue;if(modal){modal.classList.add('active');modal.setAttribute('aria-hidden','false');document.body.style.overflow='hidden'}setTimeout(()=>message?.focus(),80);updateHelpUrl('support-ticket')}
function closeTicketModal(){const modal=helpField('ticketModal');if(modal){modal.classList.remove('active');modal.setAttribute('aria-hidden','true');document.body.style.overflow=''}}
function saveHelpDraft(){const topic=helpField('supportTopic'),order=helpField('supportOrder'),message=helpField('supportMessage');localStorage.setItem('printify_help_draft',JSON.stringify({topic:topic?.value||'',order:order?.value||'',message:message?.value||'',savedAt:new Date().toISOString()}));updateHelpUrl('draft-saved');helpToast('Draft saved.')}
function startLiveChat(){localStorage.setItem('printify_live_chat_requested_at',new Date().toISOString());updateHelpUrl('live-chat');helpToast('Live chat request opened.')}
function selectHelpCategory(category){const search=helpField('helpSearch');if(search)search.value=category;runHelpSearch();helpToast(category+' articles shown.')}
function quickHelpSearch(term){const search=helpField('helpSearch');if(search)search.value=term;runHelpSearch()}
function runHelpSearch(){const search=helpField('helpSearch');const q=(search?.value||'').toLowerCase().trim();document.querySelectorAll('[data-help-item]').forEach(item=>{item.style.display=!q||item.dataset.helpItem.includes(q)?'':'none'});updateHelpUrl(q?'search':'help-center');helpToast(q?'Filtered help results.':'Showing all help results.')}
function openArticle(title){localStorage.setItem('printify_help_last_article',title);updateHelpUrl(title.toLowerCase().replace(/[^a-z0-9]+/g,'-'));helpToast(title)}
function openAnnouncement(title){localStorage.setItem('printify_help_last_announcement',title);updateHelpUrl('announcements');helpToast(title)}
function showAllArticles(){const search=helpField('helpSearch');if(search)search.value='';runHelpSearch();helpToast('All articles visible.')}
helpField('ticketModal')?.addEventListener('click',e=>{if(e.target.id==='ticketModal')closeTicketModal()});
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeTicketModal()});
helpField('supportForm')?.addEventListener('submit',async e=>{e.preventDefault();const topic=helpField('supportTopic'),order=helpField('supportOrder'),message=helpField('supportMessage');const body=(message?.value||'').trim();if(!body){helpToast('Please describe your concern.');return}const fallbackRef='TKT-'+Date.now().toString().slice(-6);let ticket={ref:fallbackRef,topic:topic?.value||'Order Concern',order:(order?.value||'').trim(),message:body,status:'Open',createdAt:new Date().toISOString()};let savedToServer=false;try{if(helpTicketStoreUrl){const res=await fetch(helpTicketStoreUrl,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':helpCsrf},body:JSON.stringify({topic:ticket.topic,order_reference:ticket.order,message:ticket.message})});if(!res.ok)throw new Error('Ticket save failed');const data=await res.json();if(data.ticket){ticket={ref:data.ticket.ref||data.ticket.reference||ticket.ref,topic:data.ticket.topic||ticket.topic,order:data.ticket.order||data.ticket.order_reference||ticket.order,message:data.ticket.message||ticket.message,status:data.ticket.status||ticket.status,createdAt:data.ticket.createdAt||data.ticket.created_at||ticket.createdAt};savedToServer=true;}}}catch(err){console.warn(err)}const items=helpTickets();items.push(ticket);saveTickets(items);localStorage.removeItem('printify_help_draft');if(message)message.value='';if(order)order.value='';renderTickets();closeTicketModal();updateHelpUrl('ticket-'+String(ticket.ref).toLowerCase());helpToast((savedToServer?'Support ticket saved: ':'Support ticket saved locally: ')+ticket.ref);});
try{const d=JSON.parse(localStorage.getItem('printify_help_draft')||'null');if(d){const topic=helpField('supportTopic'),order=helpField('supportOrder'),message=helpField('supportMessage');if(topic)topic.value=d.topic||topic.value;if(order)order.value=d.order||'';if(message)message.value=d.message||''}}catch(e){}
renderTickets();
</script>
</x-app-layout>
