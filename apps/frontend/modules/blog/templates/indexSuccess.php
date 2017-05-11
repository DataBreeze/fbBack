<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('global/map_data', array('param' => $param) ) ?>
<?php include_partial('global/footer', array('user' => $user) ) ?>
<?php include_partial('global/js_links') ?>
<?php include_partial('js_links') ?>
<script type="text/javascript">
/*<![CDATA[*/
fbBlog=<?php echo htmlspecialchars_decode($json_blogs) ?>;<?php echo "\n" ?>
fbMonthMin=<?php echo $month_range[0]-1 ?>;<?php echo "\n" ?>
fbMonthMax=<?php echo $month_range[1]-1 ?>;<?php echo "\n" ?>
fbLoc=<?php echo htmlspecialchars_decode($json_geo) ?>;<?php echo "\n" ?>
fbCfg=<?php echo htmlspecialchars_decode($json_cfg) ?>;<?php echo "\n" ?>
fbUser=<?php echo htmlspecialchars_decode($json_user) ?>;<?php echo "\n" ?>
fbNote=<?php echo htmlspecialchars_decode($json_notes) ?>;<?php echo "\n" ?>
<?php if($json_rec_stat) : ?>
fbRecStat=<?php echo htmlspecialchars_decode($json_rec_stat) ?>;<?php echo "\n" ?>
<?php endif ?>
google.maps.event.addDomListener(window, 'load', initAll);
/*]]>*/
</script>
