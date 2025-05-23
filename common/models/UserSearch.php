<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use yii\data\Sort;
use yii\db\Expression;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'verification_token','statusDescription','lastPostSeenDate'], 'safe'],
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
        $query = User::find();
        $query->select([
            'id',
            'email',
            'username',
            'status',
            new Expression('(case when status = 9 then "Moet nog geactiveerd worden" else "Actief" end ) as statusDescription'),
            new Expression('(SELECT max(from_unixtime(created_at)) FROM post_seen where user_id=user.id ) as lastPostSeenDate'),
            'created_at',
        ]);

        // add conditions that should always apply here

        $sort = new Sort(['attributes'=>[
            'email',
            'username',
            'status',
            'statusDescription',
            'created_at',
            'lastPostSeenDate',
        ],
        'defaultOrder' => ['created_at' => SORT_DESC]]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>$sort
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
            'status' => $this->statusDescription,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'verification_token', $this->verification_token]);

        return $dataProvider;
    }
}
