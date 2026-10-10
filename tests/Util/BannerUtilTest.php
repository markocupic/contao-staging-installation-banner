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

namespace Markocupic\ContaoStagingInstallationBanner\Tests\Util;

use Markocupic\ContaoStagingInstallationBanner\Util\BannerUtil;
use PHPUnit\Framework\TestCase;

class BannerUtilTest extends TestCase
{
    public function testNormalizesHexColors(): void
    {
        $this->assertSame('#FFFFFF', BannerUtil::normalizeHexColor('FFFFFF'));
        $this->assertSame('#ec4899', BannerUtil::normalizeHexColor('#ec4899'));
        $this->assertSame('#abc', BannerUtil::normalizeHexColor('abc'));
        $this->assertNull(BannerUtil::normalizeHexColor(''));
        $this->assertNull(BannerUtil::normalizeHexColor(null));
        $this->assertNull(BannerUtil::normalizeHexColor('red'));
        $this->assertNull(BannerUtil::normalizeHexColor('FFF;background:url(x)'));
    }

    public function testBuildsTheStyleDeclaration(): void
    {
        $this->assertSame('color:#FFFFFF;background-color:#EC4899', BannerUtil::buildStyle('FFFFFF', 'EC4899'));
        $this->assertSame('background-color:#EC4899', BannerUtil::buildStyle('invalid"', 'EC4899'));
        $this->assertSame('', BannerUtil::buildStyle(null, null));
    }
}
