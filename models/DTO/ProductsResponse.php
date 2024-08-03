<?php

namespace app\models\DTO;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class ProductsResponse extends Model
{
    public $name;
    public $width;
    public $length;
    public $height;
    public $weight;
    public $category;
}