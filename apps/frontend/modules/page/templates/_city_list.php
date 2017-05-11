<?php $count = count($cities); $half = $count/2; $quart = $half/2; $quart3 = $half + $quart; ?>
<div id="hmList">

  <table class="cityList">
    <tr>

    <td width="25%">
    <?php for($i=0; $i < $quart; $i++) : ?>
     <?php $city = $cities[$i]; ?>
      <p>
        <a href="http://www.fishblab.com/<?php echo $city['state'] ?>/<?php echo $city['city_esc'] ?>"><?php echo $city['city'] ?></a>
      </p>
    <?php endfor ?>
    </td>

    <td width="25%">
    <?php for($i=$quart; $i < $half; $i++) : ?>
     <?php $city = $cities[$i]; ?>  
      <p>
        <a href="http://www.fishblab.com/<?php echo $city['state'] ?>/<?php echo $city['city_esc'] ?>"><?php echo $city['city'] ?></a>
      </p>
    <?php endfor ?>
    </td>

    <td width="25%">
    <?php for($i=$half; $i < $quart3; $i++) : ?>
     <?php $city = $cities[$i]; ?>  
      <p>
        <a href="http://www.fishblab.com/<?php echo $city['state'] ?>/<?php echo $city['city_esc'] ?>"><?php echo $city['city'] ?></a>
      </p>
    <?php endfor ?>
    </td>

    <td width="25%">
    <?php for($i=$quart3; $i < $count; $i++) : ?>
     <?php $city = $cities[$i]; ?>  
      <p>
        <a href="http://www.fishblab.com/<?php echo $city['state'] ?>/<?php echo $city['city_esc'] ?>"><?php echo $city['city'] ?></a>
      </p>
    <?php endfor ?>
    </td>

    </tr>
  </table>
</div>
