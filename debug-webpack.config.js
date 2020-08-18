﻿// Template for webpack.config.js in Fable projects
// Find latest version in https://github.com/fable-compiler/webpack-config-template

// In most cases, you'll only need to edit the CONFIG object (after dependencies)
// See below if you need better fine-tuning of Webpack options

// Dependencies. Also required: core-js, fable-loader, fable-compiler, @babel/core,
// @babel/preset-env, babel-loader, sass, sass-loader, css-loader, style-loader, file-loader, resolve-url-loader
var path = require('path');
var webpack = require('webpack');
var HtmlWebpackPlugin = require('html-webpack-plugin');
var CopyWebpackPlugin = require('copy-webpack-plugin');
var MiniCssExtractPlugin = require('mini-css-extract-plugin');
var FixCssLoader = require('./fixcss-loader.js');

var CONFIG = {
    // The tags to include the generated JS and CSS will be automatically injected in the HTML template
    // See https://github.com/jantimon/html-webpack-plugin
    indexHtmlTemplate: './src/Game/index.html',
    fsharpEntry: './src/Game/Game.fsproj',
    cssEntry: './src/Game/style.scss',
    outputDir: './src/Game/debug',
    assetsDir: './src/Game/public',
    devServerPort: 8080,
    // When using webpack-dev-server, you may need to redirect some calls
    // to a external API server. See https://webpack.js.org/configuration/dev-server/#devserver-proxy
    devServerProxy: {
        // redirect requests that start with /api/ to the server on port 8085
        '/api/**': {
            target: 'http://localhost:' + (process.env.SERVER_PROXY_PORT || "8085"),
               changeOrigin: true
        },
        // redirect websocket requests that start with /socket/ to the server on the port 8085
        '/socket/**': {
            target: 'http://localhost:' + (process.env.SERVER_PROXY_PORT || "8085"),
            ws: true
           },
        '/login': {
            target: 'http://localhost:' + (process.env.SERVER_PROXY_PORT || "8085"),
            ws: true
           },
        '/auth': {
            target: 'http://localhost:' + (process.env.SERVER_PROXY_PORT || "8085"),
            ws: true
            },
        '/auth/**': {
            target: 'http://localhost:' + (process.env.SERVER_PROXY_PORT || "8085"),
            ws: true
            }
       },
    // Use babel-preset-env to generate JS compatible with most-used browsers.
    // More info at https://babeljs.io/docs/en/next/babel-preset-env.html
    babel: {
        presets: [
            ['@babel/preset-env', {
                modules: false,
                // This adds polyfills when needed. Requires core-js dependency.
                // See https://babeljs.io/docs/en/babel-preset-env#usebuiltins
                // Note that you still need to add custom polyfills if necessary (e.g. whatwg-fetch)
                useBuiltIns: 'usage',
                corejs: 3
            }]
        ],
    }
}

// If we're running the webpack-dev-server, assume we're in development mode
var isProduction = !process.argv.find(v => v.indexOf('webpack-dev-server') !== -1);
console.log('Bundling for ' + (isProduction ? 'production' : 'development') + '...');

// The HtmlWebpackPlugin allows us to use a template for the index.html page
// and automatically injects <script> or <link> tags for generated bundles.
var commonPlugins = [
    new HtmlWebpackPlugin({
        filename: 'game/index.html',
        template: resolve(CONFIG.indexHtmlTemplate),
        excludeChunks: ['join', 'joinstyle']
    }),
    new HtmlWebpackPlugin({
        filename: 'index.html',
        template: resolve('./src/Join/index.html'),
        excludeChunks: ['game', 'style']
    }),

];
var bgaPlugins = [
    new HtmlWebpackPlugin({
        filename: 'game/bga.html',
        template: resolve('./src/Debugger/index.html'),
        excludeChunks: ['join', 'game', 'joinstyle']
    })
];

