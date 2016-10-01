const gulp = require('gulp');
const zip = require('gulp-zip');

gulp.task('disqus', function () {
    return gulp.src('disqus/**/**.**')
        .pipe(zip('disqus-wp-plugin.zip'))
        .pipe(gulp.dest('dist'));
});


gulp.task('default', function () {
    return gulp.src('src/**/**.**')
        .pipe(zip('comentarismo-wp-plugin.zip'))
        .pipe(gulp.dest('dist'));
});

gulp.task('poc', function () {
    return gulp.src('poc/cm-header-footer-script-loader/trunk/**/**.**')
        .pipe(zip('poc-script.zip'))
        .pipe(gulp.dest('dist'));
});