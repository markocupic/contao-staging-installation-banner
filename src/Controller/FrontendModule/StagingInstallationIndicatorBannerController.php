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

namespace Markocupic\ContaoStagingInstallationBanner\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsFrontendModule(category: 'staging_installation_indicator')]
class StagingInstallationIndicatorBannerController extends AbstractFrontendModuleController
{
    public const TYPE = 'staging_installation_indicator_banner';

    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly TranslatorInterface $translator,
        private readonly bool $isStaging,
    ) {
    }

    public function __invoke(Request $request, ModuleModel $model, string $section, ?array $classes = null, ?PageModel $page = null): Response
    {
        if (!$this->isStaging && $this->scopeMatcher->isFrontendRequest($request)) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        // Make the banner text translatable
        $headline = match (true) {
            !empty($model->csib_bannerText) => $model->csib_bannerText,
            default => 'staging_system',
        };

        $template->set('bannerText', match (true) {
            isset($GLOBALS['TL_LANG']['CSIB'][$headline]) => $this->translator->trans('CSIB.'.$headline, [], 'contao_default'),
            default => $headline,
        });

        $arrStyle = ['color:#'.$model->csib_textColor, 'background:#'.$model->csib_bgColor];
        $template->set('banner_attributes', sprintf('styles="%s"', $this->getStringUtil()->specialchars(implode(';', $arrStyle))));

        return $template->getResponse();
    }

    protected function getStringUtil(): Adapter
    {
        return $this->framework->getAdapter(StringUtil::class);
    }
}
