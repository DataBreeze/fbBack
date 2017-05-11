<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraph.org/schema/">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <?php include_partial('global/head_styles') ?>
    <?php include_partial('global/head_js_cluster') ?>
  </head>
  <body>
    <?php echo $sf_content ?>
    <?php include_partial('global/social') ?><?php echo "\n" ?>
    <?php include_partial('global/head_js_google_analytics') ?><?php echo "\n" ?>
  </body>
</html>
