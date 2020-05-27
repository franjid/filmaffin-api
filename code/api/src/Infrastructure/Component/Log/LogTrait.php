<?php

namespace App\Infrastructure\Component\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

trait LogTrait
{
    private LoggerInterface $logger;

    /**
     * @return LoggerInterface
     * @throws \RuntimeException
     */
    protected function getLogger(): LoggerInterface
    {
        if (!$this->logger) {
            throw new \RuntimeException('Logger not found');
        }

        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param string $message
     * @param array $contextData
     * @param string $level RFC5452 logging levels (debug,info,notice,warning,error,critical,alert,emergency)
     *
     * @return void
     * @throws \RuntimeException
     */
    protected function writeLog(
        string $message,
        array $contextData = [],
        string $level = LogLevel::INFO
    ): void
    {
        if (!method_exists($this->getLogger(), $level)) {
            throw new \RuntimeException('Method ' . $level . ' does not exit in LoggerInterface');
        }

        $data = $this->generateData($contextData, $level);

        $this->getLogger()->$level($message, $data);
    }

    protected function generateData(array $contextData, string $level): array
    {
        $data = $contextData;

        $importantLevels = [
            LogLevel::CRITICAL,
            LogLevel::ALERT,
            LogLevel::EMERGENCY
        ];

        if (in_array($level, $importantLevels, true)) {
            $data = [
                'Data application' => $contextData,
                'Debug backtrace' => debug_backtrace()
            ];
        }

        return $data;
    }
}
