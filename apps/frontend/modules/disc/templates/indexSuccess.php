<?php include_partial('global/header', array('geo' => $geo, 'user' => $user, 'cfg' => $cfg) ) ?>
<?php include_partial('global/map_data', array('param' => $param) ) ?>
<?php include_partial('global/footer', array('user' => $user) ) ?>
<?php include_partial('global/js_links') ?>
<?php include_partial('js_links') ?>
<script type="text/javascript">
fbDiscuss=<?php echo htmlspecialchars_decode($json_discuss) ?>;<?php echo "\n" ?>
fbUsername='<?php echo $user['username'] ?>';<?php echo "\n" ?>
fbLoc=<?php echo htmlspecialchars_decode($json_geo) ?>;<?php echo "\n" ?>
fbCfg=<?php echo htmlspecialchars_decode($json_cfg) ?>;<?php echo "\n" ?>
fbUser=<?php echo htmlspecialchars_decode($json_user) ?>;<?php echo "\n" ?>
<?php if($json_rec_stat) : ?>
fbRecStat=<?php echo htmlspecialchars_decode($json_rec_stat) ?>;<?php echo "\n" ?>
<?php endif ?>
fbNote=<?php echo htmlspecialchars_decode($json_notes) ?>;<?php echo "\n" ?>
google.maps.event.addDomListener(window, 'load', initAll );
</script>
