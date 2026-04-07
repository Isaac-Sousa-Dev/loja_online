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
        if (! Schema::hasTable('store_hours')) {
            return;
        }

        if (Schema::hasColumn('store_hours', 'day_of_week')) {
            return;
        }

        Schema::create('store_hours_next', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedTinyInteger('day_of_week');
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_open')->default(true);
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unique(['store_id', 'day_of_week']);
        });

        $now = now();
        $grouped = DB::table('store_hours')->get()->groupBy('store_id');

        foreach ($grouped as $storeId => $items) {
            $row = $items->first();
            if ($row === null) {
                continue;
            }

            $weekOpen = $row->open_in_weekdays;
            $weekClose = $row->close_in_weekdays;
            $weekIsOpen = $weekOpen !== null && $weekClose !== null;

            for ($d = 1; $d <= 5; $d++) {
                DB::table('store_hours_next')->insert([
                    'store_id' => $storeId,
                    'day_of_week' => $d,
                    'open_time' => $weekIsOpen ? $weekOpen : null,
                    'close_time' => $weekIsOpen ? $weekClose : null,
                    'is_open' => $weekIsOpen ? 1 : 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $satOpen = $row->open_saturday;
            $satClose = $row->close_saturday;
            $satIsOpen = $satOpen !== null && $satClose !== null;
            DB::table('store_hours_next')->insert([
                'store_id' => $storeId,
                'day_of_week' => 6,
                'open_time' => $satIsOpen ? $satOpen : null,
                'close_time' => $satIsOpen ? $satClose : null,
                'is_open' => $satIsOpen ? 1 : 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $sunOpen = $row->open_sunday;
            $sunClose = $row->close_sunday;
            $sunIsOpen = $sunOpen !== null && $sunClose !== null;
            DB::table('store_hours_next')->insert([
                'store_id' => $storeId,
                'day_of_week' => 0,
                'open_time' => $sunIsOpen ? $sunOpen : null,
                'close_time' => $sunIsOpen ? $sunClose : null,
                'is_open' => $sunIsOpen ? 1 : 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::drop('store_hours');
        Schema::rename('store_hours_next', 'store_hours');
    }

    public function down(): void
    {
        // Não há reversão segura para o layout legado em uma única linha.
    }
};
