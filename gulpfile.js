// ## Command
// npm install --save-dev gulp gulp-load-plugins gulp-changed gulp-concat gulp-flatten gulp-if gulp-imagemin gulp-jshint jshint jshint-stylish lazypipe gulp-less merge-stream gulp-cssnano gulp-plumber gulp-rev run-sequence gulp-sass gulp-sourcemaps gulp-uglify gulp-autoprefixer gulp-coffee gulp-csscomb gulp-cssbeautify gulp-csso gulp-rename gulp-cache browser-sync del wiredep imagemin-pngquant asset-builder critical

// ## Globals
var argv         = require('minimist')(process.argv.slice(2));
// Load plugins
var gulp    = require('gulp'),
$       = require('gulp-load-plugins')({
    pattern: ['gulp-*', 'gulp.*'],
    replaceString: /\bgulp[\-.]/,
    lazy: true,
    camelize: true
}),
browserSync = require('browser-sync').create(),
pngquant    = require('imagemin-pngquant'),
runSequence = require('run-sequence'),
merge       = require('merge-stream'),
lazypipe    = require('lazypipe'),
critical    = require('critical')
;

// See https://github.com/austinpray/asset-builder
var manifest = require('asset-builder')('./_assets/manifest.json');

// `path` - Paths to base asset directories. With trailing slashes.
// - `path.source` - Path to the source files. Default: `assets/`
// - `path.dist` - Path to the build directory. Default: `assets/`
var path = manifest.paths;
// console.log(manifest.paths);

// `config` - Store arbitrary configuration values here.
var config = manifest.config || {};

// `globs` - These ultimately end up in their respective `gulp.src`.
// - `globs.js` - Array of asset-builder JS dependency objects. Example:
//   ```
//   {type: 'js', name: 'main.js', globs: []}
//   ```
// - `globs.css` - Array of asset-builder CSS dependency objects. Example:
//   ```
//   {type: 'css', name: 'main.css', globs: []}
//   ```
// - `globs.fonts` - Array of font path globs.
// - `globs.images` - Array of image path globs.
// - `globs.bower` - Array of all the main Bower files.
var globs = manifest.globs;
// console.log(globs.js);
// console.log(globs.bower);

// `project` - paths to first-party assets.
// - `project.js` - Array of first-party JS assets.
// - `project.css` - Array of first-party CSS assets.
var project = manifest.getProjectGlobs();

// CLI options
var enabled = {
    // Enable static asset revisioning when `--production`
    rev: argv.production,
    // Disable source maps when `--production`
    maps: !argv.production,
    // Fail styles task on error when `--production`
    failStyleTask: argv.production,
    // Fail due to JSHint warnings only when `--production`
    failJSHint: argv.production,
    // Strip debug statments from javascript when `--production`
    stripJSDebug: argv.production
};

// Path to the compiled assets manifest in the dist directory
var revManifest = path.dist + 'assets.json';

// ## Reusable Pipelines
// See https://github.com/OverZealous/lazypipe

// ### CSS processing pipeline
// Example
// ```
// gulp.src(cssFiles)
//   .pipe(cssTasks('main.css')
//   .pipe(gulp.dest(path.dist + 'styles'))
// ```
var cssTasks = function(filename) {
    return lazypipe()
    .pipe(function() {
        return $.if(!enabled.failStyleTask, $.plumber());
    })
    .pipe(function() {
        return $.if(enabled.maps, $.sourcemaps.init());
    })
    .pipe(function() {
        return $.if('*.less', $.less());
    })
    .pipe(function() {
        return $.if('*.scss', $.sass({
            outputStyle: 'nested', // libsass doesn't support expanded yet
            precision: 10,
            includePaths: ['.'],
            errLogToConsole: !enabled.failStyleTask
            // compass: true
        }));
    })
    .pipe($.concat, filename)
    .pipe($.csscomb)
    .pipe($.cssbeautify, {
        indent: '  '
    })
    .pipe($.autoprefixer, {
        browsers: [
            'last 2 versions',
            'android 4',
            'opera 12'
        ]
    })
    // .pipe(gulp.dest, path.dist + 'css')
    .pipe(function() {
        return $.if(enabled.rev, $.cssnano({
            advanced: false,
            rebase: false
        }));
    })
    // .pipe(plugins.csso())
    // .pipe(gulp.dest, path.dist + 'css')
    // .pipe($.rename, {
    //     suffix: '.min'
    // })
    .pipe(function() {
        return $.if(enabled.rev, $.rev());
    })
    .pipe(function() {
        return $.if(enabled.maps, $.sourcemaps.write('.', {
            sourceRoot: path.source + 'styles/'
        }));
    })();
};

// ### JS processing pipeline
// Example
// ```
// gulp.src(jsFiles)
//   .pipe(jsTasks('main.js')
//   .pipe(gulp.dest(path.dist + 'scripts'))
// ```
var jsTasks = function(filename) {
    return lazypipe()
    .pipe(function() {
        return $.if(enabled.maps, $.sourcemaps.init());
    })
    .pipe(function() {
        return $.if('*.coffee', $.coffee());
    })
    .pipe($.concat, filename)
    .pipe(function() {
        return $.if(enabled.rev, $.uglify({
            compress: {
                'drop_debugger': enabled.stripJSDebug
            }
        }));
    })
    .pipe(function() {
        return $.if(enabled.rev, $.rev());
    })
    .pipe(function() {
        return $.if(enabled.maps, $.sourcemaps.write('.', {
            sourceRoot: path.source + 'scripts/'
        }));
    })();
};

