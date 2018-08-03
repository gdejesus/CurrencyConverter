<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_value_model extends CI_Model
{
	public $timestamp;
	public $value;

  /*
    * Metodo que setea las propiedades a partir de un registro
  */

  function init($row, $permissions = false)
	{
		$this->timestamp= $row->timestamp;
		$this->value= $row->value;
	}

  /*
    * Metodo que devuelve un registro por id
  */
	function get_single($id_currency)
	{
		$result= array();
		$this->db->select('*');
		$this->db->from('currency_value v');
		$this->db->where('id_currency', $id_currency);
    $this->db->order_by('v.timestamp', 'desc');
		//$this->db->limit(1);
		$query=$this->db->get();
		if($query->num_rows() > 0)
    {
			$query_result=$query->result();
			$new_object= new self();
			$new_object->init($query_result[0]);
			return  $new_object;
		}
		else
    {
			return false;
		}
  }

  /*
    * Método que devuelve todos los registros
  */
	function get_all($id_currency)
	{
		$result= array();
    $this->db->select('*');
		$this->db->from('currency_value v');
		$this->db->where('id_currency', $id_currency);
    $this->db->order_by('v.timestamp', 'desc');

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
  function insert($id_currency)
	{
		$this->db->start_cache();
    $this->db->set('id_currency', $id_currency);
		$this->db->set('value', $this->value);
    $this->db->set('timestamp', $this->timestamp);
		$this->db->insert('currency_value');
		$this->db->stop_cache();
		$this->db->flush_cache();
	}

}
?>
