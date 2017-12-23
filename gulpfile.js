/**
 * @todo ConfigFile erstellen, damit jeder eine andere URL eintragen kann.
 * @todo JS-Dateien müssen noch minifiziert werden.
 */

// Project Variables
var projectDomain = '192.168.33.10';

var sourcePathScss = './scss/';
var targetPathCss = './css/';

//Files on Watchlist.
var watchLiveReload = [
    './app/Resources/**/*.twig',
    './web/css/main.min.css',
    './web/js/main.min.js',
    './app/Resources/views/**/*.html.twig',
    './src/AppBundle/**/*.php'
];

var watchFilesScss = './web/scss/**/*.scss';
var watchFilesJs = './web/js/**/*.js';

var scssLintFiles = [
    './web/scss/**/*.scss',
    '!./web/scss/modules/vendor/**/*.scss'
];

//CSS concat sources -> root = projectSource
var concatScssSources = [
];

//JS concat sources -> root = projectSource
var concatJsSources = [
    './node_modules/jquery/dist/jquery.min.js'
];

// Gulp
var gulp = require('gulp');
var gulpSass = require('gulp-sass');
var gulpRename = require('gulp-rename');
var gulpBrowserSync = require('browser-sync').create();
var gulpSourcemaps = require('gulp-sourcemaps');
var gulpUglify = require('gulp-uglify');
var gulpConcat = require('gulp-concat');
var gulpDel = require('del');
var gulpSequence = require('gulp-sequence');
var gulpPlumber = require('gulp-plumber');
var nodeNotifier = require('node-notifier');
var gulpJshint = require('gulp-jshint');
var gulpPostcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var stylelint = require('gulp-stylelint');

gulp.task('default', [
    'main.min.css'
    // 'main.min.js'
],function() {
    gulp.watch(watchFilesScss, ['main.min.css'] );
    // gulp.watch(watchFilesJs, ['main.min.js']);
});

gulp.task('live:default', ['default'], function() {
    gulpBrowserSync.init(watchLiveReload, {
        proxy: projectDomain,
        host: projectDomain,
        open: false,
        ghostMode: false
    });
});

gulp.task('main.min.css', function(callback) {
    gulpSequence('main.min.css:lint', 'main.min.css:sass','main.min.css:concat', 'main.min.css:clean')(callback)
});

gulp.task('main.min.css:lint', function() {
    return gulp
        .src(scssLintFiles)
        .pipe(stylelint({
            failAfterError: false,
            reportOutputDir: './',
            reporters: [
                {formatter: 'string', console: true}
            ]
        }));
});

gulp.task('main.min.css:sass', function() {
    return gulp
        .src(sourcePathScss + 'main.scss')
        .pipe(gulpSourcemaps.init())
        .pipe(gulpSass({
            errLogToConsole: true,
            outputStyle: 'compressed'
        }).on('error', gulpSass.logError))
        .pipe(gulpRename('main.temp.css'))
        .pipe(gulpPostcss([
            autoprefixer('> .1%')
        ]))
        .pipe(gulpSourcemaps.write({includeContent: false, sourceRoot: '../scss'}))
        .pipe(gulp.dest(targetPathCss));
});

gulp.task('main.min.css:concat', function() {
    concatScssSources[concatScssSources.length + 1] = targetPathCss + 'main.temp.css';
    return gulp.src(concatScssSources)
        .pipe(gulpSourcemaps.init({loadMaps: true}))
        .pipe(gulpConcat('main.min.css'))
        .pipe(gulpSourcemaps.write('./'))
        .pipe(gulp.dest(targetPathCss));
});

gulp.task('main.min.css:clean', function() {
    return gulpDel.sync([targetPathCss + 'main.temp.css']);
});

gulp.task('main.min.js', function(callback) {
    gulpSequence('main.min.js:uglify','main.min.js:concat', 'main.min.js:clean')(callback)
});

gulp.task('main.min.js:uglify', function () {
    return gulp.src('./web/js/main.js')
        .pipe(gulpPlumber({errorHandler: function(error){
                console.log(error);
                if(error.plugin === 'gulp-uglify') {
                    nodeNotifier.notify({
                        title: 'Error: ' + error.message,
                        message: error.cause.message + '\nFile: ' + error.cause.filename
                    });
                } else if(error.plugin === 'gulp-jshint') {
                    nodeNotifier.notify({
                        title: 'Error: ' + error.message,
                        message: ' '
                    });
                }
                this.emit('end');
            }}))
        .pipe(gulpJshint())
        .pipe(gulpJshint.reporter('jshint-stylish'))
        .pipe(gulpJshint.reporter('fail'))
        .pipe(gulpSourcemaps.init())
        .pipe(gulpUglify({}))
        .pipe(gulpRename('main.temp.js'))
        .pipe(gulpSourcemaps.write())
        .pipe(gulp.dest('./web/js/'));
});

gulp.task('main.min.js:concat', function() {
    concatJsSources[concatJsSources.length] = './web/js/main.temp.js';
    return gulp.src(concatJsSources)
        .pipe(gulpSourcemaps.init({loadMaps: true}))
        .pipe(gulpConcat('main.min.js'))
        .pipe(gulpSourcemaps.write('./'))
        .pipe(gulp.dest('./web/js/'));
});

gulp.task('main.min.js:clean', function() {
    return gulpDel.sync(['./web/js/main.temp.js']);
});