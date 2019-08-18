module.exports = function( grunt ) {

	// load all grunt tasks in package.json matching the `grunt-*` pattern
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration
	grunt.initConfig( {
		pkg:    grunt.file.readJSON( 'package.json' ),
		addtextdomain: {
	        target: {
	            files: {
	                src: [
	                    '*.php',
	                    '**/*.php',
	                    '!.sass-cache/**',
	                    '!assets/**',
	                    '!images/**',
	                    '!node_modules/**',
	                    '!tests/**'
	                ]
	            }
	        }
	    },
		makepot: {
            target: {
                options: {
                	exclude: [
	                    'assets/.*', 'images/.*', 'node_modules/.*', 'tests/.*', 'release/.*', 'build/.*'
	                ],
                    domainPath: '/languages',
                    mainFile: 'wpum-username-length.php',
                    potFilename: 'wpum-username-length.pot',
                    potHeaders: {
                        poedit: true,                 // Includes common Poedit headers.
                        'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
                    },
                    type: 'wp-plugin'
                }
            }
        },
		clean: {
			main: ['release'],
			post_build: [
               'build'
           	]
		},
		copy: {
			// Copy the plugin to a versioned release directory
			main: {
				src:  [
					'**',
					'!node_modules/**',
					'!release/**',
					'!.git/**',
					'!.sass-cache/**',
					'!css/src/**',
					'!js/src/**',
					'!img/src/**',
					'!Gruntfile.js',
					'!package.json',
					'!.gitignore',
					'!.gitmodules',
					'!distributewp.json',
					'!composer.json',
					'!readme.md',
					'!yarn.lock'
				],
				dest: 'release/<%= pkg.name %>/'
			}
		},
		compress: {
			main: {
				options: {
					mode: 'zip',
					archive: './release/wpum-username-length.<%= pkg.version %>.zip'
				},
				expand: true,
				cwd: 'release/<%= pkg.version %>/',
				src: ['**/*'],
				dest: 'wpum-username-length/'
			}
		},
		replace: {
			readme_txt: {
				src: [ 'readme.txt' ],
				overwrite: true,
				replacements: [{
					from: /Stable tag: (.*)/,
					to: "Stable tag: <%= pkg.version %>"
				}]
			},
			init_php: {
				src: [ 'wpum-username-length.php' ],
				overwrite: true,
				replacements: [{
					from: /Version:\s*(.*)/,
					to: "Version: <%= pkg.version %>"
				}, {
					from: /define\(\s*'WPUM_VERSION',\s*'(.*)'\s*\);/,
					to: "define( 'WPUM_VERSION', '<%= pkg.version %>' );"
				}]
			}
		}
	} );

	grunt.loadNpmTasks('git-changelog');

	// Default task.
	grunt.registerTask( 'default', ['concat', 'uglify', 'sass', 'cssmin'] );
	grunt.registerTask( 'textdomain', ['addtextdomain'] );
	grunt.registerTask( 'do_pot', ['makepot'] );
	grunt.registerTask( 'do_changelog', ['git_changelog'] );
	grunt.registerTask( 'version_number', [ 'replace:readme_txt', 'replace:init_php' ] );
	grunt.registerTask( 'pre_vcs', [ 'version_number' ] );
	grunt.registerTask( 'do_svn', [ 'svn_checkout', 'copy:svn_trunk', 'copy:svn_tag', 'push_svn' ] );
	grunt.registerTask( 'do_git', [  'gitcommit', 'gittag', 'gitpush' ] );
	grunt.registerTask( 'release', [ 'pre_vcs', 'do_svn', 'do_git', 'clean:post_build' ] );

	grunt.registerTask( 'build', ['clean', 'copy', 'compress'] );

	grunt.util.linefeed = '\n';
};
