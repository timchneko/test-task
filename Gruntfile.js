module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            js: {
                src: [
                    'js/*.js'
                ],
                dest: 'js/production.js'
            },
            css: {
                src: [ 
                    'css/bootstrap.css',
                    'css/styles.css'
                ],
                dest: 'backend/styles.min.css'
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
                dest: 'backend/production.min.js'
            }
        },

        clean: ['js/production.js']

    });

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['clean', 'less', 'concat', 'uglify']);

};