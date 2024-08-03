<?php

namespace app\models\DTO;

use app\models\City;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for response about manufactures.
 *
 * @property string $name
 * @property string $type_cars_name
 * @property string $telephone
 * @property string $email
 * @property string $note
 *
 * @property string[] $regions
 */
class CarsLogistResponse extends Model
{
    public $name;
    public $type_cars_name;
    public $telephone;
    public $email;
    public $note;
    public $regions = [];
}