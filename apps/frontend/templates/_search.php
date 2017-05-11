<div id="searchMap">
  <div class="floatLeft">
    <button onclick="mapZoomIn();" class="fbButton" id="mapZoomIn">Less</button>
  </div>
  <div class="floatRight">
    <button onclick="mapZoomOut();" class="fbButton" id="mapZoomOut">More</button>
  </div>
  <div class="center">
    <form name="areaForm" id="XareaForm" action="http://area.fishblab.com/" method="post" onsubmit="return loadArea();">
      <input type="submit" name="submit_geo" value="Go" id='areaSubBut' />
      <input id="locInput" type="text" name="locInput" value="" size="10" />
      <input type="hidden" name="lat" id="form_lat" value="<?php if(isset($geo['lat'])) { echo $geo['lat']; } ?>" />
      <input type="hidden" name="lon" id="form_lon" value="<?php if(isset($geo['lon'])) { echo $geo['lon']; } ?>" />
    </form>
  </div>
  <div class="clear"></div>
</div>
