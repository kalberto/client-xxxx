// Dependencias
var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    clean = require('gulp-clean'),
    useref = require('gulp-useref'),
    gulpif = require('gulp-if'),
    stylus = require('gulp-stylus'),
    watch = require('gulp-watch'),
    livereload = require('gulp-livereload'),
    minifyCSS = require('gulp-minify-css'),
    minifyHTML = require('gulp-minify-html'),
    notify = require('gulp-notify'),
    gulpNgConfig = require('gulp-ng-config'),
    rename = require("gulp-rename"),
    replace = require('gulp-replace');
var parseArgs   = require('minimist');

var environments = require('./environments.json');
var currentEnv = process.env.env || 'dev';

gulp.task('set-dev-node-env', function() {
	return process.env.env = currentEnv = 'development';
});
gulp.task('set-prod-node-env', function() {
	return process.env.env = currentEnv = 'production';
});
gulp.task('set-stag-node-env', function() {
	return process.env.env = currentEnv = 'staging';
});

var args = parseArgs(process.argv.slice(2));
if(args && args.env !== undefined && args.env !== ''){
    currentEnv = process.env.env = args.env;
}
console.log(currentEnv);

// Check if environment exist
if(!environments[currentEnv]){
    return console.error('Esse ambiente não foi configurado');
}

// Dist folder path
var distFolder = 'dist';

// Delete the dist directory
gulp.task('clean', function() {
    return gulp.src(distFolder)
        .pipe(clean({force: true}));
});

// Compile Stylus to Css Dist
gulp.task('stylus', ['clean'], function () {
    gulp.src('assets/css/main.styl')
        .pipe(stylus({compress: true}))
        .pipe(gulp.dest(distFolder+'/assets/css'));
});

// Compile Stylus to Css Src
gulp.task('stylusSrc', function () {
    gulp.src('assets/css/main.styl')
        .pipe(stylus().on("error", notify.onError({
            message: "<%= error.message %>",
            title: "Erro no Stylus"
        })))
        .pipe(gulp.dest('assets/css'))
        .pipe(livereload());
});

// Concat and Uglify Script/Styles in index.html
gulp.task('assets', ['clean', 'angularEnvironment'], function () {
    currentEnv = process.env.env || 'production';
    var assets = useref();
    return gulp.src('index.html')
        .pipe(assets)
        .pipe(gulpif('*.js', uglify({mangle: false})))
        .pipe(gulpif('*.css', minifyCSS()))
        //.pipe(assets.restore())
        .pipe(useref())
        .pipe(replace(/<base href=\"(.*?)\">/g, '<base href="'+environments[currentEnv].baseUrl+'">'))
        .pipe(gulp.dest(distFolder));
});

// Concat and Uglify Script/Styles in index.html
gulp.task('html', ['clean', 'assets'], function () {
    return gulp.src('dist/index.html')
        .pipe(minifyHTML({empty: true}))
        .pipe(gulp.dest(distFolder));
});

// Copy other files
gulp.task('copy', ['clean'], function() {

    // Copy fonts
    gulp.src('assets/fonts/**')
        .pipe(gulp.dest(distFolder+'/assets/fonts'));

    // Copy html templates
    gulp.src(['sections/*.html', 'sections/**/*.html'], {base: "."})
        .pipe(gulp.dest(distFolder))

    // Copy images
    gulp.src('assets/img/**')
        .pipe(gulp.dest(distFolder+'/assets/img'));

    // Copy other files
    gulp.src(['.htaccess'])
        .pipe(gulp.dest(distFolder));


});

gulp.task('angularEnvironment', function () {
    gulp.src('environments.json')
        .pipe(gulpNgConfig('app.environment', {
            environment: currentEnv
        }))
        .pipe(rename("app.environment.js"))
        .pipe(gulp.dest('.'));
});

gulp.task('changeBaseUrl', function(){
    var file =
        gulp.src('index.html')
            .pipe(replace(/<base href=\"(.*?)\">/g, '<base href="'+environments[currentEnv].baseUrl+'">'))
            .pipe(gulp.dest('./'));
});

// Task to build app
gulp.task('build', ['clean', 'angularEnvironment', 'assets', 'stylus', 'html', 'copy']);

// Task to watch changes in dev mode
gulp.task('watch', function(){

    // Start Tasks
    gulp.start('stylusSrc');
    gulp.start('changeBaseUrl');
    gulp.start('angularEnvironment');

    // HTML
    livereload.listen();
    watch(['*.html'], function(){
        livereload.reload();
    });

    // Stylus
    watch(['assets/css/**/*.styl', 'assets/css/*.styl'], function(){
        gulp.start('stylusSrc');
    });

    // Environments
    watch(['environments.json'], function(){
        gulp.start('changeBaseUrl');
        gulp.start('angularEnvironment');
    });

});