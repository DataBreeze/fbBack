<div id="hmFishTop" class="hmTable">
 <?php if(count($catch) > 0): ?>
  <div id="catchChart" class="hmChart"></div>
  <table class="hm" cellspacing="0" cellpadding="0">
    <thead>
    <tr>
      <th>Name</th><th>Avg Weight</th><th>Avg Length</th>
    </tr>
    </thead>
     <tbody>
   <?php foreach ($catch as $i => $fish): ?>
     <tr>
       <td>
         <a href="http://fish.fishblab.com/<?php echo urlencode($fish['name']) ?><?php if( isset($geo['state']) ){ echo '/' . $geo['state']; } ?><?php if( isset($geo['city']) ){ echo '/' . urlencode($geo['city']); } ?>">
           <?php echo $fish['name'] ?>
         </a>
       </td>
       <td><?php echo $fish['avg_weight'] ?></td><td><?php echo $fish['avg_length'] ?></td>
     </tr>
   <?php endforeach ?>
     </tbody>
  </table>
 <?php endif ?>
</div>