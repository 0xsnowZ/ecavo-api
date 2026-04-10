<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_fr')->nullable()->after('name_en');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_fr')->nullable()->after('name_en');
            $table->text('description_fr')->nullable()->after('description_en');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_fr', 'description_fr']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name_fr');
        });
    }
};
