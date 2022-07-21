<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Clientes;
use common\models\Utilerias;

/**
 * ClientesSearch represents the model behind the search form of `app\models\Clientes`.
 */
class ClientesSearch extends Clientes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g02_id', 'g02_telefono', 'g01_id', 'g02_telefono_aval', 'g02_tipo', 'user_id','g02_status'], 'integer', 'message' => Yii::$app->params['error_numer']],
            [['g02_nombre', 'g02_paterno', 'g02_materno', 'g02_domicilio', 'g02_nombre_aval', 'g02_paterno_aval', 'g02_materno_aval', 'g02_domicilio_aval', 'g02_fecha_solicitud', 'g02_created'], 'safe'],
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
        $query = Clientes::find()->where(['g02_status' => Yii::$app->params['value_active']]);

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
            'g02_telefono' => $this->g02_telefono,
            'g02_id' => $this->g02_id,
            'g01_id' => $this->g01_id,
            'g02_fecha_solicitud' => $this->g02_fecha_solicitud,
            'g02_tipo' => $this->g02_tipo,
        ]);

        $query->andFilterWhere(['like', 'g02_nombre', $this->g02_nombre])
            ->andFilterWhere(['like', 'g02_paterno', $this->g02_paterno])
            ->andFilterWhere(['like', 'g02_materno', $this->g02_materno])
            ->andFilterWhere(['like', 'g02_domicilio', $this->g02_domicilio]);


        return $dataProvider;
    }

    public function searchPromotor($params, $g01_id)
    {
        $query = Clientes::find()->where(['g02_status' => Yii::$app->params['value_active'], 'g01_id' => $g01_id]);

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
            'g02_telefono' => $this->g02_telefono,
            'g02_tipo' => $this->g02_tipo,
        ]);

        $query->andFilterWhere(['like', 'g02_nombre', $this->g02_nombre])
            ->andFilterWhere(['like', 'g02_paterno', $this->g02_paterno])
            ->andFilterWhere(['like', 'g02_materno', $this->g02_materno])
            ->andFilterWhere(['like', 'g02_domicilio', $this->g02_domicilio])
            ->andFilterWhere(['like', 'g02_fecha_solicitud', Utilerias::convertirFecha($this->g02_fecha_solicitud)]);

        return $dataProvider;
    }
}
