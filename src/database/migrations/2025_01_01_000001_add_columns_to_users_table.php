<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->text('profile_text')->nullable()->after('avatar');
            $table->decimal('rating', 3, 2)->default(0)->after('profile_text');
            $table->boolean('is_banned')->default(false)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'profile_text', 'rating', 'is_banned']);
        });
    }
};
