<div id="introBox">
 <div id="introTabs">

  <ul>
    <li><a href="#tabPhoto" rel="nofollow">Photos</a></li>
    <li><a href="#tabCatch" rel="nofollow">Fish</a></li>
    <li><a href="#tabReport" rel="nofollow">Reports</a></li>
    <li><a href="#tabSpot" rel="nofollow">Fishing Spots</a></li>
    <li><a href="#tabGroup" rel="nofollow">Groups</a></li>
    <li><a href="#tabSearch" rel="nofollow">Search</a></li>
  </ul>

  <div id="tabPhoto" class="fbTabIntro">
    <div class="introCell floatLeft">
      <h2>Save Your Fishing Memories</h2>
      <p>Quickly upload your favorite fishing photos and share them how <b>you</b> want.</p>
      <p>Share and explore fishing pictures using a simple map-based view.</p>
    </div>
    <div class="floatRight">
      <img src="/images/fb/land/roger-grab.jpg" />
    </div>
    <a class="fbButton introLink" href="http://photo.fishblab.com">Explore Fishing Photos</a>
    <div class="clear"></div>
  </div>

  <div id="tabCatch" class="fbTabIntro">
    <div class="floatLeft">
      <img src="/images/fb/land/don-tuna-100x264.jpg" />
    </div>
    <div class="floatRight introCell">
      <h2>What are people catching?</h2>
      <p>Find out which Fish people are catching around you.</p>
      <p>Easily share your catch with friends, groups, everyone - or just yourself.</p>
      <a class="fbButton introLink" href="http://fish.fishblab.com">Find Local Fish</a>
    </div>
    <div class="clear"></div>
  </div>

  <div id="tabReport" class="fbTabIntro">
    <div class="introCell floatLeft">
      <h2>Reports from the Pros</h2>
      <p>Find out where the action is from Professional guides and captains.</p>
      <p>Provide your own reports for friends and group members.</p>
      <a class="fbButton introLink" href="http://report.fishblab.com">Get Regional Fishing Reports</a>
    </div>
    <div class="floatRight marTop20">
      <img src="/images/fb/land/pro-200x95.jpg" />
    </div>
    <div class="clear"></div>
  </div>

  <div id="tabSpot" class="fbTabIntro">
    <div class="floatLeft marTop20">
      <img src="/images/fb/land/spot-map.png" />
    </div>
    <div class="floatRight introCell">
      <h2>Find Fishing Hotspots</h2>
      <p>Discover the productive fishing sites nearby.</p>
      <p>Keep track of your fishing spots and share them with friends - if you want to.</p>
      <a class="fbButton introLink" href="http://spot.fishblab.com">View Fishing Spots</a>
    </div>
    <div class="clear"></div>
  </div>

  <div id="tabGroup" class="fbTabIntro">
    <div class="floatLeft marTop20">
      <img src="/images/fb/land/team.jpg" />
    </div>
    <div class="floatRight introCell">
      <h2>Fishing Groups & Clubs</h2>
      <p>Find local fishing groups or create your own.</p>
      <p>Groups can be open to all, membership by request or private.</p>
      <a class="fbButton introLink" href="http://group.fishblab.com">Explore FishBlab Groups</a>
    </div>
    <div class="clear"></div>
  </div>

  <div id="tabSearch" class="fbTabIntro">
    <div class="mar20 center centerText">
      <h2>Find Local Fishing Activity</h2>
      <form name="areaForm" id="gwForm" action="http://photo.fishblab.com/" method="post" onsubmit="return loadArea();">
        <input type="submit" name="submit_geo" value="Search Area" id='areaSubBut' />
        <input id="locInput" type="text" name="locInput" value="" size="15" />
        <input type="hidden" name="lat" id="form_lat" value="<?php if(isset($geo['lat'])) { echo $geo['lat']; } ?>" />
        <input type="hidden" name="lon" id="form_lon" value="<?php if(isset($geo['lon'])) { echo $geo['lon']; } ?>" />
      </form>
    </div>
  </div>

 </div>
</div>