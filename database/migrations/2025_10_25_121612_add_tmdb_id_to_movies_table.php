<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // CHUYỂN CODE VÀO ĐÂY
        Schema::table('movies', function (Blueprint $table) {
            // Thêm cột 'tmdb_id'
            $table->integer('tmdb_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // HÀM 'DOWN' NÊN XÓA CỘT ĐÓ ĐI
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('tmdb_id'); // Xóa cột 'tmdb_id'
        });
    }
};