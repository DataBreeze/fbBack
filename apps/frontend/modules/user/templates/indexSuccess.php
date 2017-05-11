<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('map_data', array('data_template' => $data_template,'data' => $data, 'friends' => $friends, 'groups' => $groups, 'user' => $user, 'geo' => $geo)) ?>
<?php include_partial('global/footer', array('user' => $user) ) ?>
<?php include_partial('global/js_links') ?>
<?php include_partial('js_links') ?>
<script type="text/javascript">
/*<![CDATA[*/
fbLoc=<?php echo htmlspecialchars_decode($json_geo) ?>;<?php echo "\n" ?>
fbCfg=<?php echo htmlspecialchars_decode($json_cfg) ?>;<?php echo "\n" ?>
fbUser=<?php echo htmlspecialchars_decode($json_user) ?>;<?php echo "\n" ?>
fbNote=<?php echo htmlspecialchars_decode($json_notes) ?>;<?php echo "\n" ?>
<?php if($users): ?>
fbUsers=<?php echo htmlspecialchars_decode($json_users) ?>;<?php echo "\n" ?>
<?php endif ?>
google.maps.event.addDomListener(window, 'load', initAll);
/*]]>*/
</script>
