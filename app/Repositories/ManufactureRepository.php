<?php

namespace App\Repositories;

use App\Entities\Manufacture;

class ManufactureRepository
{
    public function synchronize(array $data)
    {
       
        return collect($data)->each(function ($order) {
            Manufacture::updateOrCreate(
                ['mo_id' => $order->BillNo, 'techroutekey_id' => $order->FromTechRouteKeyId],
                [
                    'item_id' => $order->MaterialId,
                    'customer_name' => $order->BizPartnerName,
                    'qty' => $order->ProduceQty,
                    'online_date' => $order->DemandBeginDate,
                    'so_id' => $order->FromBillNo,
                    'customer' => $order->BizPartnerName,
                ]
            );
        });
    }

    public function currentLoadedData(array $data)
    {
        return Manufacture::whereIn('mo_id', explode(',', $data['mo_id']));
    }
}
?>