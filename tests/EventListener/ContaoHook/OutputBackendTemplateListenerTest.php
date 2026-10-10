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

namespace Markocupic\ContaoStagingInstallationBanner\Tests\EventListener\ContaoHook;

use Markocupic\ContaoStagingInstallationBanner\EventListener\ContaoHook\OutputBackendTemplateListener;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class OutputBackendTemplateListenerTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($GLOBALS['TL_LANG']);

        parent::tearDown();
    }

    public function testInsertsTheBannerAfterTheMainTag(): void
    {
        $buffer = '<body><main id="main" aria-labelledby="main_headline"><h1 id="main_headline">Foo</h1></main></body>';

        $result = $this->createListener(true)($buffer, 'be_main');

        $this->assertSame('<body><main id="main" aria-labelledby="main_headline">'."\n".'[!!! STAGING !!!|color:#FFFFFF;background-color:#EC4899]<h1 id="main_headline">Foo</h1></main></body>', $result);
    }

    public function testTranslatesTheBannerText(): void
    {
        $GLOBALS['TL_LANG']['CSIB']['staging_system'] = 'Staging';

        $translator = $this->createStub(TranslatorInterface::class);
        $translator
            ->method('trans')
            ->willReturn('Staging/Test-System')
        ;

        $listener = new OutputBackendTemplateListener($this->createTwig(), $translator, true, 'EC4899', 'FFFFFF', 'staging_system');

        $this->assertStringContainsString('[Staging/Test-System|', $listener('<main id="main">', 'be_main'));
    }

    public function testDoesNothingIfNotStaging(): void
    {
        $buffer = '<main id="main" aria-labelledby="main_headline"></main>';

        $this->assertSame($buffer, $this->createListener(false)($buffer, 'be_main'));
    }

    public function testDoesNothingForOtherTemplates(): void
    {
        $buffer = '<main id="main"></main>';

        $this->assertSame($buffer, $this->createListener(true)($buffer, 'be_login'));
    }

    private function createListener(bool $isStaging): OutputBackendTemplateListener
    {
        return new OutputBackendTemplateListener($this->createTwig(), $this->createStub(TranslatorInterface::class), $isStaging, 'EC4899', 'FFFFFF', '!!! STAGING !!!');
    }

    private function createTwig(): Environment
    {
        $loader = new ArrayLoader();
        $loader->setTemplate(OutputBackendTemplateListener::TEMPLATE, '[{{ banner_text }}|{{ banner_css }}]');

        return new Environment($loader, ['autoescape' => false]);
    }
}
