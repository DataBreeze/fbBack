<div data-role="page" class="type-interior">

  <?php include_partial('intro_header', array('activity' => 'About FishBlab') ) ?>

  <div data-role="content">

    <div class="content-primary">

<h2>FishBlab.com Overview</h2>
<p>
FishBlab is a place to share information about fishing. Whether you are researching your next fishing trip or just exploring a new area, FishBlab offers a map driven view of fishing photos, fish catch, reports, spots, saltwater species and discussion.
</p>

<h2>What information is found on FishBlab.com</h2>
<p>
FishBlab.com contains both User created content as well as US government fish catch data. FishBlab user submitted data including fishing photos, fish catches, fishing reports, fishing spots and discussion. FishBlab.com also contains data about marine fish catches at various coastal sites around the country, provided by <a href="http://www.st.nmfs.noaa.gov/st1/recreational/overview/overview.html">NOAA Fisheries</a>.

<h2>How does it work</h2>
<p>
All of the data in FishBlab provided in a dynamic map-based view that can be queried by date. Just zoom or drag the map and the data will change. With NOAA data, FishBlab allows the User to explore all Fish within an Area or a single Fish within an Area. Data changes in real time as the user changes location. 
</p>


    </div>

    <div class="content-secondary">
      <div data-role="collapsible" data-collapsed="true" data-theme="b">
       <h3>More in this section</h3>

      <?php include_partial('overview_menu', array('select' => array(False,False,False,False,True),'data-dividertheme' => 'd','data-inset' => False) ) ?>   

      </div>
    </div>

  </div>

  <?php include_partial('footer', array('user' => $user) ) ?>   
   
</div>