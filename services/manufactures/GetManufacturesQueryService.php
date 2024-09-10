<?php
namespace app\services\manufactures;

use app\services\manufactures\BaseManufacturesQueryService;
use yii\db\Query;

class GetManufacturesQueryService extends BaseManufacturesQueryService
{
    public function getManufacturesData($queryParams = [])
    {
        $query = $this->buildBaseQuery();
        $manufactures = $query->all();
        return $this->processManufactureResults($manufactures);
    }
    public function getManufacturesById($manufactureId){
        $query = $this->buildBaseQuery();
        $manufactures = $query->where(['id' => $manufactureId])->one();
        return $this->processManufactureResults($manufactures);
    }

    protected function formatManufactureResponse($manufacture, $emails, $contacts)
    {
        return [
            "id" => $manufacture['id'],
            "name" => $manufacture['name'],
            "website" => $manufacture['website'],
            "is_work" => $manufacture['is_work'] == 1 ? true : false,
            "create_your_project" => $manufacture['create_your_project'] == 1 ? true : false,
            "address_loading" => $manufacture['address_loading'],
            "note" => $manufacture['note'],
            "region" => $manufacture['region_name'],
            "city" => $manufacture['city_name'],
            "district" => $manufacture['district_name'],
            "emails" => $emails,
            "contacts" => $contacts,
        ];
    }
}
