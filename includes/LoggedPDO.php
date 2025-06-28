<?php
require_once __DIR__ . '/LoggedPDOStatement.php';

class LoggedPDO extends PDO
{
    protected $logFile;
    protected $loggingEnabled;

    public function __construct($dsn, $username, $password, $options = [])
    {
        parent::__construct($dsn, $username, $password, $options);

        $config = require __DIR__ . '/../config/.env.php';
        $this->logFile = __DIR__ . '/../query.log';
        $this->loggingEnabled = $config['LOG_QUERIES'] ?? false;
    }

    #[\ReturnTypeWillChange]
    public function prepare($statement, $options = [])
    {
        $this->log("PREPARE: $statement");
        $stmt = parent::prepare($statement, $options);
        return new LoggedPDOStatement($stmt, $this->logFile, $statement, $this->loggingEnabled);
    }

    #[\ReturnTypeWillChange]
    public function query($statement, ...$fetch_mode_args)
    {
        $this->log("QUERY: $statement");
        return parent::query($statement, ...$fetch_mode_args);
    }

    #[\ReturnTypeWillChange]
    public function exec($statement)
    {
        $this->log("EXEC: $statement");
        return parent::exec($statement);
    }

    protected function log($query)
    {
        if (!$this->loggingEnabled) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[$timestamp] $query\n", FILE_APPEND);
    }
}
