/*global module:false*/
module.exports = function(grunt) {
  var pkg_config = grunt.file.readJSON('wp-content/themes/tcs-responsive/config/script-register.json');

  function addBaseFilePath(files, base) {
    var new_names = [];
    files.forEach(function(file) {
      new_names.push(base + file);
    });

    return new_names
  }

  var themeCSS = 'wp-content/themes/tcs-responsive/css/';
  var themeJS = 'wp-content/themes/tcs-responsive/js/';

  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
    // build config
    build: {
      themeRoot: 'wp-content/themes/tcs-responsive',
      themeJs: '<%= build.themeRoot %>/js',
      themeDist: '<%= build.themeRoot %>/dist',
      themeCss: '<%= build.themeRoot %>/css'
    },
    banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
      '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
      '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
      '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
      ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',
    // Task configuration.
    concat: {
      options: {
        banner: '<%= banner %>',
        stripBanners: true
      },
      css: {
        files: {
          '<%= build.themeDist %>/tc.default.concat.css': addBaseFilePath(pkg_config.packages.default.css, themeCSS),
          '<%= build.themeDist %>/tc.challengelanding.concat.css': addBaseFilePath(pkg_config.packages.challengelanding.css, themeCSS),
          '<%= build.themeDist %>/tc.challenges.concat.css': addBaseFilePath(pkg_config.packages.challenges.css, themeCSS),
          '<%= build.themeDist %>/tc.challengeterms.concat.css': addBaseFilePath(pkg_config.packages.challengeterms.css, themeCSS),
          '<%= build.themeDist %>/tc.challengesubmit.concat.css': addBaseFilePath(pkg_config.packages.challengesubmit.css, themeCSS)
        }
      }
    },
    cssmin: {
      minify: {
        cwd: '<%= build.themeCss %>',
        ext: '.min.css',
        files: {
          '<%= build.themeDist %>/css/default.min.css': ['<%= build.themeDist %>/tc.default.concat.css'],
          '<%= build.themeDist %>/css/challengelanding.min.css': ['<%= build.themeDist %>/tc.challengelanding.concat.css'],
          '<%= build.themeDist %>/css/challenges.min.css': ['<%= build.themeDist %>/tc.challenges.concat.css'],
          '<%= build.themeDist %>/css/challengeterms.min.css': ['<%= build.themeDist %>/tc.challengeterms.concat.css'],
          '<%= build.themeDist %>/css/challengesubmit.min.css': ['<%= build.themeDist %>/tc.challengesubmit.concat.css']
        }
      }
    },
    uglify: {
      options: {
        banner: '<%= banner %>'
      },
      js: {
        files: {
          '<%= build.themeDist %>/js/default.min.js': addBaseFilePath(pkg_config.packages.default.js, themeJS),
          '<%= build.themeDist %>/js/challengelanding.min.js': addBaseFilePath(pkg_config.packages.challengelanding.js, themeJS),
          '<%= build.themeDist %>/js/challenges.min.js': addBaseFilePath(pkg_config.packages.challenges.js, themeJS),
          '<%= build.themeDist %>/js/challengeterms.min.js': addBaseFilePath(pkg_config.packages.challengeterms.js, themeJS),
          '<%= build.themeDist %>/js/challengesubmit.min.js': addBaseFilePath(pkg_config.packages.challengesubmit.js, themeJS)
        }
      }
    },
    clean: ['<%= build.themeDist %>/'],
    compress: {
      main: {
        options: {
          mode: 'gzip'
        },
        files: [
          {
            expand: true,
            src: [
              '<%= build.themeDist %>/js/*.min.*',
              '!<%= build.themeDist %>/js/  *.gz'
            ]
          },
          {
            expand: true,
            src: [
              '<%= build.themeDist %>/css/*.min.*',
              '!<%= build.themeDist %>/css/*.gz'
            ]
          }
        ]
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task.
  grunt.registerTask('default', ['clean', 'concat', 'cssmin', 'uglify', 'compress']);

};