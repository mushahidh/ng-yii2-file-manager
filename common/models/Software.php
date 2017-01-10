<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "software".
 *
 * @property integer $id
 * @property string $sowtwarename
 * @property string $version
 * @property string $downloadlink
 */
class Software extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'software';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          
            [['id'], 'integer'],
            [['sowtwarename', 'downloadlink'], 'string', 'max' => 450],
            [['version'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sowtwarename' => Yii::t('app', 'Sowtwarename'),
            'version' => Yii::t('app', 'Version'),
            'downloadlink' => Yii::t('app', 'Downloadlink'),
        ];
    }
}
