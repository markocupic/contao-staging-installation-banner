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
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsHook('outputBackendTemplate', priority: 100)]
class OutputBackendTemplateListener
{
    public function __construct(
        private readonly Environment $twig,
        private readonly bool $isStaging,
    ) {
    }

    /**
     * @throws \DOMException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(string $buffer, string $template): string
    {
        if ($this->isStaging && str_starts_with($template, 'be_main')) {
            $bannerContent = $this->twig->render('@MarkocupicContaoStagingInstallationBanner/be_staging_installation_indicator_banner.html.twig', []);
            $search = '<main id="main" aria-labelledby="main_headline">';
            $replace = $search."\n".$bannerContent;
            $buffer = str_replace($search, $replace, $buffer);
        }

        return $buffer;
    }
}
