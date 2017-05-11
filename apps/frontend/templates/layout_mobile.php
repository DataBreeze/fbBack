<!DOCTYPE html> 
<html> 
  <head> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <meta name="google-site-verification" content="xcJLUL92UNL-yQol255hc9pS_yXe9xT84g9s1IBI8D0" />
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <?php include_partial('head_styles') ?>
    <?php include_partial('head_js') ?>
  </head> 
  <body>
    <?php echo $sf_content . "\n" ?>
    <?php include_partial('global/head_js_google_analytics') ?><?php echo "\n" ?>
  </body>
</html>
