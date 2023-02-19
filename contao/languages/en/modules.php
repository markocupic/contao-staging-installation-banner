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
$GLOBALS['TL_LANG']['FMD']['staging_installation_indicator'] = 'staging Installation indicator';
$GLOBALS['TL_LANG']['FMD'][StagingInstallationIndicatorBannerController::TYPE] = ['staging installation indicator banner', 'Add a banner to the layout to let you know you\'re on the staging system.'];
