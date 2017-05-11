<h1>Top Cities</h1>
<div class="loc_pane">
  <table id="loc_table" class="loc" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>City</th><th>ST</th><th>Rate</th>
      </tr>
    </thead>
    <tbody id="site_body">
      <?php foreach ($cities_top as $i => $city): ?>
      <tr>
        <td><a href="http://fish.fishblab.com/<?php echo urlencode($rec['name']) ?>/<?php echo $city['state'] ?>/<?php echo urlencode($city['city']) ?>"><?php echo $city['city'] ?></a></td>
        <td><?php echo $city['state'] ?></td>
        <td><?php echo $city['count'] ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
