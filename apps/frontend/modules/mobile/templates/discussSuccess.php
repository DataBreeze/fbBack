<div data-role="page" class="type-interior">

  <?php include_partial('activity_header', array('activity' => 'Fishing Spots') ) ?>   

  <div data-role="content" data-theme="c">

    <div class="content-primary">
      <h2>Exploring and Sharing Fishing Discussion</h2>
      <div id="fbMap">
      <h1>Discussion MAP</hi>
      </div>
    </div>

    <div class="content-secondary">
      <div data-role="collapsible" data-collapsed="true" data-theme="b">
        <h3>More in this section</h3>
<?php include_partial('activity_menu', array('select' => array(False,False,False,False,False,True),'data-dividertheme' => 'd','data-inset' => False) ) ?>   
      </div>
    </div>

  </div>

  <?php include_partial('footer', array('user' => $user) ) ?>   

</div>

