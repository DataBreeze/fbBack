<div class="hmLeft fbBorder2">
  <h2>
    <a href="http://report.fishblab.com/<?php echo $loc['state'] ?><?php if($loc['state']){ echo '/';} ?><?php echo urlencode($loc['city']) ?>">
      Fishing Reports in <?php echo $loc['input'] ?>
    </a>
  </h2>
<?php $count = $blogs['count']; $count = ($count > 10 ? 10 : $count); ?>
<?php if($count > 0) : ?>
  <table class="loc hmAct">
  <th>Caption</th>
  <th>Date</th>
  <?php for($i=0; $i<$count; $i++): ?>
    <?php $blog = $blogs['records'][$i] ?>
    <tr>
      <td><?php echo $blog['caption'] ?></td>
      <td><?php echo $blog['date_blog'] ?></td>
    </tr>
  <?php endfor ?> 
  </table>
<?php else :?>
  No Fishing Reports Found
<?php endif ?>
</div>
