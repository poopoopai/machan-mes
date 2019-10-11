<?php

namespace App\Services;

use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;
use App\Repositories\SaleOrderRepository;

class GetProduceService
{

    private $apiServer;
    private $bodyStart;
    private $bodyEnd;
    private $strConnecting;
    private $strData;
    protected $orderRepo;

    public function __construct(SaleOrderRepository $orderRepo)
    {
		$this->apiServer = 'http://'.env('MACHAN_API').'/WebService/CAPInteropServiceEx.asmx';
        $this->bodyStart = '<?xml version="1.0" encoding="utf-8" ?>'.
            '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" '.
            'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '.
            'xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><ExecuteProc xmlns="http://tempuri.org/">';
		$this->bodyEnd = '<ucoInvoke>false</ucoInvoke><logMode>0</logMode></ExecuteProc></soap:Body></soap:Envelope>';
        $this->strConnecting = '<groupId>'.env('GROUP_ID').
            '</groupId><language/>zh-TW<language/><userId>'.env('USER_ID').'</userId><password>'.env('PASSWORD').'</password>';
		$this->strData = [
            '</ExecuteProcResult></ExecuteProcResponse></soap:Body></soap:Envelope>',
            '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"'.
            ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">'.
            '<soap:Body><ExecuteProcResponse xmlns="http://tempuri.org/"><ExecuteProcResult xsi:type="xsd:string">'
        ];

        $this->orderRepo = $orderRepo;
    }

    public function getSourceOrder($orgId, $billStartDate, $billEndDate, $conStartDate, $conEndDate, $soId, $customerName)
    {
        $conStartDate = $conStartDate ? ' and A.CU_ContainerDate3 &gt;='.Carbon::parse($conStartDate)->format('Ymd') : '';
        $conEndDate = $conEndDate ? ' and A.CU_ContainerDate3 &lt;='.Carbon::parse($conEndDate)->format('Ymd') : '';
        $billStartDate = $billStartDate ?? Carbon::createFromDate(now()->year, now()->month, 1, 'Asia/Taipei')->format('Ymd');
        $billEndDate = $billEndDate ?? Carbon::createFromDate(now()->year, now()->month, now()->daysInMonth, 'Asia/Taipei')->format('Ymd');
        $customerName = $customerName ? " and A.BizPartnerName='$customerName'" : '';
        if ($soId) {
            $arrData = explode(',', $soId);
            $dataStr = 'and (';
            foreach ($arrData as $key => $value) {
                if ($key) {
                    $dataStr .= ' or ';
                }
                $dataStr .= "A.BillNo='$value'";
            }
            $dataStr .= ')';
        }

        $params = $this->strConnecting.'<progId>ppProduceOrder</progId><methodName>GetDataFromInterface</methodName>'.
            '<wParams><anyType xsi:type="xsd:string">salSalesOrder</anyType>'.
            '<anyType xsi:type="xsd:string">A.CurrentState,A.TypeId,A.OrgId,A.BillNo,A.BillDate,A.BizPartnerId,'.
                'A.BizPartnerName,A.CustomerOrderNo,B.RowId,B.RowNo,B.MaterialId,B.MaterialName,B.RequirementDate,'.
                'B.CU_MOTransfer,B.CU_ScheStatus,B.BPMaterialId, A.CU_USHdate, A.CU_ContainerDate3, B.SQuantity, A.PersonId, B.MaterialSpec, B.SUnitId, B.UnTransSQty, B.CU_Remark2</anyType>'.
            '<anyType xsi:type="xsd:string">A.BillDate &gt;= '.$billStartDate.' and A.BillDate &lt;= '.$billEndDate.' and A.OrgId='.$orgId.$conStartDate.$conEndDate.' and A.CurrentState=2 '.($dataStr ?? '').$customerName.'</anyType>'.
            '<anyType xsi:type="xsd:boolean">true</anyType></wParams>';
        $result = $this->connectWebService($params);
        return $this->orderRepo->synchronize($result);
    }

    private function connectWebService($params)
    {
        $response = Curl::to($this->apiServer)
            ->withData($this->bodyStart.$params.$this->bodyEnd)
            ->withContentType('text/xml')
            ->post();

        foreach ($this->strData as $key => $string) {
            $response = str_replace($string, '', $response);
        }

        return json_decode($response);
    }
}
