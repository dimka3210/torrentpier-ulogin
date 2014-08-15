<?php

/**
 * User: d.larchikov
 * Date: 15.08.2014
 * Time: 16:24
 */
class DataBase
{
	private static $instance = null;
	private $connectionParams = array();

	private function __construct() {
		$this->connectionParams = $this->loadConnectionParams();
	}

	/**
	 * @param bool $newInstance
	 * @return DataBase
	 */
	public static function i($newInstance = false) {
		if ($newInstance) {
			return new self();
		} else {
			if (is_null(self::$instance)) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}

	private function loadConnectionParams() {
		return Config::i()->bb_cfg['db'];
	}
}