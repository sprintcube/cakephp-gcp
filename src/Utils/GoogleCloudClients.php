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

namespace GCP\Utils;

use Cake\Core\Configure;
use Google\Cloud\Tasks\V2\CloudTasksClient;
use Google\Cloud\Logging\LoggingClient;

/**
 * Utility class for getting different service instances
 *
 * @since 1.0.0
 */
class GoogleCloudClients
{

    /**
     * Returns CloudTasksClient
     *
     * @return Google\Cloud\Tasks\V2\CloudTasksClient
     */
    public static function getTasksClient()
    {
        return new CloudTasksClient(['credentialsConfig' => self::getCredentials()]);
    }

    /**
     * Returns LoggingClient
     *
     * @return Google\Cloud\Logging\LoggingClient
     */
    public static function getLoggingClient() {
        return new LoggingClient(self::getCredentials('keyFilePath'));
    }

    /**
     * Returns the credentials
     *
     * @param string $keyFileLabel
     * @return array
     */
    public static function getCredentials($keyFileLabel = 'keyFile')
    {
        $credentials = [];

        $projectId = Configure::read('GCP.projectId', null);
        $keyFilePath = Configure::read('GCP.keyFilePath', null);

        if ($projectId) {
            $credentials['projectId'] = $projectId;
        }

        if ($keyFilePath) {
            $credentials[$keyFileLabel] = $keyFilePath;
        }

        return $credentials;
    }
}
