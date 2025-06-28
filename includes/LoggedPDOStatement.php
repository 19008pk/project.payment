<?php

class LoggedPDOStatement
{
    private PDOStatement $stmt;
    private string $logFile;
    private string $query;
    private bool $loggingEnabled;

    public function __construct(PDOStatement $stmt, string $logFile, string $query, bool $loggingEnabled)
    {
        $this->stmt = $stmt;
        $this->logFile = $logFile;
        $this->query = $query;
        $this->loggingEnabled = $loggingEnabled;
    }

    #[\ReturnTypeWillChange]
    public function execute($params = null)
    {
        if ($this->loggingEnabled) {
            $paramText = $this->formatParams($params);
            $this->log("EXECUTE: {$this->query} | PARAMS: $paramText");
        }

        return $this->stmt->execute($params);
    }

    private function formatParams($params): string
    {
        return json_encode($params ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[$timestamp] $message\n", FILE_APPEND);
    }

    #[\ReturnTypeWillChange]
    public function __call($method, $args)
    {
        return call_user_func_array([$this->stmt, $method], $args);
    }
}
