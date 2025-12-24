<?php

declare(strict_types=1);

namespace App;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

readonly class Logger implements LoggerInterface
{
    public function __construct(
        private string $logFile = '/var/www/tmp/logs/app.log',
        private int    $minLevel = 100
    ){
        $dirName = dirname($this->logFile);

        if(!is_dir($dirName)) {
            mkdir($dirName, 0755, true);
        }
    }

    /**
     * @inheritDoc
     */
    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        if(!$this->isLevelEnabled($level)) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $logLine = "[$timestamp] [$level] {$this->interpolate($message, $context)}\n";

        error_log($logLine, 3, $this->logFile);
    }

    public function interpolate(string $message, array $context = array()): string
    {
        $replace = array();
        foreach($context as $key => $val) {
            if(!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }

    public function isLevelEnabled($level): bool
    {
        return match ($level) {
            LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR => true,
            LogLevel::WARNING => $this->minLevel <= 300,
            LogLevel::NOTICE => $this->minLevel <= 250,
            LogLevel::INFO => $this->minLevel <= 200,
            LogLevel::DEBUG => $this->minLevel <= 100,
            default => false
        };
    }
}