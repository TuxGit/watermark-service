gulp         = require 'gulp'
gutil        = require 'gulp-util'
# gulpif       = require 'gulp-if'
concat       = require 'gulp-concat'
uglify       = require 'gulp-uglify'
paths        = require './paths'
addsrc       = require 'gulp-add-src'


gulp.task 'scripts', [], () ->
	return gulp.src paths.bower_js, cwd: 'client'
		.pipe addsrc [
			'**/*.js'
			'!libs/**/*'
			# '*.js'
		],
			cwd: 'client/scripts'
		# .pipe addsrc paths.bower_js, cwd: 'client'
		.pipe concat 'all.min.js'
		# TODO проверка кода и обработка ошибок 
		.pipe uglify()
		.pipe gulp.dest paths.assets_dir + 'scripts'
