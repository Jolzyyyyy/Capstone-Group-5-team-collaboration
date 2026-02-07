<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [

            // DOCUMENT PRINTING
            [
                'name' => 'Document Printing (Black & White)',
                'category' => 'Document Printing',
                'retail_price' => 2.00,
                'bulk_price' => 1.50,
                'unit' => 'per page',
                'description' => 'Standard black and white document printing using 80gsm paper.',
                'image_path' => 'images/services/bw-printing.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Document Printing (Colored)',
                'category' => 'Document Printing',
                'retail_price' => 10.00,
                'bulk_price' => 8.00,
                'unit' => 'per page',
                'description' => 'High quality colored document printing.',
                'image_path' => 'images/services/color-printing.jpg',
                'is_active' => true,
            ],

            // PHOTOCOPY
            [
                'name' => 'Photocopy (Black & White)',
                'category' => 'Photocopy & Scanning',
                'retail_price' => 2.00,
                'bulk_price' => 1.50,
                'unit' => 'per page',
                'description' => 'Fast and clear black and white photocopy service.',
                'image_path' => 'images/services/bw-photocopy.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Photocopy (Colored)',
                'category' => 'Photocopy & Scanning',
                'retail_price' => 8.00,
                'bulk_price' => 6.00,
                'unit' => 'per page',
                'description' => 'Colored photocopy with accurate color reproduction.',
                'image_path' => 'images/services/color-photocopy.jpg',
                'is_active' => true,
            ],

            // ID & PHOTO SERVICES
            [
                'name' => 'ID Picture Package',
                'category' => 'ID & Photo Services',
                'retail_price' => 120.00,
                'bulk_price' => 100.00,
                'unit' => 'per set',
                'description' => 'Standard ID picture with layout and minor photo enhancement.',
                'image_path' => 'images/services/id-picture.jpg',
                'is_active' => true,
            ],

            // LARGE FORMAT
            [
                'name' => 'Tarpaulin Printing',
                'category' => 'Large Format Printing',
                'retail_price' => 150.00,
                'bulk_price' => 120.00,
                'unit' => 'per sq ft',
                'description' => 'Durable tarpaulin printing for banners and signage.',
                'image_path' => 'images/services/tarpaulin.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
