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

namespace Markocupic\ContaoStagingInstallationBanner\Tests\EventSubscriber;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Markocupic\ContaoStagingInstallationBanner\EventSubscriber\KernelRequestSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelRequestSubscriberTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($GLOBALS['TL_CSS']);

        parent::tearDown();
    }

    public function testSubscribesToTheKernelRequestEvent(): void
    {
        $this->assertSame([KernelEvents::REQUEST => 'onKernelRequest'], KernelRequestSubscriber::getSubscribedEvents());
    }

    public function testAddsTheBackendStylesheetOnce(): void
    {
        $subscriber = new KernelRequestSubscriber($this->createPackages(), $this->createScopeMatcher(true), true);

        $subscriber->onKernelRequest($this->createEvent());
        $subscriber->onKernelRequest($this->createEvent());

        $this->assertSame(['/bundles/foo/be_stylesheet.css'], $GLOBALS['TL_CSS']);
    }

    public function testDoesNotAddTheStylesheetIfNotStaging(): void
    {
        $subscriber = new KernelRequestSubscriber($this->createPackages(), $this->createScopeMatcher(true), false);
        $subscriber->onKernelRequest($this->createEvent());

        $this->assertEmpty($GLOBALS['TL_CSS'] ?? []);
    }

    public function testDoesNotAddTheStylesheetInTheFrontend(): void
    {
        $subscriber = new KernelRequestSubscriber($this->createPackages(), $this->createScopeMatcher(false), true);
        $subscriber->onKernelRequest($this->createEvent());

        $this->assertEmpty($GLOBALS['TL_CSS'] ?? []);
    }

    private function createPackages(): Packages
    {
        $packages = $this->createStub(Packages::class);
        $packages
            ->method('getUrl')
            ->willReturn('/bundles/foo/be_stylesheet.css')
        ;

        return $packages;
    }

    private function createScopeMatcher(bool $isBackend): ScopeMatcher
    {
        $scopeMatcher = $this->createStub(ScopeMatcher::class);
        $scopeMatcher
            ->method('isBackendRequest')
            ->willReturn($isBackend)
        ;

        return $scopeMatcher;
    }

    private function createEvent(): RequestEvent
    {
        return new RequestEvent($this->createStub(HttpKernelInterface::class), new Request(), HttpKernelInterface::MAIN_REQUEST);
    }
}
