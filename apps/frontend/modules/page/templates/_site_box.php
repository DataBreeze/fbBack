<?php if(count($sites_rand) > 0) : ?>
  <div class="gwBox fbBorder2">
    <h1>
      Fishing Sites in <?php echo $city['city'] ?>
    </h1>
    <?php include_partial('sites_links_tags',array('sites_rand' => $sites_rand) ) ?>
    <div id="fbMap" class="hmMap"></div>
  </div>
<?php endif ?>