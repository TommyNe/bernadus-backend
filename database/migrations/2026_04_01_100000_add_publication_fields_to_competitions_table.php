<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table): void {
            $table->enum('status', ['draft', 'published'])->default('draft')->after('sort_order');
            $table->timestamp('published_at')->nullable()->after('status');
        });

        DB::table('competitions')->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table): void {
            $table->dropColumn(['status', 'published_at']);
        });
    }
};
