<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "company_image".
 *
 * @property int $id
 * @property int|null $company_id
 * @property resource|null $image
 *
 * @property Company $company
 */
class CompanyImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id'], 'integer'],
            [['image'], 'string'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'image' => 'Image',
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }
}
