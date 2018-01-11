<?php
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
/*
 
*/
require_once dirname( __FILE__ ) . '/class.stee_user.php';
class class_steesys {
    
    
  public static function main( $para1,$para2) {
    $res=API::data(['time'=>time().' - steefac is ready.']);
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
  static function table_name($item ) {
    $prefix=api_g("api-table-prefix");
    return $prefix.$item;
  }
  
  
  /*
  $data = $database->query(
    "SELECT * FROM account WHERE user_name = :user_name AND age = :age", [
      ":user_name" => "John Smite",
      ":age" => 20
    ]
  )->fetchAll();
 
  print_r($data);
  
  */
  public static function info( ) {
    $data=[];
    $db=api_g('db');
    
    $tblname=self::table_name('steelfactory');
    $data['nFac'] = $db->query(
      "SELECT count(*) as nFac FROM $tblname WHERE `mark` is  null or `mark` = '' "
    )->fetchAll()[0]['nFac'];

    $tblname=self::table_name('steelproject');
    $data['nProj'] = $db->query(
      "SELECT count(*) as nProj FROM $tblname WHERE `mark` is  null or `mark` = '' "
    )->fetchAll()[0]['nProj'];
    
    if(!self::userVerify()) {
      return API::data($data);
    }
    $uid=intval(API::INP('uid'));
    $data['me']=($r);
    $r=stee_user::_get_user($uid);

        

    return API::data($data);
  }
  

}
