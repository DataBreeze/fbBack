<div data-role="page" class="type-interior">

  <?php include_partial('intro_header', array('activity' => 'Managing Data') ) ?>

  <div data-role="content">

    <div class="content-primary">
      <h2>Managing Your Posts</h2>
      <p>
      When you post anything on FishBlab you decide who is allowed to view it by selecting the permission <i>Who can view this?</i>:
      <ul>
        <li><strong>Public</strong> - Everyone can see this data</li>
        <li><strong>Friends</strong> - Only your FishBlab Friends with can see the data.</li>
        <li><strong>Private</strong> - Only you can see this data.</li>
        <li><strong>Group</strong> - Only Members of the <b>selected</b> Group can se the data.</li>
      </ul>

      </p>

      <h2>Viewing Data</h2>
      <p>
      When you explore data on FishBlab you have the option to instantly switch between various views:
      <ul>
        <li><strong>Public</strong> - See all public data from all Users.</li>
        <li><strong>Friends</strong> - View only data from your FishBlab Friends.</li>
        <li><strong>Private</strong> - Show only your data (private, public, friends or group).</li>
        <li><strong>Group</strong> - Display data from the <b>selected</b> Group.</li>
      </ul>
      </p>
    </div>

    <div class="content-secondary">
      <div data-role="collapsible" data-collapsed="true" data-theme="b">
       <h3>More in this section</h3>
      <?php include_partial('overview_menu', array('select' => array(False,False,False,True,False),'data-dividertheme' => 'd','data-inset' => False) ) ?>   
      </div>
    </div>

  </div>

  <?php include_partial('footer', array('user' => $user) ) ?>   
</div>