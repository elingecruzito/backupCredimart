<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ClientesPagos;
use common\models\Utilerias;

/**
 * ClientesPagosSearch represents the model behind the search form of `app\models\ClientesPagos`.
 */
class ClientesPagosSearch extends ClientesPagos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g04_id', 'g02_id', 'g04_cantidad', 'g04_semana','g04_status','g03_id'], 'integer', 'message' => Yii::$app->params['error_numer'] ],
            [['g04_fecha'], 'safe'],
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
        $query = ClientesPagos::find()->where(['g04_status' => Yii::$app->params['value_active']]);

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
            'g04_id' => $this->g04_id,
            'g02_id' => $this->g02_id,
            'g04_cantidad' => $this->g04_cantidad,
            'g04_semana' => $this->g04_semana,
            'g04_status' => $this->g04_status,
            'g03_id' => $this->g03_id,
        ]);

        $query->andFilterWhere(['like', 'g04_fecha', Utilerias::convertirFecha($this->g04_fecha) ]);

        return $dataProvider;
    }

    public function searchPrestamos($params, $g03_id)
    {
        $query = ClientesPagos::find()->where(['g04_status' => Yii::$app->params['value_active'], 'g03_id' => $g03_id]);

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
            'g04_id' => $this->g04_id,
            'g02_id' => $this->g02_id,
            'g04_cantidad' => $this->g04_cantidad,
            'g04_semana' => $this->g04_semana,
            'g04_status' => $this->g04_status,
            'g03_id' => $this->g03_id,
        ]);

        $query->andFilterWhere(['like', 'g04_fecha', Utilerias::convertirFecha($this->g04_fecha) ]);

        return $dataProvider;
    }
}
