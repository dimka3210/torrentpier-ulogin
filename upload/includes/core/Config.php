<?php

/**
 * User: dimka3210
 * Date: 15.08.2014
 * Time: 16:36
 */
class Config
{
    private static $config = array();
    private static $instance = null;

    /**
     * TODO: нужно перевести конфиг в формат для ООП. В текущем варианте это не красиво.
     */
    private function __construct()
    {
        global $bb_cfg, $page_cfg, $rating_limits, $tr_cfg;

        self::$config['bb_cfg'] = $bb_cfg;
        self::$config['page_cfg'] = $page_cfg;
        self::$config['rating_limits'] = $rating_limits;
        self::$config['tr_cfg'] = $tr_cfg;
    }

    /**
     * @return Config
     */
    public static function i()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $key
     * @param string $group
     * @return mixed
     */
    public function getParam($key, $group = 'bb_cfg')
    {
        return self::$config[$group][$key];
    }
}