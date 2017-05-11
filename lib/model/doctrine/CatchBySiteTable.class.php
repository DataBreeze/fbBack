<?php


class CatchBySiteTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CatchBySite');
    }
    public function getCatchBySites($site_list){
      if(count($site_list) < 1){
	return array();
      }
      $q = Doctrine_Query::create()
	->select('c.fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight,f.name_common AS name')
	->from('CatchBySite c')
	->innerJoin('c.FishSpecies f')
	->whereIn('c.int_site_id', $site_list)
	->groupBy('c.fish_id')
	->orderBy('SUM(count_fish) DESC')
	->limit(50); 
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      # convert to json format
      $json = array();
      foreach($rows as $i => $row){
	$json[$i]['fish_id'] = intval($row['c_fish_id']); 
	$json[$i]['count_fish'] = $row['c_count_fish']; 
	$json[$i]['avg_weight'] = ($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
	$json[$i]['avg_length'] = ($row['c_avg_length'] ? $row['c_avg_length'] : '');
	$json[$i]['name'] = $row['f_name']; 
      }
      return $json;
    }
    
}