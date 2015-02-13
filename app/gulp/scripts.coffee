gulp         = require 'gulp'
gutil        = require 'gulp-util'
# gulpif       = require 'gulp-if'
concat       = require 'gulp-concat'
uglify       = require 'gulp-uglify'
paths        = require './paths'


gulp.task 'scripts', [], () ->
	return gulp.src [
			'**/*.js'
			'!libs/**/*'
			# '*.js'
		],
			cwd: 'client/scripts'
		.pipe concat 'all.min.js'
		# TODO проверка кода и обработка ошибок 
		.pipe uglify()
		.pipe gulp.dest paths.assets_dir + 'scripts'
