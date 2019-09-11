<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ProcessRouting extends Model
{
    protected $fillable = [
        'process_routing_id',
        'process_routing_name',
        'factory',
        'factory_id',
        'internal_code',
        'status',
        'org_id',
        'transfer_factory',
        'factory_type',
        'routing_level',
        'aps_id',
    ];
}
