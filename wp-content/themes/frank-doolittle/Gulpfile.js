'use strict';

// Settings
const proxy_url = 'https://frankdoolittle.vanwp.ca';
//const proxy_url = 'http://localhost:8888';

// Require our dependencies
const autoprefixer = require( 'autoprefixer' );
const babel = require( 'gulp-babel' );
const browserSync = require( 'browser-sync' );
const cheerio = require( 'gulp-cheerio' );
const concat = require( 'gulp-concat' );
const cssnano = require( 'gulp-cssnano' );
const del = require( 'del' );
const eslint = require( 'gulp-eslint' );
const gulp = require( 'gulp' );
const gutil = require( 'gulp-util' );
const imagemin = require( 'gulp-imagemin' );
const mqpacker = require( 'css-mqpacker' );
const notify = require( 'gulp-notify' );
const plumber = require( 'gulp-plumber' );
const postcss = require( 'gulp-postcss' );
const cssimport = require( 'gulp-cssimport' );
const reload = browserSync.reload;
const rename = require( 'gulp-rename' );
const sass = require( 'gulp-sass' );
const sassLint = require( 'gulp-sass-lint' );
const sort = require( 'gulp-sort' );
const sourcemaps = require( 'gulp-sourcemaps' );
const uglify = require( 'gulp-uglify' );
const wpPot = require( 'gulp-wp-pot' );

// Set assets paths.
const paths = {
	'css': [ './*.css', '!*.min.css' ],
	'images': [ 'assets/images/*', '!assets/images/*.svg' ],
	'php': [ './*.php', './**/*.php' ],
	'sass': 'assets/sass/**/*.scss',
 	'concat_scripts': ['assets/scripts/vendor/*.js','assets/scripts/concat/*.js'],
	'scripts': [ 'assets/scripts/*.js', '!assets/scripts/*.min.js', '!assets/scripts/*config.js' ,'!assets/scripts/customizer.js' ],
	'foundationJSpath': 'node_modules/foundation-sites/js/',
};

/**
 * Handle errors and alert the user.
 */
function handleErrors () {
	const args = Array.prototype.slice.call( arguments );

	notify.onError( {
		'title': 'Task Failed [<%= error.message %>',
		'message': 'See console.',
		'sound': 'Sosumi' // See: https://github.com/mikaelbr/node-notifier#all-notification-options-with-their-defaults
	} ).apply( this, args );

	gutil.beep(); // Beep 'sosumi' again.

	// Prevent the 'watch' task from stopping.
	this.emit( 'end' );
}

/**
 * Delete style.css and style.min.css before we minify and optimize
 */
gulp.task( 'clean:styles', () => {
	return del( [ 'style.css', 'style.min.css' ] );
});



/**
 * Compile Sass and run stylesheet through PostCSS.
 *
 * https://www.npmjs.com/package/gulp-sass
 * https://www.npmjs.com/package/gulp-postcss
 * https://www.npmjs.com/package/gulp-autoprefixer
 * https://www.npmjs.com/package/css-mqpacker
 */
gulp.task( 'scss', [ 'clean:styles' ],  () => {
  return gulp.src( 'assets/sass/*.scss' )
    .pipe( plumber( {'errorHandler': handleErrors} ) )
    .pipe( sourcemaps.init() )
    // Compile Sass using LibSass.
    .pipe( sass( {
        'errLogToConsole': true,
        'outputStyle': 'expanded' // Options: nested, expanded, compact, compressed
    } ) )

    // Parse with PostCSS plugins.
    .pipe( postcss( [
        autoprefixer(),
        mqpacker( {
            'sort': true
        } )
    ] ) )


    // Added new CSS nano task
    .pipe( cssnano( {
        'safe': true // Use safe optimizations.
    } ) )

    // Create sourcemap.
    .pipe(sourcemaps.write('.'))

    // Create style.css.
    .pipe( gulp.dest( './' ) )
    .pipe( reload( { stream: true } ) );
} );


/**
 * Optimize images.
 *
 * https://www.npmjs.com/package/gulp-imagemin
 */
