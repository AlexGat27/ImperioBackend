<?php

namespace app\models\DTO;

use app\models\Manufactures;
use app\models\ManufactureEmails;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ManufactureForm extends Model
{
    public $id;
    public $name;
    public $website;
    public $id_region;
    public $id_city;
    public $id_district;
    public $address_loading;
    public $note;
    public $create_your_project;
    public $is_work;
    public $emails = [];

    public function rules()
    {
        return [
            [['name', 'emails'], 'required'],
            [['id_region', 'id_city', 'id_district'], 'integer'],
            [['create_your_project', 'is_work'], 'boolean'],
            [['note'], 'string'],
            [['name', 'website', 'address_loading'], 'string', 'max' => 255],
            [['emails'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'website' => 'Website',
            'id_region' => 'Region',
            'id_city' => 'City',
            'id_district' => 'District',
            'address_loading' => 'Loading Address',
            'note' => 'Note',
            'create_your_project' => 'Create Your Project',
            'is_work' => 'Is Work',
            'emails' => 'Emails',
        ];
    }

    public function loadFromModel($model)
    {
        $this->id = $model->id;
        $this->name = $model->name;
        $this->website = $model->website;
        $this->id_region = $model->id_region;
        $this->id_city = $model->id_city;
        $this->id_district = $model->id_district;
        $this->address_loading = $model->address_loading;
        $this->note = $model->note;
        $this->create_your_project = $model->create_your_project;
        $this->is_work = $model->is_work;
        $this->emails = ArrayHelper::getColumn($model->manufactureEmails, 'email');
    }

    public function save()
    {
        if (!$this->validate()) {
            throw new \Exception('Validation failed');
        }
        if ($this->id) {
            $model = Manufactures::findOne($this->id);
        } else {
            $model = new Manufactures();
        }

        $model->attributes = $this->attributes;
        $model->is_work = $this->is_work ? 1 : 0;
        $model->create_your_project = $this->create_your_project ? 1 : 0;

        if ($model->save()) {
            ManufactureEmails::deleteAll(['id_manufacture' => $model->id]);
            if (is_string($this->emails)) {
                $this->emails = explode(',', $this->emails);
            }
            $this->emails = array_map('trim', $this->emails);
            foreach ($this->emails as $email) {
                $emailModel = new ManufactureEmails();
                $emailModel->id_manufacture = $model->id;
                $emailModel->email = $email;
                $emailModel->save();
            }

            return array_merge($model->getAttributes(), [
                "emails" => $this->emails,
            ]);
        }

        throw new \Exception('Cannot create manufacture model');
    }
}
