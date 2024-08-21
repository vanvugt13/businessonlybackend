<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\EventSponsor;
use yii\data\Sort;
use yii\db\Expression;

/**
 * EventSponsorSearch represents the model behind the search form of `frontend\models\EventSponsor`.
 */
class EventSponsorSearch extends EventSponsor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','event_description','sponsor_typedescription'],'safe'],
            [['id', 'event_id', 'user_id', 'sponsor_type', 'created_at', 'updated_at'], 'integer'],
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
        $query = EventSponsor::find();
        $query->joinWith(['event','user']);
        $query->select(['event_sponsor.*',
        'user.username as username',
        new Expression('(case when sponsor_type = '.EventSponsor::SPONSOR_TYPE_BAL.' then "Bal" else (case when sponsor_type = '.EventSponsor::SPONSOR_TYPE_GAME.' then "wedstrijd" else (case when sponsor_type = '.EventSponsor::SPONSOR_TYPE_SCORE.' then "Doelpunt" else "Onbekend" end)end)end) as sponsor_typedescription'),
        'post.title as event_description']);

        // add conditions that should always apply here

        $sort = new Sort(['attributes'=>[
            'username',
            'created_at',
            'event_description',
            'sponsor_type_description',
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
            'event_id' => $this->event_id,
            'user_id' => $this->user_id,
            'sponsor_type' => $this->sponsor_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
