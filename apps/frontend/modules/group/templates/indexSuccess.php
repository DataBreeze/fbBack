<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('map_data', array('data_template' => $data_template,'data' => $data, 'groups' => $group, 'members' => $member, 'user' => $user, 'geo' => $geo)) ?>
<?php include_partial('global/footer', array('user' => $user) ) ?>
<?php include_partial('global/js_links') ?>
<?php include_partial('js_links', array('mode' => $mode,'js_file' => $js_file)) ?>
<script type="text/javascript">
/*<![CDATA[*/
fbLoc=<?php echo htmlspecialchars_decode($json_geo) ?>;<?php echo "\n" ?>
fbCfg=<?php echo htmlspecialchars_decode($json_cfg) ?>;<?php echo "\n" ?>
fbUser=<?php echo htmlspecialchars_decode($json_user) ?>;<?php echo "\n" ?>
fbNote=<?php echo htmlspecialchars_decode($json_notes) ?>;<?php echo "\n" ?>
fbGroup=<?php echo htmlspecialchars_decode($json_group) ?>;<?php echo "\n" ?>
<?php if($json_member): ?>
fbMember=<?php echo htmlspecialchars_decode($json_member) ?>;<?php echo "\n" ?>
<?php endif ?>
google.maps.event.addDomListener(window, 'load', initAll);
/*]]>*/
</script>
