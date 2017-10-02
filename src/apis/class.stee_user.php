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
  public static function get_user($uid ) {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    
    $r=$db->get($tblname, $ky,
      ['uid'=>$uid ] );
    return ($r);
  }
  public static function get_admin_of_fac($id) {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    
    $r=$db->select($tblname, $ky,['and'=>[
        'is_admin[>]'=>0,
        'or'=> [ 'fac_main'=>$id,'fac_can_admin[~]'=>"$id" ]
      ]]);
    return $r;
  } 

  public static function get_admins() {
    $tblname=self::table_name();
    $db=api_g('db');
    //字段名
    $ky=self::_keys();
    
    $r=$db->select($tblname, $ky,['and'=>[
        'is_admin[>]'=>0
      ]]);
    return $r;
  } 

  
}
