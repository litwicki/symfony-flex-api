var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var minifyCss = require('gulp-minify-css');
var image = require('gulp-image');
var util = require('gulp-util');
var fs = require("fs");
var s3 = require("gulp-s3");
var awsCredentials = JSON.parse(fs.readFileSync('./aws.json'));

var config = {
    production: !!util.env.production
};

var stylesheets = [
  'src/Tavro/Bundle/AppBundle/Resources/public/sass/*.scss',
  'src/Tavro/Bundle/AppBundle/Resources/public/css/common/*.css'
];

var javascripts = [
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/jquery/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/bootstrap/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/angular/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/angular/angular-strap/angular-strap.min.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/angular/angular-strap/angular-strap.tpl.min.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/angular/angular-strap/modules/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/i18n/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/ngFileUpload/ng-file-upload-shim.min.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/ngFileUpload/ng-file-upload-all.min.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/textAngular/textAngular-rangy.min.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/textAngular/textAngular-sanitize.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/common/textAngular/textAngular.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/app.jquery.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/app.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/directives/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/filters/*.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/components/ComponentController.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/components/core/CoreController.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/services/tavroServices.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/services/ajaxServices.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/services/alertServices.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/services/ApiServices.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/services/dialogs.js',
  'src/Tavro/Bundle/AppBundle/Resources/public/js/services/dialogs-default-translations.js',
  'src/Tavro/Bundle/ApiBundle/Resources/public/js/*.js'
];

var images = [
  'src/Tavro/Bundle/CoreBundle/Resources/public/img/*',
  'src/Tavro/Bundle/CoreBundle/Resources/public/img/*/*',
  'src/Tavro/Bundle/AppBundle/Resources/public/img/*',
  'src/Tavro/Bundle/AppBundle/Resources/public/img/*/*'
];

gulp.task('styles', function () {
  gulp.src(stylesheets)
      .pipe(sass())
      .pipe(minifyCss())
      .pipe(concat('tavro.css'))
      .pipe(gulp.dest('web/assets/css'));
});

gulp.task('javascripts', function () {
  gulp.src(javascripts)
      .pipe(concat('tavro.js'))
      .pipe(gulp.dest('web/assets/js'));
});

gulp.task('images', function () {
  gulp.src(images)
      .pipe(image())
      .pipe(gulp.dest('web/assets/img'));
});

gulp.task('default', ['styles', 'javascripts', 'images']);

gulp.task('upload', ['default'], function () {
  gulp.src('web/assets/css/*')
      .pipe(s3(awsCredentials, {
        uploadPath: "/assets/css/",
        headers: {
          'x-amz-acl': 'public-read'
        }
      }));
  gulp.src('web/assets/js/*')
      .pipe(s3(awsCredentials, {
        uploadPath: "/assets/js/",
        headers: {
          'x-amz-acl': 'public-read'
        }
      }));
  gulp.src(['web/assets/img/*', 'web/assets/img/*/*'])
      .pipe(s3(awsCredentials, {
        uploadPath: "/assets/img/",
        headers: {
          'x-amz-acl': 'public-read'
        }
      }));
});