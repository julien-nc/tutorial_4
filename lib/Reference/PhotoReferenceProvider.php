<?php
/**
 * @copyright Copyright (c) 2023 Julien Veyssier <julien-nc@posteo.net>
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace OCA\Tutorial4\Reference;

use OCP\Collaboration\Reference\ADiscoverableReferenceProvider;
use OCP\Collaboration\Reference\ISearchableReferenceProvider;
use OCP\Collaboration\Reference\Reference;
use OC\Collaboration\Reference\ReferenceManager;
use OCA\Tutorial4\AppInfo\Application;
use OCA\Tutorial4\Service\PexelsService;
use OCP\Collaboration\Reference\IReference;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IURLGenerator;

class PhotoReferenceProvider extends ADiscoverableReferenceProvider implements ISearchableReferenceProvider {

	private const RICH_OBJECT_TYPE = Application::APP_ID . '_photo';

	private ?string $userId;
	private IConfig $config;
	private ReferenceManager $referenceManager;
	private IL10N $l10n;
	private IURLGenerator $urlGenerator;
	private PexelsService $pexelsService;

	public function __construct(IConfig $config,
								IL10N $l10n,
								IURLGenerator $urlGenerator,
								PexelsService $pexelsService,
								ReferenceManager $referenceManager,
								?string $userId) {
		$this->userId = $userId;
		$this->config = $config;
		$this->referenceManager = $referenceManager;
		$this->l10n = $l10n;
		$this->urlGenerator = $urlGenerator;
		$this->pexelsService = $pexelsService;
	}

	/**
	 * @inheritDoc
	 */
	public function getId(): string	{
		return 'pexels-photo';
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): string {
		return $this->l10n->t('Pexels photos');
	}

	/**
	 * @inheritDoc
	 */
	public function getOrder(): int	{
		return 10;
	}

	/**
	 * @inheritDoc
	 */
	public function getIconUrl(): string {
		return $this->urlGenerator->getAbsoluteURL(
			$this->urlGenerator->imagePath(Application::APP_ID, 'app-dark.svg')
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getSupportedSearchProviderIds(): array {
		return ['pexels-search-photos'];

	}

	/**
	 * @inheritDoc
	 */
	public function matchReference(string $referenceText): bool {
		$adminLinkPreviewEnabled = $this->config->getAppValue(Application::APP_ID, 'link_preview_enabled', '1') === '1';
		if (!$adminLinkPreviewEnabled) {
			return false;
		}
		return preg_match('/^(?:https?:\/\/)?(?:www\.)?pexels\.com\/photo\/[^\/\?]+-\d+\/?$/i', $referenceText) === 1
			|| preg_match('/^(?:https?:\/\/)?(?:www\.)?pexels\.com\/photo\/\d+\/?$/i', $referenceText) === 1;
	}

	/**
	 * @inheritDoc
	 */
	public function resolveReference(string $referenceText): ?IReference {
		if ($this->matchReference($referenceText)) {
			$photoId = $this->getPhotoId($referenceText);
			if ($photoId !== null) {
				$photoInfo = $this->pexelsService->getPhotoInfo($photoId);
				$reference = new Reference($referenceText);
				$reference->setTitle($photoInfo['alt'] ?? $this->l10n->t('Pexels photo'));
				$reference->setDescription($photoInfo['photographer'] ?? $this->l10n->t('Unknown photographer'));
				$imageUrl = $this->urlGenerator->linkToRouteAbsolute(Application::APP_ID . '.pexels.getPhotoContent', ['photoId' => $photoId, 'size' => 'large']);
				$reference->setImageUrl($imageUrl);
				$photoInfo['proxied_url'] = $imageUrl;
				$reference->setRichObject(
					self::RICH_OBJECT_TYPE,
					$photoInfo
				);
				return $reference;
			}
		}

		return null;
	}

	private function getPhotoId(string $url): ?int {
		preg_match('/^(?:https?:\/\/)?(?:www\.)?pexels\.com\/photo\/[^\/\?]+-(\d+)\/?$/i', $url, $matches);
		if (count($matches) > 1) {
			return (int)$matches[1];
		}

		preg_match('/^(?:https?:\/\/)?(?:www\.)?pexels\.com\/photo\/(\d+)\/?$/i', $url, $matches);
		if (count($matches) > 1) {
			return (int)$matches[1];
		}
		return null;
	}

	/**
	 * We use the userId here because when connecting/disconnecting from the GitHub account,
	 * we want to invalidate all the user cache and this is only possible with the cache prefix
	 * @inheritDoc
	 */
	public function getCachePrefix(string $referenceId): string {
		return $this->userId ?? '';
	}

	/**
	 * We don't use the userId here but rather a reference unique id
	 * @inheritDoc
	 */
	public function getCacheKey(string $referenceId): ?string {
		return $referenceId;
	}

	/**
	 * @param string $userId
	 * @return void
	 */
	public function invalidateUserCache(string $userId): void {
		$this->referenceManager->invalidateCache($userId);
	}
}
