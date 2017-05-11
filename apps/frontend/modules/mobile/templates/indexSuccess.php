<div data-role="page" class="type-home" id="homePage">
  <div data-role="content">
    <div class="content-secondary">
      <div id="jqm-homeheader">
	<h1>FISHBLAB</h1>
	<p>mobile</p>
	<h1 id="jqm-logo">
	  <a href="http://m.fishblab.com">
	    <img src="http://m.fishblab.com/images/fb/fishLogo100.png" alt="FishBlab Mobile Website" />
	  </a>
	</h1>
	<p>Explore and Share Local Fishing Activity</p>
      </div>
      <p class="intro"><strong>Welcome.</strong> FishBlab is the easiest way find and share Fishing Reports, Photos, Catch, Spots, Discussion and more in your area.</p>

      <ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">All Fishing Activities</li>
	<!-- <li><a id="searchLink" href="#mobMap">Set Location</a></li> -->
	<li><a href="#mobMenu?s=actNew">Share New</a></li>
	<li><a href="#mobMap">Explore Map</a></li>
	<li><a href="#mobList">View List</a></li>
      </ul>
      
      <br />

      <ul data-role="listview" data-inset="true">
	<li data-role="list-divider">Select Activity</li>
	  <li><a id="allLink" href="#mobMap?s=all">All Activities</a></li>
	  <li><a id="photoLink" href="#mobMap?s=photo">Fishing Photos</a></li>
	  <li><a id="reportLink" href="#mobMap?s=report">Fishing Reports</a></li>
	  <li><a id="catchLink" href="#mobMap?s=catch">Fish Catch</a></li>
	  <li><a id="spotLink" href="#mobMap?s=spot">Fishing Spots</a></li>
	  <li><a id="discLink" href="#mobMap?s=disc">Fishing Discussion</a></li>
	  <li><a id="userLink" href="#mobMap?s=user">FishBlab Users</a></li>
	  <li><a id="groupLink" href="#mobMap?s=group">FishBlab Groups</a></li>
	</ul>

      
    </div>
    
    <div class="content-primary">

      <div id="menuAccountHome">
	<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	  <li data-role="list-divider">Sign In</li>
	  <li><a href="#mobForm?s=login">Login</a></li>
	  <li><a href="#mobForm?s=loginNew">Create Account</a></li>
	  <li><a href="#mobForm?s=loginReset">Lookup/Reset Account</a></li>
	</ul>
      </div>

	<ul id="menuFishblab" data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	  <li data-role="list-divider">FishBlab</li>
	  <li><a href="http://m.fishblab.com">Mobile Home</a></li>
	  <li><a href="http://www.fishblab.com">Full Site Home</a></li>
	  <li><a href="http://map.fishblab.com">Map Home</a></li>
	  <li><a href="#mobForm?s=feed">Feedback</a></li>
	  <li><a href="pageMenu.html">FishBlab Corporate</a></li>
	</ul>
	
    </div>
    
   </div>
  <div data-role="footer" data-theme="f">
    <h3>
      <?php include_partial('footer',$footer) ?>
    </h3>
  </div>
</div>

<div id="mobMenu" data-role="page">  
  <div data-role="header" data-theme="f">
    <div class="mobHeadBut">
      <div id="mobMenuBut" data-role="controlgroup" data-type="horizontal" data-mini="true"></div>
    </div>
    <a id="mobMenuHome" href="/" data-icon="arrow-l" data-iconpos="notext">Back</a>
    <a id="mobMenuMenu" href="/" data-icon="gear" data-iconpos="notext">Menu</a>
  </div>
  <div data-role="content" data-theme="c" id="mobMenuContent"></div>
  <div id="mobMenuPopup" data-role="dialog" Xdata-overlay-theme="b"></div>
  <div data-role="footer" data-theme="f">
    <h3>
      <?php include_partial('footer',$footer) ?>
    </h3>
  </div>
</div>

<div id="mobForm" data-role="page">
  <div data-role="header" data-theme="f">
    <div class="mobHeadBut">
      <div id="mobFormBut" data-role="controlgroup" data-type="horizontal" data-mini="true"></div>
    </div>
    <a id="mobFormHome" href="/" data-icon="arrow-l" data-iconpos="notext">Back</a>
    <a id="mobFormMenu" href="/" data-icon="gear" data-iconpos="notext">Menu</a>
  </div>
  <div id="mobFormContent" data-role="content"></div>
  <div id="mobFormPopup" data-role="dialog" data-overlay-theme="b"></div>
  <div data-role="footer" data-theme="f">
    <h3 id="mobFormFooter">
      <?php include_partial('footer',$footer) ?>  
    </h3>
  </div>
