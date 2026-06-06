@php
    $otpLockoutSeconds = $otpLockoutSeconds ?? 0;
    $resendCooldownSeconds = $resendCooldownSeconds ?? 0;
@endphp

<x-guest-layout>
    {{-- 1. LAYOUT OVERRIDE (Same as Customer) --}}
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

    {{-- 2. EXACT CUSTOMER UI DIMENSIONS & SPACING (Admin Theme Colors) --}}
    <style>
        .auth-container {
            background-color: #fff !important;
            border-radius: 20px !important;
            box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.05) !important;
            width: 350px !important; /* EXACT SIZE */
            max-width: 95vw !important;
            padding: 30px 25px !important; /* EXACT PADDING */
            text-align: center !important;
            box-sizing: border-box !important;
        }

        .auth-title {
            font-size: 1.25rem !important; 
            font-weight: 700 !important;
            color: #1a202c !important;
            margin-bottom: 0.5rem !important;
            text-transform: none !important;
            letter-spacing: normal !important;
        }

        .instruction-text {
            font-size: 13px !important;
            color: #64748b !important;
            line-height: 1.5 !important;
            margin-bottom: 20px !important;
        }

        .otp-input {
            background-color: #f0f2f5 !important;
            border: none !important;
            padding: 12px 15px !important;
            border-radius: 8px !important; 
            width: 100% !important;
            font-size: 22px !important;
            font-weight: 800 !important;
            letter-spacing: 0.4em !important;
            text-align: center !important;
            color: #1a202c !important;
            box-sizing: border-box !important;
            outline: none !important;
            margin-bottom: 5px !important;
        }

        .auth-btn {
            background-color: #4f46e5 !important; /* ADMIN THEME BLUE */
            color: white !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            padding: 10px 0 !important;
            border-radius: 25px !important;
            width: 100% !important;
            margin-top: 15px !important;
            cursor: pointer !important;
            font-size: 12px !important;
            letter-spacing: 1px !important;
            border: none !important;
            transition: all 0.2s ease !important;
            display: block !important;
        }

        .auth-btn:hover { background-color: #4338ca !important; }
        .auth-btn:active { transform: scale(0.98) !important; }
        .auth-btn:disabled { background-color: #cbd5e1 !important; cursor: not-allowed !important; }

        .footer-nav {
            margin-top: 10px !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 2px !important;
        }

        .nav-link {
            font-size: 13px !important;
            color: #64748b !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            background: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            cursor: pointer !important;
        }

        .nav-link:hover { 
            color: #4f46e5 !important; 
            text-decoration: underline !important;
        }

        .resend-link {
            color: #4f46e5 !important; /* ADMIN THEME BLUE */
            font-weight: 700 !important;
        }

        .timer-info {
            font-size: 12px !important;
            color: #94a3b8 !important;
            font-style: italic !important;
            margin-top: 12px !important;
            margin-bottom: 5px !important;
        }
    </style>

    <div class="auth-container"
         x-data="{
            otp: '',
            timer: {{ max((int) $resendCooldownSeconds, 0) }},
            canResend: {{ (int) $resendCooldownSeconds <= 0 ? 'true' : 'false' }},
            init() {
                if (this.canResend) {
                    return;
                }

                let interval = setInterval(() => {
                    if (this.timer > 0) {
                        this.timer--;
                    } else {
                        this.canResend = true;
                        clearInterval(interval);
                    }
                }, 1000);
            }
         }">
        
        <h1 class="auth-title">Verify Admin Account</h1>
        
        <p class="instruction-text">
            Please enter the 6-digit security code sent to your email address to continue.
        </p>

        @if (session('status'))
            <div style="color: #16a34a; font-size: 12px; font-weight: 600; margin-bottom: 10px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.otp.submit') }}">
            @csrf
            <input type="hidden" name="email" value="{{ session('admin_email') }}">

            <input
                id="otp"
                type="text"
                name="otp"
                x-model="otp"
                @input="otp = otp.replace(/[^0-9]/g, '')"
                maxlength="6"
                placeholder="000000"
                class="otp-input"
                required
                autofocus
            />

            @error('otp')
                <div style="color: #dc2626; font-size: 11px; font-weight: 700; margin-top: 5px;">{{ $message }}</div>
            @enderror

            <button type="submit" class="auth-btn" x-bind:disabled="otp.length !== 6">
                Verify My Account
            </button>
        </form>

        <div class="footer-nav">
            {{-- Resend Logic Section --}}
            <div>
                <form method="POST" action="{{ route('admin.otp.resend') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('admin_email') }}">
                    
                    <button type="submit" x-show="canResend" class="nav-link resend-link">
                        Resend Code
                    </button>

                    <div x-show="!canResend" class="timer-info">
                        Resend available in <span x-text="timer" style="color:#4f46e5; font-weight: bold;"></span>s
                    </div>
                </form>
            </div>

            {{-- Logout Section --}}
            <div style="margin-top: -2px;">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="nav-link">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
