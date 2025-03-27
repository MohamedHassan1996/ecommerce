<?php

use App\Models\Product\Product;
use App\Enums\Images\MediaTypeEnum;
use App\Enums\Images\IsMainMediaEnum;
use Illuminate\Support\Facades\Schema;
use App\Traits\CreatedUpdatedByMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use CreatedUpdatedByMigration;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('media_type')->default(MediaTypeEnum::IMAGE->value);
            $table->string('is_main')->default(IsMainMediaEnum::ISNOTMAIN->value);
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnUpdate();
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
