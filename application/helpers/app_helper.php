<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/Argentina/Buenos_Aires');
/*
	* Funciones genéricas y reutilizables
*/
/*
	* Esta función retorna el valor de una variable de configuración
  * Debe estar definida en config/app.php
*/
function app_config($key){
	get_instance()->load->config('app');
	return get_instance()->config->item($key);
}

/*
	* Esta función permite cargar un modelo desde fuera de los controladores
	* Es utilizada por ejemplo por dwar_tabstrip
	* Para cargar la vista en tabs que no usan AJAX
*/
if(!function_exists('model_loader')){
  function model_loader($model){
    $CI =& get_instance();
    $CI->load->model($model);
    return $CI->$model;
  }
}

/*
	* Esta función permite cargar una vista desde fuera de los controladores
	* Es utilizada por ejemplo por dwar_tabstrip
	* Para cargar la vista en tabs que no usan AJAX
*/
if(!function_exists('view_loader')){
  function view_loader($view, $vars=array(), $output = false){
    $CI = &get_instance();
    return $CI->load->view($view, $vars, $output);
  }
}

/*
	* Retorna con formato especificado la fecha y hora recibida (Argentina)
*/
if (!function_exists('date_f'))
{
	function date_f($date, $format='d/m H:i')
  {
		date_default_timezone_set('America/Argentina/Buenos_Aires');
    if ($date)
    {
		    return date($format, strtotime($date));
    }
    else
      {
        return null;
      }
    }
 }

 /*
 	* Retorna la fecha y hora actual (Argentina)
 */
 if (!function_exists('actual_date')){
 	function actual_date($format='Y/m/d H:i:s'){
 		date_default_timezone_set('America/Argentina/Buenos_Aires');
 		return date($format, time());
 	}
 }

 if (!function_exists('date_f'))
 {
 	function date_f($date, $format='d/m H:i')
   {
 		date_default_timezone_set('America/Argentina/Buenos_Aires');
     if ($date)
     {
 		    return date($format, strtotime($date));
     }
     else
       {
         return null;
       }
     }
  }



/*
	* Retorna la fecha y hora actual (Argentina)
*/
if (!function_exists('reformat_date')){
	function reformat_date($date, $a, $b){
		//$parts = str_split(' ', $date);
		//$date = ($date) . '<br>';
		$p = explode('/', $date);
		$date = $p[2] . '-' . $p[1] . '-' . $p[0];
		echo $date . '<br>';
		/*$date = DateTime::createFromFormat($a , $date);
		$date = $date->format( $b );
		return $date;/**/
		return $date;
	}
}

/*
  * Retorna la fecha hora actual menos el intervalo indicado
*/
if (!function_exists('actual_date_sub')){
	function date_sub_interval($date, $interval, $format='Y/m/d H:i:s'){
    $result= new DateTime($date);
    $result->sub(new DateInterval($interval));
    return $result->format($format);
	}
}

/*
  * Retorna la fecha hora recibida mas el intervalo indicado
*/
if (!function_exists('date_add_interval')){
	function date_add_interval($date, $interval, $format='Y/m/d H:i:s'){
    $result= new DateTime($date);
    $result->add(new DateInterval($interval));
    return $result->format($format);
	}
}

/*
	* Retorna el nombre en español de un mes pasado como entero
*/
if( ! function_exists('get_month_name')) {
	function get_month_name($index){
		switch ($index) {
			case 1: return 'Enero';
			case 2: return 'Febrero';
			case 3: return 'Marzo';
			case 4: return 'Abril';
			case 5: return 'Mayo';
			case 6: return 'Junio';
			case 7: return 'Julio';
			case 8: return 'Agosto';
			case 9: return 'Septiembre';
			case 10: return 'Octubre';
			case 11: return 'Noviembre';
			default: return 'Diciembre';
		}
	}
}

/*
	* Función que dibuja en pantall un tabstrip
*/
if (!function_exists('draw_tabstrip')){
	function draw_tabstrip($id, $tabs){
		function get_link($tab)
	  {
	    //esta función ahorra el desprolijo  y confuso trabajo de armar el tab de cada tab del tabstrip
	    $str = $tab->active ? '<li class="active">' : '<li>';
	    if($tab->url)
	      $str.= '<a href="#'. $tab->box . $tab->position .'" data-toggle="tab_ajax" data-url="'. $tab->url  .'">' . $tab->title . '</a>';
			else
	    	$str.= '<a href="#'. $tab->box . $tab->position .'" data-toggle="tab">' . $tab->title . '</a>';
	    $str .= '</li>';
			return $str;
	  }

		function get_content($tab)
		{
			//esta función ahorra el desprolijo  y confuso trabajo de armar el contenido de cada tab del tabstrip
			$str= '';
			if($tab->active)
				$str= '<div class="tab-pane active" id="'. $tab->box . $tab->position .'">';
			else
				$str= '<div class="tab-pane" id="'. $tab->box . $tab->position .'">';
		  if(!$tab->url)
				$str.= view_loader('commons/unsolved', array(), TRUE);
			$str.='</div>';
			return $str;
		}
		echo '<ul id="'. $id .'" class="nav nav-tabs">';
		$count=0;
		foreach($tabs as $tab) {
			$count++;
			echo get_link($tab, $count, $count==1);
		}
		echo '</ul>';
		echo '<div class="tab-content">';
		foreach($tabs as $tab) {
			echo get_content($tab);
		}
		echo '</div>';
	}
}

if (!function_exists('utf8ize'))
{
	function utf8ize($d)
	{
		 if (is_array($d))
		 {
				 foreach ($d as $k => $v)
				 {
						 $d[$k] = utf8ize($v);
				 }
		 } else if (is_string ($d)) {
				 return utf8_encode($d);
		 }
		 return $d;
	 }
 }

if (!function_exists('debug_to_console'))
{
 function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
	}
}

if (!function_exists('getFechaPrimerHoraDelDia'))
{
	function getFechaPrimerHoraDelDia($fecha)
	{

	    $fecha=str_replace('/','-',$fecha);
		$resultado = new DateTime($fecha);
		$resultado->setTime(00,00);
		return $resultado;
	}
}

if (!function_exists('getFechaUltimaHoraDelDia'))
{
	function getFechaUltimaHoraDelDia($fecha)
	{
		$fecha=str_replace('/','-',$fecha);
		$resultado = new DateTime($fecha);
		$resultado->setTime(23,59);
		return $resultado;
	}
}

if (!function_exists('formatDateStringForGrid'))
{
	function formatDateStringForGrid($date)
	{
		$resultado = new DateTime($date);
		return $resultado->format('d/m/Y H:i');
	}
}

if (!function_exists('decodeNumber'))
{
 function decodeNumber($number)
 {
	 $original= $number;
	 $number = urldecode($number);
	 if($original[0]=="+") $number[0]="+";

	 return $number;
 }
}
