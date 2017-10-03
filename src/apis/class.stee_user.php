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
    return ['id','uid','name','is_admin','fac_main','fac_can_admin'];
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
      $r['is_admin'] &=1;
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
      $data=['uid'=>$userid,'is_admin'=>1,'fac_main'=>$facid,'fac_can_admin'=>$facid];
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
      ['uid'=>$uid ] );
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
        'or'=> [ 'fac_main'=>$facid,'fac_can_admin[~]'=>"$facid" ]
      ]]);
    return API::data($r);
  } 

  public static function get_admins() {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    
    $r=$db->select($tblname, $ky,['and'=>[
        'is_admin[>]'=>0
      ],"ORDER" =>'id DESC']);
    return $r;
  } 

  
}
