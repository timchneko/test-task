module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        less: {
            development: {
                options: {
                  paths: ['less']
                },
                files: {
                  'css/styles.css': 'less/styles.less',
                  'css/admin.css': 'less/admin.less'
                }
            }
        },

        uglify: {
            build: {
                expand: true,
                cwd: 'js/',
                src: '*/**.js',
                dest: 'prod/js/',
                rename: function(dest, src) { return dest + src.substring(src.lastIndexOf('/')+1, src.lastIndexOf('.')) + '.min.js' }
            }
        },

        copy: {
            backend: {
                expand: true,
                cwd: 'backend/',
                src: ['view.php', 'controller.php', 'model.php', 'db.php'],
                dest: 'prod/backend/'
            },
            html: {
                expand: true,
                cwd: 'templates/',
                src: '*',
                dest: 'prod/templates/'
            },
            fonts: {
                expand: true,
                cwd: 'fonts/',
                src: '*',
                dest: 'prod/fonts/'
            },
            css: {
                expand: true,
                cwd: 'css/',
                src: '*',
                dest: 'prod/css/'
            },
            misc: {
                expand: true,
                cwd: 'backend/',
                src: ['index.php'],
                dest: 'prod/'
            }
        },

        watch: {
            scripts: {
                files: ['js/dev/*.js', 'js/lib/*.js'],
                tasks: ['uglify']
            },
            
            less: {
                files: ['less/*.less'],
                tasks: ['less', 'copy:css']
            },
            
            html: {
                files: ['templates/*'],
                tasks: ['copy:html']
            },
            
            backend: {
                files: ['backend/*.php'],
                tasks: ['copy:backend']
            },
            
            misc: {
                files: ['backend/index.php'],
                tasks: ['copy:misc']
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', ['less', 'copy']);
    grunt.registerTask('release', ['default', 'uglify']);

};