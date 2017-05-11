<div class="hmLeft fbBorder2">
  <h2>
    <a href="http://discuss.fishblab.com/<?php echo $loc['state'] ?><?php if($loc['state']){ echo '/';} ?><?php echo urlencode($loc['city']) ?>">
      Fishing Discussion in <?php echo $loc['input'] ?>
    </a>
  </h2>
<?php $count = $discs['count']; $count = ($count > 10 ? 10 : $count); ?>
<?php if($count > 0) : ?>
  <table class="loc hmAct">
  <th>Caption</th>
  <th>Date</th>
  <?php for($i=0; $i<$count; $i++): ?>
    <?php $disc = $discs['records'][$i] ?>
    <tr>
      <td><?php echo $disc['caption'] ?></td>
      <td><?php echo $disc['date'] ?></td>
    </tr>
  <?php endfor ?> 
  </table>
<?php else :?>
  No Fishing Discussion Found
<?php endif ?>
</div>
