<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();



        // Saptamana aceasta
        $ore_de_program = \App\Models\ProgramareOraDeProgram::select('ziua_din_saptamana', 'ora')
            ->inRandomOrder()
            ->take(70)
            ->get();

        foreach ($ore_de_program->groupBy('ziua_din_saptamana') as $ore_zilnic){
            $data = \Carbon\Carbon::today()->startOfWeek();
            $data->addDays($ore_zilnic->first()->ziua_din_saptamana - 1);

            foreach($ore_zilnic as $ora){
                $programare = \App\Models\Programare::factory()->create();
                $programare->data = $data;
                $programare->ora = $ora->ora;
                $programare->save();
            }
        }

        // Saptamana viitoare
        $ore_de_program = \App\Models\ProgramareOraDeProgram::select('ziua_din_saptamana', 'ora')
            ->inRandomOrder()
            ->take(70)
            ->get();

        foreach ($ore_de_program->groupBy('ziua_din_saptamana') as $ore_zilnic){
            $data = \Carbon\Carbon::today()->addDays(7)->startOfWeek();
            $data->addDays($ore_zilnic->first()->ziua_din_saptamana - 1);

            foreach($ore_zilnic as $ora){
                $programare = \App\Models\Programare::factory()->create();
                $programare->data = $data;
                $programare->ora = $ora->ora;
                $programare->save();
            }
        }
    }
}
