<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Prestamos;

use common\models\Utilerias;

/**
 * PrestamosSearch represents the model behind the search form of `app\models\Prestamos`.
 */
class PrestamosSearch extends Prestamos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g03_id', 'g02_id', 'g03_monto', 'g03_abono', 'g03_total', 'g03_estado', 'user_id', 'g03_status'], 'integer'],
            [['g03_fecha', 'g03_created'], 'safe'],
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
        $query = Prestamos::find()
                    ->where(['g03_status' => Yii::$app->params['value_active']])
                    ->orderBy(['g03_fecha' => SORT_DESC]);

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
            'g03_id' => $this->g03_id,
            'g02_id' => $this->g02_id,
            'g03_monto' => $this->g03_monto,
            'g03_abono' => $this->g03_abono,
            'g03_total' => $this->g03_total,
            'g03_estado' => $this->g03_estado,
            'g03_created' => $this->g03_created,
            'user_id' => $this->user_id,

        ]);

        $query->andFilterWhere(['like', 'g03_fecha', Utilerias::convertirFecha($this->g03_fecha) ]);

        return $dataProvider;
    }


    public function searchClientes($params,$g02_id)
    {
        $query = Prestamos::find()
                    ->where(['g03_status' => Yii::$app->params['value_active'], 'g02_id' => $g02_id])
                    ->orderBy(['g03_fecha' => SORT_DESC]);

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
            'g03_id' => $this->g03_id,
            'g03_monto' => $this->g03_monto,
            'g03_abono' => $this->g03_abono,
            'g03_total' => $this->g03_total,
            'g03_estado' => $this->g03_estado,
            'g03_created' => $this->g03_created,
            'user_id' => $this->user_id,

        ]);

        $query->andFilterWhere(['like', 'g03_fecha', Utilerias::convertirFecha($this->g03_fecha) ]);

        return $dataProvider;
    }
}
