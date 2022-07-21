<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Promotores;

/**
 * PromotoresSearch represents the model behind the search form of `app\models\Promotores`.
 */
class PromotoresSearch extends Promotores
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g01_id', 'g01_telefono', 'user_id','g01_status'], 'integer', 'message' => Yii::$app->params['error_numer']],
            [['g01_nombre', 'g01_paterno', 'g01_materno', 'g01_domicilio', 'g01_created'], 'safe'],
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
        $query = Promotores::find()->where(['g01_status' => Yii::$app->params['value_active']]);

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
            'g01_id' => $this->g01_id,
            'g01_telefono' => $this->g01_telefono,
            'g01_created' => $this->g01_created,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'g01_nombre', $this->g01_nombre])
            ->andFilterWhere(['like', 'g01_paterno', $this->g01_paterno])
            ->andFilterWhere(['like', 'g01_materno', $this->g01_materno])
            ->andFilterWhere(['like', 'g01_domicilio', $this->g01_domicilio]);

        return $dataProvider;
    }
}
