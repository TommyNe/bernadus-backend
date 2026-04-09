<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_score_awards', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('age_group');
            $table->unsignedTinyInteger('rings');
            $table->string('award');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $competitionIds = DB::table('competitions')
            ->join('competition_types', 'competition_types.id', '=', 'competitions.competition_type_id')
            ->where('competition_types.type_key', 'plaque_shooting')
            ->pluck('competitions.id');

        $timestamp = now();

        foreach ($competitionIds as $competitionId) {
            $rows = collect([
                ['age_group' => 'bis 55 Jahre', 'rings' => 48, 'award' => 'Gold'],
                ['age_group' => 'bis 55 Jahre', 'rings' => 43, 'award' => 'Silber'],
                ['age_group' => 'bis 55 Jahre', 'rings' => 38, 'award' => 'Bronze'],
                ['age_group' => 'ab 55 Jahre', 'rings' => 43, 'award' => 'Gold'],
                ['age_group' => 'ab 55 Jahre', 'rings' => 38, 'award' => 'Silber'],
                ['age_group' => 'ab 55 Jahre', 'rings' => 33, 'award' => 'Bronze'],
                ['age_group' => 'ab 70 Jahre', 'rings' => 38, 'award' => 'Gold'],
                ['age_group' => 'ab 70 Jahre', 'rings' => 33, 'award' => 'Silber'],
                ['age_group' => 'ab 70 Jahre', 'rings' => 28, 'award' => 'Bronze'],
            ])->map(fn (array $award, int $index): array => [
                'competition_id' => $competitionId,
                'age_group' => $award['age_group'],
                'rings' => $award['rings'],
                'award' => $award['award'],
                'sort_order' => ($index + 1) * 10,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('competition_score_awards')->insert($rows->all());
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_score_awards');
    }
};
