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
        Schema::create('contacts_phonenumbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contacts_id');
            $table->string('contact_phone_number')->nullable()->unique();
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
        Schema::dropIfExists('contacts_phonenumbers');
    }
};
