<?php

use App\Models\Client\Client;
use App\Enums\Client\IsMainClient;
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
        //client_id , phone , is_main , country_code
        //client_id , address, is_main
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('phone');
            $table->boolean('is_main')->default(IsMainClient::ISNOTMAIN->value);
            $table->string('country_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
