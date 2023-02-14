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

namespace OCA\Tutorial4\Controller;

use OCA\Tutorial4\Service\PexelsService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\DataDownloadResponse;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

class PexelsController extends Controller {

	private PexelsService $pexelsService;
	private ?string $userId;

	public function __construct(string        $appName,
								IRequest      $request,
								PexelsService $pexelsService,
								?string       $userId)
	{
		parent::__construct($appName, $request);
		$this->pexelsService = $pexelsService;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 */
	public function getPhotoContent(int $photoId, string $size = 'original'): DataDisplayResponse {
		$photoResponse = $this->pexelsService->getPhotoContent($photoId, $size);
		if ($photoResponse !== null && isset($photoResponse['body'], $photoResponse['headers'])) {
			$response = new DataDisplayResponse(
				$photoResponse['body'],
				Http::STATUS_OK,
				['Content-Type' => $photoResponse['headers']['Content-Type'][0] ?? 'image/jpeg']
			);
			$response->cacheFor(60 * 60 * 24, false, true);
			return $response;
		}
		return new DataDisplayResponse('', Http::STATUS_BAD_REQUEST);
	}
}
