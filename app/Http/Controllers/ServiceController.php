<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
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

        DB::transaction(function () use ($request, $validated) {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('services', 'public');
            }

            $service = Service::create([
                'name' => $validated['name'],
                'category' => $validated['category'] ?? null,
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'is_active' => (bool) ($validated['is_active'] ?? true),

                // temporary compatibility with old public/admin screens
                'retail_price' => $validated['variations'][0]['retail_price'],
                'bulk_price' => $validated['variations'][0]['bulk_price'],
                'unit' => null,
            ]);

            foreach ($validated['variations'] as $index => $variation) {
                $variationImagePath = null;
                if ($request->hasFile("variation_images.$index")) {
                    $variationImagePath = $request->file("variation_images.$index")->store('service-variations', 'public');
                }

                $service->variations()->create([
                    'service_item_id' => $this->generateServiceItemId(
                        $service->category,
                        $variation['printing_category'] ?? null,
                        $variation['finish_type'] ?? null,
                        $variation['color_mode'] ?? null,
                        $variation['product_size'] ?? null,
                        $variation['package_type'] ?? null
                    ),
                    'variation_image_path' => $variationImagePath,
                    'printing_category' => $variation['printing_category'] ?? null,
                    'color_mode' => $variation['color_mode'] ?? null,
                    'product_size' => $variation['product_size'] ?? null,
                    'finish_type' => $variation['finish_type'] ?? null,
                    'package_type' => $variation['package_type'] ?? null,
                    'retail_price' => $variation['retail_price'],
                    'bulk_price' => $variation['bulk_price'],
                    'is_active' => (bool) ($variation['is_active'] ?? true),
                ]);
            }
        });

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

        DB::transaction(function () use ($request, $validated, $service) {
            $imagePath = $service->image_path;

            if ($request->hasFile('image')) {
                if ($service->image_path) {
                    Storage::disk('public')->delete($service->image_path);
                }

                $imagePath = $request->file('image')->store('services', 'public');
            }

            $service->update([
                'name' => $validated['name'],
                'category' => $validated['category'] ?? null,
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'is_active' => (bool) ($validated['is_active'] ?? $service->is_active),
                'retail_price' => $validated['variations'][0]['retail_price'],
                'bulk_price' => $validated['variations'][0]['bulk_price'],
            ]);

            foreach ($service->variations as $existingVariation) {
                if ($existingVariation->variation_image_path) {
                    Storage::disk('public')->delete($existingVariation->variation_image_path);
                }
            }

            $service->variations()->delete();

            foreach ($validated['variations'] as $index => $variation) {
                $variationImagePath = null;
                if ($request->hasFile("variation_images.$index")) {
                    $variationImagePath = $request->file("variation_images.$index")->store('service-variations', 'public');
                }

                $service->variations()->create([
                    'service_item_id' => $this->generateServiceItemId(
                        $service->category,
                        $variation['printing_category'] ?? null,
                        $variation['finish_type'] ?? null,
                        $variation['color_mode'] ?? null,
                        $variation['product_size'] ?? null,
                        $variation['package_type'] ?? null
                    ),
                    'variation_image_path' => $variationImagePath,
                    'printing_category' => $variation['printing_category'] ?? null,
                    'color_mode' => $variation['color_mode'] ?? null,
                    'product_size' => $variation['product_size'] ?? null,
                    'finish_type' => $variation['finish_type'] ?? null,
                    'package_type' => $variation['package_type'] ?? null,
                    'retail_price' => $variation['retail_price'],
                    'bulk_price' => $variation['bulk_price'],
                    'is_active' => (bool) ($variation['is_active'] ?? true),
                ]);
            }
        });

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage (admin).
     */
    public function destroy(Service $service)
    {
        $this->authorizeServiceManagement();

        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Quick enable/disable (admin).
     */
    public function toggleActive(Service $service)
    {
        $this->authorizeServiceManagement();

        $service->is_active = !$service->is_active;
        $service->save();

        return back()->with('success', 'Service status updated.');
    }

    protected function categoryPrefix(?string $category): string
    {
        return match ($category) {
            'Printing' => 'DOC',
            'Photocopy' => 'PSC',
            'ID Picture' => 'IPS',
            'Laminating' => 'LNB',
            'Tarpaulin' => 'LFP',
            'Custom' => 'CSP',
            default => 'GEN',
        };
    }

    private function authorizeServiceManagement(): void
    {
        $user = request()->user();

        abort_unless($user && ($user->isDeveloper() || $user->isAdmin()), 403);
    }

    protected function printingPrefix(?string $printingCategory): ?string
    {
        return match ($printingCategory) {
            'Text Only' => 'TXT',
            'Text with Image' => 'TXI',
            'Image Only' => 'IMG',
            'Photo Services' => 'PHS',
            'Sintra Board Printing' => 'SBP',
            default => null,
        };
    }

    protected function colorPrefix(?string $colorMode): ?string
    {
        return match ($colorMode) {
            'B&W' => 'BW',
            'Partial Color' => 'PC',
            'Full Color' => 'FC',
            default => null,
        };
    }

    protected function sizePrefix(?string $productSize): ?string
    {
        return match ($productSize) {
            'Short (8.5 x 11)' => 'SHT',
            'A4 (8.27 x 11.69)' => 'A4',
            'Legal (8.5 x 14)' => 'LGL',
            'A2 (22.86 x 29.7)' => 'A2',
            'A3 (11.69 x 16.54)' => 'A3',
            'A5 (10.16 x 14.87)' => 'A5',
            default => null,
        };
    }

    protected function finishPrefix(?string $finishType): ?string
    {
        return match ($finishType) {
            'Finish: Glossy' => 'GLS',
            'Finish: Matte' => 'MAT',
            'Finish: Leather' => 'LTH',
            'Finish: Canvas Matte' => 'CVM',
            'Finish: Glittered' => 'GLT',
            'Finish: 3D' => 'THD',
            'Finish: Rainbow' => 'RNB',
            'Finish: Broken Glass' => 'BGS',
            default => null,
        };
    }

    protected function packagePrefix(?string $packageType): ?string
    {
        return match ($packageType) {
            'Package A' => 'PKGA',
            'Package B' => 'PKGB',
            'Package C' => 'PKGC',
            'Package D' => 'PKGD',
            'Package E' => 'PKGE',
            'Package F' => 'PKGF',
            default => null,
        };
    }

    protected function generateServiceItemId(
        ?string $category,
        ?string $printingCategory,
        ?string $finishType,
        ?string $colorMode,
        ?string $productSize,
        ?string $packageType
    ): string {
        $parts = array_values(array_filter([
            $this->categoryPrefix($category),
            $this->printingPrefix($printingCategory),
            $this->finishPrefix($finishType),
            $this->colorPrefix($colorMode),
            $this->sizePrefix($productSize),
            $this->packagePrefix($packageType),
        ]));

        $base = implode('-', $parts);

        $latest = ServiceVariation::where('service_item_id', 'like', $base . '-%')
            ->orderByDesc('service_item_id')
            ->first();

        $next = 1;

        if ($latest) {
            $lastPart = (int) Str::afterLast($latest->service_item_id, '-');
            $next = $lastPart + 1;
        }

        return $base . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}
