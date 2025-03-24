<?php

use App\Enums\Images\IsMainMediaEnum;
use App\Enums\Images\MediaTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {//paht ,is_video
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('media_type')->default(MediaTypeEnum::IMAGE->value);
            $table->string('is_main')->default(IsMainMediaEnum::ISNOTMAIN->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
