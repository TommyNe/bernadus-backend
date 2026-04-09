<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_milestone_awards', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('threshold');
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
                ['threshold' => '1 Gold', 'award' => 'Schützenschnur Grün'],
                ['threshold' => '4 Gold', 'award' => 'Schützenschnur Silber'],
                ['threshold' => '7 Gold', 'award' => 'Schützenschnur Gold'],
                ['threshold' => '8 Gold', 'award' => 'Plakette mit Kranz'],
                ['threshold' => '11 Gold', 'award' => 'Eichenlaub Silber'],
                ['threshold' => '15 Gold', 'award' => 'Eichenlaub Gold'],
                ['threshold' => '20 Gold', 'award' => 'Plakette mit Schnur Silber'],
                ['threshold' => '25 Gold', 'award' => 'Bild mit Plakette'],
                ['threshold' => '30 Gold', 'award' => 'Plakette mit Scheibe Bronze'],
                ['threshold' => '35 Gold', 'award' => 'Plakette mit Scheibe Silber'],
                ['threshold' => '40 Gold', 'award' => 'Plakette mit Scheibe Gold'],
                ['threshold' => '50 Gold', 'award' => 'Wandteller mit Orden'],
                ['threshold' => '60 Gold', 'award' => 'Anhänger und Plakette auf Schnur Gold'],
                ['threshold' => '65 Gold', 'award' => 'Scheibe Adler und Anhänger'],
                ['threshold' => '75 Gold', 'award' => 'Plakette mit Eichenlaub, Anhänger als Orden und Wandbild aus Holz'],
                ['threshold' => '80 Gold', 'award' => 'Plakette als Orden'],
                ['threshold' => '85 Gold', 'award' => 'Plakette als Orden'],
                ['threshold' => '90 Gold', 'award' => 'Plakette als Orden'],
                ['threshold' => '95 Gold', 'award' => 'Plakette als Orden'],
                ['threshold' => '100 Gold', 'award' => 'Besondere Plakette als Orden und zum Beispiel kleine Standuhr'],
            ])->map(fn (array $award, int $index): array => [
                'competition_id' => $competitionId,
                'threshold' => $award['threshold'],
                'award' => $award['award'],
                'sort_order' => ($index + 1) * 10,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('competition_milestone_awards')->insert($rows->all());
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_milestone_awards');
    }
};
