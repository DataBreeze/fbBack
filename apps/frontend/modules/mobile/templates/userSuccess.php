<div data-role="page" class="type-interior">

  <?php include_partial('activity_header', array('activity' => 'FishBlab Users') ) ?>   

  <div data-role="content" data-theme="c">

    <div class="content-primary">
      <h2>Exploring FishBlab User Profiles</h2>
      <div id="fbMap">
       <h1>User MAP</hi>
      </div>
    </div>

    <div class="content-secondary">
      <div data-role="collapsible" data-collapsed="true" data-theme="b">
        <h3>More in this section</h3>
        <?php include_partial('profile_menu', array('select' => array(False,True),'data-dividertheme' => 'd','data-inset' => False) ) ?>   
      </div>
    </div>

  </div>

  <?php include_partial('footer', array('user' => $user) ) ?>   

</div>

