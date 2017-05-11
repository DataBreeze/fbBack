<?php include_partial('global/header',array('user' => $user, 'cfg' => $cfg  )) ?>

<?php include_partial('path',array('geo' => $geo)) ?>
<div id="hmMain">
  <?php include_partial('state_list',array('states' => $states, 'count' => $count, 'count_total' => $count_total) ) ?>
</div>
<?php include_partial('footer', $footer) ?>
