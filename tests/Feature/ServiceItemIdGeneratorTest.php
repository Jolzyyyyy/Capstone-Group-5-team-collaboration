<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceVariation;
use App\Services\ServiceItemIdGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceItemIdGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_builds_service_item_ids_from_service_variation_attributes(): void
    {
        $generator = app(ServiceItemIdGenerator::class);

        $id = $generator->generate(
            'Printing',
            'Text Only',
            'Finish: Glossy',
            'B&W',
            'Short (8.5 x 11)',
            'Package A'
        );

        $this->assertSame('DOC-TXT-GLS-BW-SHT-PKGA-001', $id);
    }

    public function test_it_increments_the_next_matching_service_item_id(): void
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

        ServiceVariation::create([
            'service_id' => $service->id,
            'service_item_id' => 'DOC-TXT-BW-SHT-002',
            'printing_category' => 'Text Only',
            'color_mode' => 'B&W',
            'product_size' => 'Short (8.5 x 11)',
            'retail_price' => 5,
            'bulk_price' => 4,
            'is_active' => true,
        ]);

        $id = app(ServiceItemIdGenerator::class)->generate(
            'Printing',
            'Text Only',
            null,
            'B&W',
            'Short (8.5 x 11)',
            null
        );

        $this->assertSame('DOC-TXT-BW-SHT-003', $id);
    }
}
