<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Movimientos;

use common\models\Utilerias;

/**
 * MovimientosSearch represents the model behind the search form of `app\models\Movimientos`.
 */
class MovimientosSearch extends Movimientos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c01_id', 'c01_tipo', 'c01_id_tabla', 'user_id'], 'integer'],
            [['c01_tabla', 'c01_date'], 'safe'],
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
        $query = Movimientos::find()->orderBy(['c01_date' => SORT_DESC]);

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
            'c01_id' => $this->c01_id,
            'c01_tipo' => $this->c01_tipo,
            'c01_id_tabla' => $this->c01_id_tabla,
            'user_id' => $this->user_id,
            //'c01_date' => $this->c01_date,
        ]);

        $query->andFilterWhere(['like', 'c01_date', Utilerias::convertirFecha($this->c01_date) ]);

        $query->andFilterWhere(['like', 'c01_tabla', $this->c01_tabla]);

        return $dataProvider;
    }
}
