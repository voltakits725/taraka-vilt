<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Preferensi level gula customer per item pesanan
            $table->enum('sugar_level', ['normal', 'less', 'no_sugar'])
                  ->default('normal')
                  ->after('subtotal');

            // Catatan tambahan opsional dari customer
            $table->text('notes')
                  ->nullable()
                  ->after('sugar_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['sugar_level', 'notes']);
        });
    }
};
