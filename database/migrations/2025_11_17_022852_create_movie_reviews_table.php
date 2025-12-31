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
        Schema::create('movie_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Người viết
            $table->foreignId('movie_id')->constrained()->onDelete('cascade'); // Phim được review
            $table->string('title'); // Tiêu đề bài review
            $table->text('body'); // Nội dung bài review (dạng blog)
            $table->unsignedTinyInteger('rating'); // Điểm (1-10) mà người viết review chấm cho phim
            // $table->integer('votes_up')->default(0); // (Tùy chọn) Số lượt "Hữu ích"
            // $table->integer('votes_down')->default(0); // (Tùy chọn) Số lượt "Không hữu ích"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_reviews');
    }
};
