<x-guest-layout>
    {{-- 1. LAYOUT RESET --}}
    <style>
        .min-h-screen {
            background-color: #f3f4f6 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            padding: 0 !important;
        }
        
        .min-h-screen > div:first-child { display: none !important; }

        .min-h-screen > div:last-child {
            width: 100% !important;
            max-width: none !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }
    </style>

    {{-- 2. UI STYLING --}}
    <style>
        .auth-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.05);
            width: 350px; 
            max-width: 90vw;
            padding: 25px;
            text-align: center;
            box-sizing: border-box;
        }

        .auth-title {
            font-size: 1.25rem; 
            font-weight: 700;
            color: #1a202c;
            margin-top: 10px;
            margin-bottom: 1rem;
        }

        .instruction-text {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .input-group {
            position: relative;
            margin-bottom: 8px;
            width: 100%;
        }

        .custom-input {
            background-color: #f0f2f5;
            border: none !important;
            padding: 10px 15px;
            border-radius: 8px;
            width: 100%;
            margin: 6px 0;
            font-size: 13px;
            color: #1a202c;
            box-sizing: border-box;
            outline: none;
        }

        .pass-input {
            padding-right: 48px !important;
        }

        .auth-btn {
            background-color: #ff4b2b;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 0;
            border-radius: 25px;
            width: 100%;
            margin-top: 15px;
            cursor: pointer;
            font-size: 12px;
            border: none;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .auth-btn:hover { background-color: #e63917; }
        .auth-btn:active { transform: scale(0.98); }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        /* EXACT MATCH: Eye Icon Button from Sign In/Up */
        .eye-icon-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 2px;
            color: #606770;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .eye-icon-btn:hover {
            color: #1c1e21;
        }

        .validation-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin: 10px 0;
            text-align: left;
            padding-left: 5px;
        }

        .val-item {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .text-neutral { color: #9ca3af !important; }
        .text-red-strong { color: #dc2626 !important; }
        .text-green-strong { color: #16a34a !important; }

        [x-cloak] { display: none !important; }
    </style>

    <div class="auth-container" x-data="{ 
        password: '', 
        password_confirmation: '',
        showModal: false,
        showConfirmReset: false,
        actionType: 'auto_login',
        isPassDirty: false,
        isConfirmDirty: false,

        get rules() {
            return {
                length: this.password.length >= 8,
                number: /[0-9]/.test(this.password),
                symbol: /[!@#$%^&*(),.?':{}|<>]/.test(this.password),
                match: (this.password === this.password_confirmation) && this.password !== ''
            }
        },

        submitResetForm(type) {
            this.actionType = type;
            this.$nextTick(() => { document.getElementById('resetForm').submit(); });
        }
    }">
        <h1 class="auth-title">Reset Password</h1>
        
        <p class="instruction-text">
            Set your new credentials below to regain access.
        </p>

        <form method="POST" action="{{ route('password.store') }}" id="resetForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? (request()->route('token') ?? session('password_reset_token')) }}">
            <input type="hidden" name="action_type" :value="actionType">

            <div class="input-group">
                <input id="email" class="custom-input" type="email" name="email" value="{{ old('email', $email ?? request('email') ?? session('password_reset_email')) }}" placeholder="Email Address" required />
            </div>

            <div class="input-group">
                <input id="password" class="custom-input pass-input" type="password" name="password" x-model="password" @input="isPassDirty = true" placeholder="New Password" required />
                <button type="button" class="eye-icon-btn" onclick="toggleFBStyle('password', 'eye-1')">
                    <svg id="eye-1" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>

            <div class="input-group">
                <input id="password_confirmation" class="custom-input pass-input" type="password" name="password_confirmation" x-model="password_confirmation" @input="isConfirmDirty = true" placeholder="Confirm New Password" required />
                <button type="button" class="eye-icon-btn" onclick="toggleFBStyle('password_confirmation', 'eye-2')">
                    <svg id="eye-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>

            <div class="validation-list">
                <div :class="!isPassDirty ? 'text-neutral' : (rules.length ? 'text-green-strong' : 'text-red-strong')" class="val-item">At least 8 characters</div>
                <div :class="!isPassDirty ? 'text-neutral' : (rules.number ? 'text-green-strong' : 'text-red-strong')" class="val-item">Must include a number</div>
                <div :class="!isPassDirty ? 'text-neutral' : (rules.symbol ? 'text-green-strong' : 'text-red-strong')" class="val-item">Contains special symbol</div>
                <div :class="!isConfirmDirty ? 'text-neutral' : (rules.match ? 'text-green-strong' : 'text-red-strong')" class="val-item">Passwords must match</div>
            </div>

            <button type="button" @click="if(rules.length && rules.match && rules.symbol && rules.number) showConfirmReset = true" class="auth-btn">
                Reset Password
            </button>

            <div class="mt-2">
                <a href="{{ route('login') }}" class="back-link">
                    Back to Login
                </a>
            </div>
        </form>

        {{-- MODALS --}}
        <div x-show="showConfirmReset" x-cloak class="fixed inset-0 flex items-center justify-center p-4 bg-black bg-opacity-50 z-[9999]">
            <div class="bg-white rounded-3xl p-6 shadow-2xl max-w-xs w-full">
                <h3 class="font-bold uppercase text-sm">Confirm Reset?</h3>
                <div class="flex gap-2 mt-4">
                    <button @click="showConfirmReset = false; showModal = true" class="flex-1 py-3 bg-gray-900 text-white rounded-lg font-bold text-xs">YES</button>
                    <button @click="showConfirmReset = false" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-lg font-bold text-xs">NO</button>
                </div>
            </div>
        </div>

        <div x-show="showModal" x-cloak class="fixed inset-0 flex items-center justify-center p-4 bg-black bg-opacity-50 z-[9999]">
            <div class="bg-white rounded-3xl p-6 shadow-2xl max-w-xs w-full">
                <h3 class="font-bold uppercase text-sm text-green-600">Success!</h3>
                <div class="flex flex-col gap-2 mt-4">
                    <button @click="submitResetForm('auto_login')" class="py-3 bg-gray-900 text-white rounded-lg font-bold text-[10px] tracking-widest">DASHBOARD</button>
                    <button @click="submitResetForm('manual_login')" class="py-3 bg-gray-100 text-gray-600 rounded-lg font-bold text-[10px] tracking-widest">LOGIN PAGE</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFBStyle(inputId, svgId) {
            const input = document.getElementById(inputId);
            const svg = document.getElementById(svgId);
            if (input.type === "password") {
                input.type = "text";
                // EXACT FB OPEN EYE
                svg.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            } else {
                input.type = "password";
                // EXACT FB CLOSED EYE
                svg.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            }
        }
    </script>
</x-guest-layout>
