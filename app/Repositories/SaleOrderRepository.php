<?php

namespace App\Repositories;

use App\Entities\SaleOrder;

class SaleOrderRepository
{
    public function synchronize(array $data)
    {
        $batch = SaleOrder::max('batch') ?: 0;
        collect($data)->each(function ($order) use ($batch) {
            SaleOrder::firstOrCreate(
                ['so_id' => $order->BillNo],
                [
                    'item' => $order->MaterialId,
                    'org_id' => $order->OrgId,
                    'current_state' => $order->CurrentState,
                    'customer_order' => $order->CustomerOrderNo,
                    'customer_name' => $order->BizPartnerName,
                    'qty' => $order->SQuantity,
                    'container_date' => $order->CU_ContainerDate3 == 0 ? null : $order->CU_ContainerDate3,
                    'bill_date' => date('Ymd', strtotime($order->BillDate)),
                    'status' => $order->CU_ScheStatus,
                    'person_id' =>$order->PersonId,
                    'material_spec' =>$order->MaterialSpec,
                    'sunit_id' =>$order->SUnitId,
                    'untrans_qty' =>$order->UnTransSQty,
                    'cu_remark' =>$order->CU_Remark2,
                    'batch' => $batch + 1,
                ]
            );
        });
    }

    public function currentLoadedData(array $data)
    {
        return SaleOrder::where('org_id', $data['org_id'])
            ->whereBetween('bill_date', [$data['bill_date_start'], $data['bill_date_end']])
            ->when($data['container_date_start'], function ($query, $conStartDate) {
                $query->where('container_date', '>=', $conStartDate);
            })
            ->when($data['container_date_end'], function ($query, $conEndDate) {
                $query->where('container_date', '<=', $conEndDate);
            })
            ->when($data['customer_name'], function ($query, $customerName) {
                $query->where('customer_name', $customerName);
            })
            ->when($data['so_id'], function ($query, $soId) {
                $query->whereIn('so_id', explode(',', $soId));
            });
    }

    public function getSynchroizedDate()
    {
        return SaleOrder::selectRaw('date(created_at) as date')
                ->distinct()
                ->orderBy('date')
                ->pluck('date');
    }

    public function getSynchroizedResult($date = null)
    {
        return $date ? SaleOrder::whereDate('created_at', $date) : [];
    }

    public function appSearchSo($params)
    {
        return SaleOrder::select('so_id')->where('so_id', 'like', $params.'%')->distinct()->get();
    }

    public function appSearchCustomer($params)
    {
        return SaleOrder::select('customer_name')
                ->where('customer_name', 'like', $params.'%')->distinct()->get();
    }
}
