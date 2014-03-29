module.exports = function(grunt) {
  grunt.initConfig({
    jshint: {
      files: ['src/Generators/templates/Router.js'],
      options: {
        curly: true,
        indent: 2,
        trailing: true,
        devel: true
      }
    },
    jasmine: {
      components: {
        src: ['src/Generators/templates/Router.js'],
        options: {
          specs: 'tests/jasmine/spec/*Spec.js',
          keepRunner : true
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-jasmine');
  grunt.loadNpmTasks('grunt-contrib-jshint');

  grunt.registerTask('travis', ['jshint','jasmine']);
}