<?php

use Illuminate\Database\Seeder;
use App\Entities\ErrorCode;

class ErrorCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(range(1, 24))->each(function () {
            factory(ErrorCode::class)->create();
        });
    }
}
