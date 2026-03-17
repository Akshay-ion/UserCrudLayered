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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->string('email')->unique();

            $table->timestamps();

            $table->fullText(['name', 'email']);
        });

        // DB::statement('ALTER TABLE users ADD FULLTEXT users_fulltext_index(name, email)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
