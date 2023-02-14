<?php
/**
 * Nextcloud - Tutorial4
 *
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 * @copyright Julien Veyssier 2023
 */

namespace OCA\Tutorial4\AppInfo;

use OCA\Tutorial4\Listener\PexelsReferenceListener;
use OCA\Tutorial4\Reference\PhotoReferenceProvider;
use OCA\Tutorial4\Search\PexelsSearchPhotosProvider;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\Collaboration\Reference\RenderReferenceEvent;

class Application extends App implements IBootstrap {
	public const APP_ID = 'tutorial_4';

	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerSearchProvider(PexelsSearchPhotosProvider::class);
		$context->registerReferenceProvider(PhotoReferenceProvider::class);
		$context->registerEventListener(RenderReferenceEvent::class, PexelsReferenceListener::class);
	}

	public function boot(IBootContext $context): void {
	}
}

