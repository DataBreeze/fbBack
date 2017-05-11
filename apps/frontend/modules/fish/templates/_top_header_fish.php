<div id="topH2" class="topH">
  <table cellpadding="0" cellspacing="0">
    <tr>

      <td class="topLeft2">
        <div class="logoSmall">
          <a href="http://www.fishblab.com">
            <img src="/images/fb/bg/fbLogoSm.png" border="0" alt="FishBlab" />
          </a>
        </div>
      </td>

      <td class="topCenter2">
        <div id="fbSearch" class="fbBorder2">
        <form name="areaForm" id="areaForm" action="http://area.fishblab.com/" method="post" onsubmit="return loadArea();">
          <input type="submit" name="submit_geo" value="Search Map" id='areaSubBut' />
          <input id="locInput" type="text" name="locInput" value="" size="15" />
          <input type="hidden" name="lat" id="form_lat" value="<?php if(isset($geo['lat'])) { echo $geo['lat']; } ?>" />
          <input type="hidden" name="lon" id="form_lon" value="<?php if(isset($geo['lon'])) { echo $geo['lon']; } ?>" />
        </form>
        </div>
      </td>

      <td class="topRight2">
        <div class="fbBorder2 rightT2" id="expGrp">
          <div class='fsz18' id="expText">
            <?php echo $explore_text ?>
          </div>
          <a id="fishFindBut" class="fbButton" href="/" onclick="fishShowSelect();return false;">Select New Fish</a>
        </div>
      </td>

    </tr>
  </table>
</div>
