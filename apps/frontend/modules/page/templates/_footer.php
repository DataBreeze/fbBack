<div class="clear"></div>
<?php include_partial('global/footer_links', array('user' => $user) ) ?>
<?php include_partial('global/js_links') ?>
<?php include_partial('js_links') ?>
<div id="jrjDialog"></div>
<div id="jrjDialog2"></div>
<div id="jrjDialog3"></div>
<script type="text/javascript">
/*<![CDATA[*/
<?php if(isset($json_catch)) : ?>
fbCatch=<?php echo htmlspecialchars_decode($json_catch) ?>;<?php echo "\n" ?>
<?php endif ?>
<?php if(isset($json_catch_annual)) : ?>
fbCatchAnnual=<?php echo htmlspecialchars_decode($json_catch_annual) ?>;<?php echo "\n" ?>
<?php endif ?>
<?php if(isset($json_sites)) : ?>
fbSite=<?php echo htmlspecialchars_decode($json_sites) ?>;<?php echo "\n" ?>
<?php endif ?>
<?php if(isset($json_chart_config)) : ?>
fbChartConfig=<?php echo htmlspecialchars_decode($json_chart_config) ?>;<?php echo "\n" ?>
<?php endif ?>
fbLoc=<?php echo htmlspecialchars_decode($json_loc) ?>;<?php echo "\n" ?>
fbCfg=<?php echo htmlspecialchars_decode($json_cfg) ?>;<?php echo "\n" ?>
fbUser=<?php echo htmlspecialchars_decode($json_user) ?>;<?php echo "\n" ?>
fbNote=<?php echo htmlspecialchars_decode($json_notes) ?>;<?php echo "\n" ?>
<?php if(isset($json_area_select)) : ?>
fbAreaSelect=<?php echo htmlspecialchars_decode($json_area_select) ?>;<?php echo "\n" ?>
<?php endif ?>
<?php if(isset($json_top_city)) : ?>
fbTopCity=<?php echo htmlspecialchars_decode($json_top_city) ?>;<?php echo "\n" ?>
<?php endif ?>
/*]]>*/
<?php if(isset($json_js_init)) : ?>
<?php echo htmlspecialchars_decode($json_js_init) ?><?php echo "\n" ?>
<?php endif ?>
</script>
<?php include_partial('global/copyright') ?>
