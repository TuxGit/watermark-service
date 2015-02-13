gulp        = require 'gulp'
runSequence = require 'run-sequence'
#my include
paths = require('./paths')


gulp.task 'watch', ->

	gulp.watch paths.source_dir + 'jade/**/*.jade', -> runSequence 'jade'

	gulp.watch paths.source_dir + 'scss/*.scss', -> runSequence 'compass'

	# gulp.watch [
	# 		paths.source_dir + 'coffee/*.coffee', 
	# 		paths.source_dir + 'coffee/**/*.coffee'
	# 	],
	# 	-> runSequence 'coffee', reload
