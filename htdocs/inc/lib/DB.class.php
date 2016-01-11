<?php
/**
 * 数据库操作文件
 * @author skyworld<pgg200@qq.com>
 * @package inc.lib
 * @since 2013-12-10
 */

if(!defined('IN_SKY')) exit ('Access Denied.');

class DB{

	/**
	 * @param String $_adapter 数据适配器
     */
	private static $_adapter;
	
	/**
	 * @param Object $_instance 数据库的实例
	 */
	private static $_instance;
	
	/**
	 * @param Object $_conn 数据库的连接
	 */
	private static $_conn;
	
	/**
	 * 获取DB的实例 
	 * @param String $adapter 数据库适配器
	 * @return Object $_instance 数据库实例
	 */
	public static function getInstance($adapter = null)
	{
		if(empty($adapter))
		{
			$adapter = config::getDefaultAdapter();
		}
		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new self;
			self::$_adapter = $adapter;
			self::conn($adapter);
			return self::$_instance;
		}
		return self::$_instance;
	}
	
	/**
	 * 建立数据库的链接
	 *
	 * @author skyworld
     * @param String $adapter 数据库适配器
     * @return void
     **/
	public static function conn($adapter){
		$dbConfig = config::dbConfig($adapter);
		self::$_conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['password'],	$dbConfig['dbname'], $dbConfig['port']);
		if (mysqli_connect_errno()){
			Log::write("Error<sup>[1]</sup> :Cannot connect to database.",'ERROR');
		}
		if (!self::$_conn->set_charset($dbConfig['charset'])){
			Log::write("Error<sup>[2]</sup>: Character set not found.",'ERROR');
		}
	}

	/**
	 * @name  __destruct
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  Destructor of db class, close the _connection to database
     * @parameters  void
     * @return void
     **/
	public function __destruct(){
		//$this->close();
	}

	/**
	 * @name  close
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  close the _connection to database,if successed return true otherwise return false
     * @parameters  void
     * @return bool
     **/
	public function close(){
		return self::$_conn->close();
	}


	/**
	 * @name  free_result
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  free resourse
     * @parameters  the result of SQL, if successed return ture, otherwise return false
     * @return bool
     **/
	public function free_result($query){
		$query->free_result();
	}

	/**
	 * @name  query
	 * @author SkyWorld
	 * @date 2011-9-17
	 * @description  exective SQL sentence, if successed return true, otherwise return false
     * @parameters  the SQL sentence to be exectived
     * @return bool
     **/
	public function query($sql){
		//return $query = self::$_conn->query($sql);
		// following code just for testing while developing
		// echo $sql;
		Log::write($sql);
		if($query = self::$_conn->query($sql)) return $query;
		else
		{
			Log::write("<b>MySQL Error</b>: syntax error in sentence : {$sql}</br>");
			return false;
		}

	}


	/**
	 * @name  fetch_array
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  Return fetch result to an array,and than you can get the data by number index or string index
	 * 				 and at the same time the pointer refer to the next record
     * @parameters  retult of query
     * @return bool
     **/
	public function fetch_array($query, $result_type = MYSQLI_ASSOC){
		return $query->fetch_array($result_type);
	}

	/**
	 * @name  fetch_row
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  Return fetch result to an array,and than you can get the data by number index only
	 * 				 and at the same time the pointer refer to the next record
     * @parameters  retult of query
     * @return bool
     **/
	public function fetch_row($query, $result_type = MYSQLI_ASSOC){
		return $query->fetch_row($result_type);
	}

	/**
	 * @name  fetch_assoc
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  Return fetch result to an array,and than you can get the data by string index only
	 * 				 and at the same time the pointer refer to the next record
     * @parameters  retult of query
     * @return bool
     **/
	public function fetch_assoc($query,$result_type = MYSQLI_ASSOC){
		return $query->fetch_assoc($result_type);
	}

	/**
	 * @name  num_rows
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  Return the number of records
     * @parameters  retult of query
     * @return int
     **/
	public function num_rows($query){
		return $query->num_rows;
	}


	/**
	 * @name  insert_id
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  Return id of the last insertion
     * @parameters  void
     * @return int
     **/
	public function insert_id(){
		return self::$_conn->insert_id;
	}


	/**
	 * @name  get_one
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  Get one record and return the result in the form of array
	 * 				 you can get the details by the number index or string index
     * @parameters  SQL sentences
     * @return array
     **/
	public function get_one($sql){
		$arr = array();
		$result = $this->query($sql);
		$arr = $this->fetch_array($result);
		$this->free_result($result);
		return $arr;
	}

	/**
	 * @name  get_one
	 * @author SkyWorld
	 * @date 2011-1-17
	 * @description  Get all records and return the result in the form of array
	 * 				 you can get the details by the number index or string index
     * @parameters  SQL sentences
     * @return array
     **/
	public function get_all($sql){
		$arr = array();
		$result = $this->query($sql);
		while ($row = $this->fetch_array($result)){
			$arr[]= $row;
		}
		$this->free_result($result);
		return $arr;
	}
	
	
	public function insert($table_name, $data)
	{
		$fileds_array = array_keys($data);
		foreach($fileds_array as $key => $val)
		{
			$fileds_array[$key] = "`{$val}`";
		}
		$fileds_string = implode(',',$fileds_array);
		foreach($data as $key => $val)
		{
			$data[$key] = "'{$val}'";
		}
		$data_string = implode(',',$data);
		$sql = "insert into {$table_name} ({$fileds_string}) values ({$data_string})";
		Log::write('[INSERT LOG]:'.$sql);
		$this->query($sql);
	}
	
	public function update($table_name, $data, $where)
	{
		$update_array = array();
		foreach($data as $key => $val)
		{
			$update_array[]= (" {$key}='{$val}' ");
		}
		$update_sql = implode(',',$update_array);
		$sql = "update {$table_name} set {$update_sql} where {$where}";
		Log::write('[UPDATE LOG]:'.$sql);
		$this->query($sql);
	}
	
	public function delete($table_name, $where)
	{
		$sql = "delete from {$table_name} where {$where}";
		Log::write('[DELETE LOG]:'.$sql);
		$this->query($sql);
	}
}
// end off script