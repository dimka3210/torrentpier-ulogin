<?php

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;

class Db {

	private $_database = null;
	private $_adapter = null;
	private $_sql = null;
	private static $db = 'db1';

	private function __construct($db) {
		$this->_database = $db;
		$this->_sql = new Sql($this->connect());
		
	}
	
	private function connect() {
		return new Adapter($this->_database);	
	}
	
	public function select() {
		return $this->_sql->select();
	}
	
	public function insert() {
		return $this->_sql->insert();
	}
	
	public function update() {
		return $this->_sql->update();
	}
	
	public function delete() {
		return $this->_sql->delete();
	}
	
	public function rowSet($q) {
		if(!is_object($q)) {
			throw new Exception("It's not object");
		}
		$result = new ResultSet();
		$result->initialize($this->result($q));
		
		return $result;
	}
	
	public function row($q) {
		if(!is_object($q)) {
			throw new Exception("It's not object");
		}	
		$result = $this->result($q);
		
		return $result->current();
		
	}
	
	private function result($q) {
		$string = $this->_sql->prepareStatementForSqlObject($q);
		
		return $string->execute();	
	}
	
	public static function q($database = false) {
	
		global $bb_cfg;
		
		$database = ($database) ? $database : self::$db;

		$database = $bb_cfg['connect'][$database];
		$class = __CLASS__;

		return new $class($database);
	}
	
}