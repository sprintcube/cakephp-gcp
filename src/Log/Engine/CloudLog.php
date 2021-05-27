<?php

namespace GCP\Log\Engine;

use Cake\Core\Configure;
use Cake\Log\Engine\BaseLog;
use Google\Cloud\Logging\LoggingClient;
use InvalidArgumentException;

class CloudLog extends BaseLog
{

    /**
     * PSR-3-compatible logger
     *
     * @var \Google\Cloud\Logging\PsrLogger
     */
    public $logger;

    /**
     * __construct method
     * 
     * Constructs a new Google Cloud Logger.
     *
     * @param array $options Configuration array
     */
    public function __construct($options = [])
    {
        parent::__construct($options);

        $projectId = $this->getConfig('projectId');
        if (empty($projectId)) {
            $projectId = Configure::read('GCP.projectId');
            if (empty($projectId)) {
                throw new InvalidArgumentException('You must set `projectId` to use Cloud Logging');
            }
        }

        $keyFilePath = $this->getConfig('keyFilePath');
        if (empty($keyFilePath)) {
            $keyFilePath = Configure::read('GCP.keyFilePath');
        }

        $logginClient = new LoggingClient([
            'projectId' => $projectId,
            'keyFilePath' => !empty($keyFilePath) ? $keyFilePath : '',
        ]);
        $this->logger = $logginClient->psrLogger('app', ['batchEnabled' => true]);
    }

    /**
     * Writes a message to Google Cloud Logging
     *
     * @param mixed $level The severity level of log you are making.
     * @param string $message The message you want to log.
     * @param array $context Additional information about the logged message
     * @return void
     * @see Cake\Log\Log::$_levels
     */
    public function log($level, $message, array $context = [])
    {
        return $this->logger->{$level}($message, $context);
    }
}
