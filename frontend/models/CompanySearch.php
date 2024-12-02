<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Company;
use yii\data\Sort;
use yii\db\Expression;

/**
 * CompanySearch represents the model behind the search form of `frontend\models\Company`.
 */
class CompanySearch extends Company
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'logo','loginaccount'], 'safe'],
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
        $query = Company::find();
      //  $query->joinWith("user");
        // add conditions that should always apply here
        $query->select(['company.*',
            new Expression('(select group_concat(username,",") from user where company_id=company.id)  as loginaccount')
        ]);
        $sort = new Sort([
            'defaultOrder' => ['created_at' => SORT_DESC],
            'attributes'=>[
                'company.name',
                'company.description',
                'company.created_at',
                'loginaccounts',
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', '(select group_concat(username,",") from user where company_id=company.id)', $this->loginaccount]);

        return $dataProvider;
    }
}
