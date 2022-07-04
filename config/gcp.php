<?php

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

return [
    'GCP' => [
        'projectId' => env('GCP_PROJECT_ID', null), // Default Project ID
        'keyFilePath' => env('GCP_APP_CREDENTIALS', null) // Path to json file
    ]
];
