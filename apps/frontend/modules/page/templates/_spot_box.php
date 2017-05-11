<div class="hmLeft fbBorder2">
  <h2>
    <a href="http://spot.fishblab.com/<?php echo $loc['state'] ?><?php if($loc['state']){ echo '/';} ?><?php echo urlencode($loc['city']) ?>">
      Fishing Spots in <?php echo $loc['input'] ?>
    </a>
  </h2>
<?php $count = $spots['count']; $count = ($count > 10 ? 10 : $count); ?>
<?php if($count > 0) : ?>
  <table class="loc hmAct">
  <th>Caption</th>
  <th>Date</th>
  <?php for($i=0; $i<$count; $i++): ?>
    <?php $spot = $spots['records'][$i] ?>
    <tr>
      <td><?php echo $spot['caption'] ?></td>
      <td><?php echo $spot['date_create'] ?></td>
    </tr>
  <?php endfor ?> 
  </table>
<?php else :?>
  No Fishing Spots Found
<?php endif ?>
</div>
