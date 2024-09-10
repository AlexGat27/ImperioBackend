<?php
namespace app\services\manufactures;

use app\services\manufactures\BaseManufacturesQueryService;
use yii\db\Query;

class SearchManufacturesQueryService extends BaseManufacturesQueryService
{
    public function searchManufacturesData($queryParams = [])
    {
        $query = $this->buildBaseQuery();
        $query = $this->addCategoryQuery($query);
        $query = $this->applySearchFilters($query, $queryParams);
        $manufactures = $query->all();
        return $this->processManufactureResults($manufactures);
    }

    private function addCategoryQuery(Query $query){
        return $query
            ->addSelect('c.name as category_name')
            ->leftJoin('manufacture_category mc', 'm.id = mc.manufacture_id')
            ->leftJoin('category c', 'mc.category_id = c.id');
    }

    private function applySearchFilters($query, $queryParams)
    {
        if (!empty($queryParams)) {
            if (isset($queryParams['category'])) {
                $query->andWhere(['like', 'c.name', $queryParams['category']]);
                if (isset($queryParams['district'])) {
                    $query->andWhere(['like', 'd.name', $queryParams['district']]);
                    if (isset($queryParams['region'])) {
                        $query->andWhere(['like', 'r.name', $queryParams['region']]);
                        if (isset($queryParams['city'])) {
                            $query->andWhere(['like', 'ci.name', $queryParams['city']]);
                        }
                    }
                }
            }
        }
        return $query;
    }

    protected function formatManufactureResponse($manufacture, $emails, $contacts)
    {
        return [
            "id" => $manufacture['id'],
            "name" => $manufacture['name'],
            "website" => $manufacture['website'],
            "category" => $manufacture['category_name'],
            "region" => $manufacture['region_name'],
            "city" => $manufacture['city_name'],
            "district" => $manufacture['district_name'],
            "emails" => $emails,
            "contacts" => $contacts,
        ];
    }
}
