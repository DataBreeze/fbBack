<div id="fishDialog">
  <ul>
    <li><a href="#tabFish" rel="nofollow">Detail</a></li>
    <li id="tab-spot"><a href="#tabSite" rel="nofollow">Top Spots</a></li>
    <li id="tab-wiki"><a href="#tabWiki" rel="nofollow">Wiki</a></li>
  </ul>
  <div id="tabFish" class="fbTab">
    <div class="fish_pane">
      <div class="dataCap">
        <span id="fishName"><?php echo $fishOne['name'] ?></span>
        <span class="italic" id="fishNameSci"><?php echo $fishOne['name_sci'] ?></span>
      </div>
      <div class="dataDis">
        <div class="bold marBot5">Catch Statistics within current map</div> 
        <div><span class="label">Total Catch Count:</span> <span id="fishCount"><?php echo $fishOne['count'] ?></span></div>
        <div><span class="label">Average Weight:</span> <span id="fishWeight"><?php echo $fishOne['avg_weight'] ?> lbs</span></div>
        <div><span class="label">Average Length:</span> <span id="fishLength"><?php echo $fishOne['avg_length'] ?> inches</span></div>
      </div>
      <div id="catchChart"></div>
      <div>
        <div id="gForm"></div>
        <div id="gBrand"></div>
        <div class="clear"></div>
        <div id="gSearch"></div>
      </div>
    </div>
  </div>
  <div id="tabSite" class="fbTab">
    <div class="fbTabCap">Top Sites for <span id="fishName2"><?php echo $fishOne['name'] ?></span></div>
    <div class="loc_pane">
      <table id="loc_table" class="loc" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Location</th><th>City</th><th>ST</th><th>Count</th>
          </tr>
        </thead>
        <tbody id="site_body">
        <?php foreach ($sites_top as $i => $site): ?>
           <tr>
             <td><a href="http://spot.fishblab.com/<?php echo $site['state'] ?>/<?php echo urlencode($site['city']) ?>" onclick="return false;"><?php echo $site['name'] ?></a></td>
             <td><?php echo $site['city'] ?></td>
             <td><?php echo $site['state'] ?></td>
             <td><?php echo $site['count'] ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
  <div id="tabWiki" class="fbTab">
    <?php include_partial('wiki',array('fishOne' => $fishOne)) ?>
  </div>
</div>
