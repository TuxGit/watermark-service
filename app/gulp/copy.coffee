gulp    = require 'gulp'
changed = require 'gulp-changed'
clean   = require 'gulp-clean'
paths   = require './paths'


# gulp.task 'copy:images', ->
# 	return gulp.src [
# 			'**/*.{png,jpg,gif}'
# 			'!sprite/**/*'
# 		],
# 			cwd: paths.appImages
# 		.pipe gulp.dest paths.images

# copy:sprites
gulp.task 'copy:fonts', () ->
	return gulp.src [
			'**/*.*'
		],
			cwd: paths.source_dir + 'fonts'
		.pipe gulp.dest paths.assets_dir + 'fonts'


gulp.task 'clean:img', () ->
	return gulp.src([paths.assets_dir + 'img'], {read: false})
		.pipe(clean({force: true}))
		# .pipe(gulp.dest('tmp'))

# copy:sprites
gulp.task 'copy:img', ['clean:img'], () ->
	return gulp.src [
			'**/*.{png,jpg,gif}'
			'!sprites/**/*'
			'!icon/**/*'
		],
			cwd: paths.source_dir + 'img'
		.pipe gulp.dest paths.assets_dir + 'img'


gulp.task 'copy', ['copy:img', 'copy:fonts']



