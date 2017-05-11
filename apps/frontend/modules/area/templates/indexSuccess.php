<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('map_data', array('sites' => $sites,'catch' => $catch, 'catch_annual' => $catch_annual, 'saved_catch' => $saved_catch, 'catch_sort_by_name' => $catch_sort_by_name, 'discuss' => $discuss, 'user' => $user)) ?>
<?php include_partial('global/footer', array('user' => $user) ) ?>
<?php include_partial('global/js_links') ?>
<?php include_partial('js_links') ?>
<script type="text/javascript">
/*<![CDATA[*/
fbCatch=<?php echo htmlspecialchars_decode($json_catch)?>;<?php echo "\n" ?>
fbSite=<?php echo htmlspecialchars_decode($json_sites)?>;<?php echo "\n" ?>
fbCatchAnnual=<?php echo htmlspecialchars_decode($json_catch_annual) ?>;<?php echo "\n" ?>
fbSelectFish=<?php echo htmlspecialchars_decode($json_fish_ids) ?>;<?php echo "\n" ?>
fbDiscuss=<?php echo htmlspecialchars_decode($json_discuss) ?>;<?php echo "\n" ?>
fbUsername='<?php echo $user['username'] ?>';<?php echo "\n" ?>
fbInput='<?php echo $geo['input'] ?>';<?php echo "\n" ?>
fbMonthMin=<?php echo $month_range[0]-1 ?>;<?php echo "\n" ?>
fbMonthMax=<?php echo $month_range[1]-1 ?>;<?php echo "\n" ?>
fbLoc=<?php echo htmlspecialchars_decode($json_geo) ?>;<?php echo "\n" ?>
fbCfg=<?php echo htmlspecialchars_decode($json_cfg) ?>;<?php echo "\n" ?>
fbUser=<?php echo htmlspecialchars_decode($json_user) ?>;<?php echo "\n" ?>
fbNote=<?php echo htmlspecialchars_decode($json_notes) ?>;<?php echo "\n" ?>
google.maps.event.addDomListener(window, 'load', initAll);
/*]]>*/
</script>
