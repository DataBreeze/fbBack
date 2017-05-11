<?php if(count($catch)) : ?>
  <div class="gwBox fbBorder2">
    <h1>Hot Fish in <?php echo $city['city'] ?> in <?php echo $cur_month ?></h1>
    <?php include_partial('fish_top',array('catch' => $catch,'geo' => $geo) ) ?>
  </div>
<?php endif ?>