<?php include_partial('global/top_header_no_search',array('user' => $user )) ?>
<div id="hmImage">
  <img class="center" src="/images/hmTarpon.jpg" />
</div>
<div id="hmMain">
    <h2>Popular Fishing Cities</h2>
    <?php include_partial('city_links',array('cities' => $cities) ) ?>
</div>
<?php include_partial('footer',$footer ) ?>
