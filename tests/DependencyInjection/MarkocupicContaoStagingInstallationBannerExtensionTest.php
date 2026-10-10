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

namespace Markocupic\ContaoStagingInstallationBanner\Tests\DependencyInjection;

use Markocupic\ContaoStagingInstallationBanner\Controller\FrontendModule\StagingInstallationIndicatorBannerController;
use Markocupic\ContaoStagingInstallationBanner\DependencyInjection\MarkocupicContaoStagingInstallationBannerExtension;
use Markocupic\ContaoStagingInstallationBanner\EventListener\ContaoHook\OutputBackendTemplateListener;
use Markocupic\ContaoStagingInstallationBanner\EventSubscriber\KernelRequestSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MarkocupicContaoStagingInstallationBannerExtensionTest extends TestCase
{
    public function testRegistersTheServicesAndDefaultParameters(): void
    {
        $container = new ContainerBuilder();

        (new MarkocupicContaoStagingInstallationBannerExtension())->load([], $container);

        $this->assertTrue($container->hasDefinition(StagingInstallationIndicatorBannerController::class));
        $this->assertTrue($container->hasDefinition(OutputBackendTemplateListener::class));
        $this->assertTrue($container->hasDefinition(KernelRequestSubscriber::class));

        $this->assertFalse($container->getParameter('markocupic_contao_staging_installation_banner.is_staging_system'));
        $this->assertSame('staging_system', $container->getParameter('markocupic_contao_staging_installation_banner.backend_banner_translatable_text'));
        $this->assertSame('FFFFFF', $container->getParameter('markocupic_contao_staging_installation_banner.backend_banner_text_color'));
        $this->assertSame('EC4899', $container->getParameter('markocupic_contao_staging_installation_banner.backend_banner_bg_color'));
    }

    public function testProcessesTheConfiguration(): void
    {
        $container = new ContainerBuilder();

        (new MarkocupicContaoStagingInstallationBannerExtension())->load([[
            'is_staging_system' => true,
            'backend_banner_translatable_text' => '!!! STAGING !!!',
            'backend_banner_text_color' => '000000',
            'backend_banner_bg_color' => 'FFCC00',
        ]], $container);

        $this->assertTrue($container->getParameter('markocupic_contao_staging_installation_banner.is_staging_system'));
        $this->assertSame('!!! STAGING !!!', $container->getParameter('markocupic_contao_staging_installation_banner.backend_banner_translatable_text'));
        $this->assertSame('000000', $container->getParameter('markocupic_contao_staging_installation_banner.backend_banner_text_color'));
        $this->assertSame('FFCC00', $container->getParameter('markocupic_contao_staging_installation_banner.backend_banner_bg_color'));
    }
}
