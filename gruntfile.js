module.exports = function(grunt) {
    var files = {
        css: {
            'www/css/deploy.css': 'www/css/src/deploy.less'
        },
        js: {
            "www/js/deploy.js": [
                "bower_components/jquery/dist/jquery.js",
                "bower_components/jquery-ui/jquery-ui.js",
                "bower_components/jqueryui-touch-punch/jquery.ui.touch-punch.js",
                "bower_components/bootstrap/js/dropdown.js",
                "bower_components/bootstrap/js/collapse.js",
                "bower_components/bootstrap/js/transition.js",
                "bower_components/bootstrap/js/modal.js",
                "bower_components/bootstrap/js/button.js",
                "bower_components/nette-forms/src/assets/netteForms.js",
                "bower_components/nette.ajax.js/nette.ajax.js",
                "www/js/src/*.js"
            ]
        },
        copy: {
            fonts: {
                cwd: 'bower_components/bootstrap/fonts/',
                src: '*',
                dest: 'www/fonts/',
                expand: true
            }
        }
    };

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        less: {
            development: {
                files: files.css
            },
            production: {
                files: files.css,
                options: {
                    cleancss: true,
                    ieCompat: true
                }
            }
        },

        uglify: {
            development: {
                files: files.js,
                options: {
                    preserveComments: 'all',
                    beautify: {
                        beautify: true
                    }
                    //compress: false
                }
            },
            production: {
                files: files.js,
                options: {
                    preserveComments: false,
                    //sourceMap: true,
                    //sourceMapName: "dist/jquery.min.map",
                    //report: "min",
                    //beautify: {
                        //"ascii_only": true
                    //},
                    //banner: "/*! jQuery v<%= pkg.version %> | " +
                    //"(c) jQuery Foundation | jquery.org/license */",
                    compress: {
                        "hoist_funs": false,
                        loops: false,
                        unused: false
                    }
                }
            }
        },

        copy: files.copy,

        watch: {
            scripts: {
                files: [
                    'www/js/src/*.js',
                    'bower_components/*'
                ],
                tasks: ['uglify:development'],
                options: {
                    spawn: false
                }
            },
            styles: {
                files: [
                    'www/css/src/*.less',
                    'bower_components/*'
                ],
                tasks: ['less:development'],
                options: {
                    spawn: false
                }
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('copy-files', ['copy:fonts']);
    grunt.registerTask('default', ['less:development', 'uglify:development', 'copy-files']);
    grunt.registerTask('production', ['less:production', 'uglify:production', 'copy-files']);
};