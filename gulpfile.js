const gulp = require('gulp');

const sass = require('gulp-sass');
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const sassGlob = require('gulp-sass-glob');


/**
 * Files
 */
const files = { 
    sass: {
        src:    'assets/scss/*.scss',
        watch:  'assets/scss/**/*.scss',
        dist:   'assets/css'
    },
    js: {
        src:    ['assets/js/*.js', '!assets/js/app.min.js'],
        watch:  ['assets/js/*.js', '!assets/js/app.min.js'],
        dist:   'assets/js'
    }
}

/**
 * scss
 */
gulp.task('scss' , function () {

    console.log('Compiling SCSS.');
    
    return gulp.src(files.sass.src)
        .pipe(sassGlob())
        .pipe(sass())
        .pipe(postcss([ autoprefixer(), cssnano() ]))
        .pipe(gulp.dest(files.sass.dist)
    );

});


/**
 * js
 */
gulp.task('js' , function () {

    console.log('Compiling JS.');

    return gulp.src(files.js.src)
        .pipe(concat('app.min.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))      
        .pipe(uglify())
        .pipe(gulp.dest(files.js.dist)
    );

});


/**
 * Watch for changes
 */
gulp.task('watch' , function () {
    console.log('Watching for changes.');
    
    gulp.watch(files.sass.watch, gulp.series('scss'));
    gulp.watch(files.js.watch, gulp.series('js'));
    
    return;
});



exports.default = gulp.series('scss','js','watch');