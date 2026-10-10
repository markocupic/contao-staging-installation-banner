<?php

declare(strict_types=1);

/*
 * This file is part of contao-staging-installation-banner.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-staging-installation-banner
 */

namespace Markocupic\ContaoStagingInstallationBanner\EventSubscriber;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Packages $packages,
        private readonly ScopeMatcher $scopeMatcher,
        #[Autowire('%markocupic_contao_staging_installation_banner.is_staging_system%')]
        private readonly bool $isStaging,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->isStaging || !$event->isMainRequest() || !$this->scopeMatcher->isBackendRequest($event->getRequest())) {
            return;
        }

        $stylesheet = $this->packages->getUrl('css/be_stylesheet.css', 'markocupic_contao_staging_installation_banner');

        if (!\in_array($stylesheet, $GLOBALS['TL_CSS'] ?? [], true)) {
            $GLOBALS['TL_CSS'][] = $stylesheet;
        }
    }
}
