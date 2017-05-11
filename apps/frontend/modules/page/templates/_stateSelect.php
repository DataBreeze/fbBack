<form name="selectState" action="/" class="inline">
<select id="stateSelect" name="state">
  <?php for($i=0; $i<count($states); $i++) : ?>
	<option value="<?php echo $states[$i]['state'] ?>"><?php echo $states[$i]['state'] ?></option>
  <?php endfor ?> 
</select>
</form>