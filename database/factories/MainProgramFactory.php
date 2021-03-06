<?php

use Faker\Generator as Faker;

$factory->define(App\Entities\MainProgram::class, function (Faker $faker) {

    static $index = 0;
    $MainInfo = [
        [1, '訂單開始','0','0','1'],
        [2, '訂單結束','0','0','1'],
        [3, '機台啟動','0','成品預計數量','1'],
        [4, '機台停止','0','X','1'],
        [5, '二次元異常開始','二次元','二次元異常碼二次元異常碼送料機異常碼','1'],
        [6, '二次元異常結束','二次元','送料機異常碼','1'],
        [7, '送料異常開始','送料機','','1'],
        [8, '送料異常結束','送料機','','1'],
        [9, '二次元成品完成','二次元','成品目前數量','1'],
        [10,'送料機成品完成','送料機','成品目前數量','1'],
        [11,'二次元連線開始','二次元','X','1'],
        [12,'二次元連線結束','二次元','X','1'],
        [13,'送料機連線開始','送料機','X','1'],
        [14,'送料機連線結束','送料機','X','1'],
        [15,'Sensro1','0','0','1'],
        [16,'Sensro2','0','X','1'],
        [17,'成品數量到達','二次元','X','1'],
        [18,'X','0','X','1'],
        [19,'X','0','X','1'],
        [20,'送料機換料開始','送料機','X','1'],
        [21,'送料機換料結束','送料機','X','1'],
        [22,'集料轉移開始','二次元','X','1'],
        [23,'集料轉移結束','二次元','X','1'],
    ];

    return [
        'status' => $MainInfo[$index][0],
        'description' => $MainInfo[$index][1],
        'type' => $MainInfo[$index][2],
        'codeX' => $MainInfo[$index][3],
        'group' => $MainInfo[$index++][4],
    ];
});
