<?php

/**
 * User: d.larchikov
 * Date: 15.08.2014
 * Time: 16:36
 */
class Config
{
	public $bb_cfg;
	private static $instance = null;

	private function __construct() {
		$bb_cfg = $page_cfg = $rating_limits = $tr_cfg = null;
		self::$config = new stdClass();

		include(realpath('.' . DIRECTORY_SEPARATOR . 'config.php'));

		$this->bb_cfg = $bb_cfg;
		$this->page_cfg = $page_cfg;
		$this->rating_limits = $rating_limits;
		$this->tr_cfg = $tr_cfg;
	}

	public static function i() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$config;
	}
}