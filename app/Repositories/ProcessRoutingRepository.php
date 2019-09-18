<?php

namespace App\Repositories;

use App\Entities\ProcessRouting;

class ProcessRoutingRepository
{
    public function syncTechRouting(array $datas)
    {
        $org_id = ['10', '', '20', '30', '', '60', '50'];
        $transfer_factory = ['0', '1', '2', '3', '4', '5', '6', 'A' => 'A'];
        $routing_level = ['11', '20', '30', '40', '50'];
        $routing = ['11', '13', '12', '14'];
        foreach ($datas as $key => $techRouting) {
            if ($techRouting->CU_APSState == 0) {
                $tech_route_id = explode('-', $techRouting->TechRouteKeyId);
                $techRouting->org_id = $org_id[$tech_route_id[0][3]];
                $org_id2 = $org_id[$tech_route_id[0][3]];
                if ($tech_route_id[1][0] == 8) {
                    $techRouting->transfer_factory = '6';
                } else {
                    $techRouting->transfer_factory = $transfer_factory[$tech_route_id[1][0]];
                }

                if ($techRouting->transfer_factory == 'A') {
                    $techRouting->factory_type = $techRouting->org_id[0].$techRouting->transfer_factory;
                } else {
                    $techRouting->factory_type = (int) $techRouting->org_id + (int) $techRouting->transfer_factory;
                }

                if (isset($tech_route_id[2])) {
                    $techRouting->routing_level = $routing[$tech_route_id[2]];
                } else {
                    $techRouting->routing_level = $routing_level[$tech_route_id[1][1]];
                }
                $techRouting->aps_id = $techRouting->routing_level.$techRouting->factory_type;


                ProcessRouting::updateOrCreate(
                    [
                        'process_routing_id' => $techRouting->TechRouteKeyId
                    ],
                    [
                        'process_routing_name' => $techRouting->TechRouteKeyName,
                        'factory_id' => $techRouting->FactoryId,
                        'org_id' => $techRouting->org_id,
                        'transfer_factory' => $techRouting->transfer_factory,
                        'factory_type' => $techRouting->factory_type,
                        'routing_level' => $techRouting->routing_level,
                        'aps_id' => $techRouting->aps_id
                    ]
                );
            }
        }
        return 1;
    }

    public function processRoutingIndex($amount)
    {
        return ProcessRouting::paginate($amount);
    }

    public function getData()
    {
        return ProcessRouting::get();
    }

}