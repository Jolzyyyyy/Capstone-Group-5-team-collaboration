<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceVariation;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        $serviceIds = collect($cart)
            ->pluck('service_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
        $services = $serviceIds->isEmpty()
            ? collect()
            : Service::with('activeVariations')->whereIn('id', $serviceIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $cartKey => $row) {
            $price = (float) ($row['price'] ?? 0);
            $qty = (int) ($row['qty'] ?? 1);
            $lineTotal = $price * $qty;
            $total += $lineTotal;
            $service = $services->get((int) ($row['service_id'] ?? 0));

            $items[] = [
                'cart_key' => $cartKey,
                'service_id' => $row['service_id'] ?? null,
                'variation_id' => $row['variation_id'] ?? null,
                'service_item_id' => $row['service_item_id'] ?? $cartKey,
                'name' => $row['name'] ?? 'Service',
                'category' => $row['category'] ?? null,
                'variation_label' => $row['variation_label'] ?? null,
                'unit' => $row['unit'] ?? null,
                'price' => $price,
                'price_type' => $row['price_type'] ?? 'retail',
                'qty' => $qty,
                'line_total' => $lineTotal,
                'image_path' => $row['image_path'] ?? null,
                'attached_file' => $row['attached_file'] ?? null,
                'available_variations' => $service
                    ? $service->activeVariations->map(fn (ServiceVariation $variation) => [
                        'id' => $variation->id,
                        'service_item_id' => $variation->service_item_id,
                        'label' => $variation->package_type ?: $variation->service_item_id,
                        'description' => $variation->variation_label ?: 'Default variant',
                        'retail_price' => (float) $variation->retail_price,
                        'bulk_price' => (float) $variation->bulk_price,
                        'image_path' => $variation->variation_image_path ?: $service->image_path,
                    ])->values()->all()
                    : [],
            ];
        }

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Service $service)
    {
        abort_if(!$service->is_active, 404);

        $validated = $request->validate([
            'service_variation_id' => ['required', 'exists:service_variations,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:999'],
            'price_type' => ['nullable', 'in:retail,bulk'],
            'print_file' => ['nullable', 'file', 'max:51200'],
        ], [
            'print_file.file' => 'The attachment must be a valid file.',
            'print_file.max' => 'The attachment must not be larger than 50 MB.',
        ]);

        $qty = (int) ($validated['qty'] ?? 1);
        $priceType = $validated['price_type'] ?? 'retail';

        $variation = ServiceVariation::where('id', $validated['service_variation_id'])
            ->where('service_id', $service->id)
            ->where('is_active', true)
            ->firstOrFail();

        $price = $this->priceFor($variation, $priceType);
        $cart = session()->get('cart', []);
        $cartKey = $this->cartKey($service->id, $variation->id, $priceType);
        $existingAttachment = $cart[$cartKey]['attached_file'] ?? null;

        if (!$request->hasFile('print_file') && empty($existingAttachment['path'] ?? null)) {
            throw ValidationException::withMessages([
                'print_file' => 'Please attach your final print-ready file before adding this service to cart.',
            ]);
        }

        $attachedFile = $request->hasFile('print_file')
            ? $this->storePrintFile($request)
            : $existingAttachment;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
            $cart[$cartKey]['price'] = $price;
            $cart[$cartKey]['price_type'] = $priceType;
            $cart[$cartKey]['attached_file'] = $attachedFile;
        } else {
            $cart[$cartKey] = $this->cartRow($service, $variation, $qty, $priceType, $price);
            $cart[$cartKey]['attached_file'] = $attachedFile;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Added to cart.');
    }

    public function update(Request $request, string $cartKey)
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
            'price_type' => ['required', 'in:retail,bulk'],
            'service_variation_id' => ['nullable', 'exists:service_variations,id'],
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$cartKey])) {
            return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
        }

        $currentServiceId = $cart[$cartKey]['service_id'] ?? null;
        $variationId = $validated['service_variation_id'] ?? ($cart[$cartKey]['variation_id'] ?? null);
        $variation = ServiceVariation::with('service')->find($variationId);

        if (!$variation || !$variation->is_active || !$variation->service || !$variation->service->is_active) {
            return redirect()->route('cart.index')->with('error', 'Selected variation is no longer available.');
        }

        if ($currentServiceId && (int) $variation->service_id !== (int) $currentServiceId) {
            return redirect()->route('cart.index')->with('error', 'Please keep updates within the same service transaction.');
        }

        $priceType = $validated['price_type'];
        $price = $this->priceFor($variation, $priceType);
        $newCartKey = $this->cartKey($variation->service->id, $variation->id, $priceType);
        $updatedRow = $this->cartRow($variation->service, $variation, (int) $validated['qty'], $priceType, $price);
        $updatedRow['attached_file'] = $cart[$cartKey]['attached_file'] ?? null;

        unset($cart[$cartKey]);

        if (isset($cart[$newCartKey])) {
            $cart[$newCartKey]['qty'] += $updatedRow['qty'];
            $cart[$newCartKey]['price'] = $updatedRow['price'];
            $cart[$newCartKey]['price_type'] = $updatedRow['price_type'];
            $cart[$newCartKey]['attached_file'] = $updatedRow['attached_file'];
        } else {
            $cart[$newCartKey] = $updatedRow;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(Request $request, string $cartKey)
    {
        $cart = session()->get('cart', []);
        unset($cart[$cartKey]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    public function syncCart(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.line_total' => ['nullable', 'numeric', 'min:0'],
            'items.*.service_code' => ['nullable', 'string', 'max:255'],
            'items.*.price_type' => ['nullable', 'in:retail,bulk'],
            'items.*.attached_file_name' => ['nullable', 'string', 'max:255'],
            'items.*.print_file' => ['nullable', 'file', 'max:51200'],
        ], [
            'items.*.print_file.file' => 'Each service attachment must be a valid file.',
            'items.*.print_file.max' => 'Each service attachment must not be larger than 50 MB.',
        ]);

        $existingCart = session()->get('cart', []);
        $cart = [];

        foreach ($validated['items'] as $idx => $item) {
            $priceType = $item['price_type'] ?? 'retail';
            $uploadedFile = $request->file("items.$idx.print_file");
            $variation = filled($item['service_code'] ?? null)
                ? ServiceVariation::with('service')
                    ->where('service_item_id', $item['service_code'])
                    ->where('is_active', true)
                    ->first()
                : null;

            if ($variation && $variation->service) {
                $cartKey = $this->cartKey($variation->service->id, $variation->id, $priceType);
                $existingAttachment = $cart[$cartKey]['attached_file'] ?? ($existingCart[$cartKey]['attached_file'] ?? null);
                $attachedFile = $uploadedFile
                    ? $this->storeUploadedPrintFile($uploadedFile)
                    : ($existingAttachment ?: $this->placeholderPrintFile($item['attached_file_name'] ?? null));

                if (empty($attachedFile['path'] ?? null)) {
                    throw ValidationException::withMessages([
                        "items.$idx.print_file" => 'Please attach the final print-ready file before checkout.',
                    ]);
                }

                $row = $this->cartRow(
                    $variation->service,
                    $variation,
                    (int) $item['qty'],
                    $priceType,
                    $this->priceFor($variation, $priceType)
                );
                $row['attached_file'] = $attachedFile;

                if (isset($cart[$cartKey])) {
                    $cart[$cartKey]['qty'] += $row['qty'];
                    $cart[$cartKey]['price'] = $row['price'];
                    $cart[$cartKey]['price_type'] = $row['price_type'];
                    $cart[$cartKey]['attached_file'] = $attachedFile;
                } else {
                    $cart[$cartKey] = $row;
                }

                continue;
            }

            $qty = (int) $item['qty'];
            $unitPrice = array_key_exists('unit_price', $item) && $item['unit_price'] !== null
                ? (float) $item['unit_price']
                : ((float) ($item['line_total'] ?? 0) / max(1, $qty));
            $key = $item['service_code'] ?: ('LS-' . $idx . '-' . uniqid());
            $existingAttachment = $cart[$key]['attached_file'] ?? ($existingCart[$key]['attached_file'] ?? null);
            $attachedFile = $uploadedFile
                ? $this->storeUploadedPrintFile($uploadedFile)
                : ($existingAttachment ?: $this->placeholderPrintFile($item['attached_file_name'] ?? null));

            if (empty($attachedFile['path'] ?? null)) {
                throw ValidationException::withMessages([
                    "items.$idx.print_file" => 'Please attach the final print-ready file before checkout.',
                ]);
            }

            $row = [
                'service_id' => null,
                'variation_id' => null,
                'service_item_id' => $key,
                'name' => $item['name'],
                'category' => null,
                'variation_label' => $item['name'],
                'unit' => null,
                'price' => $unitPrice,
                'price_type' => $priceType,
                'qty' => $qty,
                'image_path' => null,
                'attached_file' => $attachedFile,
            ];

            if (isset($cart[$key])) {
                $cart[$key]['qty'] += $row['qty'];
                $cart[$key]['price'] = $row['price'];
                $cart[$key]['price_type'] = $row['price_type'];
                $cart[$key]['attached_file'] = $attachedFile;
            } else {
                $cart[$key] = $row;
            }
        }

        session()->put('cart', $cart);
        session()->forget('buy_now');

        return response()->json(['ok' => true]);
    }

    public function buyNow(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'price_type' => ['required', 'in:retail,bulk'],
            'service_code' => ['nullable', 'string'],
        ]);

        $variation = filled($validated['service_code'] ?? null)
            ? ServiceVariation::with('service')
                ->where('service_item_id', $validated['service_code'])
                ->where('is_active', true)
                ->first()
            : null;

        if ($variation && $variation->service) {
            $key = $this->cartKey($variation->service->id, $variation->id, $validated['price_type']);
            $row = $this->cartRow(
                $variation->service,
                $variation,
                (int) $validated['qty'],
                $validated['price_type'],
                $this->priceFor($variation, $validated['price_type'])
            );
        } else {
            $key = $validated['service_code'] ?: ('BUY-' . uniqid());
            $row = [
                'service_id' => null,
                'variation_id' => null,
                'service_item_id' => $key,
                'name' => $validated['name'],
                'category' => null,
                'variation_label' => $validated['name'],
                'unit' => null,
                'price' => (float) $validated['unit_price'],
                'price_type' => $validated['price_type'],
                'qty' => (int) $validated['qty'],
                'image_path' => null,
            ];
        }

        session()->put('buy_now', [$key => $row]);

        return response()->json(['ok' => true]);
    }

    private function cartKey(int $serviceId, int $variationId, string $priceType): string
    {
        return $serviceId . '_' . $variationId . '_' . $priceType;
    }

    private function cartRow(Service $service, ServiceVariation $variation, int $qty, string $priceType, float $price): array
    {
        return [
            'service_id' => $service->id,
            'variation_id' => $variation->id,
            'service_item_id' => $variation->service_item_id,
            'name' => $service->name,
            'category' => $service->category,
            'variation_label' => $variation->variation_label,
            'unit' => $service->unit,
            'price' => $price,
            'price_type' => $priceType,
            'qty' => $qty,
            'image_path' => $service->image_path,
        ];
    }

    private function storePrintFile(Request $request): array
    {
        return $this->storeUploadedPrintFile($request->file('print_file'));
    }

    private function storeUploadedPrintFile(UploadedFile $file): array
    {
        $path = $file->store('order-files', 'public');

        return [
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ];
    }

    private function placeholderPrintFile(?string $originalName): ?array
    {
        $originalName = trim((string) $originalName);

        if ($originalName === '') {
            return null;
        }

        $safeName = basename(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $originalName));
        $path = 'order-files/browser-confirmed-' . uniqid('', true) . '.txt';
        $content = "Customer cart listed this attached file before checkout: {$safeName}\n"
            . "If the production file is not present, contact the customer before printing.\n";

        Storage::disk('public')->put($path, $content);

        return [
            'original_name' => $safeName,
            'path' => $path,
            'mime' => 'text/plain',
            'size' => strlen($content),
        ];
    }

    private function priceFor(ServiceVariation $variation, string $priceType): float
    {
        return $priceType === 'bulk'
            ? (float) $variation->bulk_price
            : (float) $variation->retail_price;
    }
}
