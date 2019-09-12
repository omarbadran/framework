const { src, dest, watch, series, parallel } = require('gulp');

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
        src:    'assets/js/**/*.js',
        dist:   'assets/js'
    }
}

/**
 * Compile SASS
 */
const compileSASS = () => {    
    return src(files.sass.src)
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(postcss([ autoprefixer(), cssnano() ]))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(files.sass.dist)
    );
}

/**
 * Compile javascript
 */
const compileJS = () => {
    return src([files.js.src])
        .pipe(concat('app.min.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))      
        .pipe(uglify())
        .pipe(dest(files.js.dist)
    );
}

/**
 * Watch for changes
 */
const change = () => {
    watch([files.sass.src, files.js.src], 
        parallel(compileSASS, compileJS));    
}

/**
 * Default Task
 */
exports.default = series(
    parallel(compileSASS, compileJS), 
    change
);