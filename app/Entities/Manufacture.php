<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    protected $fillable = [
    'mo_id',
    'item_id',
    'qty',
    'techroutekey_id',
    'online_date',
    'so_id',
    'complete_date',
    'customer'
    ];
}
