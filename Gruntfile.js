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
            'assets/js/vendor/*',
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
    uglify: {
      dist: {
        files: {
          '<%= dir.dist %>/assets/js/main.js': [
            '<%= dir.src %>/assets/js/*.js'
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
  grunt.loadNpmTasks('grunt-contrib-uglify');

  // Register tasks
  grunt.registerTask('default', [
    'clean',
    'jshint',
    'copy',
    'cssmin',
    'uglify'
  ]);

};
