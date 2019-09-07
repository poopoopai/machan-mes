<?php

use Illuminate\Database\Seeder;
use App\Entities\MainProgram;

class MainProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(range(1, 23))->each(function () {
            factory(MainProgram::class)->create();
        });
    }
}
