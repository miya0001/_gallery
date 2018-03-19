var gulp = require('gulp'),
	concat = require('gulp-concat'),
	minifyCSS = require('gulp-minify-css'),
	uglify = require('gulp-uglify');

gulp.task('js', function(){
	return gulp.src(["node_modules/simplelightbox/dist/simple-lightbox.js", "src/*.js"])
		.pipe(concat('script.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('js'));
});

gulp.task('css', function(){
	return gulp.src(["node_modules/simplelightbox/dist/simplelightbox.css", "src/*.css"])
		.pipe(concat('style.min.css'))
		.pipe(minifyCSS())
		.pipe(gulp.dest('css'))
});

gulp.task('default', ['js', 'css']);
