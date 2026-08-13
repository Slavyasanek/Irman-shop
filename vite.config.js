import { defineConfig } from 'vite';
import path from 'path';
import fs from 'fs';
import { globSync } from 'glob';
import browserSync from 'browser-sync';

const root = process.cwd();

function getEntries() {
    const entries = {
        main: path.resolve(root, 'src/js/app.js'),

        style: path.resolve(root, 'src/scss/index.scss'),
        'product-page': path.resolve(root, 'src/scss/product-page.scss'),

        'product-page-js': path.resolve(root, 'src/js/product-page.js'),

        'product-section-js': path.resolve(
            root,
            'blocks/products-section/script.js'
        ),
        'hero-section-js': path.resolve(
            root,
            'blocks/hero-section/script.js'
        ),

        shop: path.resolve(root, 'src/scss/shop.scss'),
        'not-found': path.resolve(root, 'src/scss/not-found.scss'),
        'checkout': path.resolve(root, 'src/scss/checkout.scss'),
        'thankyou': path.resolve(root, 'src/scss/thankyou.scss'),
        'shop-js': path.resolve(root, 'src/js/shop.js'),

        'editor-tweaks': path.resolve(
            root,
            'src/scss/editor-tweaks.scss'
        ),
    };

    // Automatically discover block SCSS files
    globSync('blocks/**/*.scss').forEach((filePath) => {
        const blockName = path.basename(path.dirname(filePath));

        entries[`block-${blockName}`] = path.resolve(
            root,
            filePath
        );
    });

    return entries;
}

/**
 * Remove empty JS files generated for SCSS entries.
 */
function removeEmptyCssJs() {
    return {
        name: 'remove-empty-css-js',

        generateBundle(_, bundle) {
            Object.entries(bundle).forEach(([fileName, chunk]) => {
                if (chunk.type === 'chunk') {
                    const isCssOnlyChunk = chunk.moduleIds && chunk.moduleIds.every(
                        (id) => id.endsWith('.scss') || id.endsWith('.css') || id.endsWith('.sass')
                    );

                    if (isCssOnlyChunk || chunk.code.trim() === '') {
                        delete bundle[fileName];
                    }
                }
            });
        },
    };
}

/**
 * BrowserSync proxy for WordPress/PHP development.
 */
function browserSyncPlugin() {
    let bs;

    return {
        name: 'browser-sync',

        configureServer() {
            if (bs) return;

            bs = browserSync.create();

            bs.init({
                proxy: 'http://localhost:8000',

                files: [
                    '**/*.php',
                    'build/**/*.css',
                    'build/**/*.js',
                ],

                reloadDelay: 0,

                notify: false,

                open: false,
            });
        },

        closeBundle() {
            if (bs) {
                bs.exit();
                bs = null;
            }
        },
    };
}

export default defineConfig(({ mode }) => {
    const isProduction = mode === 'production';

    return {
        build: {
            outDir: 'build',

            emptyOutDir: true,

            sourcemap: !isProduction,

            minify: isProduction,

            rollupOptions: {
                input: getEntries(),

                output: {
                    entryFileNames: 'js/[name].js',

                    chunkFileNames: 'js/[name]-[hash].js',

                    assetFileNames: (assetInfo) => {
                        if (assetInfo.name?.endsWith('.css')) {
                            return 'css/[name][extname]';
                        }

                        const fontExts = ['.ttf', '.woff', '.woff2', '.eot', '.otf'];
                        if (fontExts.some(ext => assetInfo.name?.endsWith(ext))) {
                            return 'css/fonts/[name][extname]';
                        }

                        return 'assets/[name][extname]';
                    },
                },
            },
        },

        css: {
            devSourcemap: true,
        },

        plugins: [
            removeEmptyCssJs(),
            browserSyncPlugin(),
        ],
    };
});