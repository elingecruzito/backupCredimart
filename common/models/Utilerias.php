<?php
namespace common\models;

use Yii;
/**
* 
*/
class Utilerias {

	const DIA_VIERNES = 5;
	const DIA_SABADO = 6;
	
	//convierte dd/mm/yyyy -> yyyy-mm-dd
	public static function convertirFecha($date){
		if($date == "" || $date == null)
			return "";
		
		$fecha = explode('/', $date);
		return $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
	}

	//convierte yyyy-mm-dd -> dd/mm/yyyy
	public static function invertirFecha($date){
		$fecha = explode('-', $date);
		return $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
	}

	// yyyy-mm-dd hh:mm:ss -> dd/mm/yyyy
	public static function convertTimeStampToFecha($timestamp){
		$fecha = explode(" ", $timestamp);
		return Utilerias::invertirFecha($fecha[0]);
	}

	public static function formatMoney($cantidad){
		return "$" . number_format($cantidad);
	}

	public static function getTimeFromTimestamp($date){
		$fecha = explode(' ', $date);
		return $fecha[1];
	}

	public static function getRandomString(){
    	return Yii::$app->security->generateRandomString() . '_' . time();
    }

    public static function getDayOfWeek($date){
    	$arraydays = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
    	$day = date('w', strtotime($date));
    	return $arraydays[$day];
    }

    public static function getValidPayDay($date){

    	$day = date('w', strtotime($date));

    	if($day == Utilerias::DIA_VIERNES || $day == Utilerias::DIA_SABADO){
    		return true;
    	}

    	return false;
    }

    public static function getCorteSemanas($date){
    	return Utilerias::invertirFecha(date('Y-m-d', strtotime($date . ' +14 week')));
    }

    public static function generateQueryBitacora($model, $table){

        return json_encode($model->attributes);
    }

    public static function generateQuery($json, $table){

        $data = json_decode($json, true);
        $keys = array_keys($data);

        $txt = "UPDATE " . $table . " SET ";
        for ($x = 1 ; $x < count($keys) ; $x++) {
            $txt .= $table . "." . $keys[$x] . " = " . (is_numeric($data[$keys[$x]]) ? $data[$keys[$x]] : "'". $data[$keys[$x]] ."'");
            if($x != count($keys) - 1)
                $txt .= ",";
            $txt .= " ";
        }

        $txt .= "WHERE " . $keys[0] . " = " . $data[$keys[0]] . "; ";

        return $txt;
    }

    public static function getLabelModel($table, $key){
        $array = [];

        switch ($table) {
            case Promotores::tableName(): 
                $array = [
                    'g01_id' => 'ID',
                    'g01_nombre' => 'Nombre(s)',
                    'g01_paterno' => 'Apellido parterno',
                    'g01_materno' => 'Apellido materno',
                    'g01_domicilio' => 'Domicilio',
                    'g01_telefono' => 'Telefono',
                    'g01_created' => 'Fecha alta',
                    'user_id' => 'Usuario alta',
                    'g01_status' => 'Status'
                ]; 
            break;
            case Clientes::tableName(): 
                $array = [
                    'g02_id' => 'ID',
                    'g02_nombre' => 'Nombre (s)',
                    'g02_paterno' => 'Apellido paterno',
                    'g02_materno' => 'Apellido materno',
                    'g02_img' => 'Foto',
                    'file' => 'Foto',
                    'g02_domicilio' => 'Domicilio ',
                    'g02_telefono' => 'Telefono',
                    'g02_maximo' => 'Prestamo maximo',
                    'g01_id' => 'Promotor',
                    'g02_nombre_aval' => 'Nombre(s) del aval',
                    'g02_paterno_aval' => 'Apellido paterno del aval',
                    'g02_materno_aval' => 'Apellido materno del aval',
                    'g02_domicilio_aval' => 'Direccion del aval',
                    'g02_telefono_aval' => 'Telefono del aval',
                    'g02_fecha_solicitud' => 'Fecha de solicitud',
                    'g02_tipo' => 'Tipo cliente',
                    'g02_created' => 'Fecha alta',
                    'user_id' => 'Usuario alta',
                    'g02_status' => 'Status',
                ]; 
            break;
            case Prestamos::tableName(): 
                $array = [
                    'g03_id' => 'ID',
                    'g02_id' => 'Cliente',
                    'g03_monto' => 'Prestamo',
                    'g03_abono' => 'Abono',
                    'g03_total' => 'Total',
                    'totalPagos' => 'Total Pagado',
                    'g03_fecha' => 'Fecha prestamo',
                    'g03_estado' => 'Estado',
                    'g03_created' => 'Fecha alta',
                    'user_id' => 'Usuario alta',
                    'g03_status' => 'Status'
                ]; 
            break;
            case Pagos::tableName(): 
                $array = [
                    'g04_id' => 'ID',
                    'g01_id' => 'Promotor',
                    'g02_id' => 'Cliente',
                    'g03_id' => 'ID prestamo',
                    'g04_cantidad' => 'Monto',
                    'g04_fecha' => 'Fecha pago',
                    'g04_semana' => 'Semana',
                    'g04_created' => 'Fecha alta',
                    'user_id' => 'Usuario alta',
                    'g04_status' => 'Status',
                ]; 
            break;
        }

        return $array[$key];
    }

    public static function getFechaEnRangoSemana($today, $date){

        $estaEnRango = false;
        $dayIndex = date('w', strtotime($today));
        $firstDate = date("d-m-Y",strtotime($today."- " . $dayIndex . " days"));
        $lastDay = date("d-m-Y",strtotime($today."+ " . (6 - $dayIndex ) . " days"));

        if(strtotime($date) >= strtotime($firstDate) && strtotime($date) <= strtotime($lastDay)){
            $estaEnRango = true;
        }
        
        return $estaEnRango;
    }
}