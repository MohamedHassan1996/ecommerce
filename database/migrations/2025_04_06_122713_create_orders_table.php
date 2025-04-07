<?php

use App\Enums\Order\DiscountType;
use App\Enums\Order\OrderStatus;
use App\Models\Client\Client;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignIdFor(Client::class)->constrained('clients');
            $table->foreignId('client_phone_id')->nullable()->constrained('phones');
            $table->foreignId('client_email_id')->nullable()->constrained('emails');
            $table->foreignId('client_address_id')->nullable()->constrained('addresses');
            $table->tinyInteger('status')->default(OrderStatus::DRAFT->value);
            $table->decimal('discount',8,2);
            $table->decimal('price', 10, 2);//total items price
            $table->tinyInteger('discount_type')->default(DiscountType::NO_DISCOUNT->value);
            $table->decimal('price_after_discount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
