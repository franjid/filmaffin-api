<?php

namespace Component\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

trait LogTrait
{
    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * Get logger object
     *
     * @return LoggerInterface
     * @throws \RuntimeException
     */
    protected function getLogger()
    {
        if (!$this->logger)
        {
            throw new \RuntimeException('Logger not found');
        }

        return $this->logger;
    }

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Write log
     *
     * @param string $message
     * @param array $contextData
     * @param string $level RFC5452 logging levels (debug,info,notice,warning,error,critical,alert,emergency)
     *
     * @return void
     * @throws \RuntimeException
     */
    protected function writeLog($message, array $contextData = [], $level = LogLevel::INFO)
    {
        if (!method_exists($this->getLogger(), $level)) {
            throw new \RuntimeException('Method ' . $level . ' does not exit in LoggerInterface');
        }

        $data = $this->generateData($contextData, $level);

        $this->getLogger()->$level($message, $data);
    }

    /**
     * @param array $contextData
     * @param $level
     * @return array
     */
    protected function generateData(array $contextData, $level)
    {
        $data = $contextData;

        $importantLevels = [
            LogLevel::CRITICAL,
            LogLevel::ALERT,
            LogLevel::EMERGENCY
        ];

        if (in_array($level, $importantLevels))
        {
            $data = [
                'Data application' => $contextData,
                'Debug backtrace' => debug_backtrace()
            ];
        }

        return $data;
    }
}
