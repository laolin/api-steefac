<?php
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
/*
 
*/
class class_stee_user {
    
    
  public static function main( $para1,$para2) {
    $res=API::data(['time'=>time().' - stee_user is ready.']);
    return $res;
  }
  static function userVerify() {
    return USER::userVerify();
  }
  
  //test
  public static function test( ) {
    $r=self::userVerify();
    if(!$r)
      return API::msg(202001,'error userVerify');
    return API::data('Test passed.');
  }
 
//=========================================================
  //获取 数据表名
  static function table_name( $item='stee_user' ) {
    $prefix=api_g("api-table-prefix");
    return $prefix.$item;
  }
  static function _keys(   ) {
    return ['id','uid','name','is_admin','fac_main','fac_can_admin'];
  }  
   
   //=====【C---】==【Create】==============
   /**
   *  API:
   *    /steel_user/add
   */
  public static function add( ) {

  }  


  //=====【-R--】==【Restrive】==============
   /**
   *  API:
   *    /steel_user/me
   *  获得自己的权限
   */
  public static function me( ) {
    $tblname=self::table_name();
    $db=api_g('db');
    
    if(!self::userVerify()) {
      return API::msg(202001,'Error userVerify@get');
    }
    //字段名
    $ky=self::_keys();
    $uid=intval(API::INP('uid'));
    
    $r=$db->get($tblname, $ky,
      ['uid'=>$uid ] );

    //var_dump($db);
    return API::data($r);
  }  

  
}
