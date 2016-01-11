<?php

abstract class Table
{
	protected $db;
	
	public function __construct()
	{
		$this->db = DB::getInstance();
	}
	
	private function array_to_str($arr){
		$sql = '1=1';
		foreach ($arr as $key => $val) {
			$sql .= " and {$key} = '{$val}'";
		}
		return $sql;
	}

	public function get($primary_key){
		$sql = "select * from {$this->table_name} where {$this->primary_key} = {$primary_key}";
		return $this->db->get_one($sql);
	}

	public function exists($where){
		if(is_array($where)){
			$where = $this->array_to_str($where);
		}
		$sql = "select 1 from {$this->table_name} where {$where}";
		if($this->db->get_one($sql)) return true;
		return false;
	}

	public function fetchOne($where = "1=1", $fileds = "*"){
		if(is_array($where)){
			$where = $this->array_to_str($where);
		}

		if(is_array($fileds))
		{
			$fileds_string = implode(',', $fileds);
		}
		else
		{
			$fileds_string = $fileds;
		}
		$sql = "select {$fileds_string} from {$this->table_name} where {$where} limit 1";
		return $this->db->get_one($sql);		
	}

	public function fetchAll($where = "1=1", $fileds = "*")
	{
		if(is_array($where)){
			$where = $this->array_to_str($where);
		}

		if(is_array($fileds))
		{
			$fileds_string = implode(',', $fileds);
		}
		else
		{
			$fileds_string = $fileds;
		}
		$sql = "select {$fileds_string} from {$this->table_name} where {$where}";
		return $this->db->get_all($sql);
	}
	
	public function insert($data)
	{
		$this->db->insert($this->table_name, $data);
		return $this->db->insert_id();
	}
	
	public function update($data, $where)
	{
		if(is_array($where)){
			$where = $this->array_to_str($where);
		}
		$this->db->update($this->table_name, $data, $where);
	}
	
	public function delete($where)
	{
		if(is_array($where)){
			$where = $this->array_to_str($where);
		}
		$this->db->delete($this->table_name, $where);
	}
	
	public function sum($where = "1=1",$filed)
	{
		if(is_array($where)){
			$where = $this->array_to_str($where);
		}

		$sql = "select sum({$filed}) {$filed} from {$this->table_name} where {$where}";
		$rst = $this->db->get_one($sql);
		return $rst[$filed];
	}
}