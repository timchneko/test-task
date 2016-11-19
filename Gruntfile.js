module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            js: {
                src: [
                    'js/dev/*.js'
                ],
                dest: 'js/production.js'
            },
            css: {
                src: [ 
                    'css/bootstrap.css',
                    'css/styles.css'
                ],
                dest: 'prod/css/styles.min.css'
            }
        },

        less: {
            development: {
                options: {
                  paths: ['less']
                },
                files: {
                  'css/styles.css': 'less/styles.less'
                }
            }
        },

        uglify: {
            build: {
                src: 'js/production.js',
                dest: 'prod/js/production.min.js'
            }
        },

        clean: {
            prod: ['prod/*'],
            js: ['prod/js/*'],
            css: ['prod/css/*'],
            html: ['prod/templates/*']
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
            misc: {
                expand: true,
                cwd: 'backend/',
                src: ['index.php', 'db.sq3'],
                dest: 'prod/'
            }
        },

        watch: {
            scripts: {
                files: ['js/dev/*.js'],
                tasks: ['concat:js', 'uglify']
            },
            
            less: {
                files: ['less/*.less'],
                tasks: ['less', 'concat:css']
            },
            
            html: {
                files: ['template/*'],
                tasks: ['copy:html']
            },
            
            backend: {
                files: ['backend/*.php'],
                tasks: ['copy:backend']
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', ['copy', 'less', 'concat', 'uglify']);

};