gulp.task( 'imagemin', () => {
	return gulp.src( paths.images )
		.pipe( plumber( {'errorHandler': handleErrors} ) )
		.pipe( imagemin( {
			'optimizationLevel': 5,
			'progressive': true,
			'interlaced': true
		} ) )
		.pipe( gulp.dest( 'assets/images' ) );
});




gulp.task('foundation-js', () => {
	return gulp.src([

		/* Choose what JS Plugin you'd like to use. Note that some plugins also
		require specific utility libraries that ship with Foundation—refer to a
		plugin's documentation to find out which plugins require what, and see
		the Foundation's JavaScript page for more information.
		http://foundation.zurb.com/sites/docs/javascript.html */

		// Core Foundation - needed when choosing plugins ala carte
		paths.foundationJSpath + 'foundation.core.js',

		// Choose the individual plugins you want in your project
		paths.foundationJSpath + 'foundation.dropdown.js',

		paths.foundationJSpath + 'foundation.equalizer.js',

		//paths.foundationJSpath + 'foundation.tabs.js',

		paths.foundationJSpath + 'foundation.accordion.js',
        
        paths.foundationJSpath + 'foundation.tooltip.js',
        
 		paths.foundationJSpath + 'foundation.accordionMenu.js',

		/*
		paths.foundationJSpath + 'foundation.abide.js',

		paths.foundationJSpath + 'foundation.drilldown.js',

		

		paths.foundationJSpath + 'foundation.interchange.js',
		paths.foundationJSpath + 'foundation.magellan.js',

		paths.foundationJSpath + 'foundation.orbit.js',
		

		paths.foundationJSpath + 'foundation.slider.js',
		paths.foundationJSpath + 'foundation.sticky.js',


		

		*/
        
        paths.foundationJSpath + 'foundation.dropdownMenu.js',
        
		paths.foundationJSpath + 'foundation.offcanvas.js',
        
        paths.foundationJSpath + 'foundation.responsiveMenu.js',
		paths.foundationJSpath + 'foundation.responsiveToggle.js',

		paths.foundationJSpath + 'foundation.toggler.js',

		paths.foundationJSpath + 'foundation.reveal.js',

		paths.foundationJSpath + 'foundation.util.mediaQuery.js',
 		paths.foundationJSpath + 'foundation.util.box.js',
		paths.foundationJSpath + 'foundation.util.triggers.js',

		paths.foundationJSpath + 'foundation.util.keyboard.js',
		paths.foundationJSpath + 'foundation.util.motion.js',
		paths.foundationJSpath + 'foundation.util.timerAndImageLoader.js',
		
		paths.foundationJSpath + 'foundation.util.nest.js',
        
		paths.foundationJSpath + 'foundation.util.touch.js',
	
	])
	.pipe(babel({
		presets: ['es2015'],
		compact: false
	}))
	.pipe(concat('foundation.js'))
 	.pipe( gulp.dest( 'assets/scripts' ) );
});



/**
 * Concatenate and transform JavaScript.
 *
 * https://www.npmjs.com/package/gulp-concat
 * https://github.com/babel/gulp-babel
 * https://www.npmjs.com/package/gulp-sourcemaps
 */
gulp.task( 'concat', () => {
	return gulp.src(paths.concat_scripts)

		// Deal with errors.
		.pipe( plumber(
			{'errorHandler': handleErrors}
		) )

		// Start a sourcemap.
		.pipe( sourcemaps.init() )

		// Convert ES6+ to ES2015.
		.pipe( babel( {
			presets: [ 'es2015' ]
		} ) )

		// Concatenate partials into a single script.
		.pipe( concat( 'project.js' ) )

		// Append the sourcemap to project.js.
		.pipe( sourcemaps.write('.') )

		.pipe( gulp.dest( 'assets/scripts' ) )

		.pipe( reload( { stream: true } ) );
});

/**
  * Minify compiled JavaScript.
  *
  * https://www.npmjs.com/package/gulp-uglify
  */
