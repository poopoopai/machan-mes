<?php
    namespace App\Repositories;
    use App\Entities\Resource;
    class RollerDataRepository
    {
        public function findFirstOpenId($findFirstOpen)
        {
            return Resource::where('id', $findFirstOpen->resources_id)->first();
        }

        public function findPreviousId($data)
        {
            return Resource::where('id', '<', $data['id'])->where('date', $data['date'])->orderby('id', 'desc')->first();
        }
    }

?>