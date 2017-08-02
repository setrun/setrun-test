<?php

namespace sys\entities;

use Yii;
use sys\entities\queries\SettingQuery;

/**
 * This is the model class for table "{{%sys_setting}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $did
 * @property string  $name
 * @property string  $json_value
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Domain $domain
 * @property User   $user
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sys_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'did', 'created_at', 'updated_at'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'required'],
            [['json_value'], 'string'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('sys\setting', 'ID'),
            'user_id'    => Yii::t('sys\setting', 'User ID'),
            'did'        => Yii::t('sys\setting', 'Did'),
            'name'       => Yii::t('sys\setting', 'Name'),
            'json_value' => Yii::t('sys\setting', 'Json Value'),
            'created_at' => Yii::t('sys\setting', 'Created At'),
            'updated_at' => Yii::t('sys\setting', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getD()
    {
        return $this->hasOne(Domain::className(), ['id' => 'did']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \sys\entities\queries\SettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingQuery(get_called_class());
    }
}
