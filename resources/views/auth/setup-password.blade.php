<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="d-flex justify-content-center">
                <div class="card border-0 shadow-sm" style="width: 100%; max-width: 420px; border-radius: 16px;">
                    <div class="card-body p-4 p-md-5">
                        
                        {{-- Icon/Logo Section --}}
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 80px; height: 80px;">
                                <svg width="40" height="40" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M61.8548 14.6253L31.3533 0.135315C31.1113 -0.0451051 30.8887 -0.0451051 30.6467 0.135315L0.14519 14.6253C-0.0483967 14.7155 -0.0483967 15.0763 0.14519 15.1665L30.6467 29.6565C30.8887 29.837 31.1113 29.837 31.3533 29.6565L61.8548 15.1665C62.0484 15.0763 62.0484 14.7155 61.8548 14.6253Z" fill="#1a202c"/>
                                </svg>
                            </div>
                        </div>

                        <h4 class="text-center fw-bold mb-2" style="color: #1a202c;">Set Up Password</h4>
                        <p class="text-center text-muted small mb-4 px-3">
                            Secure your account by setting a password for direct login access.
                        </p>

                        {{-- Alert for Success/Error Messages --}}
                        @if (session('status'))
                            <div class="alert alert-success small py-2 text-center mb-4" style="border-radius: 10px;">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('password.setup.submit') }}" method="POST">
                            @csrf
                            
                            {{-- New Password --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">NEW PASSWORD</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror" 
                                           style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 0.75rem; border-radius: 10px;" 
                                           placeholder="Minimum 8 characters" required autofocus>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit" class="btn btn-dark w-100 py-3 fw-bold shadow-sm submit-btn" 
                                    style="background-color: #1a202c; border: none; border-radius: 10px; transition: 0.3s;">
                                SAVE AND CONTINUE
                            </button>

                            {{-- Skip link --}}
                            <div class="text-center mt-4">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small hover-opacity">
                                    I'll do this later
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-opacity:hover { opacity: 0.7; transition: 0.2s; }
        
        .form-control:focus {
            background-color: #fff !important;
            border-color: #1a202c !important;
            box-shadow: 0 0 0 3px rgba(26, 32, 44, 0.1) !important;
        }

        .submit-btn:hover {
            background-color: #2d3748 !important;
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Customizing the invalid feedback to match the UI style */
        .invalid-feedback {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
</x-app-layout>
