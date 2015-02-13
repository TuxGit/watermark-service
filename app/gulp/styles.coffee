gulp         = require 'gulp'
concat       = require 'gulp-concat'
gutil        = require 'gulp-util'
gulpif       = require 'gulp-if'
compass      = require 'gulp-compass'
autoprefixer = require 'gulp-autoprefixer'
minifyCss    = require 'gulp-minify-css'
paths        = require './paths'
pkg          = require '../package.json'
addsrc       = require 'gulp-add-src'


gulp.task 'compass', () ->
    gulp.src(paths.source_dir + 'scss/*.scss')
        .pipe(compass({
            config_file: __dirname + '/../config.rb'  # \\
            # project: path.join(__dirname, '/')
            css: paths.source_dir + 'styles'
            sass: paths.source_dir + 'scss'
            image: paths.source_dir + 'img'
            # require: ['susy', 'modular-scale']
        }))
        .on('error', (error) ->
            # Would like to catch the error here
            console.log(error)
            # this.emit('end')
        )
        # .pipe(minifyCSS())
        # .pipe(gulp.dest('app/assets/temp'))


gulp.task 'styles', ['compass'], () ->
	return gulp.src paths.bower_css, cwd: 'client'
        .pipe addsrc ['*.css'], cwd: 'client/styles'
		.pipe concat 'all.min.css'
		.pipe autoprefixer(
			'Android >= ' + pkg.browsers.android
			'Chrome >= ' + pkg.browsers.chrome
			'Firefox >= ' + pkg.browsers.firefox
			'Explorer >= ' + pkg.browsers.ie
			'iOS >= ' + pkg.browsers.ios
			'Opera >= ' + pkg.browsers.opera
			'Safari >= ' + pkg.browsers.safari
		)
		.pipe minifyCss()
		.pipe gulp.dest paths.assets_dir + 'styles'
