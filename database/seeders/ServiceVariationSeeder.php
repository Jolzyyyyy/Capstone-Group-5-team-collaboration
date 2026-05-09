<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceVariation;
use Illuminate\Database\Seeder;

class ServiceVariationSeeder extends Seeder
{
    public function run(): void
    {
        $variations = [
            'Document Printing' => [
                [
                    'service_item_id' => 'DOC-BW-A4',
                    'printing_category' => 'Document',
                    'color_mode' => 'Black and White',
                    'product_size' => 'A4',
                    'retail_price' => 5,
                    'bulk_price' => 4,
                ],
                [
                    'service_item_id' => 'DOC-FC-A4',
                    'printing_category' => 'Document',
                    'color_mode' => 'Full Color',
                    'product_size' => 'A4',
                    'retail_price' => 10,
                    'bulk_price' => 8,
                ],
            ],
            'Photocopy & Scanning' => [
                [
                    'service_item_id' => 'COPY-BW-A4',
                    'printing_category' => 'Photocopy',
                    'color_mode' => 'Black and White',
                    'product_size' => 'A4',
                    'retail_price' => 3,
                    'bulk_price' => 2,
                ],
            ],
            'ID & Photo Services' => [
                [
                    'service_item_id' => 'PHOTO-ID-1X1',
                    'printing_category' => 'Photo',
                    'color_mode' => 'Full Color',
                    'product_size' => '1x1 ID Photo',
                    'package_type' => 'Set of 6',
                    'retail_price' => 60,
                    'bulk_price' => 60,
                ],
            ],
            'Lamination & Binding' => [
                [
                    'service_item_id' => 'LAM-A4-GLOSS',
                    'printing_category' => 'Finishing',
                    'product_size' => 'A4',
                    'finish_type' => 'Gloss Lamination',
                    'retail_price' => 25,
                    'bulk_price' => 20,
                ],
            ],
            'Large Format Printing' => [
                [
                    'service_item_id' => 'LFP-TARP-3X4',
                    'printing_category' => 'Tarpaulin',
                    'color_mode' => 'Full Color',
                    'product_size' => '3x4 ft',
                    'retail_price' => 100,
                    'bulk_price' => 95,
                ],
            ],
        ];

        foreach ($variations as $serviceName => $serviceVariations) {
            $service = Service::where('name', $serviceName)->first();

            if (!$service) {
                continue;
            }

            foreach ($serviceVariations as $variation) {
                ServiceVariation::updateOrCreate(
                    ['service_item_id' => $variation['service_item_id']],
                    array_merge(
                        [
                            'service_id' => $service->id,
                            'variation_image_path' => null,
                            'is_active' => true,
                        ],
                        $variation
                    )
                );
            }
        }
    }
}
