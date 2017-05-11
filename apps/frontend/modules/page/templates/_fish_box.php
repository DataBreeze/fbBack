<?php if(count($fish_rand) > 0) : ?>
  <div class="gwBox fbBorder2">
    <h1>
      Fish in <?php echo $city['city'] ?>, <?php echo $city['state'] ?>
    </h1>
    <?php include_partial('fish_links_tags',array('fish_rand' => $fish_rand,'geo' => $geo)) ?>
  </div>
<?php endif ?>
