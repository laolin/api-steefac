<?php
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
/*
 
*/
class stee_user {
//=========================================================
  //获取 数据表名
  static function table_name( $item='stee_user' ) {
    $prefix=api_g("api-table-prefix");
    return $prefix.$item;
  }
  static function _keys(   ) {
    return ['id','uid','name','is_admin','update_at','fac_can_admin','steefac_can_admin','steeproj_can_admin'];
  }  
  static function  _check_obj_type( $type ) {
    $ok=['steefac','steeproj'];
    //注意，这里需要true,严格类型检查
    return in_array($type,$ok,true);
  }
//=================================================
//  普通用户申请增加一个工厂的管理权限
  public static function apply_fac_admin($userid,$facid) {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    
    $r=self::get_user($userid);
    if($r) {
      $id=$r['id'];
      unset($r['id']);
      $r['is_admin'] |= 1;
      if($r['fac_can_admin']){
        $ff=explode(',',$r['fac_can_admin']);
        if(count($ff)>3){
          return API::msg(202001,"count exceed");
        }
        $r['fac_can_admin'].=','.$facid;
      }
      else $r['fac_can_admin']=$facid;
      $r2=$db->update($tblname,$r,['id'=>$id]);
    } else {
      $data=['uid'=>$userid,'is_admin'=>1,
        'fac_can_admin'=>$facid];
      $r2=$db->insert($tblname,$data);
    }
    if( !$r2 ){
      return API::msg(202001,"run sql err");
    }
    return API::data($r2);
  }
  //======1//======//======//======
  public static function apply_admin($type,$userid,$obj_id) {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    if(!self::_check_obj_type($type)){
      return API::msg(202001,"E:type:".$type);
    }
    
    $col_name=$type.'_can_admin';
    $r=self::get_user($userid);
    if($r) {
      $id=$r['id'];
      unset($r['id']);
      $r['is_admin'] |= 1;
      if($r[$col_name]){
        $ff=explode(',',$r[$col_name]);
        if(count($ff)>3){
          return API::msg(202001,"count exceed");
        }
        $r[$col_name].=','.$obj_id;
      }
      else $r[$col_name]=$obj_id;
      $r2=$db->update($tblname,$r,['id'=>$id]);
    } else {
      $data=['uid'=>$userid,'is_admin'=>1,
        $col_name=>$obj_id];
      $r2=$db->insert($tblname,$data);
    }
    if( !$r2 ){
      return API::msg(202001,"run sql err");
    }
    return API::data($r2);
  }
//=================================================
  public static function get_user($uid ) {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    
    $r=$db->get($tblname, $ky,
      ['and'=>['uid'=>$uid ,'mark'=>'']] );
    return ($r);
  }
  public static function get_admin_of_fac($facid) {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    if( strlen($facid)<5 ){
      return API::msg(202001,"facid err");
    }
    
    $r=$db->select($tblname, $ky,['and'=>[
        'is_admin[>]'=>0,
        'mark'=>'',
        'fac_can_admin[~]'=>"$facid"
      ],"ORDER" =>'update_at DESC']);
    return API::data($r);
  } 
  //======1//======//======//======
  public static function get_admin_of_obj($type,$obj_id) {
    $tblname=self::table_name();
    if(!self::_check_obj_type($type)){
      return API::msg(202001,"E:type:".$type);
    }
    
    $col_name=$type.'_can_admin';
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    if( $obj_id<1 ){
      return API::msg(202001,"E:obj_id:".$obj_id);
    }
    
    $r=$db->select($tblname, $ky,['and'=>[
        'is_admin[>]'=>0,
        'mark'=>'',
        $col_name.'[~]'=>"$obj_id"
      ],"ORDER" =>'update_at DESC']);
    return API::data($r);
  } 

  public static function get_admins() {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    
    $r=$db->select($tblname, $ky,['and'=>[
        'is_admin[>]'=>0,'mark'=>'',
      ],"ORDER" =>'update_at DESC']);
    return API::data($r);
  } 

  
}
