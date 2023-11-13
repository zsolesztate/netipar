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
        Schema::create('contacts_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contacts_id');
            $table->string('residential_address')->nullable();
            $table->string('mailing_address')->nullable();
            $table->timestamps();

            $table->foreign('contacts_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->index('contacts_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts_addresses');
    }
};
