runSequence = require 'run-sequence'
gulp        = require 'gulp'

# usage: 
# gulp [--debug]
# gulp build --prod [--debug]

gulp.task 'build', ->
	return runSequence(
		# 'clean:images'
		'copy'
		'jade'
		'styles'
		'scripts'
	)

gulp.task 'default', ->
	return runSequence(
		'jade'
		'compass'
		# 'coffee'
		'watch'
	)
