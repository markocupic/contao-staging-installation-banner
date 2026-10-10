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
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Markocupic\ContaoStagingInstallationBanner\Util\BannerUtil;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsFrontendModule(StagingInstallationIndicatorBannerController::TYPE, category: 'staging_installation_indicator')]
class StagingInstallationIndicatorBannerController extends AbstractFrontendModuleController
{
    public const TYPE = 'staging_installation_indicator_banner';

    public function __construct(
        private readonly ScopeMatcher $scopeMatcher,
        private readonly TranslatorInterface $translator,
        #[Autowire('%markocupic_contao_staging_installation_banner.is_staging_system%')]
        private readonly bool $isStaging,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        // Only display the banner on the staging system
        if (!$this->isStaging && $this->scopeMatcher->isFrontendRequest($request)) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        // Make the banner text translatable
        $bannerText = (string) $model->csib_bannerText ?: 'staging_system';

        if (isset($GLOBALS['TL_LANG']['CSIB'][$bannerText])) {
            $bannerText = $this->translator->trans('CSIB.'.$bannerText, [], 'contao_default');
        }

        $template->set('bannerText', $bannerText);
        $template->set('banner_style', BannerUtil::buildStyle($model->csib_textColor, $model->csib_bgColor));

        return $template->getResponse();
    }
}
