<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_model extends CI_Model
{
	public $id_currency;
	public $name;
  public $symbol;

  /*
    * Metodo que setea las propiedades a partir de un registro
  */

  function init($row)
	{
		$this->id_currency= $row->id_currency;
    $this->name= $row->name;
		$this->symbol= $row->symbol;
	}

  /*
    * Metodo que devuelve un registro por id
  */
	function get_single($id_currency)
	{
		$result= array();
		$this->db->select('*');
		$this->db->from('currency c');
		$this->db->where('id_currency', $id_currency);
		//$this->db->limit(1);
		$query=$this->db->get();
		if($query->num_rows() == 1){
			$query_result=$query->result();
			$new_object= new self();
			$new_object->init($query_result[0]);
			return  $new_object;
		}
		else{
			return false;
		}
  }

  /*
    * Método que devuelve todos los registros
  */
	function get_all()
	{
		$result= array();
		$this->db->select('*');
		$this->db->from('currency c');
		$this->db->order_by('c.name');
		$query=$this->db->get();
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$new_object= new self();
				$new_object->init($row);
				$result[] =  $new_object;
			}
		}
		return $result;
	}

  /*
    * Método para insertar un registro
  */
  function insert()
	{
		$this->db->set('id_currency', $this->id_currency);
		$this->db->set('name', $this->name);
		$this->db->set('symbol', $this->symbol);
		$this->db->insert('currency');
	}

	function get_last_value()
	{
		$this->load->model('Currency_value_model');
		return $this->Currency_value_model->get_single($this->id_currency);
	}

	function get_historical()
	{
		$this->load->model('Currency_value_model');
		return $this->Currency_value_model->get_all($this->id_currency);
	}

	function get_historical_conversion($to)
	{
		$result= array();
		$this->db->select('(v1.value / v2.value) value, v1.timestamp');
		$this->db->from('currency_value v1');
		$this->db->join('currency_value v2', 'v1.timestamp = v2.timestamp');
		$this->db->where('v1.id_currency', $this->id_currency);
		$this->db->where('v2.id_currency', $to->id_currency);
		$query=$this->db->get();
		return $query->result();
	}

	function convert_amount($from, $to, $amount, $include_symbol = false)
	{
		$fromCurrency = $this->get_single($from);
		$toCurrency = $this->get_single($to);
		$result = ($fromCurrency->get_last_value()->value * $amount) / $toCurrency->get_last_value()->value;
		$result = number_format((float)$result,2,'.','');
		if ($include_symbol)
		{
			$symbol = $toCurrency->symbol;
			if (!isset($symbol) || trim($symbol) === '')
			{
				$result.=" ".$toCurrency->id_currency;
			}
			else
			{
				$result.=" ".$toCurrency->symbol;
			}

		}
		return $result;
	}

	function update_value($new_value, $timestamp)
	{
		$this->load->model('Currency_value_model');
		$this->Currency_value_model->value=$new_value;
		$this->Currency_value_model->timestamp=$timestamp;
		$this->Currency_value_model->insert($this->id_currency);
	}

	function get_marquee()
	{
		$result=" - ";
		$currencies = $this->get_all();
		foreach ($currencies as $currency)
		{
			if ($currency->id_currency != 'ARS')
			{
				$result.=$currency->name . ": " . $this->convert_amount($currency->id_currency,"ARS",1) . " ARS - ";
			}
		}
		return $result;
	}
}
?>
