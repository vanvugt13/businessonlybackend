<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_company".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $company_id
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class UserCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'company_id' => 'Company ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
