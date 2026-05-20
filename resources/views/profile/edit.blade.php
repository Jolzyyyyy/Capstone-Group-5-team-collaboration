<x-app-layout>
    <style>
        /* CARDS & GRID STYLES */
        .pro-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .dark-mode-active .pro-card { 
            background: #1F2937; 
            color: white; 
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; 
            gap: 1.5rem 4rem;
        }

        .field-label {
            font-size: 11px;
            font-weight: 800;
            color: #1E293B;
            text-transform: uppercase;
            margin-bottom: 0.2rem;
            display: block;
        }
        .dark-mode-active .field-label { color: #94A3B8; }

        .field-value {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }
        .dark-mode-active .field-value { color: #F8FAFC; }

        .bold-header {
            font-size: 11px;
            font-weight: 900 !important;
            color: #1E293B;
            text-transform: uppercase;
        }
        .dark-mode-active .bold-header { color: #FFFFFF; }

        /* PROFILE HEADER STYLES */
        .profile-header-info { margin-left: 1.5rem; }
        .profile-name-text { font-size: 1.5rem; font-weight: 900; }
        .profile-role-text { font-size: 11px; font-weight: 800; letter-spacing: 0.1em; }

        .section-divider { margin-top: 3.5rem; margin-bottom: 1.5rem; }

        .btn-edit {
            background-color: #4F46E5;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-edit:hover { background-color: #4338CA; }

        /* PHOTO STYLES */
        .profile-main-wrapper { position: relative; display: inline-block; }
        .main-avatar-container {
            height: 140px;
            width: 140px;
            border-radius: 50%;
            background: #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 5px solid #4F46E5;
            position: relative;
        }

        .online-dot {
            position: absolute;
            background-color: #22C55E;
            border: 2px solid white;
            border-radius: 50%;
            z-index: 10;
        }
        .dark-mode-active .online-dot { border-color: #1F2937; }

        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ 
            currentTab: 'my-profile', 
            isEditing: false,
            imageUrl: 'https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=6366f1&color=fff',
            profile: {
                firstName: '{{ Auth::user()->first_name ?? 'Eyra Mae' }}',
                lastName: '{{ Auth::user()->last_name ?? 'Alla' }}',
                email: '{{ Auth::user()->email }}',
                phone: '+63 945 346 46',
                region: 'Metro Manila',
                city: 'Quezon City',
                baranggay: 'Commonwealth',
                postalCode: '1121',
                street: 'Blk 6 Lot 8 Ninada St. Litex Rd.',
                bio: 'CUSTOMER ACCOUNT'
            },
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imageUrl = URL.createObjectURL(file);
                }
            }
         }">

        <!-- TAB NAVIGATION (Optional: If you want internal tabs) -->
        <div class="flex gap-6 mb-6 border-b border-gray-200 dark:border-gray-700">
            <button @click="currentTab = 'my-profile'" :class="currentTab === 'my-profile' ? 'border-b-2 border-indigo-500 text-indigo-500' : 'text-gray-500'" class="pb-2 font-bold text-sm uppercase tracking-wider">My Profile</button>
            <button @click="currentTab = 'security'" :class="currentTab === 'security' ? 'border-b-2 border-indigo-500 text-indigo-500' : 'text-gray-500'" class="pb-2 font-bold text-sm uppercase tracking-wider">Security & Privacy</button>
        </div>

        <!-- MY PROFILE SECTION -->
        <div x-show="currentTab === 'my-profile'" x-transition>
            <h1 class="text-2xl font-black mb-6" :class="darkMode ? 'text-white' : 'text-slate-900'">My Profile</h1>
            
            <div class="pro-card shadow-sm">
                <div class="flex items-center">
                    <div class="profile-main-wrapper">
                        <div class="main-avatar-container shadow-2xl">
                            <img :src="imageUrl" class="h-full w-full object-cover">
                            <label class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer rounded-full">
                                <input type="file" class="hidden" @change="handleFileSelect">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </label>
                        </div>
                        <div class="online-dot" style="width: 24px; height: 24px; bottom: 8px; right: 8px; border-width: 4px;"></div>
                    </div>
                    <div class="profile-header-info">
                        <h3 class="profile-name-text" x-text="profile.firstName + ' ' + profile.lastName"></h3>
                        <p class="profile-role-text text-indigo-500 mt-0.5" x-text="profile.bio"></p>
                        <p class="text-slate-400 text-xs font-bold mt-1" x-text="profile.city"></p>
                    </div>
                </div>
            </div>

            <div class="pro-card shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="bold-header text-base">Personal Information</h2>
                    <button @click="isEditing = !isEditing" class="btn-edit" x-text="isEditing ? 'Save Changes' : 'Edit Profile'"></button>
                </div>
                
                <div class="info-grid">
                    <div>
                        <label class="field-label">First Name</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.firstName"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.firstName"></template>
                    </div>
                    <div>
                        <label class="field-label">Last Name</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.lastName"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.lastName"></template>
                    </div>
                    <div>
                        <label class="field-label">Email Address</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.email"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.email"></template>
                    </div>
                    <div>
                        <label class="field-label">Phone Number</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.phone"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.phone"></template>
                    </div>
                </div>

                <div class="section-divider border-t dark:border-gray-700 pt-6">
                    <h2 class="bold-header text-base">Address Selection</h2>
                </div>

                <div class="info-grid">
                    <div>
                        <label class="field-label">Region</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.region"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.region"></template>
                    </div>
                    <div>
                        <label class="field-label">City</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.city"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.city"></template>
                    </div>
                    <div>
                        <label class="field-label">Baranggay</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.baranggay"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.baranggay"></template>
                    </div>
                    <div>
                        <label class="field-label">Postal Code</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.postalCode"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.postalCode"></template>
                    </div>
                    <div class="col-span-2">
                        <label class="field-label">Street Name, Building, House No.</label>
                        <template x-if="!isEditing"><p class="field-value" x-text="profile.street"></p></template>
                        <template x-if="isEditing"><input class="w-full border rounded-xl p-3 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white" x-model="profile.street"></template>
                    </div>
                </div>
            </div>
        </div>

   <!-- SECURITY SECTION -->
   <div x-show="currentTab === 'security'" x-transition x-cloak>
            <h1 class="text-2xl font-black mb-6" :class="darkMode ? 'text-white' : 'text-slate-900'">Security & Privacy</h1>
            
            <!-- OK LANG ITO -->
            <div class="pro-card shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <!-- DAPAT NAKATAGO/COMMENT LAHAT NG NASA BABA NITO PARA WALANG ERROR -->
            {{-- 
            <div class="pro-card shadow-sm border border-red-100">
                @include('profile.partials.delete-user-form')
            </div>
            --}}

        </div> <!-- End of Security Section -->
    </div> <!-- End of x-data container -->
</x-app-layout>