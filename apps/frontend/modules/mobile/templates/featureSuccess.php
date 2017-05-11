<div data-role="page" class="type-interior">

  <?php include_partial('intro_header', array('activity' => 'Intro to FishBlab') ) ?>   

  <div data-role="content">

    <div class="content-primary">
      <h2>Key features:</h2>
      <ul>
        <li><strong>Easy & Fast</strong> Photo sharing</li>
        <li><strong>Share Data the way you want</strong> - Share with Friends, Groups, Everyone - or just yourself.</li>
        <li><strong>View the activity you want</strong> - Instantly flip between posts from Friends, Member Groups, public or private data.</li>
        <li><strong>Map Driven Browsing</strong> - Simply drag and zoom the map to find more data.</li>
        <li><strong>Saltwater Research</strong> - Find local fish species and fishing sites.</li>
      </ul>
    </div>

    <div class="content-secondary">
      <div data-role="collapsible" data-collapsed="true" data-theme="b">
        <h3>More in this section</h3>
        <?php include_partial('overview_menu', array('select' => array(False,False,True),'data-dividertheme' => 'd','data-inset' => False) ) ?>   
      </div>
    </div>

  </div>

  <?php include_partial('footer', array('user' => $user) ) ?>   
   
</div>