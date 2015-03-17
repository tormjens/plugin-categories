module.exports = function( grunt ) {

	// Project configuration
	grunt.initConfig( {
		pkg:    grunt.file.readJSON( 'package.json' ),
		concat: {
			options: {
				stripBanners: true,
				banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
					' * <%= pkg.homepage %>\n' +
					' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
					' * Licensed GPLv2+' +
					' */\n'
			},
			plugin_categories: {
				src: [
					'assets/js/src/plugin_categories.js'
				],
				dest: 'assets/js/plugin_categories.js'
			}
		},
		uglify: {
			all: {
				files: {
					'assets/js/plugin_categories.min.js': ['assets/js/plugin_categories.js']
				},
				options: {
					banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
						' * <%= pkg.homepage %>\n' +
						' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
						' * Licensed GPLv2+' +
						' */\n',
					mangle: {
						except: ['jQuery']
					}
				}
			}
		},
		sass:   {
			all: {
				files: {
					'assets/css/plugin_categories.css': 'assets/css/sass/plugin_categories.scss'
				}
			}
		},
		
		cssmin: {
			options: {
				banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
					' * <%= pkg.homepage %>\n' +
					' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
					' * Licensed GPLv2+' +
					' */\n'
			},
			minify: {
				expand: true,
				
				cwd: 'assets/css/',				
				src: ['plugin_categories.css'],
				
				dest: 'assets/css/',
				ext: '.min.css'
			}
		},
		watch:  {
			
			sass: {
				files: ['assets/css/sass/*.scss'],
				tasks: ['sass', 'cssmin'],
				options: {
					debounceDelay: 500
				}
			},
			
			scripts: {
				files: ['assets/js/src/**/*.js', 'assets/js/vendor/**/*.js'],
				tasks: ['concat', 'uglify'],
				options: {
					debounceDelay: 500
				}
			}
		},
		clean: {
			main: ['release/<%= pkg.version %>']
		},
		copy: {
			// Copy the plugin to a versioned release directory
			tag: {
				src:  [
					'**',
					'!node_modules/**',
					'!release/**',
					'!plugin/**',
					'!.git/**',
					'!js/src/**',
					'!img/src/**',
					'!Gruntfile.js',
					'!package.json',
					'!.gitignore',
					'!.gitmodules',
					'!assets/wordpress/banner/**',
					'!assets/wordpress/screenshots/**',
					'!assets/wordpress/**',
				],
				dest: 'plugin/tags/<%= pkg.version %>/'
			},
			banner: {
				cwd: 'assets/wordpress/banner/',
			    src: '*',
			    dest: 'plugin/assets/',
			    expand: true
			},
			screenshots: {
				cwd: 'assets/wordpress/screenshots/',
			    src: '*',
			    dest: 'plugin/tags/<%= pkg.version %>/',
			    expand: true
			},
			trunk: {
				cwd: 'plugin/tags/<%= pkg.version %>/',
			    src: '**',
			    dest: 'plugin/trunk/',
			    expand: true
			}	
		},
		compress: {
			main: {
				options: {
					mode: 'zip',
					archive: './release/plugin_categories.<%= pkg.version %>.zip'
				},
				expand: true,
				cwd: 'release/<%= pkg.version %>/',
				src: ['**/*'],
				dest: 'plugin_categories/'
			}		
		}
	} );
	
	// Load other tasks
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	
	grunt.loadNpmTasks('grunt-contrib-sass');
	
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-compress' );
	
	// Default task.
	
	grunt.registerTask( 'default', ['concat', 'uglify', 'sass', 'cssmin'] );
	
	grunt.registerTask( 'build', ['default', 'clean', 'copy', 'compress'] );

	grunt.util.linefeed = '\n';
};