module.exports = [/*{
    // In development, split the JavaScript and CSS files in order to
    // have a faster HMR support. In production bundle styles together
    // with the code because the MiniCssExtractPlugin will extract the
    // CSS in a separate files.
    entry: isProduction ? {
        game: [resolve(CONFIG.fsharpEntry),
              resolve(CONFIG.cssEntry)],

        join: [resolve('./src/Join/Join.fsproj'),
            resolve('./src/Join/join-style.scss')],
    } : {
            game: [resolve(CONFIG.fsharpEntry)],
            join: [resolve('./src/Join/Join.fsproj')],
            joinstyle: [resolve('./src/Join/join-style.scss')],
            style: [resolve(CONFIG.cssEntry)],
        },
    // Add a hash to the output file name in production
    // to prevent browser caching if code changes
    output: {
        path: resolve(CONFIG.outputDir),
        filename: isProduction ? '[name].[hash].js' : '[name].js'
    },
    mode: isProduction ? 'production' : 'development',
    devtool: isProduction ? 'source-map' : 'eval-source-map',
    optimization: {
        splitChunks: {
            chunks: 'all'
        },
    },
    // Besides the HtmlPlugin, we use the following plugins:
    // PRODUCTION
    //      - MiniCssExtractPlugin: Extracts CSS from bundle to a different file
    //          To minify CSS, see https://github.com/webpack-contrib/mini-css-extract-plugin#minimizing-for-production    
    //      - CopyWebpackPlugin: Copies static assets to output directory
    // DEVELOPMENT
    //      - HotModuleReplacementPlugin: Enables hot reloading when code changes without refreshing
    plugins: isProduction ?
        commonPlugins.concat([
            new MiniCssExtractPlugin({ filename: '[name].[hash].css' }),
            new CopyWebpackPlugin([{ from: resolve(CONFIG.assetsDir) }]),
        ])
        : commonPlugins.concat([
            new webpack.HotModuleReplacementPlugin(),
        ]),
    resolve: {
        // See https://github.com/fable-compiler/Fable/issues/1490
        symlinks: false,
        alias: {
            fonts: path.resolve(__dirname, "src/Game/public/font"),
            img: path.resolve(__dirname, "src/Game/public/img")
        }

    },
    // Configuration for webpack-dev-server
    devServer: {
        publicPath: '/',
        contentBase: resolve(CONFIG.assetsDir),
        host: '0.0.0.0',
        port: CONFIG.devServerPort,
        proxy: CONFIG.devServerProxy,
        historyApiFallback: {
             rewrites: [
                { from: /^\/game\/.* /, to: '/game/index.html' },
             ]
        },
        hot: true,
        inline: true
    },
    // - fable-loader: transforms F# into JS
    // - babel-loader: transforms JS to old syntax (compatible with old browsers)
    // - sass-loaders: transforms SASS/SCSS into JS
    // - file-loader: Moves files referenced in the code (fonts, images) into output folder
    module: {
        rules: [
            {
                test: /\.fs(x|proj)?$/,
                use: {
                    loader: 'fable-loader',
                    options: {
                        babel: CONFIG.babel
                    }
                }
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: CONFIG.babel
                },
            },
            {
                test: /\.(sass|scss|css)$/,
                use: [
                    isProduction
                        ? MiniCssExtractPlugin.loader
                        : 'style-loader',
                    'css-loader',
                    {
                        loader: 'resolve-url-loader',
                    },
                    {
                      loader: 'sass-loader',
                      options: { implementation: require('sass') }
                    }
                ],
            },
            {
                test: /\.(png|jpg|jpeg|gif|svg)(\?.*)?$/,
                use: [isProduction ? 'file-loader?name=/img/[name].[ext]' : 'file-loader?name=/img/[name].[ext]']
            },
            {
                test: /fa-.*\.(woff|woff2|ttf|eot)(\?.*)?$/,
                use: ['file-loader']
            },
            {
                test: /\.(woff|woff2|ttf|eot)(\?.*)?$/,
                exclude: /fa-.*\.(woff|woff2|ttf|eot)(\?.*)?$/,
                use:  [isProduction ? 'file-loader?name=/font/[name].[ext]' : 'file-loader?name=/font/[name].[ext]'] 
            }
        ]
    }
},*/

{
    /// BGA
    entry: isProduction ? {
        crazy: [resolve('./src/Debugger/Debugger.fsproj'),
              resolve(CONFIG.cssEntry)],
    } : {
            crazy: [resolve('./src/Debugger/Debugger.fsproj')],
            style: [resolve(CONFIG.cssEntry)],

        },
    // Add a hash to the output file name in production
    // to prevent browser caching if code changes
    output: {
        path: resolve("./debugger/"),
        filename: isProduction ? 'modules/[name].js' : 'modules/[name].js',
        //libraryTarget: 'var',
        //library: 'crazy'
    },
    mode: isProduction ? 'production' : 'development',
    devtool: isProduction ? 'source-map' : 'eval-source-map',
    optimization: {
        splitChunks: {
            chunks: 'all'
        },
    },
    // Besides the HtmlPlugin, we use the following plugins:
    // PRODUCTION
    //      - MiniCssExtractPlugin: Extracts CSS from bundle to a different file
    //          To minify CSS, see https://github.com/webpack-contrib/mini-css-extract-plugin#minimizing-for-production    
    //      - CopyWebpackPlugin: Copies static assets to output directory
    // DEVELOPMENT
    //      - HotModuleReplacementPlugin: Enables hot reloading when code changes without refreshing
    plugins: isProduction ?
        bgaPlugins.concat([
            new MiniCssExtractPlugin({ filename: 'crazyfarmers.css' }),
            new CopyWebpackPlugin([{ from: resolve(CONFIG.assetsDir) }]),
            new CopyWebpackPlugin([{ from: resolve('./bga') }]), 
        ])
        : bgaPlugins.concat([
            new webpack.HotModuleReplacementPlugin(),
        ]),
    resolve: {
        // See https://github.com/fable-compiler/Fable/issues/1490
        symlinks: false,
        alias: {
            fonts: path.resolve(__dirname, "src/Game/public/font"),
            img: path.resolve(__dirname, "src/Game/public/img")
        }

    },
    resolveLoader: {
        modules: ['node_modules', path.resolve(__dirname)]
        },
    // // Configuration for webpack-dev-server
    // devServer: {
    //     publicPath: '/1/',
    //     contentBase: resolve(CONFIG.assetsDir),
    //     host: '0.0.0.0',
    //     port: CONFIG.devServerPort,
    //     proxy: CONFIG.devServerProxy,
    //     historyApiFallback: {
    //          rewrites: [
    //             { from: /^\/game\/.*/, to: '/game/index.html' },
    //          ]
    //     },
    //     hot: true,
    //     inline: true
    // },
    // - fable-loader: transforms F# into JS
    // - babel-loader: transforms JS to old syntax (compatible with old browsers)
    // - sass-loaders: transforms SASS/SCSS into JS
    // - file-loader: Moves files referenced in the code (fonts, images) into output folder
    module: {
        rules: [
            {
                test: /\.fs(x|proj)?$/,
                use: {
                    loader: 'fable-loader',
                    options: {
                        babel: CONFIG.babel
                    }
                }
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: CONFIG.babel
                },
            },
            {
                test: /\.(sass|scss|css)$/,
                use: [
                    isProduction
                        ? MiniCssExtractPlugin.loader
                        : 'style-loader',
                    'fixcss-loader',
                    'css-loader',
                    {
                        loader: 'resolve-url-loader',
                    },
                    {
                      loader: 'sass-loader',
                      options: { implementation: require('sass') }
                    }
                ],
            },
            {
                test: /\.(png|jpg|jpeg|gif|svg)(\?.*)?$/,
                use: [isProduction ? 'file-loader?name=./img/[name].[ext]' : 'file-loader?name=[name].[ext]']
            },
            {
                test: /\.(woff|woff2|ttf|eot)(\?.*)?$/,
                use: [isProduction ? 'file-loader?name=./img/[name].[ext]' : 'file-loader?name=[name].[ext]'] 
            }


        ]
    }
}
]


;

function resolve(filePath) {
    return path.isAbsolute(filePath) ? filePath : path.join(__dirname, filePath);
}
