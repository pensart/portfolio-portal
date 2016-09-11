// =======================================================================
//      Created by Guy Pensart on 12/09/2016.
// =======================================================================

// --   General
var     gulp = require('gulp'),
        gulpRename = require('gulp-rename'),
        plumber = require('gulp-plumber'
        );

// --   Styling
var     sass = require('gulp-sass'),
        sourceMaps = require('gulp-sourcemaps'),
        autoPrefixer = require('gulp-autoprefixer'
        );

// --   Javascript
var     babel = require('gulp-babel');


// =======================================================================
//      AVAILABLE TASKS
// =======================================================================

gulp.task('styles', function () {
    return gulp.src('src/**/*.scss')
        .pipe(plumber({
            errorHandler: function(error) {
                console.log(error.message);
                this.emit('end');
            }
        }))
        .pipe(sourceMaps.init())
        .pipe(sass())
        .pipe(autoPrefixer({
            browsers: ['last 3 versions'],
            cascade: false
        }))
        .pipe(gulpRename({
            dirname: 'css',
        }))
        .pipe(sourceMaps.write())
        .pipe(gulp.dest('./'))
});

gulp.task('scripts', function() {
    return gulp.src('src/js/**/*.js')
        .pipe(babel({
            presets: ['es2015']
        }))
        .pipe(gulp.dest('./js'));
});

// =======================================================================
//      DEFINE WATCHERS WHEN THINGS CHANGE
// =======================================================================

gulp.task('default', ['styles', 'scripts'], function() {
    gulp.watch('src/**/*.scss', ['styles']);
    gulp.watch('src/js/**/*.js', ['scripts']);
});