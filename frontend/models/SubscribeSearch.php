<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Subscribe;
use yii\data\Sort;

/**
 * SubscribeSearch represents the model behind the search form of `frontend\models\Subscribe`.
 */
class SubscribeSearch extends Subscribe
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'news_id', 'post_id', 'event_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['username','news_description','event_description','post_description'],'safe'],
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
        $query = Subscribe::find();
        $query->select(['subscribe.*'
        ,'user.username as username'
        ,'post.title as post_description'
        ,'news.title as news_description'
        ,'event.title as event_description'
        ]);
        $query->joinWith(['news','post','event','user']);
        // add conditions that should always apply here


        $sort = new Sort(['attributes'=>[
            'subscribe.id',
            'event_description',
            'news_description',
            'post_description',
            'username',
            'created_at',
        ]
        ]);
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
            'news_id' => $this->news_id,
            'post_id' => $this->post_id,
            'event_id' => $this->event_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            ['like','event.title'=>$this->event_description],
            ['like','news.title'=>$this->news_description],
        ]);

        return $dataProvider;
    }
}
