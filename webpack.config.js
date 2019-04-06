let Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .addEntry('app', './assets/js/app.js')
    .setPublicPath('/build')
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSassLoader();

const config = Encore.getWebpackConfig();
config.resolve.alias = { vue: 'vue/dist/vue.min.js' };

module.exports = config;