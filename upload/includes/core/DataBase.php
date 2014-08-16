<?php

/**
 * User: dimka3210
 * Date: 15.08.2014
 * Time: 16:24
 */
class DataBase
{
    private static $instance = null;
    private $masterParams = array();
    private $slaveParams = array();

    /** @var PDO */
    private $master = null;
    /** @var PDO */
    private $slave = null;

    private function __construct()
    {
        $this->masterParams = $this->loadMasterParams();
        $this->slaveParams = $this->loadSlaveParams();
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * @param bool $newInstance
     * @return DataBase
     */
    public static function i($newInstance = false)
    {
        if ($newInstance) {
            return new self();
        } else {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }
    }

    /**
     * @return array
     */
    private function loadMasterParams()
    {
        $dbParams = Config::i()->getParam('db');
        return $dbParams['master'];
    }

    /**
     * @return array
     */
    private function loadSlaveParams()
    {
        $dbParams = Config::i()->getParam('db');
        return $dbParams['slave'];
    }

    private function connect()
    {
        $masterDsn = $this->masterParams['dbms'] . ':host=' . $this->masterParams[0] . ';charset=' . $this->masterParams[4] . ';dbname=' . $this->masterParams[1];
        $this->master = new PDO($masterDsn, $this->masterParams[2], $this->masterParams[3]);

        if ($this->master->errorCode()) {
            throw new Exception('Failed to connect to database <master>: ' . print_r($this->master->errorInfo(), true));
        }

        // If connection slave
        if ($this->slaveParams) {
            $slaveDsn = $this->slaveParams['subd'] . ':host=' . $this->slaveParams[0] . ';charset=' . $this->slaveParams[4] . ';dbname=' . $this->slaveParams[1];
            $this->slave = new PDO($slaveDsn, $this->slaveParams[2], $this->slaveParams[3]);

            if ($this->slave->errorCode()) {
                throw new Exception('Failed to connect to database <slave>: ' . print_r($this->slave->errorInfo(), true));
            }
        }
    }

    /**
     * @param $sql string
     * @param array $params
     * @return PDOStatement
     */
    public function q($sql, $params = array())
    {
        $stmt = $this->master->prepare($sql);
        if ($params) {
            foreach ($params as $key => $param) {
                $type = PDO::PARAM_STR;

                $key = (is_int($key)) ? $key + 1 : $key;

                if (is_int($param)) {
                    $type = PDO::PARAM_INT;
                } elseif (is_bool($param)) {
                    $type = PDO::PARAM_BOOL;
                }

                $stmt->bindParam($key, $param, $type);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * @return array
     */
    public function getErrorInfo()
    {
        return $this->master->errorInfo();
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->master->errorCode();
    }

    private function disconnect()
    {
        // А нужно ли?
    }
}