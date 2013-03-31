'use strict';
module.exports = function(grunt) {

    grunt.initConfig({
        dir: {
            src: 'src',
            dist: 'dist'
        },
        clean: {
            dist: [
                'dist'
            ]
        },
        copy: {
            dist: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= dir.src %>',
                    dest: '<%= dir.dist %>',
                    src: [
                        'assets/css/editor-style.css',
                        'assets/img/{,*/}*.{webp,gif}',
                        'lang/*',
                        'lib/*',
                        'templates/*',
                        '*.{css,ico,php,png}'
                    ]
                }]
            }
        },
        cssmin: {
            dist: {
                files: {
                    '<%= dir.dist %>/assets/css/main.css': [
                        '<%= dir.src %>/assets/css/*.css',
                        '!<%= dir.src %>/assets/css/editor-style.css'
                    ]
                }
            }
        },
        jshint: {
            options: {
                jshintrc: '.jshintrc'
            },
            all: [
                'Gruntfile.js',
                '<%= dir.src %>/assets/js/*.js'
            ]
        },
        'string-replace': {
            dist: {
                options: {
                    replacements: [
                        {
                            pattern: /(wp_register_script\('jquery',(\s*[^,]+,))\s*[^,]+,\s*([^\)]+)\);/,
                            replacement: ''
                        },
                        {
                            pattern: /(wp_enqueue_script\('jquery')\);/,
                            replacement: ''
                        }
                    ]
                },
                files: {
                    '<%= dir.dist %>/lib/theme.php': [
                        '<%= dir.dist %>/lib/theme.php'
                    ]
                }
            }
        },
        uglify: {
            dist: {
                files: {
                    '<%= dir.dist %>/assets/js/main.js': [
                        '<%= dir.src %>/assets/js/vendor/jquery-1.9.1.js',
                        '<%= dir.src %>/assets/js/*.js'
                    ],
                    '<%= dir.dist %>/assets/js/vendor/modernizr-2.6.2.js': [
                        '<%= dir.src %>/assets/js/vendor/modernizr-2.6.2.js'
                    ]
                }
            }
        }
    });

    // Load tasks
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-string-replace');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // Register tasks
    grunt.registerTask('default', [
        'clean',
        'jshint',
        'copy',
        'string-replace',
        'cssmin',
        'uglify'
    ]);

};
