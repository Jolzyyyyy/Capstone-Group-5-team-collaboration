<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [

            [
                'name' => 'Document Printing',
                'category' => 'Printing',
                'retail_price' => 5,
                'bulk_price' => 4,
                'description' => 'Professional document printing services.',
                'image_path' => 'images/services/document-printing.jpg',
                'is_active' => true,
            ],

            [
                'name' => 'Photocopy & Scanning',
                'category' => 'Printing',
                'retail_price' => 3,
                'bulk_price' => 2,
                'description' => 'High speed photocopy and scanning services.',
                'image_path' => 'images/services/photocopy.jpg',
                'is_active' => true,
            ],

            [
                'name' => 'ID & Photo Services',
                'category' => 'Photo',
                'retail_price' => 60,
                'bulk_price' => 60,
                'description' => 'Professional ID and photo printing services.',
                'image_path' => 'images/services/id-photo.jpg',
                'is_active' => true,
            ],

            [
                'name' => 'Lamination & Binding',
                'category' => 'Finishing',
                'retail_price' => 25,
                'bulk_price' => 20,
                'description' => 'Document lamination and binding.',
                'image_path' => 'images/services/lamination.jpg',
                'is_active' => true,
            ],

            [
                'name' => 'Large Format Printing',
                'category' => 'Large Format',
                'retail_price' => 100,
                'bulk_price' => 95,
                'description' => 'Tarpaulin and large format printing.',
                'image_path' => 'images/services/tarpaulin.jpg',
                'is_active' => true,
            ],

            [
                'name' => 'Custom Special Printing',
                'category' => 'Custom',
                'retail_price' => 150,
                'bulk_price' => 140,
                'description' => 'Special custom printing services.',
                'image_path' => 'images/services/custom.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}