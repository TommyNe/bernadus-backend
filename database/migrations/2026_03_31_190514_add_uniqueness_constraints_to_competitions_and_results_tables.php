<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table): void {
            $table->unique(['competition_type_id', 'year'], 'competitions_type_year_unique');
        });

        Schema::table('competition_results', function (Blueprint $table): void {
            $table->unique(['competition_result_category_id', 'rank'], 'competition_results_category_rank_unique');
        });
    }

    public function down(): void
    {
        Schema::table('competition_results', function (Blueprint $table): void {
            $table->dropUnique('competition_results_category_rank_unique');
        });

        Schema::table('competitions', function (Blueprint $table): void {
            $table->dropUnique('competitions_type_year_unique');
        });
    }
};
