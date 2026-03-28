<!DOCTYPE html>
<html>
<head>
    <title>Admin Services</title>
</head>
<body>

    <h1>Services (Admin)</h1>

    <p>
        <a href="{{ route('services.create') }}">+ Add Service</a>
    </p>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if($services->isEmpty())
        <p>No services found.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Retail Price</th>
                    <th>Bulk Price</th>
                    <th>Unit</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->category ?? '-' }}</td>
                        <td>{{ $service->retail_price }}</td>
                        <td>{{ $service->bulk_price }}</td>
                        <td>{{ $service->unit ?? '-' }}</td>
                        <td>{{ $service->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            @if($service->image_path)
                                <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->name }}" width="80">
                            @else
                                No image
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('services.edit', $service) }}">Edit</a>

                            <form action="{{ route('services.toggle', $service) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit">
                                    {{ $service->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>

                            <form action="{{ route('services.destroy', $service) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this service?');">
                                @csrf
                                @method('DELETE')Category-based ID (PRN-001, LAM-001)
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p>
        <a href="{{ route('orders.index') }}">Go to Orders</a>
    </p>

</body>
</html>