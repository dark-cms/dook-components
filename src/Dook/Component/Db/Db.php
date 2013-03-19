<?php

namespace Dook\Component\Db;

class Db {

    /**
     * PDO instance
     * @var \PDO
     */
    protected $_pdo = null;

    /**
     * Number of affected rows
     * @var int
     */
    protected $_affected = 0;

    /**
     * Constructor
     * @param \PDO|\Closure|null $pdo PDO instance, or a Closure (lazy connection) or null to set the connection later
     */
    public function __construct($pdo = null)
    {
        $this->setPdo($pdo);
    }

    /**
     * Set a PDO connection
     * @param \PDO|\Closure $pdo PDO instance or a Closure that will be executed when getting the connection on first use
     * @return \Dook\Component\Db\Db
     */
    public function setPdo($pdo)
    {
        $this->_pdo = $pdo;
        return $this;
    }

    /**
     * Get the PDO connection
     * @return \PDO
     * @throws \ErrorException If unable to acquire a database connection
     */
    public function getPdo()
    {
        if ($this->_pdo instanceof \PDO) {
            return $this->_pdo;
        }

        if ($this->_pdo instanceof \Closure) {
            $closure    = $this->_pdo;
            $this->_pdo = $closure();
            return $this->_pdo;
        }

        throw new \ErrorException('Database connection not available');
    }

    /**
     * Prepare and execute a SQL statement
     * @param mixed $sql If it's an array will be joined using a space, if it's an object it must implement __toString()
     * @param array $params Statement parameters
     * @return \PDOStatement
     */
    protected function prepareAndExecute($sql, array $params = [])
    {
        $cmd = null;

        switch (gettype($sql)) {
            case 'array':
                $cmd    = implode(' ', $sql);
                break;
            case 'object':
                $cmd    = (string) $sql;
                $params = (method_exists($sql, 'getParams')) ? $sql->getParams() : $params;
                break;
            case 'string':
            default:
                $cmd    = $sql;
        }

        $stmt = $this->getPdo()->prepare($cmd);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch all rows
     * @param mixed $sql SQL SELECT (if it's an array will be joined using a space, if it's an object it must implement __toString())
     * @param array $params Query parameters
     * @param string $class Class name to be used on the results (defaults to \stdClass)
     * @param string $ctor Class constructor parameters
     * @return array
     */
    public function all($sql, array $params = [], $class = '\stdClass', array $ctor = [])
    {
        $stmt = $this->prepareAndExecute($sql, $params);
        return ($stmt->columnCount() > 0) ? $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class, $ctor) : [];
    }

    /**
     * Fetch a single row
     * @param mixed $sql SQL SELECT (if it's an array will be joined using a space, if it's an object it must implement __toString())
     * @param array $params Query parameters
     * @param string $class Class name to be used on the results (defaults to \stdClass)
     * @param array $ctor Class constructor parameters
     * @return object Returns an instance of the named $class value
     */
    public function row($sql, array $params = [], $class = '\stdClass', array $ctor = [])
    {
        $stmt = $this->prepareAndExecute($sql, $params);

        if ($stmt->columnCount() > 0) {
            return $stmt->fetch(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class, $ctor);
        }

        $ref = new \ReflectionClass($class);
        return $ref->newInstanceArgs($ctor);
    }

    /**
     * Fetch a single value (value of the first column of the first row)
     * @param mixed $sql SQL SELECT (if it's an array will be joined using a space, if it's an object it must implement __toString())
     * @param array $params Query parameters
     * @return mixed Null if nothing found
     */
    public function one($sql, array $params = [])
    {
        $stmt = $this->prepareAndExecute($sql, $params);
        return ($stmt->columnCount() > 0) ? $stmt->fetchColumn(0) : null;
    }

    /**
     * Execute a SQL Command except SELECT statements
     * @param type $sql SQL command except SELECT (if it's an array will be joined using a space, if it's an object it must implement __toString())
     * @param array $params SQL command parameters
     * @return \Dook\Component\Db\Db
     */
    public function execute($sql, array $params = [])
    {
        $stmt            = $this->prepareAndExecute($sql, $params);
        $this->_affected = $stmt->rowCount();
        return $this;
    }

    /**
     * Get the number of affected rows from the last execute() call
     * @return int
     */
    public function getAffected()
    {
        return $this->_affected;
    }

    /**
     * Begin a transaction (only if there's no open transactions)
     * @return \Dook\Component\Db\Db
     */
    public function begin()
    {
        if (!$this->getPdo()->inTransaction()) {
            $this->getPdo()->beginTransaction();
        }

        return $this;
    }

    /**
     * Commit a transaction (only if any transaction is open)
     * @return \Dook\Component\Db\Db
     */
    public function commit()
    {
        if ($this->getPdo()->inTransaction()) {
            $this->getPdo()->commit();
        }

        return $this;
    }

    /**
     * Rollback a transaction (only if any transaction is open)
     * @return \Dook\Component\Db\Db
     */
    public function rollback()
    {
        if ($this->getPdo()->inTransaction()) {
            $this->getPdo()->rollBack();
        }

        return $this;
    }

}