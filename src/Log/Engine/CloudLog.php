<?php

declare(strict_types=1);

/**
 * GCP Plugin for CakePHP
 * Copyright (c) SprintCube (https://www.sprintcube.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.md
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) SprintCube (https://www.sprintcube.com)
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://github.com/sprintcube/cakephp-gcp
 * @since     1.0.0
 */

namespace GCP\Log\Engine;

use Cake\Log\Engine\BaseLog;
use GCP\Utils\GoogleCloudClients;

/**
 * Writes logs to Google Stackdriver Logging.
 */
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
        $logginClient = GoogleCloudClients::getLoggingClient();
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
