<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Protect admin-only actions.
     * Customers can access: index, show
     * Admin (logged-in users) can access the rest.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,developer'])->except(['index', 'show']);
    }

    /**
     * Display a listing of services (customer-facing).
     * Optional filter: ?category=Photocopy
     */
    public function index(Request $request)
    {
        $query = Service::query()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name');

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        $services = $query->paginate(12)->withQueryString();

        return view('services.index', compact('services'));
    }

    /**
     * Display a single service (customer-facing).
     */
    public function show(Service $service)
    {
        abort_if(!$service->is_active, 404);

        return view('services.show', compact('service'));
    }

    /**
     * Show the form for creating a new service (admin).
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created service in storage (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'category'     => ['nullable', 'string', 'max:255'],
            'retail_price' => ['required', 'numeric', 'min:0'],
            'bulk_price'   => ['required', 'numeric', 'min:0'],
            'unit'         => ['nullable', 'string', 'max:50'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        Service::create([
            'name'         => $validated['name'],
            'category'     => $validated['category'] ?? null,
            'retail_price' => $validated['retail_price'],
            'bulk_price'   => $validated['bulk_price'],
            'unit'         => $validated['unit'] ?? null,
            'description'  => $validated['description'] ?? null,
            'image_path'   => $imagePath,
            'is_active'    => (bool)($validated['is_active'] ?? true),
        ]);

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Show the form for editing the specified service (admin).
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage (admin).
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'category'     => ['nullable', 'string', 'max:255'],
            'retail_price' => ['required', 'numeric', 'min:0'],
            'bulk_price'   => ['required', 'numeric', 'min:0'],
            'unit'         => ['nullable', 'string', 'max:50'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }
            $service->image_path = $request->file('image')->store('services', 'public');
        }

        $service->update([
            'name'         => $validated['name'],
            'category'     => $validated['category'] ?? null,
            'retail_price' => $validated['retail_price'],
            'bulk_price'   => $validated['bulk_price'],
            'unit'         => $validated['unit'] ?? null,
            'description'  => $validated['description'] ?? null,
            'is_active'    => (bool)($validated['is_active'] ?? $service->is_active),
        ]);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage (admin).
     */
    public function destroy(Service $service)
    {
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Quick enable/disable (admin).
     */
    public function toggleActive(Service $service)
    {
        $service->is_active = !$service->is_active;
        $service->save();

        return back()->with('success', 'Service status updated.');
    }
}
