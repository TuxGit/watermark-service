gulp         = require 'gulp'
gutil        = require 'gulp-util'
jade         = require 'gulp-jade'
affected     = require 'gulp-jade-find-affected'
prettify     = require 'gulp-prettify'
pkg          = require '../package.json'
paths        = require './paths'

gulp.task 'jade', ->
	_env = if gutil.env.prod then 'prod' else 'dev'
	_dest = if gutil.env.prod then paths.build_dir else paths.source_dir + 'html'
	return gulp.src paths.source_dir + '/jade/pages/**/*.jade'
		.pipe affected()
		.pipe jade
			data:
				env: _env
				bower_css: paths.bower_css
				bower_js: paths.bower_js
				page:					
					copyright: pkg.copyright
					description: pkg.description
					keywords: pkg.keywords.join ', '
					title: pkg.title
		# test!
		.on('error', (error) ->
            console.log(error)
        )
		.pipe prettify
			brace_style: 'expand'
			indent_size: 1
			indent_char: '\t'
			indent_with_tabs: true
			condense: true
			indent_inner_html: true
			preserve_newlines: true
		.pipe gulp.dest _dest