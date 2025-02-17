<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('label')->nullable();
            $table->float('qty_in_stock')->nullable();
            $table->float('leftovers')->nullable();
            $table->float('ordered_qty')->nullable();
            $table->float('qty_to_process')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('label');
            $table->dropColumn('qty_in_stock');
            $table->dropColumn('leftovers');
            $table->dropColumn('ordered_qty');
            $table->dropColumn('qty_to_process');
        });
    }
};
