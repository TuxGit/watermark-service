require('coffee-script/register');
// require('./gulpfile.coffee');

require('./gulp/jade.coffee');
require('./gulp/styles.coffee');
require('./gulp/scripts.coffee');
require('./gulp/copy.coffee');
//bower task ??
require('./gulp/default.coffee');
require('./gulp/watch.coffee');


// gulp = require('gulp');
// gutil = require('gulp-util');
// gulp.task('default', [], function (){
// 	// gulp --env dev
// 	// gulp --env
// 	console.log(gutil.env); //{ _: [], env: 'dev' } // { _: [], dev: true }
// });