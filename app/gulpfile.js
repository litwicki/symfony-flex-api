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
  'sass/*.scss',
  'css/common/*.css'
];

var javascripts = [
  'js/common/jquery/*.js',
  'js/common/bootstrap/*.js',
  'js/common/angular/*.js',
  'js/common/angular/angular-strap/angular-strap.min.js',
  'js/common/angular/angular-strap/angular-strap.tpl.min.js',
  'js/common/angular/angular-strap/modules/*.js',
  'js/common/i18n/*.js',
  'js/common/*.js',
  'js/common/ngFileUpload/ng-file-upload-shim.min.js',
  'js/common/ngFileUpload/ng-file-upload-all.min.js',
  'js/common/textAngular/textAngular-rangy.min.js',
  'js/common/textAngular/textAngular-sanitize.js',
  'js/common/textAngular/textAngular.js',
  'js/*.js',
  'js/app.jquery.js',
  'js/app.js',
  'js/directives/*.js',
  'js/filters/*.js',
  'js/components/ComponentController.js',
  'js/components/core/CoreController.js',
  'js/components/user/UserController.js',
  'js/components/user/user.jquery.js',
  'js/components/node/NodeController.js',
  'js/components/node/node.jquery.js',
  'js/components/admin/AdminController.js',
  'js/components/comment/CommentController.js',
  'js/services/tavroServices.js',
  'js/services/ajaxServices.js',
  'js/services/alertServices.js',
  'js/services/ApiServices.js',
  'js/services/nodeService.js',
  'js/services/dialogs.js',
  'js/services/dialogs-default-translations.js',
  'js/services/userService.js',
  'src/Tavro/Bundle/ApiBundle/Resources/public/js/*.js'
];

var images = [
  'src/Tavro/Bundle/CoreBundle/Resources/public/img/*',
  'src/Tavro/Bundle/CoreBundle/Resources/public/img/*/*',
  'img/*',
  'img/*/*'
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