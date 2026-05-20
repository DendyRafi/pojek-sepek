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
        Schema::table('criterias', function (Blueprint $table) {
            $table->float('p')->default(0)->after('preference_function');
            $table->float('q')->default(0)->after('p');
            $table->float('s')->default(0)->after('q');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->dropColumn(['p', 'q', 's']);
        });
    }
};
