<!DOCTYPE html>
<html>
<head>
    <title>Edit Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<h1>Edit Service</h1>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('services.update', $service) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <p>
        Category:<br>
        <select name="category" id="category">
            <option value="">Select category</option>
            <option value="Printing" {{ old('category', $service->category) == 'Printing' ? 'selected' : '' }}>Document Printing</option>
            <option value="Photocopy" {{ old('category', $service->category) == 'Photocopy' ? 'selected' : '' }}>Photocopy &amp; Scanning</option>
            <option value="ID Picture" {{ old('category', $service->category) == 'ID Picture' ? 'selected' : '' }}>ID &amp; Photo Services</option>
            <option value="Laminating" {{ old('category', $service->category) == 'Laminating' ? 'selected' : '' }}>Lamination &amp; Binding</option>
            <option value="Tarpaulin" {{ old('category', $service->category) == 'Tarpaulin' ? 'selected' : '' }}>Large Format Printing</option>
            <option value="Custom" {{ old('category', $service->category) == 'Custom' ? 'selected' : '' }}>Custom Special Printing</option>
        </select>
    </p>

    <p>
        Service Name:<br>
        <input type="text" name="name" value="{{ old('name', $service->name) }}" required>
    </p>

    <p>
        Description:<br>
        <textarea name="description">{{ old('description', $service->description) }}</textarea>
    </p>

    <p>
        Current Image:<br>
        @if($service->image_path)
            <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->name }}" width="120"><br>
        @else
            No image
        @endif
    </p>

    <p>
        Change Image:<br>
        <input type="file" name="image">
    </p>

    <p>
        Active:
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
    </p>

    <hr>

    <h3>Service Variations</h3>

    <div id="variations-wrapper">
        @php
            $oldVariations = old('variations');
            $variations = is_array($oldVariations) ? $oldVariations : $service->variations->map(function ($variation) {
                return [
                    'printing_category' => $variation->printing_category,
                    'color_mode' => $variation->color_mode,
                    'product_size' => $variation->product_size,
                    'finish_type' => $variation->finish_type,
                    'package_type' => $variation->package_type,
                    'retail_price' => $variation->retail_price,
                    'bulk_price' => $variation->bulk_price,
                    'is_active' => $variation->is_active,
                ];
            })->toArray();
        @endphp

        @forelse($variations as $i => $variation)
            <div class="variation-item" style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">
                <p>
                    Printing Category:<br>
                    <select name="variations[{{ $i }}][printing_category]">
                        <option value="">Select</option>
                        <option value="Text Only" {{ ($variation['printing_category'] ?? '') === 'Text Only' ? 'selected' : '' }}>Text Only</option>
                        <option value="Text with Image" {{ ($variation['printing_category'] ?? '') === 'Text with Image' ? 'selected' : '' }}>Text with Image</option>
                        <option value="Image Only" {{ ($variation['printing_category'] ?? '') === 'Image Only' ? 'selected' : '' }}>Image Only</option>
                        <option value="Photo Services" {{ ($variation['printing_category'] ?? '') === 'Photo Services' ? 'selected' : '' }}>Photo Services</option>
                        <option value="Sintra Board Printing" {{ ($variation['printing_category'] ?? '') === 'Sintra Board Printing' ? 'selected' : '' }}>Sintra Board Printing</option>
                    </select>
                </p>

                <p>
                    Color Mode / Variation:<br>
                    <select name="variations[{{ $i }}][color_mode]">
                        <option value="">Select</option>
                        <option value="B&W" {{ ($variation['color_mode'] ?? '') === 'B&W' ? 'selected' : '' }}>B&amp;W</option>
                        <option value="Partial Color" {{ ($variation['color_mode'] ?? '') === 'Partial Color' ? 'selected' : '' }}>Partial Color</option>
                        <option value="Full Color" {{ ($variation['color_mode'] ?? '') === 'Full Color' ? 'selected' : '' }}>Full Color</option>
                    </select>
                </p>

                <p>
                    Paper Size / Product Size:<br>
                    <select name="variations[{{ $i }}][product_size]">
                        <option value="">Select</option>
                        <option value="Short (8.5 x 11)" {{ ($variation['product_size'] ?? '') === 'Short (8.5 x 11)' ? 'selected' : '' }}>Short (8.5 x 11)</option>
                        <option value="A4 (8.27 x 11.69)" {{ ($variation['product_size'] ?? '') === 'A4 (8.27 x 11.69)' ? 'selected' : '' }}>A4 (8.27 x 11.69)</option>
                        <option value="Legal (8.5 x 14)" {{ ($variation['product_size'] ?? '') === 'Legal (8.5 x 14)' ? 'selected' : '' }}>Legal (8.5 x 14)</option>
                        <option value="A2 (22.86 x 29.7)" {{ ($variation['product_size'] ?? '') === 'A2 (22.86 x 29.7)' ? 'selected' : '' }}>A2 (22.86 x 29.7)</option>
                        <option value="A3 (11.69 x 16.54)" {{ ($variation['product_size'] ?? '') === 'A3 (11.69 x 16.54)' ? 'selected' : '' }}>A3 (11.69 x 16.54)</option>
                        <option value="A5 (10.16 x 14.87)" {{ ($variation['product_size'] ?? '') === 'A5 (10.16 x 14.87)' ? 'selected' : '' }}>A5 (10.16 x 14.87)</option>
                    </select>
                </p>

                <p>
                    Finish Type:<br>
                    <select name="variations[{{ $i }}][finish_type]">
                        <option value="">Select</option>
                        <option value="Finish: Glossy" {{ ($variation['finish_type'] ?? '') === 'Finish: Glossy' ? 'selected' : '' }}>Finish: Glossy</option>
                        <option value="Finish: Matte" {{ ($variation['finish_type'] ?? '') === 'Finish: Matte' ? 'selected' : '' }}>Finish: Matte</option>
                        <option value="Finish: Leather" {{ ($variation['finish_type'] ?? '') === 'Finish: Leather' ? 'selected' : '' }}>Finish: Leather</option>
                        <option value="Finish: Canvas Matte" {{ ($variation['finish_type'] ?? '') === 'Finish: Canvas Matte' ? 'selected' : '' }}>Finish: Canvas Matte</option>
                        <option value="Finish: Glittered" {{ ($variation['finish_type'] ?? '') === 'Finish: Glittered' ? 'selected' : '' }}>Finish: Glittered</option>
                        <option value="Finish: 3D" {{ ($variation['finish_type'] ?? '') === 'Finish: 3D' ? 'selected' : '' }}>Finish: 3D</option>
                        <option value="Finish: Rainbow" {{ ($variation['finish_type'] ?? '') === 'Finish: Rainbow' ? 'selected' : '' }}>Finish: Rainbow</option>
                        <option value="Finish: Broken Glass" {{ ($variation['finish_type'] ?? '') === 'Finish: Broken Glass' ? 'selected' : '' }}>Finish: Broken Glass</option>
                    </select>
                </p>

                <p>
                    Package Type:<br>
                    <select name="variations[{{ $i }}][package_type]">
                        <option value="">Select</option>
                        <option value="Package A" {{ ($variation['package_type'] ?? '') === 'Package A' ? 'selected' : '' }}>Package A</option>
                        <option value="Package B" {{ ($variation['package_type'] ?? '') === 'Package B' ? 'selected' : '' }}>Package B</option>
                        <option value="Package C" {{ ($variation['package_type'] ?? '') === 'Package C' ? 'selected' : '' }}>Package C</option>
                        <option value="Package D" {{ ($variation['package_type'] ?? '') === 'Package D' ? 'selected' : '' }}>Package D</option>
                        <option value="Package E" {{ ($variation['package_type'] ?? '') === 'Package E' ? 'selected' : '' }}>Package E</option>
                        <option value="Package F" {{ ($variation['package_type'] ?? '') === 'Package F' ? 'selected' : '' }}>Package F</option>
                    </select>
                </p>

                <p>
                    Retail Price:<br>
                    <input type="number" step="0.01" min="0" name="variations[{{ $i }}][retail_price]" value="{{ $variation['retail_price'] ?? '' }}" required>
                </p>

                <p>
                    Bulk Price:<br>
                    <input type="number" step="0.01" min="0" name="variations[{{ $i }}][bulk_price]" value="{{ $variation['bulk_price'] ?? '' }}" required>
                </p>

                <p>
                    Active:
                    <input type="checkbox" name="variations[{{ $i }}][is_active]" value="1" {{ !empty($variation['is_active']) ? 'checked' : '' }}>
                </p>

                <p>
                    <button type="button" class="remove-variation-btn">Remove Variation</button>
                </p>
            </div>
        @empty
            <div class="variation-item" style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">
                <p>
                    Printing Category:<br>
                    <select name="variations[0][printing_category]">
                        <option value="">Select</option>
                        <option value="Text Only">Text Only</option>
                        <option value="Text with Image">Text with Image</option>
                        <option value="Image Only">Image Only</option>
                        <option value="Photo Services">Photo Services</option>
                        <option value="Sintra Board Printing">Sintra Board Printing</option>
                    </select>
                </p>

                <p>
                    Color Mode / Variation:<br>
                    <select name="variations[0][color_mode]">
                        <option value="">Select</option>
                        <option value="B&W">B&amp;W</option>
                        <option value="Partial Color">Partial Color</option>
                        <option value="Full Color">Full Color</option>
                    </select>
                </p>

                <p>
                    Paper Size / Product Size:<br>
                    <select name="variations[0][product_size]">
                        <option value="">Select</option>
                        <option value="Short (8.5 x 11)">Short (8.5 x 11)</option>
                        <option value="A4 (8.27 x 11.69)">A4 (8.27 x 11.69)</option>
                        <option value="Legal (8.5 x 14)">Legal (8.5 x 14)</option>
                        <option value="A2 (22.86 x 29.7)">A2 (22.86 x 29.7)</option>
                        <option value="A3 (11.69 x 16.54)">A3 (11.69 x 16.54)</option>
                        <option value="A5 (10.16 x 14.87)">A5 (10.16 x 14.87)</option>
                    </select>
                </p>

                <p>
                    Finish Type:<br>
                    <select name="variations[0][finish_type]">
                        <option value="">Select</option>
                        <option value="Finish: Glossy">Finish: Glossy</option>
                        <option value="Finish: Matte">Finish: Matte</option>
                        <option value="Finish: Leather">Finish: Leather</option>
                        <option value="Finish: Canvas Matte">Finish: Canvas Matte</option>
                        <option value="Finish: Glittered">Finish: Glittered</option>
                        <option value="Finish: 3D">Finish: 3D</option>
                        <option value="Finish: Rainbow">Finish: Rainbow</option>
                        <option value="Finish: Broken Glass">Finish: Broken Glass</option>
                    </select>
                </p>

                <p>
                    Package Type:<br>
                    <select name="variations[0][package_type]">
                        <option value="">Select</option>
                        <option value="Package A">Package A</option>
                        <option value="Package B">Package B</option>
                        <option value="Package C">Package C</option>
                        <option value="Package D">Package D</option>
                        <option value="Package E">Package E</option>
                        <option value="Package F">Package F</option>
                    </select>
                </p>

                <p>
                    Retail Price:<br>
                    <input type="number" step="0.01" min="0" name="variations[0][retail_price]" required>
                </p>

                <p>
                    Bulk Price:<br>
                    <input type="number" step="0.01" min="0" name="variations[0][bulk_price]" required>
                </p>

                <p>
                    Active:
                    <input type="checkbox" name="variations[0][is_active]" value="1" checked>
                </p>
            </div>
        @endforelse
    </div>

    <button type="button" id="add-variation-btn">+ Add Variation</button>
    <br><br>

    <button type="submit">Update Service</button>
