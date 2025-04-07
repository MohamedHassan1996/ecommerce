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

        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('email');
            $table->boolean('is_main')->default(IsMainClient::ISNOTMAIN->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