gulp.task( 'uglify', () => {
	return gulp.src( paths.scripts )
		.pipe( rename( {'suffix': '.min'} ) )
		.pipe( uglify( {
			'mangle': false
		} ).on('error', function(e){
            console.log(e);
         }) )
		.pipe( gulp.dest( 'assets/scripts' ) );
});



/**
 * Delete the theme's .pot before we create a new one.
 */
gulp.task( 'clean:pot', () => {
	return del( [ 'languages/_s.pot' ] );
});


/**
 * Scan the theme and create a POT file.
 *
 * https://www.npmjs.com/package/gulp-wp-pot
 */
gulp.task( 'wp-pot', [ 'clean:pot' ], () => {
	return gulp.src( paths.php )
		.pipe( plumber( {'errorHandler': handleErrors} ) )
		.pipe( sort() )
		.pipe( wpPot( {
			'domain': '_s',
			'package': '_s',
		} ) )
		.pipe( gulp.dest( 'languages/_s.pot' ) );
});

/**
 * Sass linting.
 *
 * https://www.npmjs.com/package/sass-lint
 */
gulp.task( 'sass:lint', () => {
	return gulp.src( [
		'assets/sass/**/*.scss',
		'!assets/sass/base/_normalize.scss',
		'!assets/sass/base/_sprites.scss',
		'!node_modules/**'
	] )
		.pipe( sassLint() )
		.pipe( sassLint.format() )
		.pipe( sassLint.failOnError() );
});


/**
 * JavaScript linting.
 *
 * https://www.npmjs.com/package/gulp-eslint
 */
gulp.task( 'js:lint', () => {
	return gulp.src( [
		'assets/scripts/concat/*.js',
		'assets/scripts/*.js',
        '!assets/scripts/vendor/*.js',
		'!assets/scripts/project.js',
		'!assets/scripts/*.min.js',
		'!assets/scripts/*config.js',
		'!Gruntfile.js',
		'!Gulpfile.js',
		'!node_modules/**'
	] )
		.pipe( eslint() )
		.pipe( eslint.format() )
		.pipe( eslint.failAfterError() );
});


gulp.task('bower', () => {
    return gulp.src([
            'assets/bower_components/slick-carousel/slick/slick.js'])
    .pipe(gulp.dest('assets/scripts/vendor/'));
});

/**
 * Process tasks and reload browsers on file changes.
 *
 * https://www.npmjs.com/package/browser-sync
 */
gulp.task( 'watch', function () {

	// Kick off BrowserSync.
	browserSync( {
		'open': true,             // Open project in a new tab?
		'notify': true,
		'injectChanges': true,     // Auto inject changes instead of full reload.
		'proxy': proxy_url,    // Use http://_s.com:3000 to use BrowserSync.
		'watchOptions': {
			'debounceDelay': 1000  // Wait 1 second before injecting.
		},
		/*
		'https': {
			'key': '/localhost.key',
			'cert': '/localhost.crt'
		}
        */
		 'https': {
			'key': '/Users/kylerumble/Documents/Certificates/localhost.key',
			'cert': '/Users/kylerumble/Documents/Certificates/localhost.crt'
		}, 
	} );

	// Run tasks when files change.
 	gulp.watch( paths.sass, [ 'styles' ] );
	gulp.watch( paths.concat_scripts, [ 'concat' ] );
	gulp.watch( paths.scripts, [ 'scripts' ] );
 	gulp.watch( paths.php, [ 'markup' ] );
} );

/**
 * Create individual tasks.
 */
gulp.task( 'markup', reload );
gulp.task( 'i18n', [ 'wp-pot' ] );
//gulp.task( 'icons', [ 'svg' ] );
gulp.task( 'scripts', [ 'uglify' ] );
gulp.task( 'styles', [ 'scss' ] );
gulp.task( 'lint', [ 'sass:lint', 'js:lint' ] );
gulp.task( 'default', [ 'i18n', 'styles', 'bower', 'foundation-js', 'concat', 'scripts', 'imagemin'] );
