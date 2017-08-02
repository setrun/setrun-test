<?php

namespace sys\entities;

use Yii;
use sys\entities\queries\DomainQuery;

/**
 * This is the model class for table "{{%sys_domain}}".
 *
 * @property integer $id
 * @property string  $domain
 * @property string  $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Setting[]  $settings
 * @property Language[] $languages
 */
class Domain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sys_domain}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain', 'name', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['domain', 'name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('sys\domain', 'ID'),
            'domain'     => Yii::t('sys\domain', 'Domain'),
            'name'       => Yii::t('sys\domain', 'Name'),
            'created_at' => Yii::t('sys\domain', 'Created At'),
            'updated_at' => Yii::t('sys\domain', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponentSettings()
    {
        return $this->hasMany(Setting::className(), ['did' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::className(), ['did' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \sys\entities\queries\DomainQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DomainQuery(get_called_class());
    }
}
