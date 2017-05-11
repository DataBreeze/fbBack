<div class="hmLeft fbBorder2">
  <h2>
    <a href="http://catch.fishblab.com/<?php echo $loc['state'] ?><?php if($loc['state']){ echo '/';} ?><?php echo urlencode($loc['city']) ?>">
      Fish Catch in <?php echo $loc['input'] ?>
    </a>
    <a class="marLeft5" href="http://catch.fishblab.com/<?php echo $loc['state'] ?><?php if($loc['state']){ echo '/';} ?><?php echo urlencode($loc['city']) ?>">More &gt;</a>
  </h2>
  <?php $count = $reports['count']; $count = ($count > 10 ? 10 : $count); ?>
  <?php if($count > 0) : ?>
  <table class="hmAct loc">
    <th>Fish</th>
    <th>Date</th>
    <th>len</th>
    <th>Wgt</th>
    <?php for($i=0; $i<$count; $i++): ?>
	  <?php $rec = $reports['records'][$i] ?>
    <tr>
      <td><a href="http://catch.fishblab.com/<?php echo $rec['id'] ?>"><?php echo $rec['fish_name'] ?></a></td>
      <td><?php echo $rec['date_catch'] ?></td>
      <td><?php echo $rec['length'] ?></td>
      <td><?php echo $rec['weight'] ?></td>
    </tr>
    <?php endfor ?> 
  </table>
  <?php else :?>
  No Catch Reports Found
  <?php endif ?>
</div>
