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

namespace Markocupic\ContaoStagingInstallationBanner\EventListener\ContaoHook;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Markocupic\ContaoStagingInstallationBanner\Util\BannerUtil;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsHook('outputBackendTemplate', priority: 100)]
class OutputBackendTemplateListener
{
    public const TEMPLATE = '@MarkocupicContaoStagingInstallationBanner/be_staging_installation_indicator_banner.html.twig';

    public function __construct(
        private readonly Environment $twig,
        private readonly TranslatorInterface $translator,
        #[Autowire('%markocupic_contao_staging_installation_banner.is_staging_system%')]
        private readonly bool $isStaging,
        #[Autowire('%markocupic_contao_staging_installation_banner.backend_banner_bg_color%')]
        private readonly string $backendBannerBgColor,
        #[Autowire('%markocupic_contao_staging_installation_banner.backend_banner_text_color%')]
        private readonly string $backendBannerTextColor,
        #[Autowire('%markocupic_contao_staging_installation_banner.backend_banner_translatable_text%')]
        private readonly string $backendBannerTranslatableText,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(string $buffer, string $template): string
    {
        if (!$this->isStaging || !str_starts_with($template, 'be_main')) {
            return $buffer;
        }

        // Make the banner text translatable
        $bannerText = $this->backendBannerTranslatableText ?: 'staging_system';

        if (isset($GLOBALS['TL_LANG']['CSIB'][$bannerText])) {
            $bannerText = $this->translator->trans('CSIB.'.$bannerText, [], 'contao_default');
        }

        $bannerContent = $this->twig->render(self::TEMPLATE, [
            'banner_text' => $bannerText,
            'banner_css' => BannerUtil::buildStyle($this->backendBannerTextColor, $this->backendBannerBgColor),
        ]);

        // Insert the banner right after the opening <main id="main"> tag (Contao 5.3 and 6)
        return preg_replace_callback(
            '/<main\b[^>]*\bid="main"[^>]*>/',
            static fn (array $matches): string => $matches[0]."\n".$bannerContent,
            $buffer,
            1,
        ) ?? $buffer;
    }
}
