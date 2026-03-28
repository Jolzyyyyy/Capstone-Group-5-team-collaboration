<!DOCTYPE html>
<html>
<head>
    <title>Create Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<h1>Create Service</h1>

{{-- Success message --}}
@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

{{-- Validation errors --}}
@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Category --}}
    <p>
        Category:<br>
        <select name="category" id="category">
            <option value="">Select category</option>
            <option value="Printing" {{ old('category') == 'Printing' ? 'selected' : '' }}>Document Printing</option>
            <option value="Photocopy" {{ old('category') == 'Photocopy' ? 'selected' : '' }}>Photocopy &amp; Scanning</option>
            <option value="ID Picture" {{ old('category') == 'ID Picture' ? 'selected' : '' }}>ID &amp; Photo Services</option>
            <option value="Laminating" {{ old('category') == 'Laminating' ? 'selected' : '' }}>Lamination &amp; Binding</option>
            <option value="Tarpaulin" {{ old('category') == 'Tarpaulin' ? 'selected' : '' }}>Large Format Printing</option>
            <option value="Custom" {{ old('category') == 'Custom' ? 'selected' : '' }}>Custom Special Printing</option>
        </select>
    </p>

    {{-- Service Name --}}
    <p>
        Service Name:<br>
        <input type="text" name="name" value="{{ old('name') }}" required>
    </p>

    {{-- Description --}}
    <p>
        Description:<br>
        <textarea name="description">{{ old('description') }}</textarea>
    </p>

    {{-- Image --}}
    <p>
        Image:<br>
        <input type="file" name="image">
    </p>

    {{-- Active --}}
    <p>
        Active:
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
    </p>

    <hr>

    <h3>Service Variations</h3>

    <div id="variations-wrapper">
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
                <input type="number" step="0.01" min="0" name="variations[0][retail_price]" value="{{ old('variations.0.retail_price') }}" required>
            </p>

            <p>
                Bulk Price:<br>
                <input type="number" step="0.01" min="0" name="variations[0][bulk_price]" value="{{ old('variations.0.bulk_price') }}" required>
            </p>

            <p>
                Active:
                <input type="checkbox" name="variations[0][is_active]" value="1" {{ old('variations.0.is_active', 1) ? 'checked' : '' }}>
            </p>
        </div>
    </div>

    <button type="button" id="add-variation-btn">+ Add Variation</button>
    <br><br>

    <button type="submit">Save Service</button>
</form>

<p>
    <a href="{{ route('services.admin.index') }}">Back to Admin Services</a>
</p>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let variationIndex = 1;

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