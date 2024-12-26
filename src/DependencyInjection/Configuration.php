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

namespace Markocupic\ContaoStagingInstallationBanner\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROOT_KEY = 'markocupic_contao_staging_installation_banner';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_KEY);

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('is_staging_system')->defaultFalse()->end()
                ->scalarNode('backend_banner_translatable_text')->defaultValue('staging_system')->end()
                ->scalarNode('backend_banner_text_color')->defaultValue('FFFFFF')->end()
                ->scalarNode('backend_banner_bg_color')->defaultValue('EC4899')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
