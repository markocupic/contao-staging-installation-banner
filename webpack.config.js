const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/')
    .setPublicPath('/bundles/markocupiccontaostaginginstallationbanner')
    .setManifestKeyPrefix('')

    //.addEntry('backend', './assets/backend.js')
    //.addEntry('frontend', './assets/frontend.js')

    .copyFiles({
        from: './assets/css',
        to: 'css/[path][name].[hash:8].[ext]',
        pattern: /(\.css)$/,
    })
    .copyFiles({
        from: './assets/icons',
        to: 'icons/[path][name].[hash:8].[ext]',
        pattern: /(\.svg)$/,
    })
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps()
    .enableVersioning()

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
