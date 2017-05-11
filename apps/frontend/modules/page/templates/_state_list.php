<?php $count = count($states); $half = $count/2; ?>
<div id="hmList">

  <table class="stateList">
    <tr>

    <td width="50%">
    <?php for($i=0; $i < $half; $i++) : ?>
     <?php $state = $states[$i]; ?>
      <p>
        <a href="http://www.fishblab.com/<?php echo $state['state'] ?>"><?php echo $state['state_full'] ?></a>
      </p>
    <?php endfor ?>
    </td>

    <td width="50%">
    <?php for($i=$half; $i < $count; $i++) : ?>
     <?php $state = $states[$i]; ?>  
      <p>
        <a href="http://www.fishblab.com/<?php echo $state['state'] ?>"><?php echo $state['state_full'] ?></a>
      </p>
    <?php endfor ?>
    </td>

    </tr>
  </table>
</div>
