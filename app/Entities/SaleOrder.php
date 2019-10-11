<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    protected $fillable = [
        'so_id',
        'item',
        'customer_name',
        'customer_order',
        'qty',
        'container_date',
        'status',
        'bill_date',
        'org_id',
        'current_state',
        'batch',
        'person_id',
        'material_spec',
        'sunit_id',
        'untrans_qty',
        'cu_remark',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function parentParts()
    {
        return $this->hasMany('App\Entities\ParentPart', 'material_id', 'item');
    }
}
