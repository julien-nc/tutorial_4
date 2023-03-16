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
		// we use this route to get the search result thumbnail and in the reference widget to get the image itself
		// this is a way to avoid allowing the page to access Pexels directly. We let the server get the image.
		['name' => 'pexels#getPhotoContent', 'url' => '/photos/{photoId}/{size}', 'verb' => 'GET'],
		// this route is used by the admin settings page to save the option values
		['name' => 'config#setAdminConfig', 'url' => '/admin-config', 'verb' => 'PUT'],
	],
];
