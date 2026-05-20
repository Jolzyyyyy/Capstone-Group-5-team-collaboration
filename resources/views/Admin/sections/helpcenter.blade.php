<style>
    :root {
        --primary-blue: #4F46E5;
        --primary-hover: #4338CA;
        --border-color: #cbd5e1;
        --bg-light: #f4f7fa;
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    /* Styles are kept exactly as your original code */
    .main-wrapper {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .giant-title { 
        font-family: 'DM Serif Display', serif;
        font-size: 38px; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
        margin-bottom: 25px; 
        color: var(--text-main);
    }

    .search-container {
        position: relative;
        margin-bottom: 25px; 
    }
    .search-container input {
        width: 100%;
        padding: 15px 20px 15px 45px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: white;
        font-size: 16px;
        outline: none;
        transition: 0.2s;
    }
    .search-container input:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }

    .grid-3-cols {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px; 
    }
    .help-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 25px 20px;
        text-align: center;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .help-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .card-title { font-family: 'DM Serif Display', serif; font-size: 20px; color: var(--primary-blue); margin-top: 10px; }

    .bottom-layout {
        display: grid;
        grid-template-columns: 1.8fr 1.2fr;
        gap: 20px;
        align-items: start;
        margin-bottom: 30px;
    }

    .kb-section {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        max-width: 600px;
    }
    
    .kb-header { 
        padding: 15px 20px; 
        border-bottom: 3px solid #cbd5e1; 
        font-family: 'DM Serif Display', serif;
        font-style: italic;
        font-weight: 700;
        text-transform: capitalize;
        letter-spacing: 0.5px; 
        color: var(--text-main); 
        font-size: 22px; 
    }
    
    .kb-row {
        padding: 12px 20px; 
        color: var(--text-main);
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
        border-bottom: 1px solid #e2e8f0; 
    }
    .kb-row:last-child { border-bottom: none; }
    .kb-row:hover { 
        background-color: var(--primary-blue); 
        color: white; 
    }

    .sidebar-vbox { display: flex; flex-direction: column; gap: 20px; }

    .chat-box {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        padding: 20px;
    }
    .chat-title { 
        font-family: 'DM Serif Display', serif;
        font-style: italic;
        font-weight: 700;
        font-size: 22px; 
        margin-bottom: 5px; 
        color: var(--text-main);
    }
    
    .online-status { font-size: 12px; color: #10b981; display: flex; align-items: center; gap: 5px; font-weight: 600; }
    .online-dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; }

    .btn-chat {
        background: var(--primary-blue);
        color: white;
        border: none;
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background 0.3s ease;
        margin-top: 15px;
    }
    .btn-chat:hover { background: var(--primary-hover); }

    .system-status {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .status-tag { font-size: 11px; background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 20px; font-weight: 700; }

    .extra-section-title {
        font-family: 'DM Serif Display', serif;
        font-size: 24px;
        margin: 30px 0 20px 0;
        border-left: 4px solid var(--primary-blue);
        padding-left: 15px;
    }

    .extra-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 40px; 
    }
    .info-box {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .info-box:hover {
        border-color: var(--primary-blue);
        background-color: #f8fafc;
    }
    .info-box h3 { font-family: 'DM Serif Display', serif; font-size: 20px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; }
    .info-box p { font-size: 14px; color: var(--text-muted); line-height: 1.6; }
    
    .arrow-icon {
        color: var(--primary-blue);
        font-weight: bold;
        transition: transform 0.2s;
    }
    .info-box:hover .arrow-icon { transform: translateX(5px); }

    @media (max-width: 768px) {
        .grid-3-cols, .bottom-layout, .extra-grid { grid-template-columns: 1fr; }
        .kb-section { max-width: 100%; }
    }
</style>

<div class="main-wrapper" x-data="helpApp">
    <h1 class="giant-title">Help Center</h1>

    <div class="search-container">
        <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="Search for help articles..." x-model="searchQuery">
    </div>

    <div class="grid-3-cols">
        <div class="help-card" @click="alert('Opening Guide...')">
            <svg width="32" height="32" fill="none" stroke="var(--primary-blue)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <div class="card-title">Getting Started Guide</div>
        </div>
        <div class="help-card" @click="alert('Loading FAQs...')">
            <svg width="32" height="32" fill="none" stroke="var(--primary-blue)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="card-title">FAQs</div>
        </div>
        <div class="help-card" @click="alert('Connecting to Support...')">
            <svg width="32" height="32" fill="none" stroke="var(--primary-blue)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <div class="card-title">Contact Support</div>
        </div>
    </div>

    <div class="bottom-layout">
        <div class="kb-section">
            <div class="kb-header">Knowledge Base</div>
            <div class="kb-content">
                <template x-for="(item, index) in filteredKB" :key="index">
                    <div class="kb-row" @click="alert('Opening: ' + item)">
                        <span x-text="item"></span>
                    </div>
                </template>
            </div>
        </div>

        <div class="sidebar-vbox">
            <div class="chat-box">
                <div class="chat-header">
                    <div class="chat-title">Live Chat Support</div>
                    <div class="online-status">
                        <span class="online-dot"></span> Online
                    </div>
                </div>
                <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">
                    Average response time: 2 minutes. Our agents are ready to help.
                </p>
                <button class="btn-chat" @click="startChat()">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                    Start Chat Now
                </button>
            </div>

            <div class="system-status">
                <span style="font-size: 13px; font-weight: 600;">System Status</span>
                <span class="status-tag">ALL SYSTEMS OPERATIONAL</span>
            </div>
        </div>
    </div>

    <h2 class="extra-section-title">Explore Resources</h2>
    <div class="extra-grid">
        <div class="info-box" @click="alert('Opening Video Tutorials...')">
            <h3>Video Tutorials <span class="arrow-icon">→</span></h3>
            <p>Watch step-by-step guides on how to set up your store and optimize products.</p>
        </div>
        <div class="info-box" @click="alert('Opening Community Forum...')">
            <h3>Community Forum <span class="arrow-icon">→</span></h3>
            <p>Connect with other entrepreneurs. Share strategies and get inspired.</p>
        </div>
        <div class="info-box" @click="alert('Opening API Reference...')">
            <h3>API Reference <span class="arrow-icon">→</span></h3>
            <p>Detailed documentation for developers to integrate our printing services.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('helpStore')) {
            Alpine.data('helpApp', () => ({
                searchQuery: '',
                kbItems: [
                    'Account & Billing Settings',
                    'Privacy and Data Security',
                    'Technical API Documentation',
                    'Order Tracking & Returns',
                    'Shipping & Delivery Policies'
                ],
                get filteredKB() {
                    return this.kbItems.filter(item => 
                        item.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },
                startChat() {
                    alert("Connecting you to a live support representative...");
                }
            }));
            Alpine.store('helpStore', true);
        }
    });
</script>