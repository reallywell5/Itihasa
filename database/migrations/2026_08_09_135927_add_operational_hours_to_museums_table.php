<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('museums', function (Blueprint $table) {
            $table->json('operational_hours')->nullable()->after('closing_time');
        });

        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        DB::table('museums')->orderBy('id')->chunkById(50, function ($museums) use ($days) {
            foreach ($museums as $museum) {
                $sessions = [];

                if ($museum->opening_time && $museum->closing_time) {
                    $sessions = [[
                        'open' => substr($museum->opening_time, 0, 5),
                        'close' => substr($museum->closing_time, 0, 5),
                    ]];
                }

                $schedule = [];
                foreach ($days as $day) {
                    $schedule[$day] = $sessions
                        ? ['closed' => false, 'sessions' => $sessions]
                        : ['closed' => true, 'sessions' => []];
                }

                DB::table('museums')
                    ->where('id', $museum->id)
                    ->update(['operational_hours' => json_encode($schedule)]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('museums', function (Blueprint $table) {
            $table->dropColumn('operational_hours');
        });
    }
};
