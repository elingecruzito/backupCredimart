<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ReportePrestamos;
use common\models\Utilerias;

/**
 * ReportePrestamosSearch represents the model behind the search form of `common\models\ReportePrestamos`.
 */
class ReportePrestamosSearch extends ReportePrestamos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g03_id', 'g03_estado', 'g03_total', 'g02_tipo', 'g01_id', 'g02_id'], 'integer'],
            [['g01_nombre', 'g02_nombre', 'g03_fecha'], 'safe'],
            [['g04_cantidad'], 'number'],
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
        $query = ReportePrestamos::find();

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
            'g03_estado' => $this->g03_estado,
            //'g03_fecha' => $this->g03_fecha,
            'g03_total' => $this->g03_total,
            'g04_cantidad' => $this->g04_cantidad,
            'g02_tipo' => $this->g02_tipo,
            'g01_id' => $this->g01_id, 
            'g02_id' => $this->g02_id,
        ]);

        $query->andFilterWhere(['like', 'g03_fecha', Utilerias::convertirFecha($this->g03_fecha) ]);

        return $dataProvider;
    }
}
