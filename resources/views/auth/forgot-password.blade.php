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

    {{-- 2. MATCHING UI STYLING --}}
    <style>
        .forgot-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.05);
            width: 350px; 
            max-width: 90vw;
            padding: 25px; /* In-align sa padding ng Reset Page */
            text-align: center;
            box-sizing: border-box;
        }

        /* TITLE STYLE: Match sa Sign In/Sign Up & Reset Password (1.25rem) */
        .auth-title {
            font-size: 1.25rem; 
            font-weight: 700;
            color: #1a202c;
            margin-top: 10px;
            margin-bottom: 1rem;
        }

        .custom-input {
            background-color: #f0f2f5;
            border: none !important;
            padding: 10px 15px; /* Match sa height ng ibang forms */
            border-radius: 8px;
            width: 100%;
            margin-top: 6px;
            font-size: 13px;
            color: #1a202c;
            box-sizing: border-box;
            outline: none;
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

        .instruction-text {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        /* BACK TO LOGIN: Match sa Reset Password (13px & Grey color) */
        .back-link {
            display: inline-block;
            margin-top: 25px;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            color: #2563eb; /* Match sa hover color ng Reset Page */
            text-decoration: underline;
        }

        .label-text {
            display: block;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-left: 2px;
        }
    </style>

    <div class="forgot-container">
        {{-- TITLE: Updated to .auth-title class --}}
        <h1 class="auth-title">Forgot Password</h1>
        
        <p class="instruction-text">
            {{ __('Enter your email address and we will send you a code to reset your password.') }}
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="text-left">
                <label for="email" id="email-label" class="label-text">{{ __('Email Address') }}</label>
                <input id="email" 
                       class="custom-input" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your email"
                       required 
                       autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
            </div>

            <div class="flex items-center mt-4 px-1">
                <input id="use_backup" type="checkbox" 
                       class="rounded border-gray-300 text-red-500 focus:ring-red-500" 
                       name="use_backup" value="1" onchange="toggleEmailLabel()">
                <label for="use_backup" class="ms-2 text-sm text-gray-600 cursor-pointer select-none">
                    {{ __('Use recovery email') }}
                </label>
            </div>

            <button type="submit" class="auth-btn">
                {{ __('Send Reset Code') }}
            </button>

            <div class="mt-2">
                <a class="back-link" href="{{ route('login') }}">
                    {{ __('Back to Login') }}
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleEmailLabel() {
            const checkbox = document.getElementById('use_backup');
            const label = document.getElementById('email-label');
            const input = document.getElementById('email');
            
            if (checkbox.checked) {
                label.innerText = "{{ __('Recovery Email') }}";
                input.placeholder = "Enter your recovery email";
            } else {
                label.innerText = "{{ __('Email Address') }}";
                input.placeholder = "Enter your email";
            }
        }
    </script>
</x-guest-layout>