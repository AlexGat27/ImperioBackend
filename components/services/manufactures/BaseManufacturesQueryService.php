<?php


use yii\db\Query;

abstract class BaseManufacturesQueryService
{
    protected function buildBaseQuery()
    {
        return (new Query())
            ->select([
                'm.id',
                'm.name',
                'm.website',
                'm.address_loading',
                'm.note',
                'm.create_your_project',
                'm.is_work',
                'r.name AS region_name',
                'ci.name AS city_name',
                'd.name AS district_name'
            ])
            ->from('manufactures m')
            ->leftJoin('city r', 'm.id_region = r.id')
            ->leftJoin('city ci', 'm.id_city = ci.id')
            ->leftJoin('city d', 'r.parentid = d.id');
    }

    protected function processManufactureResults($manufactures)
    {
        $response = [];
        foreach ($manufactures as $manufacture) {
            $emails = $this->getManufactureEmails($manufacture['id']);
            $contacts = $this->getManufactureContacts($manufacture['id']);
            $response[] = $this->formatManufactureResponse($manufacture, $emails, $contacts);
        }
        return $response;
    }

    protected function getManufactureEmails($manufactureId)
    {
        return (new Query())
            ->select('email')
            ->from('manufacture_emails')
            ->where(['id_manufacture' => $manufactureId])
            ->column();
    }

    protected function getManufactureContacts($manufactureId)
    {
        return (new Query())
            ->select(['id', 'telephone', 'name_personal', 'note'])
            ->from('manufacture_contacts')
            ->where(['id_manufacture' => $manufactureId])
            ->all();
    }

    abstract protected function formatManufactureResponse($manufacture, $emails, $contacts);
}
