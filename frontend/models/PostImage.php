<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "post_image".
 *
 * @property int $id
 * @property int|null $post_id
 * @property resource|null $image
 * @property int $checked
 *
 * @property Post $post
 */
class PostImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id','checked'], 'integer'],
            [['image'], 'string'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'image' => 'Image',
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }
}
