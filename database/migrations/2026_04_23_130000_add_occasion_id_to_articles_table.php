<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('occasion_id')->nullable()->after('product_id');
            $table->foreign('occasion_id')->references('id')->on('occasions')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['occasion_id']);
            $table->dropColumn('occasion_id');
        });
    }
};