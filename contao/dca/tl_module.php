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

use Markocupic\ContaoStagingInstallationBanner\Controller\FrontendModule\StagingInstallationIndicatorBannerController;

/**
 * Frontend modules
 */
$GLOBALS['TL_DCA']['tl_module']['palettes'][StagingInstallationIndicatorBannerController::TYPE] = '
{title_legend},name,headline,type;
{template_legend:hide},customTpl;
{protected_legend:hide},protected;
{expert_legend:hide},guests,cssID
';
