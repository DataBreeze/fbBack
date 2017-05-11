<div id="fbTabs">

  <ul>
    <li><a href="#tabSite" rel="nofollow">NOAA Spots</a></li>
    <li><a href="#tabCatch" rel="nofollow">NOAA Fish</a></li>
    <li><a href="#tabChart" rel="nofollow">Catch Chart</a></li>
    <li><a href="#tabFilter" rel="nofollow">Filter</a></li>
  </ul>

  <div id="tabSite" class="fbTab">
    <div class="fbTabCap">NOAA Fish Survey Sites</div>
    <div class="loc_pane">
      <div>
        <div class="float_left">Max Sites: <span id="site_max"></span></div>
        <div class="float_right"><span id="site_cur"><?php echo count($sites) ?></span> currently displayed</div>
        <div class="clear"></div>
      </div>
      <div id="loc_slide"></div>
      <br />
      <table id="loc_table" class="loc" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>#</th><th>Location</th><th>City</th><th>State</th><th>Distance</th>
          </tr>
        </thead>
        <tbody id="site_body">
       <?php if(count($sites) > 0): ?>
        <?php foreach ($sites as $i => $site): ?>
          <tr>
             <td><?php echo ($i+1) ?></td>
             <td><?php echo $site['name'] ?></td>
             <td><?php echo $site['city'] ?></td>
             <td><?php echo $site['state'] ?></td>
             <td><?php echo $site['distance'] ?></td>
          </tr>
        <?php endforeach ?>
       <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="tabCatch" class="fbTab">
    <div class="fbTabCap">NOAA Fish Catch Totals</div>
    <div class="loc_pane">
      <table id="catch_table" class="loc" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Name</th><th>Count</th><th>AvgWgt</th><th>AvgLen</th>
          </tr>
        </thead>
        <tbody id="catch_body">
        <?php foreach ($catch as $fish): ?>
          <tr onclick="openFishWin('<?php echo $fish['name'] ?>');">
            <td><a href="http://fish.fishblab.com/<?php echo urlencode($fish['name']) ?>" onclick="return false;"><?php echo $fish['name'] ?></a></td>
            <td><?php echo $fish['count'] ?></td>
            <td><?php echo $fish['avg_weight'] ?></td>
            <td><?php echo $fish['avg_length'] ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="tabChart" class="fbTab">
    <div class="fbTabCap">Chart of Selected/Top Fish Catch</div>
    <div class="loc_pane">
      <div id="catchChart"></div>
      <div id="chartAdd">
        <form name="chartAddForm" method="post" onsubmit="chartAdd();return false;" action="/">
          <select id="chartAddSelect" name="fish_id">
              <option value="0" selected="selected">Select a Fish</option>
            <?php foreach ($catch_sort_by_name as $fid => $fname): ?>
              <option value="<?php echo $fid ?>"><?php echo $fname ?></option>
            <?php endforeach ?> 
          </select>
	  <input type="submit" id="chartAddSubmit" name="Add to Chart" value="Add to Chart" />
        </form>
      </div>
      <div id="chartRemove">
	<?php if($saved_catch): ?>
          <?php foreach ($catch_annual as $fish): ?>
          <p>
            <a href="/" onclick="chartDelete(<?php echo $fish['fish_id'] ?>);return false;" rel="nofollow">
              <img src="/images/fb/close.png" border="0" alt="Remove <?php echo $fish['name'] ?> from Chart" />
            </a>  
            <a class="removeOpenFish" href="#" rel="nofollow"><?php echo $fish['name'] ?></a>
          </p>
          <?php endforeach ?> 
        <?php endif ?> 
      </div>
    </div>
  </div>

  <div id="tabFilter" class="fbTab">
    <?php include_partial('global/filter_noaa',array('user' => $user)) ?>
  </div>

</div>
