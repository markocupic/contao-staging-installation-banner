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
{title_legend},name,headline,csib_bannerText,type;
{config_legend},csib_textColor,csib_bgColor;
{template_legend:hide},customTpl;
{protected_legend:hide},protected;
{expert_legend:hide},guests,cssID
';

$GLOBALS['TL_DCA']['tl_module']['fields']['csib_bannerText'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 200, 'tl_class' => 'clr'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['csib_textColor'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['mandatory' => true, 'maxlength' => 6, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'clr w25 wizard'],
    'sql'       => "varchar(6) COLLATE ascii_bin NOT NULL default 'FFFFFF'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['csib_bgColor'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['mandatory' => true, 'maxlength' => 6, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'w25 wizard'],
    'sql'       => "varchar(6) COLLATE ascii_bin NOT NULL default 'EC4899'",
];
