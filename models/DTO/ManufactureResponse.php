<?php

namespace app\models\DTO;

use app\models\ManufactureContact;
use app\models\ManufactureContacts;
use app\models\ManufactureEmail;
use app\models\ManufactureEmails;
use yii\base\Model;

/**
 * This is the model class for response about manufactures.
 *
 * @property string $name
 * @property string $website
 * @property string $region
 * @property string $city
 *
 * @property ManufactureContacts[] $manufactureContacts
 * @property ManufactureEmails[] $manufactureEmails
 */
class ManufactureResponse extends Model
{
    public $name;
    public $website;
    public $region;
    public $city;
    public function rules()
    {
        return [
            [['name', 'website', 'region', 'city'], 'required'],
            [['name', 'website', 'region', 'city'], 'string', 'max' => 255],
            [['website'], 'url'], // Валидация URL
        ];
    }
    // Метод для преобразования данных в массив
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = parent::toArray($fields, $expand, $recursive);
        $data['manufactureContacts'] = array_map(function ($contact) {
            return $contact->toArray();
        }, $this->manufactureContacts);
        $data['manufactureEmails'] = array_map(function ($email) {
            return $email->toArray();
        }, $this->manufactureEmails);
        return $data;
    }
}