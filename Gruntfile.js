module.exports = function(grunt) {

    // 1. Вся настройка находится здесь
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            js: {
                src: [
                    'dev/js/libs/*.js' // Все JS в папке libs
                    //'js/global.js'  // Конкретный файл
                ],
                dest: 'js/production.js'
            },
            css: {
                src: [ 'css/bootstrap.css',
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
                dest: 'js/production.min.js'
            }
        }

    });

    // 3. Тут мы указываем Grunt, что хотим использовать этот плагин
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-concat');

    // 4. Указываем, какие задачи выполняются, когда мы вводим «grunt» в терминале
    grunt.registerTask('default', ['less', 'concat:css']);

};