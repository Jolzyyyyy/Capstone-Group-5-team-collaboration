<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServiceCatalogManager
{
    public function __construct(private ServiceItemIdGenerator $serviceItemIdGenerator)
    {
    }

    public function createService(Request $request, array $validated): Service
    {
        return DB::transaction(function () use ($request, $validated): Service {
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

            $this->createVariations($request, $service, $validated['variations']);

            return $service;
        });
    }

    public function updateService(Request $request, Service $service, array $validated): Service
    {
        return DB::transaction(function () use ($request, $service, $validated): Service {
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
            $this->createVariations($request, $service, $validated['variations']);

            return $service;
        });
    }

    private function createVariations(Request $request, Service $service, array $variations): void
    {
        foreach ($variations as $index => $variation) {
            $variationImagePath = null;
            if ($request->hasFile("variation_images.$index")) {
                $variationImagePath = $request->file("variation_images.$index")->store('service-variations', 'public');
            }

            $service->variations()->create([
                'service_item_id' => $this->serviceItemIdGenerator->generate(
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
    }
}
