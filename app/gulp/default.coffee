runSequence = require 'run-sequence'
gulp        = require 'gulp'

# usage: 
# gulp [--debug]
# gulp build --prod [--debug]

gulp.task 'build', ['copy', 'jade', 'styles', 'scripts'], ->
	# return runSequence(
	# 	# 'clean:images'
	# 	'copy'
	# 	'jade'
	# 	'styles'
	# 	'scripts'
	# )

gulp.task 'default', ['jade', 'compass', 'watch'], ->
	# return runSequence(
	# 	'jade'
	# 	'compass'
	# 	# 'coffee'
	# 	'watch'
	# )
