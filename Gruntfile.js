module.exports = function(grunt) {
	grunt.initConfig( {
		pkg: grunt.file.readJSON('package.json'),

		sass: {
			dist: {
				files: {
					'includes/css/wp-listings.css': 'includes/scss/wp-listings.scss',
					'includes/css/wp-listings-single.css': 'includes/scss/wp-listings-single.scss',
					'includes/css/wp-listings-widgets.css': 'includes/scss/wp-listings-widgets.scss'
				},
				options: {
					outputStyle: 'compressed'
				}
			},
			files: {
				'includes/css/wp-listings.css': 'includes/scss/wp-listings.scss',
				'includes/css/wp-listings-single.css': 'includes/scss/wp-listings-single.scss',
				'includes/css/wp-listings-widgets.css': 'includes/scss/wp-listings-widgets.scss'
			}
		},

		makepot: {
			target: {
				options: {
					cwd: '',                          // Directory of files to internationalize.
					domainPath: '/languages',         // Where to save the POT file.
					potComments: '',                  // The copyright at the beginning of the POT file.
					potFilename: 'wp_listings.pot',   // Name of the POT file.
					potHeaders: {
						poedit: true,                 // Includes common Poedit headers.
						'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
					},                                // Headers to add to the generated POT file.
					processPot: null,                 // A callback function for manipulating the POT file.
					type: 'wp-plugin',                // Type of project (wp-plugin or wp-theme).
					updateTimestamp: true             // Whether the POT-Creation-Date should be updated without other changes.
				}
			}
		},

		watch: {
			sass: {
				files: '/includes/scss/*.scss',
				tasks: ['sass']
			}
		}
	} );

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-wp-i18n');

  grunt.registerTask('build', ['sass', 'makepot']);
  grunt.registerTask('default', ['build','watch']);
};