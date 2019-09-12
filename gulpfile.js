const gulp = require('gulp');

const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');


/**
 * Files
 */
const files = { 
    sass: {
        src:    'assets/scss/**/*.scss',
        dist:   'assets/css'
    },
    js: {
        src:    ['assets/js/**/*.js', '!assets/js/app.min.js'],
        dist:   'assets/js'
    }
}

/**
 * Styles
 */
function styles () {    
    return gulp.src(files.sass.src)
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(postcss([ autoprefixer(), cssnano() ]))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(files.sass.dist)
    );
}


/**
 * Scripts
 */
function scripts () {
    return gulp.src(files.js.src)
        .pipe(concat('app.min.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))      
        .pipe(uglify())
        .pipe(gulp.dest(files.js.dist)
    );
}


/**
 * Watch for changes
 */
function watch () {
    gulp.watch(files.sass.src, styles);
    gulp.watch(files.js.src, scripts);
}



exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;