</div>

<div id="mobMap" data-role="page">
  <div id="mobMapHeader" data-role="header" data-theme="f">
    <div class="mobHeadBut">
      <div id="mobMapBut" data-role="controlgroup" data-type="horizontal" data-mini="true"></div>
    </div>
    <a id="mobMapHome" href="/" data-icon="arrow-l" data-iconpos="notext">Back</a>
    <a id="mobMapMenu" href="/" data-icon="gear" data-iconpos="notext">Menu</a>
  </div>
  <div id="mobMapContent" Xdata-role="content">
    <div id="fbMap"></div>
  </div>
  <div id="mobMapPopup" data-role="dialog" data-overlay-theme="b"></div>
</div>

<div id="mobDetail" data-role="page">
  <div class="center" data-role="header" data-theme="f">
    <div class="mobHeadBut">
      <div id="mobDetailBut" data-role="controlgroup" data-type="horizontal" data-mini="true"></div>
    </div>
    <a id="mobDetailHome" href="/" data-icon="arrow-l" data-iconpos="notext">Back</a>
    <a id="mobDetailMenu" href="/" data-icon="gear" data-iconpos="notext">Menu</a>
  </div>
  <div id="mobDetailContent" data-role="content"></div>
  <div id="mobDetailPopup" data-role="dialog" XXdata-overlay-theme="b"></div>
  <div id="mobDetailFooter" data-role="footer" data-theme="f">
    <h3 id="mobDetailFooter">
      <?php include_partial('footer',$footer) ?>  
    </h3>
  </div>
</div>

<div id="mobList" data-role="page">
  <div class="center" data-role="header" data-theme="f">
    <div class="mobHeadBut">
      <div id="mobListBut" data-role="controlgroup" data-type="horizontal" data-mini="true"></div>
    </div>
    <a id="mobListHome" href="/" data-icon="arrow-l" data-iconpos="notext">Back</a>
    <a id="mobListMenu" href="/" data-icon="gear" data-iconpos="notext">Menu</a>
  </div>
  <div id="mobListContent" data-role="content"></div>
  <div id="mobListPopup" data-role="dialog" data-overlay-theme="b"></div>
  <div id="mobListFooter" data-role="footer" data-theme="f">
    <h3 id="mobListFooter">
      <?php include_partial('footer',$footer) ?>  
    </h3>
  </div>
</div>

<div id="mobDialog" data-role="dialog">
  <div class="center" data-role="header" data-theme="b">
    <h1 id="mobDialogHeader"></h1>
  </div>
  <div id="mobDialogContent" data-role="content">
    <a href="#" data-rel="back">Ok</a>
  </div>
</div>

<div id="u" data-role="page">
  <div class="center" data-role="header" data-theme="f">
    <h1 id="uHeader"></h1>
  </div>
  <div id="uContent" data-role="content">
  </div>
  <div id="uFooter" data-role="footer" data-theme="f">
    <h3 id="uFooter">
      <?php include_partial('footer',$footer) ?>  
    </h3>
  </div>
</div>

<div id="mobSearchLoc" data-role="page">
  <div class="center" data-role="header" data-theme="f">
    <div class="mobHeadBut">
      <div id="mobSearchLocBut" data-role="controlgroup" data-type="horizontal" data-mini="true"></div>
    </div>
    <a id="mobSearchLocHome" href="/" data-icon="arrow-l" data-iconpos="notext">Back</a>
    <a id="mobSearchLocMenu" href="/" data-icon="gear" data-iconpos="notext">Menu</a>
  </div>
  <div id="mobSearchLocContent" data-role="content"></div>
  <div id="mobSearchLocPopup" data-role="dialog" data-overlay-theme="b"></div>
  <div id="mobSearchLocFooter" data-role="footer" data-theme="f">
    <h3 id="mobSearchLocFooter">
      <?php include_partial('footer',$footer) ?>  
    </h3>
  </div>
</div>

<?php include_partial('footer_js',$footer) ?>
