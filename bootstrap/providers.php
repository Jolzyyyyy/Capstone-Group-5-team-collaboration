<?php

/**
 * 🛡️ SERVICE PROVIDER REGISTRATION
 * This file is used by Laravel to load the core service providers for Printify & Co.
 * Your AppServiceProvider here handles the 'RedirectIfAuthenticated' logic,
 * ensuring users are routed correctly based on their Role (Admin vs Customer).
 */

return [
    App\Providers\AppServiceProvider::class,
];