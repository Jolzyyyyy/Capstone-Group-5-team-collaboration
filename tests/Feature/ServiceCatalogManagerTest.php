<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceVariation;
use App\Services\ServiceCatalogManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceCatalogManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_service_with_variations(): void
    {
        $service = app(ServiceCatalogManager::class)->createService(
            Request::create('/p-co-2026/admin/services', 'POST'),
            [
                'name' => 'Document Printing',
                'category' => 'Printing',
                'description' => 'Print documents.',
                'is_active' => true,
                'variations' => [
                    [
                        'printing_category' => 'Text Only',
                        'color_mode' => 'B&W',
                        'product_size' => 'Short (8.5 x 11)',
                        'finish_type' => null,
                        'package_type' => null,
                        'retail_price' => 5,
                        'bulk_price' => 4,
                        'is_active' => true,
                    ],
                ],
            ]
        );

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Document Printing',
            'category' => 'Printing',
            'retail_price' => 5,
            'bulk_price' => 4,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('service_variations', [
            'service_id' => $service->id,
            'service_item_id' => 'DOC-TXT-BW-SHT-001',
            'retail_price' => 5,
            'bulk_price' => 4,
            'is_active' => true,
        ]);
    }

    public function test_it_updates_service_details_and_replaces_variations(): void
    {
        $service = Service::create([
            'name' => 'Old Service',
            'category' => 'Custom',
            'retail_price' => 10,
            'bulk_price' => 8,
            'unit' => null,
            'description' => null,
            'is_active' => true,
        ]);

        ServiceVariation::create([
            'service_id' => $service->id,
            'service_item_id' => 'CSP-001',
            'retail_price' => 10,
            'bulk_price' => 8,
            'is_active' => true,
        ]);

        app(ServiceCatalogManager::class)->updateService(
            Request::create("/p-co-2026/admin/services/{$service->id}", 'PUT'),
            $service,
            [
                'name' => 'Updated Service',
                'category' => 'Photocopy',
                'description' => 'Updated description.',
                'is_active' => false,
                'variations' => [
                    [
                        'printing_category' => 'Image Only',
                        'color_mode' => 'Full Color',
                        'product_size' => 'A4 (8.27 x 11.69)',
                        'finish_type' => null,
                        'package_type' => null,
                        'retail_price' => 12,
                        'bulk_price' => 9,
                        'is_active' => true,
                    ],
                ],
            ]
        );

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Updated Service',
            'category' => 'Photocopy',
            'retail_price' => 12,
            'bulk_price' => 9,
            'is_active' => false,
        ]);

        $this->assertDatabaseMissing('service_variations', [
            'service_id' => $service->id,
            'service_item_id' => 'CSP-001',
        ]);

        $this->assertDatabaseHas('service_variations', [
            'service_id' => $service->id,
            'service_item_id' => 'PSC-IMG-FC-A4-001',
            'retail_price' => 12,
            'bulk_price' => 9,
            'is_active' => true,
        ]);
    }

    public function test_it_deletes_a_service_and_its_catalog_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('services/document-printing.jpg', 'image');

        $service = Service::create([
            'name' => 'Document Printing',
            'category' => 'Printing',
            'retail_price' => 5,
            'bulk_price' => 4,
            'unit' => null,
            'description' => null,
            'image_path' => 'services/document-printing.jpg',
            'is_active' => true,
        ]);

        app(ServiceCatalogManager::class)->deleteService($service);

        $this->assertDatabaseMissing('services', [
            'id' => $service->id,
        ]);
        Storage::disk('public')->assertMissing('services/document-printing.jpg');
    }

    public function test_it_toggles_service_active_status(): void
    {
        $service = Service::create([
            'name' => 'Document Printing',
            'category' => 'Printing',
            'retail_price' => 5,
            'bulk_price' => 4,
            'unit' => null,
            'description' => null,
            'is_active' => true,
        ]);

        app(ServiceCatalogManager::class)->toggleServiceActive($service);

        $this->assertFalse($service->refresh()->is_active);
    }
}