</form>

<p>
    <a href="{{ route('services.admin.index') }}">Back to Admin Services</a>
</p>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let variationIndex = {{ count($variations) }};

    document.getElementById('add-variation-btn').addEventListener('click', function () {
        const wrapper = document.getElementById('variations-wrapper');

        const html = `
            <div class="variation-item" style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">
                <p>
                    Printing Category:<br>
                    <select name="variations[\${variationIndex}][printing_category]">
                        <option value="">Select</option>
                        <option value="Text Only">Text Only</option>
                        <option value="Text with Image">Text with Image</option>
                        <option value="Image Only">Image Only</option>
                        <option value="Photo Services">Photo Services</option>
                        <option value="Sintra Board Printing">Sintra Board Printing</option>
                    </select>
                </p>

                <p>
                    Color Mode / Variation:<br>
                    <select name="variations[\${variationIndex}][color_mode]">
                        <option value="">Select</option>
                        <option value="B&W">B&W</option>
                        <option value="Partial Color">Partial Color</option>
                        <option value="Full Color">Full Color</option>
                    </select>
                </p>

                <p>
                    Paper Size / Product Size:<br>
                    <select name="variations[\${variationIndex}][product_size]">
                        <option value="">Select</option>
                        <option value="Short (8.5 x 11)">Short (8.5 x 11)</option>
                        <option value="A4 (8.27 x 11.69)">A4 (8.27 x 11.69)</option>
                        <option value="Legal (8.5 x 14)">Legal (8.5 x 14)</option>
                        <option value="A2 (22.86 x 29.7)">A2 (22.86 x 29.7)</option>
                        <option value="A3 (11.69 x 16.54)">A3 (11.69 x 16.54)</option>
                        <option value="A5 (10.16 x 14.87)">A5 (10.16 x 14.87)</option>
                    </select>
                </p>

                <p>
                    Finish Type:<br>
                    <select name="variations[\${variationIndex}][finish_type]">
                        <option value="">Select</option>
                        <option value="Finish: Glossy">Finish: Glossy</option>
                        <option value="Finish: Matte">Finish: Matte</option>
                        <option value="Finish: Leather">Finish: Leather</option>
                        <option value="Finish: Canvas Matte">Finish: Canvas Matte</option>
                        <option value="Finish: Glittered">Finish: Glittered</option>
                        <option value="Finish: 3D">Finish: 3D</option>
                        <option value="Finish: Rainbow">Finish: Rainbow</option>
                        <option value="Finish: Broken Glass">Finish: Broken Glass</option>
                    </select>
                </p>

                <p>
                    Package Type:<br>
                    <select name="variations[\${variationIndex}][package_type]">
                        <option value="">Select</option>
                        <option value="Package A">Package A</option>
                        <option value="Package B">Package B</option>
                        <option value="Package C">Package C</option>
                        <option value="Package D">Package D</option>
                        <option value="Package E">Package E</option>
                        <option value="Package F">Package F</option>
                    </select>
                </p>

                <p>
                    Retail Price:<br>
                    <input type="number" step="0.01" min="0" name="variations[\${variationIndex}][retail_price]" required>
                </p>

                <p>
                    Bulk Price:<br>
                    <input type="number" step="0.01" min="0" name="variations[\${variationIndex}][bulk_price]" required>
                </p>

                <p>
                    Active:
                    <input type="checkbox" name="variations[\${variationIndex}][is_active]" value="1" checked>
                </p>

                <p>
                    <button type="button" class="remove-variation-btn">Remove Variation</button>
                </p>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        variationIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-variation-btn')) {
            e.target.closest('.variation-item').remove();
        }
    });
});
</script>

</body>
</html>