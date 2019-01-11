/* eslint-env node, es6 */
/* global Promise */
/*
  eslint-disable strict,
  key-spacing,
  one-var,
  no-multi-spaces,
  max-nested-callbacks,
  quote-props,
*/

'use strict';

const magicImporter = require('node-sass-magic-importer');

const options = {};

// #############################
// Edit these paths and options.
// #############################

// The root paths are used to construct all the other paths in this
// configuration. The "project" root path is where this gulpfile.js is located.
// While Zen distributes this in the theme root folder, you can also put this
// (and the package.json) in your project's root folder and edit the paths
// accordingly.
options.rootPath = {
  project     : `${__dirname}/`,
  theme       : `${__dirname}/`,
};

options.theme = {
  name       : 'mzdrupal',
  root       : options.rootPath.theme,
  components : `${options.rootPath.theme}components/`,
  build      : `${options.rootPath.theme}components/asset-builds/`,
  css        : `${options.rootPath.theme}components/asset-builds/css/`,
  js         : `${options.rootPath.theme}js/`,
};

// Set the URL used to access the Drupal website under development. This will
// allow Browser Sync to serve the website and update CSS changes on the fly.
options.drupalURL = 'http://mzdrupal.lab/';
// options.drupalURL = 'http://localhost';

// Define the node-sass configuration. The includePaths is critical!
options.sass = {
  importer: magicImporter(),
  includePaths: [
    options.theme.components,
  ],
  outputStyle: 'expanded',
};

// Define which browsers to add vendor prefixes for.
options.autoprefixer = {
  browsers: [
    'last 2 versions',
    'not ie <= 10',
    'not ie_mob <= 10',
  ],
};

// Define the paths to the JS files to lint.
options.eslint = {
  files  : [
    `${options.rootPath.project}gulpfile.js`,
    `${options.theme.js}**/*.js`,
    `!${options.theme.js}**/*.min.js`,
    `${options.theme.components}**/*.js`,
    `!${options.theme.build}**/*.js`,
  ],
};

// If your files are on a network share, you may want to turn on polling for
// Gulp watch. Since polling is less efficient, we disable polling by default.
options.gulpWatchOptions = {};
// options.gulpWatchOptions = {interval: 1000, mode: 'poll'};


// ################################
// Load Gulp and tools we will use.
// ################################
const gulp    = require('gulp'),
  $           = require('gulp-load-plugins')(),
  browserSync = require('browser-sync').create(),
  del         = require('del'),
  // gulp-load-plugins will report "undefined" error unless you load gulp-sass manually.
  sass        = require('gulp-sass'),
  cache       = require('gulp-cached');

// The default task.
gulp.task('default', [ 'build' ]);

// #################
// Build everything.
// #################
gulp.task('build', [ 'styles:production', 'lint' ]);

// ##########
// Build CSS.
// ##########
const sassFiles = [
  `${options.theme.components}**/*.scss`,
  // Do not open Sass partials as they will be included as needed.
  // '!' + options.theme.components + '**/_*.scss',
];

gulp.task('styles', () => gulp.src(sassFiles)
  // .pipe($.sourcemaps.init())
  .pipe(cache('sass'))
  .pipe(sass(options.sass).on('error', sass.logError))
  .pipe($.autoprefixer(options.autoprefixer))
  .pipe($.rename({ dirname: '' }))
  .pipe($.size({ showFiles: true }))
  // .pipe($.sourcemaps.write('./'))
  .pipe(gulp.dest(options.theme.css))
  .pipe($.if(browserSync.active, browserSync.stream({ match: '**/*.css' }))));

gulp.task('styles:production', [ 'clean:css' ], () => gulp.src(sassFiles)
  .pipe(sass(options.sass).on('error', sass.logError))
  .pipe($.autoprefixer(options.autoprefixer))
  .pipe($.rename({ dirname: '' }))
  .pipe($.size({ showFiles: true }))
  .pipe(gulp.dest(options.theme.css)));

// #########################
// Lint Sass and JavaScript.
// #########################
gulp.task('lint', [ 'lint:sass', 'lint:js' ]);

// Lint JavaScript.
gulp.task('lint:js', () => gulp.src(options.eslint.files)
  .pipe($.eslint())
  .pipe($.eslint.format()));

// Lint JavaScript and throw an error for a CI to catch.
gulp.task('lint:js-with-fail', () => gulp.src(options.eslint.files)
  .pipe($.eslint())
  .pipe($.eslint.format())
  .pipe($.eslint.failOnError()));

// Lint Sass.
gulp.task('lint:sass', () => gulp.src(`${options.theme.components}**/*.scss`)
  .pipe($.sassLint())
  .pipe($.sassLint.format()));

// Lint Sass and throw an error for a CI to catch.
gulp.task('lint:sass-with-fail', () => gulp.src(`${options.theme.components}**/*.scss`)
  .pipe($.sassLint())
  .pipe($.sassLint.format())
  .pipe($.sassLint.failOnError()));

// ##############################
// Watch for changes and rebuild.
// ##############################
gulp.task('watch', [ 'browser-sync', 'watch:lint', 'watch:js' ]);

gulp.task('browser-sync', [ 'watch:css' ], () => {
  if (!options.drupalURL) {
    return Promise.resolve();
  }
  return browserSync.init({
    proxy: options.drupalURL,
  });
});

gulp.task('watch:css', [ 'styles' ], () => gulp.watch(`${options.theme.components}**/*.scss`, options.gulpWatchOptions, [ 'styles' ]));

gulp.task('watch:lint', [ 'lint:sass' ], () => gulp.watch([
  `${options.theme.components}**/*.scss`,
], options.gulpWatchOptions, [ 'lint:sass' ]));

gulp.task('watch:js', [ 'lint:js' ], () => gulp.watch(options.eslint.files, options.gulpWatchOptions, [ 'lint:js' ]));

// ######################
// Clean all directories.
// ######################
gulp.task('clean', [ 'clean:css' ]);

// Clean CSS files.
gulp.task('clean:css', () => del([
  `${options.theme.css}**/*.css`,
  `${options.theme.css}**/*.map`,
], { force: true }));


// Resources used to create this gulpfile.js:
// - https://github.com/google/web-starter-kit/blob/master/gulpfile.babel.js
// - https://github.com/dlmanning/gulp-sass/blob/master/README.md
// - http://www.browsersync.io/docs/gulp/
