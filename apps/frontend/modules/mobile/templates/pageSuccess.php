<div data-role="page" class="type-interior">
  
  <div data-role="header" data-theme="f">
    <h1><?php echo $pageCaption ?></h1>
    <a href="/" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
  </div>
  
  <div data-role="content">

    <div class="content-primary">       
      <?php echo htmlspecialchars_decode($pageContent) ?>
    </div>

    <div class="content-secondary">
      <div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="d">
	<h3>More in this section</h3>
	<ul data-role="listview" data-theme="c" data-dividertheme="d">
	  <li><a href="/about.html">About</a></li>
	  <li><a href="/corporate.html">Corporate</a></li>
	  <li><a href="/contact.html">Contact</a></li>
	  <li><a href="/terms.html">Terms</a></li>
	  <li><a href="/privacy.html">Privacy</a></li>
	</ul>
      </div>
    </div>

  </div>

  <div data-role="footer" data-theme="f">
    <h3>
      <?php include_partial('footer',$footer) ?>
    </h3>
  </div>

</div>

