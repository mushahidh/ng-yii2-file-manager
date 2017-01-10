<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property string $code
 * @property string $country
 * @property integer $population
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'country', 'population'], 'required'],
            [['population'], 'integer'],
            [['code', 'country'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'country' => Yii::t('app', 'Country'),
            'population' => Yii::t('app', 'Population'),
        ];
    }
}
