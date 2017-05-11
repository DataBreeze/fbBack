<div data-role="page" class="type-interior">

  <?php include_partial('intro_header', array('activity' => 'Introduction') ) ?>   

  <div data-role="content">

    <div class="content-primary">

     <div>
     <h2>Fishing Photos</h2>
     <img class="fRight mar10" src="/images/fb/land/roger-grab-small.jpg" />
     <p>Quickly upload your favorite fishing photos and share them how <b>you</b> want.</p>
     <p>Share and explore fishing pictures using a simple map-based view.</p>
     <a href="http://m.fishblab.com/map/photo" data-inline="true" data-theme="b" data-role="button">Explore Photos</a>
     <div class="clear"></div>
     </div>

     <div>
     <h2>Fish Catch</h2>
     <img class="fLeft mar10" src="/images/fb/land/don-tuna-100x264.jpg" />
     <p>What are people catching?</p>
     <p>Find out which Fish people are catching around you.</p>
     <p>Easily share your catch with friends, groups, everyone - or just yourself.</p>
     <a href="http://m.fishblab.com/map/catch" data-inline="true" data-theme="b" data-role="button">Fish Catch</a>
     <div class="clear"></div>
     </div>

     <div>
     <h2>Reports from the Pros</h2>
     <img class="fRight mar10" src="/images/fb/land/pro-200x95.jpg" />
     <p>Find out where the action is from Professional guides and captains.</p>
     <p>Provide your own reports for friends and group members.</p>
     <a href="http://m.fishblab.com/map/report" data-inline="true" data-theme="b" data-role="button">Find Fishing Reports</a>
     <div class="clear"></div>
     </div>

     <div>
     <h2>Fishing Groups & Clubs</h2>
     <img class="fLeft mar10" src="/images/fb/land/team.jpg" />
     <p>Find local fishing groups or create your own.</p>
     <p>Groups can be open to all, membership by request or private.</p>
     <a href="http://m.fishblab.com/map/group" data-inline="true" data-theme="b" data-role="button">Fishing Groups</a>
     <div class="clear"></div>
     </div>
   </div>

    <div class="content-secondary">
      <div data-role="collapsible" data-collapsed="true" data-theme="b">
       <h3>More in this section</h3>
         <?php include_partial('overview_menu', array('select' => array(False,True),'data-dividertheme' => 'd','data-inset' => False) ) ?>   
      </div>
    </div>

  </div>

  <?php include_partial('footer', array('user' => $user) ) ?>   
   
</div>