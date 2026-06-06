    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #4F46E5;
            --accent-red: #ef4444;
            --border-color: #cbd5e1;
            --bg-light: #f4f7fa;
            --text-main: #1e293b;
            --card-bg: #ffffff;
        }

        .dark-mode {
            --bg-light: #0f172a;
            --text-main: #f1f5f9;
            --card-bg: #1e293b;
            --border-color: #334155;
        }

        /* Glass Effect Logic */
        .glass-mode .setting-card {
            background: rgba(255, 255, 255, 0.6) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark-mode.glass-mode .setting-card {
            background: rgba(30, 41, 59, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-wrapper {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 50px 40px;
        }

        .giant-title { 
            font-family: 'DM Serif Display', serif;
            font-size: 42px; 
            text-transform: uppercase; 
            letter-spacing: 0.02em; 
            margin-bottom: 40px; 
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        @media (max-width: 1400px) { .settings-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 1100px) { .settings-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 700px) { .settings-grid { grid-template-columns: 1fr; } }

        .setting-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            height: fit-content;
        }

        .card-header {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .list-item {
            width: 100%;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-main);
            cursor: pointer;
            margin-bottom: 8px;
            transition: background-color 0.2s, border-color 0.2s, color 0.2s;
            text-align: left;
        }

        .list-item:hover {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white !important;
        }
        .list-item:hover i, .list-item:hover span { color: white !important; }

        .item-label-group {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
        }

        .section-tag { 
            font-size: 10px; 
            font-weight: 800; 
            color: var(--accent-red); 
            text-transform: uppercase; 
            letter-spacing: 0.1em;
            margin-bottom: 6px;
            display: block;
            margin-top: 10px;
        }

        .theme-switcher {
            display: flex;
            background: rgba(0,0,0,0.05);
            padding: 4px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .theme-btn {
            flex: 1;
            padding: 8px;
            border-radius: 7px;
            border: none;
            background: transparent;
            color: var(--text-main);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .theme-btn.active {
            background: white;
            color: #1e293b;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .accent-grid { display: flex; gap: 10px; margin-bottom: 5px; }
        .accent-circle {
            width: 28px; height: 28px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 0 0 1px var(--border-color);
        }
        .accent-circle.active { box-shadow: 0 0 0 2px var(--primary-blue); }

        .toggle-track {
            width: 38px; height: 20px;
            background: #cbd5e1;
            border-radius: 20px;
            position: relative;
            transition: 0.3s;
        }
        .toggle-track::after {
            content: '';
            position: absolute;
            width: 14px; height: 14px;
            background: white;
            border-radius: 50%;
            top: 3px; left: 3px;
            transition: 0.3s;
        }
        .is-active .toggle-track { background: var(--primary-blue); }
        .is-active .toggle-track::after { left: 21px; }

        /* Animation for loading */
        .spinning { animation: spin 1s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

    </style>
<div x-data="adminSettings" :class="{ 'dark-mode': dark, 'glass-mode': glass }">

    <div class="main-wrapper settings-section">
        <h1 class="giant-title">Settings</h1>

        <div class="settings-grid">
            
            <div class="setting-card">
                <div class="card-header"><i class="fa-solid fa-circle-user"></i> Account</div>
                <button class="list-item" @click="updateProfile()">
                    <div class="item-label-group"><i class="fa-regular fa-id-badge"></i> Profile Info</div>
                    <span x-text="username" style="font-size: 11px; opacity: 0.7;"></span>
                </button>
                <button class="list-item" @click="changePassword()">
                    <div class="item-label-group"><i class="fa-solid fa-key"></i> Password</div>
                    <i class="fa-solid fa-chevron-right" style="font-size: 10px; opacity: 0.5;"></i>
                </button>
                <button class="list-item" @click="updateEmail()">
                    <div class="item-label-group"><i class="fa-regular fa-envelope"></i> Email</div>
                    <span x-text="email" style="font-size: 11px; opacity: 0.7;"></span>
                </button>
            </div>

            <div class="setting-card">
                <div class="card-header"><i class="fa-solid fa-sliders"></i> Preferences</div>
                <div class="list-item" @click="toggleLang()">
                    <div class="item-label-group"><i class="fa-solid fa-globe"></i> Language</div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--primary-blue)" x-text="lang"></span>
                </div>
                <div class="list-item" :class="notif && 'is-active'" @click="notif = !notif; alert('Notifications ' + (notif ? 'Enabled' : 'Disabled'))">
                    <div class="item-label-group"><i class="fa-regular fa-bell"></i> Notifications</div>
                    <div class="toggle-track"></div>
                </div>
                <div class="list-item">
                    <div class="item-label-group"><i class="fa-regular fa-clock"></i> Time Zone</div>
                    <span style="font-size: 10px; font-weight: 800;">GMT +8</span>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-header"><i class="fa-solid fa-database"></i> System</div>
                <button class="list-item" @click="checkUpdates()">
                    <div class="item-label-group"><i class="fa-solid fa-square-rss"></i> Updates</div>
                    <span x-show="newUpdate" style="font-size: 8px; background: var(--accent-red); color: white; padding: 2px 6px; border-radius: 4px; font-weight: 900;">NEW</span>
                </button>
                <button class="list-item" @click="runBackup()" :disabled="isBackingUp">
                    <div class="item-label-group">
                        <i class="fa-solid fa-hard-drive" :class="isBackingUp && 'spinning'"></i> 
                        <span x-text="isBackingUp ? 'Backing up...' : 'Backup Now'"></span>
                    </div>
                </button>
                <div class="list-item">
                    <div class="item-label-group"><i class="fa-solid fa-floppy-disk"></i> Storage</div>
                    <span style="font-size: 10px; font-weight: 800; opacity: 0.7;" x-text="storageUsed + '% Full'"></span>
                </div>
            </div>

            <div class="setting-card">
                <div class="card-header"><i class="fa-solid fa-shield-halved"></i> Security</div>
                <div class="list-item" :class="tfa && 'is-active'" @click="tfa = !tfa">
                    <div class="item-label-group"><i class="fa-solid fa-fingerprint"></i> 2FA Auth</div>
                    <div class="toggle-track"></div>
                </div>
                <button class="list-item" @click="showHistory()">
                    <div class="item-label-group"><i class="fa-solid fa-clock-rotate-left"></i> History</div>
                    <i class="fa-solid fa-chevron-right" style="font-size: 10px; opacity: 0.5;"></i>
                </button>
                <button class="list-item" @click="alert('Opening Role Management...')">
                    <div class="item-label-group"><i class="fa-solid fa-user-lock"></i> Permissions</div>
                    <i class="fa-solid fa-chevron-right" style="font-size: 10px; opacity: 0.5;"></i>
                </button>
            </div>

            <div class="setting-card">
                <div class="card-header"><i class="fa-solid fa-plug"></i> Integrations</div>
                <button class="list-item" @click="toggleGoogle()">
                    <div class="item-label-group"><i class="fa-brands fa-google"></i> Google</div>
                    <span x-text="googleLinked ? 'Linked' : 'Unlinked'" :style="googleLinked ? 'color: #10b981' : 'color: #ef4444'" style="font-size: 10px; font-weight: 800;"></span>
                </button>
                <button class="list-item" @click="alert('Redirecting to Merchant Center...')">
                    <div class="item-label-group"><i class="fa-brands fa-google-play"></i> Merchant</div>
                    <i class="fa-solid fa-link" style="font-size: 10px; color: #10b981;"></i>
                </button>
                <button class="list-item" @click="syncData()">
                    <div class="item-label-group"><i class="fa-solid fa-layer-group"></i> Synnus Sync</div>
                    <i class="fa-solid fa-arrows-rotate" :class="isSyncing && 'spinning'" style="font-size: 10px;"></i>
                </button>
            </div>

            <div class="setting-card">
                <div class="card-header"><i class="fa-solid fa-chart-line"></i> Dashboard</div>
                <div class="list-item" :class="showRevenue && 'is-active'" @click="showRevenue = !showRevenue">
                    <div class="item-label-group"><i class="fa-solid fa-sack-dollar"></i> Show Revenue</div>
                    <div class="toggle-track"></div>
                </div>
                <div class="list-item" :class="autoRefresh && 'is-active'" @click="autoRefresh = !autoRefresh">
                    <div class="item-label-group"><i class="fa-solid fa-arrows-rotate"></i> Auto Refresh</div>
                    <div class="toggle-track"></div>
                </div>
                <button class="list-item" @click="exportLogs()">
                    <div class="item-label-group"><i class="fa-solid fa-file-export"></i> Export Logs</div>
                    <i class="fa-solid fa-download" style="font-size: 10px; opacity: 0.5;"></i>
                </button>
            </div>

            <div class="setting-card">
                <div class="card-header"><i class="fa-solid fa-wand-magic-sparkles"></i> Appearance</div>
                <span class="section-tag">Theme Mode</span>
                <div class="theme-switcher">
                    <button class="theme-btn" :class="!dark && 'active'" @click="dark = false">Light</button>
                    <button class="theme-btn" :class="dark && 'active'" @click="dark = true">Dark</button>
                </div>
                <span class="section-tag">Accent Color</span>
                <div class="accent-grid">
                    <template x-for="c in accentColors">
                        <div class="accent-circle" :style="'background:' + c" :class="currentAccent === c && 'active'" @click="updateAccent(c)"></div>
                    </template>
                </div>
                <div class="list-item" :class="glass && 'is-active'" @click="glass = !glass" style="margin-top: 10px;">
                    <div class="item-label-group"><i class="fa-solid fa-border-all"></i> Glass Effect</div>
                    <div class="toggle-track"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminSettings', () => ({
                // Appearance State
                dark: false,
                glass: false,
                currentAccent: '#4F46E5',
                accentColors: ['#4F46E5', '#0ea5e9', '#10b981', '#ec4899', '#f59e0b'],

                // Account State
                username: 'Admin_User',
                email: 'admin@printify.co',

                // Preferences
                lang: 'English',
                notif: true,

                // System & Dashboard
                newUpdate: true,
                storageUsed: 78,
                isBackingUp: false,
                showRevenue: true,
                autoRefresh: true,
                isSyncing: false,

                // Security & Integration
                tfa: false,
                googleLinked: true,

                // Functions
                updateAccent(color) {
                    this.currentAccent = color;
                    document.documentElement.style.setProperty('--primary-blue', color);
                },
                checkUpdates() {
                    alert('Checking for system updates...');
                    setTimeout(() => {
                        this.newUpdate = false;
                        alert('System is up to date!');
                    }, 1500);
                },
                runBackup() {
                    this.isBackingUp = true;
                    setTimeout(() => {
                        this.isBackingUp = false;
                        this.storageUsed = 45; // Simulating cleared space after backup
                        alert('Cloud Backup Successful!');
                    }, 3000);
                },
                updateProfile() {
                    let newName = prompt("Enter new username:", this.username);
                    if(newName) this.username = newName;
                },
                updateEmail() {
                    let newEmail = prompt("Enter new email:", this.email);
                    if(newEmail) this.email = newEmail;
                },
                changePassword() {
                    confirm("Send password reset link to your email?");
                },
                toggleLang() {
                    this.lang = this.lang === 'English' ? 'Tagalog' : 'English';
                },
                showHistory() {
                    alert("Recent Logins:\n- 127.0.0.1 (Today)\n- 192.168.1.5 (Yesterday)");
                },
                toggleGoogle() {
                    this.googleLinked = !this.googleLinked;
                    alert(this.googleLinked ? 'Google Account Connected' : 'Google Account Unlinked');
                },
                syncData() {
                    this.isSyncing = true;
                    setTimeout(() => { this.isSyncing = false; alert('Sync Complete!'); }, 2000);
                },
                exportLogs() {
                    alert('Generating system logs... CSV file will download shortly.');
                }
            }))
        });
    </script>
</div>
