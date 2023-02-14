<?php
/**
 * Nextcloud - Tutorial4
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 * @copyright Julien Veyssier 2023
 */

return [
	'routes' => [
		['name' => 'pexels#getPhotoContent', 'url' => '/photos/{photoId}/{size}', 'verb' => 'GET'],
	],
];
