<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\ServiceCatalogManager;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private ServiceCatalogManager $serviceCatalogManager)
    {
    }

    /**
     * Protect admin-only actions.
     * Customers can access: index, show
     * Admin (logged-in users) can access the rest.
     */
   

    /**
     * Display a listing of services (customer-facing).
     * Optional filter: ?category=Photocopy
     */
    public function index(Request $request)
    {
        $query = Service::query()
            ->where('is_active', true)
            ->with(['activeVariations' => function ($q) {
                $q->orderBy('service_item_id');
            }])
            ->orderBy('category')
            ->orderBy('name');

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        $services = $query->paginate(12)->withQueryString();

        $activeSection = 'products';

        return view('welcome', compact('services', 'activeSection'));
    }

    public function adminIndex()
    {
        $services = Service::query()
            ->withCount(['variations', 'activeVariations'])
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('services.admin_index', [
            'services' => $services,
            'isViewOnly' => auth()->user()?->isAdminClient() ?? false,
        ]);
    }

    /**
     * Display a single service (customer-facing).
     */
    public function show(Service $service)
    {
        abort_if(!$service->is_active, 404);

        $service->load(['activeVariations' => function ($q) {
            $q->orderBy('service_item_id');
        }]);

        return view('services.show', compact('service'));
    }

    /**
     * Show the form for creating a new service (admin).
     */
    public function create()
    {
        $this->authorizeServiceManagement();

        return view('services.create');
    }

    /**
     * Store a newly created service in storage (admin).
     */
    public function store(Request $request)
    {
        $this->authorizeServiceManagement();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],

            'variations' => ['required', 'array', 'min:1'],
            'variations.*.printing_category' => ['nullable', 'string', 'max:255'],
            'variations.*.color_mode' => ['nullable', 'string', 'max:255'],
            'variations.*.product_size' => ['nullable', 'string', 'max:255'],
            'variations.*.finish_type' => ['nullable', 'string', 'max:255'],
            'variations.*.package_type' => ['nullable', 'string', 'max:255'],
            'variations.*.retail_price' => ['required', 'numeric', 'min:0'],
            'variations.*.bulk_price' => ['required', 'numeric', 'min:0'],
            'variations.*.is_active' => ['nullable', 'boolean'],
            'variation_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $this->serviceCatalogManager->createService($request, $validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Show the form for editing the specified service (admin).
     */
    public function edit(Service $service)
    {
        $this->authorizeServiceManagement();

        $service->load('variations');

        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage (admin).
     */
    public function update(Request $request, Service $service)
    {
        $this->authorizeServiceManagement();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],

            'variations' => ['required', 'array', 'min:1'],
            'variations.*.printing_category' => ['nullable', 'string', 'max:255'],
            'variations.*.color_mode' => ['nullable', 'string', 'max:255'],
            'variations.*.product_size' => ['nullable', 'string', 'max:255'],
            'variations.*.finish_type' => ['nullable', 'string', 'max:255'],
            'variations.*.package_type' => ['nullable', 'string', 'max:255'],
            'variations.*.retail_price' => ['required', 'numeric', 'min:0'],
            'variations.*.bulk_price' => ['required', 'numeric', 'min:0'],
            'variations.*.is_active' => ['nullable', 'boolean'],
            'variation_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $this->serviceCatalogManager->updateService($request, $service, $validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage (admin).
     */
    public function destroy(Service $service)
    {
        $this->authorizeServiceManagement();

        $this->serviceCatalogManager->deleteService($service);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Quick enable/disable (admin).
     */
    public function toggleActive(Service $service)
    {
        $this->authorizeServiceManagement();

        $this->serviceCatalogManager->toggleServiceActive($service);

        return back()->with('success', 'Service status updated.');
    }

    private function authorizeServiceManagement(): void
    {
        $user = request()->user();

        abort_unless($user && ($user->isDeveloper() || $user->isAdmin()), 403);
    }
}
