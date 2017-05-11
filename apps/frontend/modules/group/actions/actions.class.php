<?php

/**
 * group actions.
 *
 * @package    fb
 * @subpackage group
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class groupActions extends sfActions{

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
  }

  public function executeIndex(sfWebRequest $request){
    $loc = $this->fbLib->getLoc();
    $geo = $loc['geo'];
    $this->geo = $geo;
    $this->json_geo = json_encode($loc);
    $month_range = $this->fbLib->getMonthRange();
    $this->zoom = 10;
    $this->js_file = 'pgGroup';
    $this->group = array('count' => 0, 'records' => array());
    $this->json_member = False;
    $this->mode = 'multi';
    $this->data_template = 'tabs';
    if($request->hasParameter('group_name')){
      $name = $request->getParameter('group_name');
      $group = Doctrine_Core::getTable('UserGroup')->groupByName($name);
    }
    if($group){
      $this->data_template = 'tabs_one';
      $this->mode = 'one';
      $this->group['records'] = array($group);
      $this->group['count_total'] = 1;
      $this->group['count'] = 1;
      $this->member = Doctrine_Core::getTable('UserForGroup')->getMembers($group['id']);
      $this->json_member = json_encode($this->member);
      $this->js_file = 'pgGroupP';
    }else{
      $this->group = Doctrine_Core::getTable('UserGroup')->getGroupsBB();
    }
    $this->json_group = json_encode($this->group);
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($user['id']);
    $this->json_notes = json_encode($this->notes);
    $this->getResponse()->setTitle('FishBlab Exploring All Groups ' . $geo['input']);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Local FishBlab Fishing Groups';
    $this->cfg['page'] = 'group';
    $this->cfg['about_onclick'] = 'groupAbout();';
    $cfg_js = array('page' => 'group','mode' => $this->mode);
    $this->json_cfg = json_encode($cfg_js);
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    $this->msg = array('text' => '', 'id' => 'msgGroup', 'about_onclick' => 'groupAbout();');    
  }
  
  public function executeANewGroup(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array();
    $ret['error'] = True;
    if( ! $this->user['id']){
      $ret['desc'] = "User not logged in";
    }elseif ( !$request->hasParameter('name') ){
      $ret['desc'] = "Group name value missing";
      $ret['field'] = 'name';
    }else{
      $geo = $this->fbLib->boundsFromRequest();
      $post = array();
      $post['user_id'] = $this->user['id'];
      $post['name'] = $request->getParameter('name');
      $post['website'] = $request->getParameter('website');
      $post['caption'] = $request->getParameter('caption');
      $post['keywords'] = $request->getParameter('keywords');
      $post['fish'] = $request->getParameter('fish');
      $post['location'] = $request->getParameter('location');
      $post['gtype'] = $request->getParameter('gtype');
      $post['about'] = $request->getParameter('about');
      $post['sec'] = $request->getParameter('sec');
      $post['geo'] = $this->fbLib->boundsFromRequest();
      if(Doctrine_Core::getTable('UserGroup')->groupByName($post['name'])){
	$ret['desc'] = "Group name exists, please try another";
	$ret['field'] = 'name';
      }else{
	$group = Doctrine_Core::getTable('UserGroup')->addGroup($post);
	if($group){
	  ## add current user as member with admin priv
	  $p = array('user_id' => $this->user['id'], 'group_id' => $group['id'], 'sec' => 99);
	  if(Doctrine_Core::getTable('UserForGroup')->add($p)){
	    $group = Doctrine_Core::getTable('UserGroup')->groupById($group['id']);
	    $ret['error'] = False;
	    $ret['desc'] = "New Group Added";
	    $ret['id'] = $group['id'];
	    $ret['record'] = $group;
	  }else{
	    $ret['desc'] = "Group owner was not added as Admin member";
	  }
	}else{
	  $ret['desc'] = "Insert Error";
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  private function validateEdit(){
    $request = $this->getRequest();
    $ret = array('error' => True);    
    if($this->user['id']){
      if($request->hasParameter('id')){
	$group_id = intval($request->getParameter('id'));
	if($group_id > 0){
	  $is_admin = Doctrine_Core::getTable('UserForGroup')->isAdmin($group_id,$this->user['id']);
	  if($is_admin){
	    $ret['error'] = False;
	  }else{
	    $ret['desc'] = "System error - user not group admin : ";
	  }
	}else{
	  $ret['desc'] = "Id value invalid";
	}
      }else{
	$ret['desc'] = "Id value missing";
      }
    }else{
      $ret['desc'] = "User not logged in";
    }
    return $ret;
  }

  public function executeAEdit(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if( ! $ret['error']){
      $group_id = intval($request->getParameter('id'));
      $post = array();
      $post['id'] = $group_id;
      $post['name'] = $request->getParameter('name');
      $post['caption'] = $request->getParameter('caption');
      $post['keywords'] = $request->getParameter('keywords');
      $post['fish'] = $request->getParameter('fish');
      $post['website'] = $request->getParameter('website');
      $post['location'] = $request->getParameter('location');
      $post['gtype'] = $request->getParameter('gtype');
      $post['about'] = $request->getParameter('about');
      $post['sec'] = $request->getParameter('sec');
      if(Doctrine_Core::getTable('UserGroup')->editGroup($post)){
	if($group = Doctrine_Core::getTable('UserGroup')->groupById($post['id'])){
	  $ret['error'] = False;
	  $ret['desc'] = "Group changes Saved";
	  $ret['record'] = $group;
	}else{
	  $ret['desc'] = "System error - group not found";
	}
      }else{
	$ret['desc'] = "System error - group not saved";
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAGroupEditGeo(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    $geo = $this->fbLib->boundsFromRequest();
    if( ! $ret['error']){
      if( ( ! $geo['lat']) || (! $geo['lon']) ){
	$ret['desc'] = "Lat/Lon value missing";
      }else{
	$group_id = intval($request->getParameter('id'));
	$post['id'] = $group_id;
	$post['geo'] = $geo;
	if(Doctrine_Core::getTable('UserGroup')->editGeo($post)){
	  if($group = Doctrine_Core::getTable('UserGroup')->groupById($group_id)){
	    $ret['error'] = False;
	    $ret['desc'] = "Group Geo Saved";
	    $ret['record'] = $group;
	  }else{
	    $ret['desc'] = "Group not restored";
	  }
	}else{
	  $ret['desc'] = "Group not saved";
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeADelete(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if( ! $ret['error']){
      $group_id = intval($request->getParameter('id'));
      if(Doctrine_Core::getTable('UserGroup')->deleteGroup($group_id)){
	$ret['error'] = False;
	$ret['id'] = $group_id;
	$ret['desc'] = "Group deleted";
      }else{
	$ret['desc'] = "System error - group not deleted";
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAFetchGroupsBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $offset = $request->getParameter('groupOffset');
    if(! $offset){
      $offset = 0;
    }
    $geo = $this->fbLib->boundsFromRequest();
    $recs = Doctrine_Core::getTable('UserGroup')->getGroupsBB();
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  ## auto-lookup of group name by first few chars in search box
  public function executeALookup(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$request->hasParameter('term') ){
      $json = array();
      return $this->renderText($json);
    }
    $substr = $request->getParameter('term');
    $recs = Doctrine_Core::getTable('UserGroup')->groupByNameLike($substr);
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  ## auto-lookup of user/member name by first few chars in search box
  public function executeALookupUser(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$request->hasParameter('term') ){
      $json = array();
      return $this->renderText($json);
    }
    $substr = $request->getParameter('term');
    $recs = Doctrine_Core::getTable('User')->userByNameLikeCont($substr);
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  public function executeANameExists(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);    
    if ( !$request->hasParameter('name') ){
      $ret['desc'] = 'Name parameter not found';
    }else{
      $name = trim($request->getParameter('name'));
      if(strlen($name) > 0){
	$group = Doctrine_Core::getTable('UserGroup')->groupByName($name);
	$ret['error'] = False;
	if($group){
	  $ret['desc'] = "Group name exists, please try another";
	  $ret['name'] = $name;
	  $ret['name_exists'] = True;
	}else{
	  $ret['desc'] = "Group name not found";
	  $ret['name_exists'] = False;
	}
      }else{
	$ret['desc'] = 'Name parameter empty';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAMemberUpdate(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $user_id = $this->user['id'];
    $ret = array('error' => True, 'update_members' => False);
    $group_id = intval($request->getParameter('id'));
    if( ! $user_id){
      $ret['desc'] = "User not logged in";
    }elseif ($group_id == 0){
      $ret['desc'] = "Group id value missing";
    }else{
      $p = array('user_id' => $user_id, 'group_id' => $group_id, 'sec' => 1);
      $group = Doctrine_Core::getTable('UserGroup')->groupById($group_id);
      if( ! $group){
	$ret['desc'] = 'Group not found';
      }else{
	$request = $request->getParameter('request');
	$status = Doctrine_Core::getTable('UserGroup')->memberStatus($group_id,$user_id);
	$ret['desc'] = $status;
	if($status == 'blocked'){
	  $ret['desc'] = 'User has been blocked from this Group';
	}elseif( ($status == 'member') or ($status == 'admin') ){
	  if($request == 'remove'){
	    if(Doctrine_Core::getTable('UserForGroup')->delete($group_id,$user_id)){
	      $ret['error'] = False;
	      $ret['desc'] = 'User removed from group';
	      $ret['update_members'] = True;
	      #$ret['members'] = Doctrine_Core::getTable('UserForGroup')->getMembers($group_id);
	    }else{
	      $ret['desc'] = 'Error removing User from group';
	    }
	  }elseif($request == 'join'){
	    $ret['desc'] = 'User is current Member';
	  }else{
	    $ret['desc'] = 'Member request not recognized';
	  }
	}elseif($request == 'join'){
	  if($group['sec'] == 1){
	    if(Doctrine_Core::getTable('UserForGroup')->add($p)){
	      $ret['error'] = False;
	      $ret['desc'] = 'New Group Member added';
	      $ret['update_members'] = True;
	      #$ret['members'] = Doctrine_Core::getTable('UserForGroup')->getMembers($group_id);
	    }else{
	      $ret['desc'] = 'Error adding new member';
	    }
	  }elseif($group['sec'] == 5){
	    if(Doctrine_Core::getTable('UserForGroupReq')->add($p)){
	      $ret['error'] = False;
	      $ret['desc'] = 'Group Member request added';
	    }else{
	      $ret['desc'] = 'A request for membership is already pending';		
	    }
	  }else{
	    $ret['desc'] = 'Group Membership is closed';
	  }
	}elseif($request == 'ignore'){
	  if(Doctrine_Core::getTable('UserForGroupInvite')->delete($group_id,$user_id)){
	    $ret['error'] = False;
	    $ret['desc'] = 'Group invite has been ignored';
	  }else{
	    $ret['desc'] = 'Error ignoring group invite';
	  }
	}elseif($request == 'remove'){
	  $ret['desc'] = 'User is not a current member';
	}
      }
    }
    if( ! $ret['error'] ){
      $ret['record'] = Doctrine_Core::getTable('UserGroup')->groupById($group_id);
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function executeAAdminUpdate(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if( ! $ret['error']){
      $user_id = intval($request->getParameter('user_id'));
      $group_id = intval($request->getParameter('id'));
      if( ! $user_id){
	$ret['desc'] = 'User Id not found';
      }else{
	$group = Doctrine_Core::getTable('UserGroup')->groupById($group_id);
	if( ! $group){
	  $ret['desc'] = 'Group not found';
	}else{
	  $p = array('user_id' => $user_id, 'group_id' => $group_id, 'sec' => 1);
	  $update = $request->getParameter('update');
	  $status = Doctrine_Core::getTable('UserGroup')->memberStatus($group_id,$user_id);
	  if($update == 'block'){
	    if(Doctrine_Core::getTable('UserForGroupBlock')->add($group_id,$user_id)){
	      $ret['error'] = False;
	      $ret['desc'] = 'User has been blocked from this Group';
	    }else{
	      $ret['desc'] = 'Error blocking User';	      
	    }
	  }elseif($update == 'unblock'){
	    if($result = Doctrine_Core::getTable('UserForGroupBlock')->delete($group_id,$user_id)){
	      $ret['error'] = False;
	      $ret['desc'] = 'User has been UN-blocked from this Group';
	    }else{
	      $ret['desc'] = 'Error unblocking User';	      
	    }
	  }elseif($update == 'remove_admin'){
	    $p['sec'] = 1;
	    if(Doctrine_Core::getTable('UserForGroup')->edit($p)){
	      $ret['error'] = False;
	      $ret['desc'] = 'User Admin privileges removed';
	      $ret['update_members'] = True;
	      #$ret['members'] = Doctrine_Core::getTable('UserForGroup')->getMembers($group_id);
	    }else{
	      $ret['desc'] = 'Error removing User admin privileges';
	    }
	  }elseif($update == 'remove'){
	    if(Doctrine_Core::getTable('UserForGroup')->delete($group_id,$user_id)){
	      $ret['error'] = False;
	      $ret['desc'] = 'User removed from group';
	      $ret['update_members'] = True;
	      #$ret['members'] = Doctrine_Core::getTable('UserForGroup')->getMembers($group_id);
	    }else{
	      $ret['desc'] = 'Error removing User from group';
	    }
	  }elseif($update == 'add'){
	    $p['sec'] = 1;
	    if(Doctrine_Core::getTable('UserForGroup')->add($p)){
	      $ret['error'] = False;
	      $ret['desc'] = 'Member added to group';
	      $ret['update_members'] = True;
	      #$ret['members'] = Doctrine_Core::getTable('UserForGroup')->getMembers($group_id);
	    }else{
	      $ret['desc'] = 'Error adding Member to group';
	    }
	  }elseif($update == 'add_admin'){
	    $p['sec'] = 50;
	    if(Doctrine_Core::getTable('UserForGroup')->edit($p)){
	      $ret['error'] = False;
	      $ret['desc'] = 'Membership changed to Admin';
	      $ret['update_members'] = True;
	      #$ret['members'] = Doctrine_Core::getTable('UserForGroup')->getMembers($group_id);
	    }else{
	      $ret['desc'] = 'Error adding Admin to group';
	    }
	  }
	}
      }
    }
    if( ! $ret['error'] ){
      $ret['record'] = Doctrine_Core::getTable('UserGroup')->groupById($group_id);
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAGetMember(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'user_not_found' => False);    
    $group_id = intval($request->getParameter('id'));
    $username = trim($request->getParameter('username'));
    if($group_id < 1){
      $ret['desc'] = 'Group Id parameter not found';
    }elseif( ! $username){
      $ret['desc'] = 'UserName parameter not found';
    }elseif( ! $group = Doctrine_Core::getTable('UserGroup')->groupById($group_id) ){
      $ret['desc'] = 'Group not restored';
    }elseif( ! $member = Doctrine_Core::getTable('UserForGroup')->getMember($group_id,$username) ){
      $ret['desc'] = "User not found";
      $ret['user_not_found'] = True;
    }else{
      $ret['error'] = False;
      $ret['member'] = $member;
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAGetGroup(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);    
    $group_id = intval($request->getParameter('id'));
    if($group_id < 1){
      $ret['desc'] = 'Group Id parameter not found';
    }elseif( ! $group = Doctrine_Core::getTable('UserGroup')->groupById($group_id) ){
      $ret['desc'] = 'Group not restored';
    }else{
      $ret['desc'] = 'Group restored';
      $ret['error'] = False;
      $ret['record'] = $group;
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAGroupSearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('caption') or $request->hasParameter('name') or $request->hasParameter('about') ){
      $param = array(array('name' => 'caption', 'table' => 'g', 'value' => $request->getParameter('caption'), 'type' => 'contain'),
		     array('name' => 'name', 'table' => 'g', 'value' => $request->getParameter('name'), 'type' => 'start'),
		     array('name' => 'fish', 'table' => 'g', 'value' => $request->getParameter('fish'), 'type' => 'contain'),
		     array('name' => 'about', 'table' => 'g', 'value' => $request->getParameter('about'), 'type' => 'contain')
		     );
      $ret = Doctrine_Core::getTable('UserGroup')->groupSearch($param);
      if($ret['error']){
	$ret['desc'] = 'database error';	
      }else{
	$ret['error'] = false;
      }
    }else{
      $ret['desc'] = 'No search parameters passed';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function DELETEexecuteAGroupSearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('name') or $request->hasParameter('caption') or $request->hasParameter('location') ){
      $param['name'] = $request->getParameter('name');
      $param['caption'] = $request->getParameter('caption');
      $param['location'] = $request->getParameter('location');
      $param['user_id'] = $this->user['id'];
      $recs = Doctrine_Core::getTable('UserGroup')->groupSearch($param);
      $ret['error'] = false;
      $ret['records'] = $recs;
    }else{
      $ret['desc'] = 'No search parameters passed';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  ## groups the current user is a member of
  public function executeAGetGroups(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if(! $this->user['id']){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{
      if($groups = Doctrine_Core::getTable('UserGroup')->groupsByUserId($this->user['id']) ){
	$ret = $groups;
	$ret['error'] = false;
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  ## get all group invites for this user
  public function executeAGetRequests(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if(! $this->user['id']){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{
      $ret['records'] = array();
      if($groups = Doctrine_Core::getTable('UserGroup')->groupRequests($this->user['id']) ){
	$ret['records'] = $groups;
      }
      $ret['error'] = false;
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  ## ajax fish calls
  public function executeAFishGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbGroupFish();
    $fish->parentFishAjax();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbGroupFish();
    $fish->create();
    return $this->renderText($fish->jsonResponse());
  }  
  public function executeAFishEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbGroupFish();
    $fish->edit();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbGroupFish();
    $fish->delete();
    return $this->renderText($fish->jsonResponse());
  }
  
}
