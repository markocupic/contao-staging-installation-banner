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
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\StringUtil;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsHook('outputBackendTemplate', priority: 100)]
class OutputBackendTemplateListener
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Environment $twig,
        private readonly TranslatorInterface $translator,
        private readonly bool $isStaging,
        private readonly string $backendBannerBgColor,
        private readonly string $backendBannerTextColor,
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
        if ($this->isStaging && str_starts_with($template, 'be_main')) {
            $template = [];

            // Make the banner Text translatable
            $headline = match (true) {
                !empty($this->backendBannerTranslatableText) => $this->backendBannerTranslatableText,
                default => 'staging_system',
            };

            $template['banner_text'] = match (true) {
                isset($GLOBALS['TL_LANG']['CSIB'][$headline]) => $this->translator->trans('CSIB.'.$headline, [], 'contao_default'),
                default => $headline,
            };

            $arrStyle = ['color:#'.$this->backendBannerTextColor, 'background:#'.$this->backendBannerBgColor];
            $template['banner_css'] = $this->getStringUtil()->specialchars(implode(';', $arrStyle));

            $bannerContent = $this->twig->render('@MarkocupicContaoStagingInstallationBanner/be_staging_installation_indicator_banner.html.twig', $template);

            $search = '<main id="main" aria-labelledby="main_headline">';
            $replace = $search."\n".$bannerContent;
            $buffer = str_replace($search, $replace, $buffer);
        }

        return $buffer;
    }

    protected function getStringUtil(): Adapter
    {
        return $this->framework->getAdapter(StringUtil::class);
    }
}
