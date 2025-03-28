<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Setting;

/**
 * SettingSearch represents the model behind the search form of `frontend\models\Setting`.
 */
class SettingSearch extends Setting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mode', 'created_at', 'updated_at'], 'integer'],
            [['beheerderMail_test', 'from_test', 'to_test', 'title', 'theme_color','background_color','background_template','logo_url'], 'safe'],
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
        $query = Setting::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'mode' => $this->mode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'beheerderMail_test', $this->beheerderMail_test])
            ->andFilterWhere(['like', 'from_test', $this->from_test])
            ->andFilterWhere(['like', 'to_test', $this->to_test])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'background_color',$this->background_color])
            ->andFilterWhere(['like', 'background_template',$this->background_template])
            ->andFilterWhere(['like', 'logo_url',$this->logo_url])
            ->andFilterWhere(['like', 'theme_color', $this->theme_color]);

        return $dataProvider;
    }
}
