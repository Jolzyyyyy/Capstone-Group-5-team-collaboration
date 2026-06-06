<?php

namespace App\Services;

use App\Models\ServiceVariation;
use Illuminate\Support\Str;

class ServiceItemIdGenerator
{
    public function generate(
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

    private function categoryPrefix(?string $category): string
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

    private function printingPrefix(?string $printingCategory): ?string
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

    private function colorPrefix(?string $colorMode): ?string
    {
        return match ($colorMode) {
            'B&W' => 'BW',
            'Partial Color' => 'PC',
            'Full Color' => 'FC',
            default => null,
        };
    }

    private function sizePrefix(?string $productSize): ?string
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

    private function finishPrefix(?string $finishType): ?string
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

    private function packagePrefix(?string $packageType): ?string
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
}
