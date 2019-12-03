<?php

namespace Lonicera\core;

class DB
{
    private $dbLink;
    private $queryNum = 0;
    private static $instance;
    protected $PDOStatement;
    private $dbname;

    // 事务数
    protected $transTimes = 0;
    protected $bind = [];
    // 行数
    public $rows = 0;

    private function __construct($config)
    {
        $this->connect($config);
    }

    public static function getInstance($config)
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function connect($config)
    {
        try {
            $this->dbname = $config['dbname'];
            $dns = "{$config['db']}:host={$config['host']};dbname={$this->dbname}";
            $this->dbLink = new \PDO($dns, $config['username'], $config['password'], $config['param']);
        } catch (\PDOException $e) {
            throw $e;
        }

        return $this->dbLink;
    }

    public function query($sql, $bind = [], $fetchType = \PDO::FETCH_ASSOC)
    {
        if (!$this->dbLink) {
            throw new \Exception('数据库连接失败');
        }

        $this->PDOStatement = $this->dbLink->prepare($sql);
        $this->PDOStatement->execute($bind);
        $rel = $this->PDOStatement->fetchAll($fetchType);
        $this->rows = count($rel);

        return $rel;
    }

    public function execute($sql, $bind = [])
    {
        if (!$this->dbLink) {
            throw new \Exception('Failed to connect to database');
        }
        $this->PDOStatement = $this->dbLink->prepare($sql);
        $result = $this->PDOStatement->execute($bind);
        $this->rows = $this->PDOStatement->rowCount();

        return $result;
    }

    // 为了避免隐式提交事务，我们在每次创建事务的时候，都去判断是否已经存在事务
    // 如果存在就去创建一个 savepoint，而不是直接开启事务。这样就基本实现了事务的嵌套。
    public function startTrans()
    {
        ++$this->transTimes;
        if (1 == $this->transTimes) {
            // 不存在已创建的事务才开启新的事务
            $this->dbLink->beginTransaction();
        } else {
            // 创建一个 savepoint
            $this->dbLink->execute("SAVEPOINT tr{$this->transTimes}");
        }
    }

    public function commit()
    {
        if (1 == $this->transTimes) {
            $this->dbLink->commit();
        }

        --$this->transTimes;
    }

    public function rollback()
    {
        if (1 == $this->transTimes) {
            $this->dbLink->rollBack();
        } elseif ($this->transTimes > 1) {
            $this->dbLink->execute("ROLLBACK TO SAVEPOINT tr{$this->transTimes}");
        }
        $this->transTimes = max(0, $this->transTimes - 1);
    }

    public function getDbname()
    {
        return $this->dbname;
    }
}
