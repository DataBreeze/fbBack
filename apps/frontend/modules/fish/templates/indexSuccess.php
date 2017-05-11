<?php include_partial('global/header', array('geo' => $geo, 'user' => $user, 'cfg' => $cfg) ) ?>
<?php include_partial('global/map_data', array( 'param' => $param) ); ?>
<?php include_partial('global/footer', array('user' => $user) ) ?>
<?php include_partial('tabs',array('fishOne' => $fishOne,'sites_top' => $sites_top)) ?>
<?php include_partial('global/js_links') ?>
<?php include_partial('js_links') ?>
<script type="text/javascript">
fbSpot=<?php echo htmlspecialchars_decode($json_spots)?>;<?php echo "\n" ?>
fbCatch=<?php echo htmlspecialchars_decode($json_catch)?>;<?php echo "\n" ?>
fbFish=<?php echo htmlspecialchars_decode($json_fish)?>;<?php echo "\n" ?>
fbFishWiki=<?php echo htmlspecialchars_decode($json_fish_wiki)?>;<?php echo "\n" ?>
fbLoc=<?php echo htmlspecialchars_decode($json_geo) ?>;<?php echo "\n" ?>
fbCfg=<?php echo htmlspecialchars_decode($json_cfg) ?>;<?php echo "\n" ?>
fbUser=<?php echo htmlspecialchars_decode($json_user) ?>;<?php echo "\n" ?>
fbNote=<?php echo htmlspecialchars_decode($json_notes) ?>;<?php echo "\n" ?>
google.maps.event.addDomListener(window, 'load', initAll );
</script>
