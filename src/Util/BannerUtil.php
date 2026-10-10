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

namespace Markocupic\ContaoStagingInstallationBanner\Util;

final class BannerUtil
{
    /**
     * Returns the hex color with a leading "#" or null if the value is not a valid 3
     * or 6 digit hex color.
     */
    public static function normalizeHexColor(string|null $color): string|null
    {
        $color = ltrim(trim((string) $color), '#');

        if (!preg_match('/^([0-9a-f]{3}|[0-9a-f]{6})$/i', $color)) {
            return null;
        }

        return '#'.$color;
    }

    /**
     * Builds the inline style declaration for the banner.
     */
    public static function buildStyle(string|null $textColor, string|null $bgColor): string
    {
        $style = [];

        if (null !== ($color = self::normalizeHexColor($textColor))) {
            $style[] = 'color:'.$color;
        }

        if (null !== ($color = self::normalizeHexColor($bgColor))) {
            $style[] = 'background-color:'.$color;
        }

        return implode(';', $style);
    }
}
