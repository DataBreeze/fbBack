<h1>Top Spots</h1>
<div class="loc_pane">
  <table id="loc_table" class="loc" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Location</th><th>City</th><th>ST</th><th>Rate</th>
      </tr>
    </thead>
    <tbody id="site_body">
      <?php foreach ($sites_top as $i => $site): ?>
      <tr>
        <td><a href="http://fish.fishblab.com/<?php echo urlencode($rec['name']) ?>/<?php echo $site['state'] ?>/<?php echo urlencode($site['city']) ?>/<?php echo urlencode($site['name']) ?>"><?php echo $site['name'] ?></a></td>
        <td><?php echo $site['city'] ?></td>
        <td><?php echo $site['state'] ?></td>
        <td><?php echo $site['count'] ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
