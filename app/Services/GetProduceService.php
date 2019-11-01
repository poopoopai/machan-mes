<?php

namespace App\Services;

use Ixudra\Curl\Facades\Curl;
use App\Repositories\ManufactureRepository;

class GetProduceService
{ 
    private $apiServer;
    private $bodyStart;
    private $bodyEnd;
    private $strData;
    protected $manufactRepo;

    public function __construct(ManufactureRepository $manufactRepo)
    {
      
		$this->apiServer = 'http://'.env('MACHAN_API').'/WebService/CAPInteropServiceEx.asmx';
        $this->bodyStart = '<?xml version="1.0" encoding="utf-8" ?>
		<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" 
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
		xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><ExecuteProc xmlns="http://tempuri.org/">
		
		<groupId>'.env('GROUP_ID').'</groupId><language/>zh-TW<language/>
		<userId>'.env('USER_ID').'</userId><password>'.env('PASSWORD').
		'</password><progId>ppProduceOrder</progId>
		<methodName>GetDataFromInterface</methodName><wParams>';
		$this->bodyEnd = '</wParams><ucoInvoke>false</ucoInvoke></ExecuteProc></soap:Body></soap:Envelope>';
		$this->strData = [
			'</ExecuteProcResult></ExecuteProcResponse></soap:Body></soap:Envelope>', 
			'<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><ExecuteProcResponse xmlns="http://tempuri.org/"><ExecuteProcResult xsi:type="xsd:string">'
		];

        $this->manufactRepo = $manufactRepo;
    }

    public function getSourceOrder($mo_id)
    {
        if ($mo_id) {
            $arrData = explode(',', $mo_id);
            $dataStr = 'and (';
            foreach ($arrData as $key => $value) {
                if ($key) {
                    $dataStr .= ' or ';
                }
                $dataStr .= "A.BillNo='$value'";
            }
            $dataStr .= ')';
        }

        $params = '<anyType xsi:type="xsd:string">ppProduceOrder</anyType>
			<anyType xsi:type="xsd:string">A.TypeId,A._P_A1_FactoryId,A.BillNo,A.BillDate,A.BizPartnerId,A.BizPartnerName,A.FromBillNo,A.MaterialId,A.ProduceQty,A.UnitId,A.FromTechRouteKeyId,A.DemandBeginDate,A.DemandCompleteDate,A.DemandStockInDate,A.ParentBillNo,A.ProduceState,A.CurrentState</anyType>
			<anyType xsi:type="xsd:string">A.TypeId="MO10" '.($dataStr ?? '').'</anyType>
			<anyType xsi:type="xsd:boolean">true</anyType>';

        $result = $this->connectWebService($params); 
      
        return $this->manufactRepo->synchronize($result);
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
