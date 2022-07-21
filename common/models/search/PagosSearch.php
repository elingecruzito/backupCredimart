<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Pagos;

use common\models\Utilerias;
/**
 * PagosSearch represents the model behind the search form of `app\models\Pagos`.
 */
class PagosSearch extends Pagos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g04_id', 'g01_id', 'g02_id', 'g03_id', 'g04_cantidad', 'g04_semana', 'user_id','g04_status'], 'integer', 'message' => Yii::$app->params['error_numer']]],
            [['g04_fecha', 'g04_created'], 'safe'],
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
        
        $query = Pagos::find()->where(['g04_status' => Yii::$app->params['value_active']]);
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
            'g01_id' => $this->g01_id,
            'g02_id' => $this->g02_id,
            'g03_id' => $this->g03_id,
            'g04_cantidad' => $this->g04_cantidad,
            'g04_semana' => $this->g04_semana,
            'g04_created' => $this->g04_created,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'g04_fecha', Utilerias::convertirFecha($this->g04_fecha) ]);

        return $dataProvider;
    }

    public function searchPrestamos($params, $g03_id)
    {
        $query = Pagos::find()->where(['g04_status' => Yii::$app->params['value_active'], 'g03_id' => $g03_id]);

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
            'g01_id' => $this->g01_id,
            'g02_id' => $this->g02_id,
            'g04_cantidad' => $this->g04_cantidad,
            'g04_semana' => $this->g04_semana,
            'g04_created' => $this->g04_created,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'g04_fecha', Utilerias::convertirFecha($this->g04_fecha) ]);

        return $dataProvider;
    }
}
