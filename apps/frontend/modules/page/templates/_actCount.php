<div class="gwActAll fbBorder2X">
  <h1>
    <?php echo $recs['count_total'] ?> <?php echo $act['namePlural'] ?> found
    <?php if($loc['input']) : ?>
      in <?php echo $loc['input'] ?>
    <?php endif ?>
  </h1>
  <?php if($recs['count_total'] > $recs['count'] ) : ?>
  <div class="gwActMore">
    Showing <?php echo $recs['count'] ?> <a href="?offset=<?php echo ($offset + 1) ?>">Get More &gt;&gt;</a>
  </div>
  <?php endif ?>
</div>
