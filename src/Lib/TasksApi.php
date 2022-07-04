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

namespace GCP\Lib;

use GCP\Utils\GoogleCloudClients;
use Google\Cloud\Tasks\V2\HttpMethod;
use Google\Cloud\Tasks\V2\HttpRequest;
use Google\Cloud\Tasks\V2\Task;

class TasksApi
{
    /**
     * Google Project ID
     *
     * @var string
     */
    protected $projectId = '';

    /**
     * Queue name
     *
     * @var string
     */
    protected $queue = '';

    /**
     * Location name
     *
     * @var string
     */
    protected $location = '';

    protected $taskClient;

    /**
     * Constructor
     *
     * ### Available options:
     *
     * - `projectId` ID of the Google project.
     * - `queue` Name of the queue in Cloud Tasks.
     * - `location` Boolean indicating whether to use query logging.
     *
     * @param array<string, mixed> $options Options
     */
    public function __construct(array $options = [])
    {
        foreach (['projectId', 'queue', 'location'] as $key) {
            if (isset($options[$key])) {
                $this->{$key} = $options[$key];
            }
        }

        $this->taskClient = GoogleCloudClients::getTasksClient();
    }

    /**
     * Sets a Google project ID
     *
     * @param string $projectId
     * @return this
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Sets a queue name
     *
     * @param string $queueName
     * @return this
     */
    public function setQueue($queueName)
    {
        $this->queue = $queueName;

        return $this;
    }

    /**
     * Sets a location (region)
     *
     * @param string $locationName
     * @return this
     */
    public function setLocation($locationName)
    {
        $this->location = $locationName;

        return $this;
    }

    /**
     * Creates a task with Http Request handler
     *
     * #### Available options:
     *
     * - `headers` HTTP Headers in name and value form.
     *
     * @param string $url A full URL of task handler
     * @param mixed $payload Task payload data. It is applicable for POST/PUT requests only.
     * @param string $method A valid HTTP method e.g. POST, PUT
     * @param array<string, mixed> $options Options
     * @return \Google\Cloud\Tasks\V2\Task
     */
    public function createHttpTask($url, $payload, $method = 'POST', array $options = [])
    {
        $queueName = $this->taskClient->queueName($this->projectId, $this->location, $this->queue);

        // Create an Http Request Object.
        $httpRequest = new HttpRequest();

        // The full url path that the task request will be sent to.
        $httpRequest->setUrl($url);

        // POST is the default HTTP method, but any HTTP method can be used.
        $httpRequest->setHttpMethod(HttpMethod::value($method));

        // Setting a body value is only compatible with HTTP POST and PUT requests.
        if (isset($payload)) {
            $httpRequest->setBody($payload);
        }

        // Set headers if available
        if (isset($options['headers'])) {
            $httpRequest->setHeaders($options['headers']);
        }

        // Create a Cloud Task object.
        $task = new Task();
        $task->setHttpRequest($httpRequest);

        // Send request and print the task name.
        $response = $this->taskClient->createTask($queueName, $task);

        return $response;
    }
}