// ### Write to rev manifest
// If there are any revved files then write them to the rev manifest.
// See https://github.com/sindresorhus/gulp-rev
var writeToManifest = function(directory) {
    return lazypipe()
    .pipe(gulp.dest, path.dist + directory)
    .pipe(browserSync.stream, {match: '**/*.{js,css}'})
    .pipe($.rev.manifest, revManifest, {
        base: path.dist,
        merge: true
    })
    .pipe(gulp.dest, path.dist)();
};

// ## Gulp tasks
// Run `gulp -T` for a task summary

// ### Styles
// `gulp styles` - Compiles, combines, and optimizes Bower CSS and project CSS.
// By default this task will only log a warning if a precompiler error is
// raised. If the `--production` flag is set: this task will fail outright.
gulp.task('styles', ['wiredep'], function() {
    var merged = merge();
    manifest.forEachDependency('css', function(dep) {
        var cssTasksInstance = cssTasks(dep.name);
        if (!enabled.failStyleTask) {
            cssTasksInstance.on('error', function(err) {
                console.error(err.message);
                this.emit('end');
            });
        }
        merged.add(gulp.src(dep.globs, {base: 'css'})
        .pipe(cssTasksInstance)
    );
});
return merged
.pipe(writeToManifest('css'));
});

// ### Scripts
// `gulp scripts` - Runs JSHint then compiles, combines, and optimizes Bower JS
// and project JS.
gulp.task('scripts', ['jshint'], function() {
    var merged = merge();
    manifest.forEachDependency('js', function(dep) {
        merged.add(
            gulp.src(dep.globs, {base: 'js'})
            .pipe(jsTasks(dep.name))
        );
    });
    return merged
    .pipe(writeToManifest('js'));
});

// ### Fonts
// `gulp fonts` - Grabs all the fonts and outputs them in a flattened directory
// structure. See: https://github.com/armed/gulp-flatten
gulp.task('fonts', function() {
    return gulp.src(globs.fonts)
    // .pipe($.flatten())
    .pipe(gulp.dest(path.dist + 'fonts'))
    .pipe(browserSync.stream());
});

// ### Images
// `gulp images` - Run lossless compression on all the images.
gulp.task('images', function() {
    return gulp.src(globs.images)
    .pipe($.imagemin({
        progressive: true,
        interlaced: true,
        svgoPlugins: [{removeUnknownsAndDefaults: false}, {cleanupIDs: false}],
        use: [pngquant()]
    }))
    .pipe(gulp.dest(path.dist + 'images'))
    .pipe(browserSync.stream());
});

// ### JSHint
// `gulp jshint` - Lints configuration JSON and project JS.
gulp.task('jshint', function() {
    return gulp.src([
        'bower.json', 'gulpfile.js'
    ].concat(project.js))
    // .pipe($.jshint())
    .pipe(function() {
        return $.if('*.js', $.jshint());
    }())
    // .pipe($.jshint.reporter('jshint-stylish'))
    .pipe(function() {
        return $.if('*.js', $.jshint.reporter('jshint-stylish'));
    }())
    // uncomment this line if you to fail due to jshint warnings;
    //   .pipe($.if(enabled.failJSHint, $.jshint.reporter('fail')))
    ;
});

// ### Clean
// `gulp clean` - Deletes the build folder entirely.
gulp.task('clean', require('del').bind($.cache.clearAll(), [path.dist]));

// ### Watch
// `gulp watch` - Use BrowserSync to proxy your dev server and synchronize code
// changes across devices. Specify the hostname of your dev server at
// `manifest.config.devUrl`. When a modification is made to an asset, run the
// build step for that asset and inject the changes into the page.
// See: http://www.browsersync.io
gulp.task('watch', function() {
    // browserSync.init({
    //     files: ['{lib,templates}/**/*.php', '*.php'],
    //     proxy: config.devUrl,
    //     snippetOptions: {
    //         whitelist: ['/wp-admin/admin-ajax.php'],
    //         blacklist: ['/wp-admin/**']
    //     }
    // });
    browserSync.init({
        files: config.browserSync.watch,
        proxy: config.devUrl,
        // server: {
        //     baseDir: config.browserSync.baseDir
        // },
        // host: "localhost",
        port: config.browserSync.port || 3000,
        snippetOptions: {
            whitelist: ['/wp-admin/admin-ajax.php'],
            blacklist: ['/wp-admin/**']
        }
    });
    gulp.watch([path.source + 'styles/**/*'], ['styles']);
    gulp.watch([path.source + 'scripts/**/*'], ['jshint', 'scripts']);
    gulp.watch([path.source + 'fonts/**/*'], ['fonts']);
    gulp.watch([path.source + 'images/**/*'], ['images']);
    gulp.watch(['bower.json', './_assets/manifest.json'], ['build']);
});

// ### Build
// `gulp build` - Run all the build tasks but don't clean up beforehand.
// Generally you should be running `gulp` instead of `gulp build`.
gulp.task('build', function(callback) {
    runSequence('styles',
    'scripts',
    ['fonts', 'images'],
    callback);
});

// ### Wiredep
// `gulp wiredep` - Automatically inject Less and Sass Bower dependencies. See
// https://github.com/taptapship/wiredep
gulp.task('wiredep', function() {
    var wiredep = require('wiredep').stream;
    return gulp.src(project.css)
    .pipe(wiredep())
    .pipe($.changed(path.source + 'styles', {
        hasChanged: $.changed.compareSha1Digest
    }))
    .pipe(gulp.dest(path.source + 'styles'));
});

// ### Gulp
// `gulp` - Run a complete build. To compile for production run `gulp --production`.
gulp.task('default', ['clean'], function() {
    gulp.start('build');
});
