
<div>
  <div id="fishHead" class="listHead fbBorder5G">
    <div id="fishHeadText" class="listHeadText floatLeft"></div>
    <div class="listHeadBut floatRight">
      <button style="display:none;" class="fbButton fbButBig" onclick="repNew();return false;">New Fish Catch</button>
    </div>
    <div class="clear"></div>
  </div>

  <div id="fishList" class="dataList">

    <table id="catch_table" class="loc" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th>Name</th><th>Catch Count</th><th>AvgWgt</th><th>AvgLen</th>
        </tr>
      </thead>
      <tbody id="catch_body">
      <?php if($catch['count'] > 0) : ?>
        <?php foreach ($catch['records'] as $fish): ?>
          <tr onclick="fishDetail(<?php echo $fish['id'] ?>);">
            <td><a href="http://fish.fishblab.com/<?php echo urlencode($fish['name']) ?>" onclick="return false;"><?php echo $fish['name'] ?></a></td>
            <td><?php echo $fish['count'] ?></td>
            <td><?php echo $fish['avg_weight'] ?></td>
            <td><?php echo $fish['avg_length'] ?></td>
          </tr>
        <?php endforeach ?>
       <?php endif ?>
      </tbody>
    </table>
  </div>
</div>
