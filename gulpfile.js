// Project Variables
var projectDomain = '192.168.33.10';

//CSS concat sources -> root = projectSource
var concatScssSources = [
    './node_modules/owl.carousel/dist/assets/owl.carousel.min.css'
];

//JS concat sources -> root = projectSource
var concatJsSources = [
    // './node_modules/jquery/dist/jquery.min.js',
    './node_modules/mustache/mustache.min.js',
    './node_modules/owl.carousel/dist/owl.carousel.min.js',
    './web/js/vendor/debounce.js',
    './web/js/modules/autocomplete.js',
    './web/js/modules/disableScrollingOnHover.js',
    './web/js/modules/errorHandler.js',
    './web/js/modules/logger.js',
    './web/js/modules/login.js',
    './web/js/modules/overlays.js',
    './web/js/modules/searchbox.js',
    './web/js/modules/exposeSlider.js',
    './web/js/modules/numberFormatter.js',
    './web/js/modules/wizard.js',
    './web/js/modules/maps.js',
    './web/js/modules/scrollTo.js',
    './web/js/modules/baufi-calculator.js',
    './web/js/modules/sticky-nav.js',
    './web/js/modules/contactMultiForm.js',
    './web/js/modules/rentorbuy-calculator.js',
    './web/js/modules/additionalexpenses-calculator.js',
    './web/js/modules/sell-form.js',
    './web/js/app.js'
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

gulp.task('default', ['main.min.css', 'main.min.js'],function() {
    gulp.watch('./web/scss/**/*.scss', ['main.min.css'] );
    gulp.watch('./web/js/**/*.js', ['main.min.js']);
});

gulp.task('live:default', ['default'], function() {
    gulpBrowserSync.init([
        './app/Resources/**/*.twig',
        './web/css/main.min.css',
        './web/js/main.min.js',
        './app/Resources/views/**/*.html.twig',
        './src/AppBundle/**/*.php'
    ], {
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
        .src(['./web/scss/**/*.scss', '!./web/scss/modules/vendor/**/*.scss'])
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
        .src('./web/scss/main.scss')
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
        .pipe(gulp.dest('./web/css/'));
});

gulp.task('main.min.css:concat', function() {
    concatScssSources[concatScssSources.length + 1] = './web/css/main.temp.css';
    return gulp.src(concatScssSources)
        .pipe(gulpSourcemaps.init({loadMaps: true}))
        .pipe(gulpConcat('main.min.css'))
        .pipe(gulpSourcemaps.write('./'))
        .pipe(gulp.dest('./web/css/'));
});

gulp.task('main.min.css:clean', function() {
    return gulpDel.sync(['./web/css/main.temp.css']);
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