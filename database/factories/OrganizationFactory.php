<?php

use Faker\Generator as Faker;

$factory->define(App\Entities\Organization::class, function (Faker $faker) {
    static $index = 0;
    $organizationInfo = [
        ['一群', '10', '1000'],
        ['二群', '20', '1002'],
        ['三群', '30', '1003'],
        ['五群', '50', '1009'],
        ['六群', '60', '1005'],
    ];

    return [
        'name' => $organizationInfo[$index][0],
        'type' => $organizationInfo[$index][1],
        'factory_id' => $organizationInfo[$index++][2]
    ];
});
