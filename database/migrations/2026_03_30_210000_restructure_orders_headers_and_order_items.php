<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'code')) {
            return;
        }

        Schema::rename('orders', 'order_items');

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('client_id');
            $table->string('status')->index();
            $table->string('fulfillment_type')->default('pickup')->index();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->unsignedTinyInteger('payment_installments')->nullable();
            $table->string('payment_status')->nullable();
            $table->text('message')->nullable();
            $table->boolean('shift')->default(false);
            $table->boolean('finance')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('separator_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->timestamps();

            $table->foreign('store_id', 'fk_order_headers_store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->foreign('client_id', 'fk_order_headers_client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('assigned_to', 'fk_order_headers_assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->foreign('separator_id', 'fk_order_headers_separator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('seller_id', 'fk_order_headers_seller_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('note')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('id');
            $table->decimal('unit_price', 12, 2)->nullable()->after('quantity');
            $table->decimal('line_subtotal', 12, 2)->nullable()->after('unit_price');
        });

        $rows = DB::table('order_items')->orderBy('id')->get();
        $groups = [];
        foreach ($rows as $row) {
            $key = $row->order_ref !== null && $row->order_ref !== '' ? $row->order_ref : 'single_'.$row->id;
            if (! isset($groups[$key])) {
                $groups[$key] = [];
            }
            $groups[$key][] = $row;
        }

        $mapStatus = static function (?string $s): string {
            return match ($s) {
                'paid' => 'confirmed',
                'sold' => 'completed',
                'canceled' => 'cancelled',
                default => 'pending',
            };
        };

        foreach ($groups as $groupRows) {
            $first = $groupRows[0];
            $status = $mapStatus($first->status ?? null);
            $fulfillment = $first->delivery_type ?? 'pickup';
            if (! in_array($fulfillment, ['pickup', 'delivery'], true)) {
                $fulfillment = 'pickup';
            }

            $subtotal = 0.0;
            foreach ($groupRows as $r) {
                $price = (float) (DB::table('products')->where('id', $r->product_id)->value('price') ?? 0);
                $qty = max(1, (int) ($r->quantity ?? 1));
                $subtotal += $price * $qty;
            }

            $code = ($first->order_ref !== null && $first->order_ref !== '') ? $first->order_ref : '#'.str_pad((string) $first->id, 6, '0', STR_PAD_LEFT);

            $orderId = DB::table('orders')->insertGetId([
                'code' => $code,
                'store_id' => $first->store_id,
                'client_id' => $first->client_id,
                'status' => $status,
                'fulfillment_type' => $fulfillment,
                'subtotal' => $subtotal,
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'total' => $subtotal,
                'payment_method' => $first->payment_method,
                'payment_installments' => 1,
                'payment_status' => 'pending',
                'message' => $first->message,
                'shift' => (bool) ($first->shift ?? false),
                'finance' => (bool) ($first->finance ?? false),
                'notified_at' => null,
                'assigned_to' => null,
                'separator_id' => null,
                'completed_at' => $status === 'completed' ? $first->updated_at : null,
                'seller_id' => $first->seller_id,
                'created_at' => $first->created_at,
                'updated_at' => $first->updated_at,
            ]);

            DB::table('order_status_histories')->insert([
                'order_id' => $orderId,
                'from_status' => null,
                'to_status' => $status,
                'note' => null,
                'changed_by' => null,
                'created_at' => $first->created_at,
                'updated_at' => $first->updated_at,
            ]);

            foreach ($groupRows as $r) {
                $price = (float) (DB::table('products')->where('id', $r->product_id)->value('price') ?? 0);
                $qty = max(1, (int) ($r->quantity ?? 1));
                DB::table('order_items')->where('id', $r->id)->update([
                    'order_id' => $orderId,
                    'unit_price' => $price,
                    'line_subtotal' => $price * $qty,
                ]);
            }
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id', 'fk_order_items_order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        //
    }
};
