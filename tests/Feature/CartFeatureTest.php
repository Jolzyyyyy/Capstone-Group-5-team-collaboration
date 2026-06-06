<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\ServiceVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CartFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_variation_can_be_added_updated_and_removed_from_cart(): void
    {
        Storage::fake('public');
        [$service, $variation] = $this->createServiceWithVariation();

        $this
            ->post(route('cart.add', $service, false), [
                'service_variation_id' => $variation->id,
                'qty' => 2,
                'price_type' => 'retail',
                'print_file' => UploadedFile::fake()->create('artwork.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect(route('cart.index', absolute: false));

        $retailCartKey = "{$service->id}_{$variation->id}_retail";
        $cart = session('cart');

        $this->assertArrayHasKey($retailCartKey, $cart);
        $this->assertSame(2, $cart[$retailCartKey]['qty']);
        $this->assertSame('retail', $cart[$retailCartKey]['price_type']);
        $this->assertSame(25.0, $cart[$retailCartKey]['price']);
        $this->assertSame('artwork.pdf', $cart[$retailCartKey]['attached_file']['original_name']);
        Storage::disk('public')->assertExists($cart[$retailCartKey]['attached_file']['path']);

        $this
            ->post(route('cart.update', $retailCartKey, false), [
                'qty' => 3,
                'price_type' => 'bulk',
            ])
            ->assertRedirect(route('cart.index', absolute: false));

        $bulkCartKey = "{$service->id}_{$variation->id}_bulk";
        $cart = session('cart');

        $this->assertArrayNotHasKey($retailCartKey, $cart);
        $this->assertArrayHasKey($bulkCartKey, $cart);
        $this->assertSame(3, $cart[$bulkCartKey]['qty']);
        $this->assertSame('bulk', $cart[$bulkCartKey]['price_type']);
        $this->assertSame(20.0, $cart[$bulkCartKey]['price']);
        $this->assertSame('artwork.pdf', $cart[$bulkCartKey]['attached_file']['original_name']);

        $this
            ->post(route('cart.remove', $bulkCartKey, false))
            ->assertRedirect(route('cart.index', absolute: false));

        $this->assertSame([], session('cart'));
    }

    public function test_service_requires_print_file_before_add_to_cart(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();

        $this
            ->from(route('services.show', $service, false))
            ->post(route('cart.add', $service, false), [
                'service_variation_id' => $variation->id,
                'qty' => 1,
                'price_type' => 'retail',
            ])
            ->assertRedirect(route('services.show', $service, false))
            ->assertSessionHasErrors('print_file');

        $this->assertNull(session('cart'));
    }

    public function test_existing_saved_cart_attachment_is_reused_when_adding_same_service_again(): void
    {
        Storage::fake('public');
        [$service, $variation] = $this->createServiceWithVariation();

        $this
            ->post(route('cart.add', $service, false), [
                'service_variation_id' => $variation->id,
                'qty' => 1,
                'price_type' => 'retail',
                'print_file' => UploadedFile::fake()->create('final-artwork.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect(route('cart.index', absolute: false));

        $cartKey = "{$service->id}_{$variation->id}_retail";
        $firstAttachment = session("cart.$cartKey.attached_file");

        $this
            ->post(route('cart.add', $service, false), [
                'service_variation_id' => $variation->id,
                'qty' => 2,
                'price_type' => 'retail',
            ])
            ->assertRedirect(route('cart.index', absolute: false));

        $cart = session('cart');

        $this->assertSame(3, $cart[$cartKey]['qty']);
        $this->assertSame($firstAttachment['path'], $cart[$cartKey]['attached_file']['path']);
        $this->assertSame('final-artwork.pdf', $cart[$cartKey]['attached_file']['original_name']);
    }

    public function test_homepage_cart_sync_maps_service_code_to_database_variation_and_merges_duplicates(): void
    {
        Storage::fake('public');
        [$service, $variation] = $this->createServiceWithVariation([
            'name' => 'Document Printing',
        ], [
            'service_item_id' => 'DOC-A4-BW',
        ]);

        $this
            ->post(route('cart.sync', absolute: false), [
                'items' => [
                    [
                        'name' => 'Document Printing',
                        'qty' => 1,
                        'line_total' => 25,
                        'service_code' => 'DOC-A4-BW',
                        'price_type' => 'retail',
                        'print_file' => UploadedFile::fake()->create('document-one.pdf', 80, 'application/pdf'),
                    ],
                    [
                        'name' => 'Document Printing',
                        'qty' => 2,
                        'line_total' => 50,
                        'service_code' => 'DOC-A4-BW',
                        'price_type' => 'retail',
                        'print_file' => UploadedFile::fake()->create('document-two.pdf', 90, 'application/pdf'),
                    ],
                ],
            ])
            ->assertOk()
            ->assertJson(['ok' => true]);

        $cartKey = "{$service->id}_{$variation->id}_retail";
        $cart = session('cart');

        $this->assertCount(1, $cart);
        $this->assertSame($service->id, $cart[$cartKey]['service_id']);
        $this->assertSame($variation->id, $cart[$cartKey]['variation_id']);
        $this->assertSame('DOC-A4-BW', $cart[$cartKey]['service_item_id']);
        $this->assertSame(3, $cart[$cartKey]['qty']);
        $this->assertSame(25.0, $cart[$cartKey]['price']);
        $this->assertSame('document-two.pdf', $cart[$cartKey]['attached_file']['original_name']);
        Storage::disk('public')->assertExists($cart[$cartKey]['attached_file']['path']);
    }

    public function test_homepage_cart_sync_accepts_remembered_attached_filename(): void
    {
        Storage::fake('public');
        [$service, $variation] = $this->createServiceWithVariation([
            'name' => 'Scanning',
        ], [
            'service_item_id' => 'PCS-SCAN-IM-A4-FC-STD',
        ]);

        $this
            ->post(route('cart.sync', absolute: false), [
                'items' => [
                    [
                        'name' => 'Scanning',
                        'qty' => 4,
                        'line_total' => 160,
                        'service_code' => 'PCS-SCAN-IM-A4-FC-STD',
                        'price_type' => 'retail',
                        'attached_file_name' => 'customer-scan.jpg',
                    ],
                ],
            ])
            ->assertOk()
            ->assertJson(['ok' => true]);

        $cartKey = "{$service->id}_{$variation->id}_retail";
        $cart = session('cart');

        $this->assertSame('customer-scan.jpg', $cart[$cartKey]['attached_file']['original_name']);
        Storage::disk('public')->assertExists($cart[$cartKey]['attached_file']['path']);
    }

    public function test_checkout_accepts_reviewed_homepage_items_that_are_not_database_variations(): void
    {
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);

        config(['services.paymongo.secret_key' => 'sk_test_123']);

        Http::fake([
            'https://api.paymongo.com/v1/checkout_sessions' => Http::response([
                'data' => [
                    'id' => 'cs_homepage_reviewed_123',
                    'attributes' => [
                        'checkout_url' => 'https://checkout.paymongo.test/homepage-reviewed',
                    ],
                ],
            ]),
        ]);

        $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
                'cart' => [
                    'LEGACY-ITEM' => [
                        'service_id' => null,
                        'variation_id' => null,
                        'service_item_id' => 'LEGACY-ITEM',
                        'name' => 'Legacy Browser Item',
                        'price' => 75,
                        'price_type' => 'retail',
                        'qty' => 1,
                        'attached_file' => $this->attachedFileMetadata('legacy-reviewed.pdf'),
                    ],
                ],
            ])
            ->post(route('checkout.place', absolute: false), [
                'customer_name' => 'Test Customer',
                'customer_email' => 'customer@example.com',
                'customer_phone' => '+63 900 000 0000',
                'fulfillment_method' => 'pickup',
                'payment_method' => 'gcash',
                'print_file_confirmed' => '1',
            ])
            ->assertRedirect('https://checkout.paymongo.test/homepage-reviewed');

        $order = Order::query()->firstOrFail();

        $this->assertDatabaseHas('services', [
            'name' => 'Reviewed Checkout Item',
            'category' => 'Manual Checkout',
            'is_active' => false,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'service_name' => 'Legacy Browser Item',
            'service_item_id' => 'LEGACY-ITEM',
            'variation_label' => null,
            'quantity' => 1,
            'subtotal' => 75,
        ]);
        $this->assertDatabaseHas('order_files', [
            'order_id' => $order->id,
            'original_name' => 'legacy-reviewed.pdf',
        ]);
    }

    public function test_checkout_page_uses_service_page_attachment_and_does_not_offer_checkout_upload(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);

        $cartKey = "{$service->id}_{$variation->id}_retail";

        $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
                'cart' => [
                    $cartKey => [
                        'service_id' => $service->id,
                        'variation_id' => $variation->id,
                        'service_item_id' => $variation->service_item_id,
                        'name' => $service->name,
                        'category' => $service->category,
                        'variation_label' => $variation->variation_label,
                        'unit' => $service->unit,
                        'price' => 25,
                        'price_type' => 'retail',
                        'qty' => 1,
                        'attached_file' => $this->attachedFileMetadata('checkout-artwork.pdf'),
                    ],
                ],
            ])
            ->get(route('checkout.index', absolute: false))
            ->assertOk()
            ->assertSee('Review your order')
            ->assertSee('PayMongo secure checkout')
            ->assertSee('Place Order')
            ->assertSee('Release Schedule')
            ->assertSee('PayMongo')
            ->assertSee('GCash')
            ->assertSee('Maya')
            ->assertSee('I confirm the attached file is final and print-ready')
            ->assertSee('Attached file: checkout-artwork.pdf')
            ->assertDontSee('Upload Print Files')
            ->assertDontSee('File Upload')
            ->assertDontSee('print_zip')
            ->assertDontSee('multipart/form-data');
    }

    public function test_checkout_blocks_cart_items_without_service_page_attachment(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);

        $cartKey = "{$service->id}_{$variation->id}_retail";
        $cart = [
            $cartKey => [
                'service_id' => $service->id,
                'variation_id' => $variation->id,
                'service_item_id' => $variation->service_item_id,
                'name' => $service->name,
                'category' => $service->category,
                'variation_label' => $variation->variation_label,
                'unit' => $service->unit,
                'price' => 25,
                'price_type' => 'retail',
                'qty' => 1,
            ],
        ];

        $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
                'cart' => $cart,
            ])
            ->get(route('checkout.index', absolute: false))
            ->assertRedirect(route('cart.index', absolute: false))
            ->assertSessionHas('error', 'Please attach a print-ready file to every service before checkout.');

        $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
                'cart' => $cart,
            ])
            ->post(route('checkout.place', absolute: false), [
                'customer_name' => 'Missing File Customer',
                'customer_email' => 'missing-file@example.com',
            ])
            ->assertRedirect(route('cart.index', absolute: false))
            ->assertSessionHas('error', 'Please attach a print-ready file to every service before checkout.');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_cart_page_edits_package_with_service_transaction_controls(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $secondVariation = ServiceVariation::create([
            'service_id' => $service->id,
            'service_item_id' => 'TEST-A3-COLOR',
            'printing_category' => 'Document',
            'color_mode' => 'Color',
            'product_size' => 'A3',
            'package_type' => 'A3 Package',
            'variation_label' => 'A3 color',
            'retail_price' => 40,
            'bulk_price' => 32,
            'is_active' => true,
        ]);
        $cartKey = "{$service->id}_{$variation->id}_retail";

        $this
            ->withSession([
                'cart' => [
                    $cartKey => [
                        'service_id' => $service->id,
                        'variation_id' => $variation->id,
                        'service_item_id' => $variation->service_item_id,
                        'name' => $service->name,
                        'category' => $service->category,
                        'variation_label' => $variation->variation_label,
                        'unit' => $service->unit,
                        'price' => 25,
                        'price_type' => 'retail',
                        'qty' => 1,
                        'attached_file' => $this->attachedFileMetadata(),
                    ],
                ],
            ])
            ->get(route('cart.index', absolute: false))
            ->assertOk()
            ->assertSee('Review service selections')
            ->assertSee('Package / Variant')
            ->assertSee($secondVariation->service_item_id)
            ->assertSee('PayMongo hosted checkout')
            ->assertSee('Attached file: artwork.pdf')
            ->assertSee('Edit Like Add to Cart');

        $this
            ->withSession([
                'cart' => [
                    $cartKey => [
                        'service_id' => $service->id,
                        'variation_id' => $variation->id,
                        'service_item_id' => $variation->service_item_id,
                        'name' => $service->name,
                        'category' => $service->category,
                        'variation_label' => $variation->variation_label,
                        'unit' => $service->unit,
                        'price' => 25,
                        'price_type' => 'retail',
                        'qty' => 1,
                        'attached_file' => $this->attachedFileMetadata(),
                    ],
                ],
            ])
            ->post(route('cart.update', $cartKey, false), [
                'service_variation_id' => $secondVariation->id,
                'qty' => 3,
                'price_type' => 'bulk',
            ])
            ->assertRedirect(route('cart.index', absolute: false))
            ->assertSessionHas('success', 'Cart updated.');

        $updatedKey = "{$service->id}_{$secondVariation->id}_bulk";
        $cart = session('cart');

        $this->assertArrayHasKey($updatedKey, $cart);
        $this->assertArrayNotHasKey($cartKey, $cart);
        $this->assertSame($secondVariation->id, $cart[$updatedKey]['variation_id']);
        $this->assertSame('TEST-A3-COLOR', $cart[$updatedKey]['service_item_id']);
        $this->assertSame(3, $cart[$updatedKey]['qty']);
        $this->assertSame('bulk', $cart[$updatedKey]['price_type']);
        $this->assertSame(32.0, $cart[$updatedKey]['price']);
        $this->assertSame('artwork.pdf', $cart[$updatedKey]['attached_file']['original_name']);
    }

    public function test_checkout_does_not_save_order_when_paymongo_secret_is_missing(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);
        $cartKey = "{$service->id}_{$variation->id}_retail";

        config(['services.paymongo.secret_key' => null]);
        Http::fake();

        $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
                'cart' => [
                    $cartKey => [
                        'service_id' => $service->id,
                        'variation_id' => $variation->id,
                        'service_item_id' => $variation->service_item_id,
                        'name' => $service->name,
                        'category' => $service->category,
                        'variation_label' => $variation->variation_label,
                        'unit' => $service->unit,
                        'price' => 25,
                        'price_type' => 'retail',
                        'qty' => 2,
                        'attached_file' => $this->attachedFileMetadata('order-artwork.pdf'),
                    ],
                ],
            ])
            ->from(route('checkout.index', absolute: false))
            ->post(route('checkout.place', absolute: false), [
                'customer_name' => 'PayMongo Missing',
                'customer_email' => 'paymongo-missing@example.com',
                'customer_phone' => '+63 912 345 6789',
                'fulfillment_method' => 'pickup',
                'customer_note' => 'Hold until payment is available.',
                'payment_method' => 'gcash',
                'print_file_confirmed' => '1',
            ])
            ->assertRedirect(route('checkout.index', absolute: false))
            ->assertSessionHas('error', 'Online payment is not configured yet. Add PAYMONGO_SECRET_KEY in .env before placing PayMongo orders.');

        $this->assertDatabaseCount('orders', 0);
        $this->assertArrayHasKey($cartKey, session('cart', []));
        Http::assertNothingSent();
    }

    public function test_checkout_places_order_with_service_page_attached_file(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);

        $cartKey = "{$service->id}_{$variation->id}_retail";

        config(['services.paymongo.secret_key' => 'sk_test_123']);

        Http::fake([
            'https://api.paymongo.com/v1/checkout_sessions' => Http::response([
                'data' => [
                    'id' => 'cs_checkout_place_123',
                    'attributes' => [
                        'checkout_url' => 'https://checkout.paymongo.test/from-checkout',
                    ],
                ],
            ]),
        ]);

        $response = $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
                'cart' => [
                    $cartKey => [
                        'service_id' => $service->id,
                        'variation_id' => $variation->id,
                        'service_item_id' => $variation->service_item_id,
                        'name' => $service->name,
                        'category' => $service->category,
                        'variation_label' => $variation->variation_label,
                        'unit' => $service->unit,
                        'price' => 25,
                        'price_type' => 'retail',
                        'qty' => 2,
                        'attached_file' => $this->attachedFileMetadata('order-artwork.pdf'),
                    ],
                ],
            ])
            ->post(route('checkout.place', absolute: false), [
                'customer_name' => 'No File Customer',
                'customer_email' => 'nofile@example.com',
                'customer_phone' => '+63 912 345 6789',
                'fulfillment_method' => 'delivery',
                'delivery_address' => '414 Building, U.P. Sikatuna Bliss 2, Quezon City',
                'customer_note' => 'Please release after payment confirmation.',
                'payment_method' => 'gcash',
                'print_file_confirmed' => '1',
            ]);

        $orderId = (int) Order::query()->value('id');

        $response
            ->assertRedirect('https://checkout.paymongo.test/from-checkout');

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'user_id' => $customer->id,
            'customer_name' => 'No File Customer',
            'customer_email' => 'nofile@example.com',
            'customer_phone' => '+63 912 345 6789',
            'fulfillment_method' => 'delivery',
            'delivery_address' => '414 Building, U.P. Sikatuna Bliss 2, Quezon City',
            'customer_note' => 'Please release after payment confirmation.',
            'status' => 'Pending',
            'payment_status' => Order::PAYMENT_PENDING,
            'payment_method' => 'gcash',
            'paymongo_checkout_session_id' => 'cs_checkout_place_123',
            'total_price' => 50,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId,
            'service_id' => $service->id,
            'service_variation_id' => $variation->id,
            'quantity' => 2,
            'subtotal' => 50,
        ]);
        $this->assertDatabaseHas('order_files', [
            'order_id' => $orderId,
            'original_name' => 'order-artwork.pdf',
            'path' => 'order-files/order-artwork.pdf',
        ]);
        $this->assertNull(session('cart'));
    }

    public function test_payment_checkout_uses_saved_order_instead_of_cart_session(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);
        $order = $this->createOrderForCustomer($customer, $service, $variation, 2);

        $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
            ])
            ->get(route('payment.checkout', $order, false))
            ->assertOk()
            ->assertSee('Payment for Order #'.$order->id)
            ->assertSee('ORDER-'.$order->id)
            ->assertSee($service->name)
            ->assertSee('Pay Now');
    }

    public function test_paymongo_payment_request_is_created_from_order_id(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);
        $order = $this->createOrderForCustomer($customer, $service, $variation, 2);

        config(['services.paymongo.secret_key' => 'sk_test_123']);

        Http::fake([
            'https://api.paymongo.com/v1/checkout_sessions' => Http::response([
                'data' => [
                    'id' => 'cs_test_123',
                    'attributes' => [
                        'checkout_url' => 'https://checkout.paymongo.test/session',
                    ],
                ],
            ]),
        ]);

        $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
            ])
            ->post(route('payment.pay', $order, false), [
                'payment_method' => 'gcash',
            ])
            ->assertRedirect('https://checkout.paymongo.test/session');

        Http::assertSent(function ($request) use ($order) {
            $payload = $request->data();

            return $request->url() === 'https://api.paymongo.com/v1/checkout_sessions'
                && data_get($payload, 'data.attributes.description') === 'Order #'.$order->id.' payment'
                && data_get($payload, 'data.attributes.reference_number') === 'ORDER-'.$order->id
                && data_get($payload, 'data.attributes.metadata.order_id') === (string) $order->id
                && data_get($payload, 'data.attributes.line_items.0.amount') === 2500
                && data_get($payload, 'data.attributes.line_items.0.quantity') === 2
                && data_get($payload, 'data.attributes.payment_method_types.0') === 'gcash'
                && data_get($payload, 'data.attributes.success_url') === route('payment.success', $order);
        });

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => Order::PAYMENT_PENDING,
            'payment_method' => 'gcash',
            'paymongo_checkout_session_id' => 'cs_test_123',
            'payment_reference' => 'ORDER-'.$order->id,
        ]);
    }

    public function test_payment_success_keeps_order_pending_until_paymongo_webhook_confirms(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);
        $order = $this->createOrderForCustomer($customer, $service, $variation, 1);
        $order->forceFill([
            'payment_status' => Order::PAYMENT_PENDING,
            'paymongo_checkout_session_id' => 'cs_test_123',
        ])->save();

        $this
            ->actingAs($customer)
            ->withSession([
                'customer_otp_passed' => true,
                'cart' => ['stale' => ['name' => 'Stale cart row']],
                'payment_order_id' => $order->id,
            ])
            ->get(route('payment.success', $order, false))
            ->assertOk()
            ->assertSee('Payment Submitted')
            ->assertSee('waiting for PayMongo confirmation');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'For Verification',
            'payment_status' => Order::PAYMENT_PENDING,
        ]);
        $this->assertNull(session('cart'));
        $this->assertNull(session('payment_order_id'));
    }

    public function test_paymongo_webhook_marks_matching_order_as_paid(): void
    {
        [$service, $variation] = $this->createServiceWithVariation();
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);
        $order = $this->createOrderForCustomer($customer, $service, $variation, 1);
        $order->forceFill([
            'payment_status' => Order::PAYMENT_PENDING,
            'paymongo_checkout_session_id' => 'cs_test_123',
            'payment_reference' => 'ORDER-'.$order->id,
        ])->save();

        $this
            ->postJson(route('payment.webhook', absolute: false), [
                'data' => [
                    'type' => 'event',
                    'attributes' => [
                        'type' => 'checkout_session.payment.paid',
                        'data' => [
                            'id' => 'cs_test_123',
                            'type' => 'checkout_session',
                            'attributes' => [
                                'paid_at' => 1778942400,
                                'metadata' => [
                                    'order_id' => (string) $order->id,
                                ],
                                'payments' => [
                                    [
                                        'id' => 'pay_test_123',
                                        'attributes' => [
                                            'status' => 'paid',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ])
            ->assertOk()
            ->assertJson(['message' => 'received']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'For Verification',
            'payment_status' => Order::PAYMENT_PAID,
            'payment_reference' => 'pay_test_123',
        ]);
        $this->assertNotNull($order->fresh()->paid_at);
    }

    /**
     * @return array{0: \App\Models\Service, 1: \App\Models\ServiceVariation}
     */
    private function createServiceWithVariation(array $serviceAttributes = [], array $variationAttributes = []): array
    {
        $service = Service::create(array_merge([
            'name' => 'Test Printing',
            'category' => 'Documents',
            'retail_price' => 25,
            'bulk_price' => 20,
            'unit' => 'page',
            'description' => 'Test service',
            'is_active' => true,
        ], $serviceAttributes));

        $variation = ServiceVariation::create(array_merge([
            'service_id' => $service->id,
            'service_item_id' => 'TEST-A4-BW',
            'printing_category' => 'Document',
            'color_mode' => 'Black and White',
            'product_size' => 'A4',
            'retail_price' => 25,
            'bulk_price' => 20,
            'is_active' => true,
        ], $variationAttributes));

        return [$service, $variation];
    }

    private function createOrderForCustomer(User $customer, Service $service, ServiceVariation $variation, int $quantity): Order
    {
        $unitPrice = 25.0;
        $order = Order::create([
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'status' => 'Pending',
            'payment_status' => Order::PAYMENT_UNPAID,
            'total_price' => $unitPrice * $quantity,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'service_id' => $service->id,
            'service_variation_id' => $variation->id,
            'service_item_id' => $variation->service_item_id,
            'service_name' => $service->name,
            'variation_label' => $variation->variation_label,
            'price_type' => 'retail',
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'subtotal' => $unitPrice * $quantity,
        ]);

        return $order->fresh('items');
    }

    private function attachedFileMetadata(string $name = 'artwork.pdf'): array
    {
        return [
            'original_name' => $name,
            'path' => 'order-files/' . $name,
            'mime' => 'application/pdf',
            'size' => 120000,
        ];
    }
}
