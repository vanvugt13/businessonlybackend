<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Post;
use yii\data\Sort;
use yii\db\Expression;

/**
 * PostSearch represents the model behind the search form of `frontend\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category', 'visible_till', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'image','username','category_description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();
        $query->joinWith('user');
        $query->select([
            'post.id as id',
            'post.title',
            'post.description',
            'post.category',
            
            // new Expression('(case when post.category = '.self::CATEGORY_EVENTS.' then "'
            // .Post::categories(self::CATEGORY_EVENTS).'" else (case when post.category = '.self::CATEGORY_NEWS.' then "'
            // .Post::categories(self::CATEGORY_NEWS).'" else (case when post.category = '.self::CATEGORY_POST.' then "'
            // .Post::categories(self::CATEGORY_POST).'" end) end) end) as category_description'),
            'post.visible_till',
            'user.contactperson as username'
        ]);

        $sort = new Sort([
            'attributes'=>[
                'username',
                'title',
                'description',
                'category',
                'visible_till',
                'category_description',
            //     new Expression('(case when post.category = '.self::CATEGORY_EVENTS.' then "'
            // .Post::visibleOptions(self::CATEGORY_EVENTS).'" else (case when post.category = '.self::CATEGORY_NEWS.' then "'
            // .Post::visibleOptions(self::CATEGORY_NEWS).'" else "'.Post::visibleOptions(self::CATEGORY_POST).'" end) end) '),
            ],
            'defaultOrder' => ['created_at' => SORT_DESC]
        ]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>$sort,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category' => $this->category,
            'visible_till' => $this->visible_till,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
        //    ->andFilterWhere(['like', 'category', $this->category_description])
            // ->andFilterWhere(['like', new Expression('(case when post.category = '.self::CATEGORY_EVENTS.' then "'
            // .Post::categories(self::CATEGORY_EVENTS).'" else (case when post.category = '.self::CATEGORY_NEWS.' then "'
            // .Post::categories(self::CATEGORY_NEWS).'" else (case when post.category = '.self::CATEGORY_POST.' then "'
            // .Post::categories(self::CATEGORY_POST).'" end) end) end)'), $this->category_description])

            ->andFilterWhere(['like', 'image', $this->image]);

            // echo $query->createCommand()->getRawSql();
            // exit;
        return $dataProvider;
    }
}
