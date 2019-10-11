<?php

use Illuminate\Database\Seeder;
use App\Entities\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(range(1, 5))->each(function () {
            factory(Organization::class)->create();
        });
    }
}
