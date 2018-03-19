var gulp = require('gulp'),
	gp_concat = require('gulp-concat'),
	gp_rename = require('gulp-rename'),
	gp_uglify = require('gulp-uglify');

gulp.task('js-fef', function(){
	return gulp.src("src/*.js")
		.pipe(gp_concat('script.min.js'))
		// .pipe(gulp.dest('js'))
		// .pipe(gp_rename('uglify.js'))
		.pipe(gp_uglify())
		.pipe(gulp.dest('dist'));
});

gulp.task('default', ['js-fef'], function(){});
