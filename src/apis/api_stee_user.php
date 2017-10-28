<?php
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
/*
 
*/

require_once dirname( __FILE__ ) . '/class.stee_user.php';

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
   
   //=====【C---】==【Create】==============
   /**
   *  API:
   *    /steel_user/applyAdmins
   */
  public static function apply_fac_admin( ) {
    if(!self::userVerify()) {
      return API::msg(202001,'Error userVerify@get');
    }
    $userid=intval(API::INP('userid'));
    $facid=intval(API::INP('facid'));
    return stee_user::apply_fac_admin($userid,$facid);
  }  
  public static function apply_admin( ) {
    if(!self::userVerify()) {
      return API::msg(202001,'Error userVerify@get');
    }
    $type=API::INP('type');
    $userid=intval(API::INP('uid'));
    $facid=intval(API::INP('facid'));
    return stee_user::apply_admin($type,$userid,$facid);
  }  



  //=====【-R--】==【Restrive】==============
   /**
   *  API:
   *    /steel_user/me
   *  获得自己的权限
   */
  public static function me( ) {
    
    if(!self::userVerify()) {
      return API::msg(202001,'Error userVerify@get');
    }
    $uid=intval(API::INP('uid'));
    $r=stee_user::get_user($uid);
    
    return API::data($r);
  }
  
  
   /**
   *  API:
   *    /steel_user/get_admins
   *  获得 所有的管理员
   */
  public static function get_admins( ) {
    
    if(!self::userVerify()) {
      return API::msg(202001,'Error userVerify@get');
    }
    
    $uid=intval(API::INP('uid'));
    $user=stee_user::get_user($uid );
    if(!($user['is_admin']& 0x10000)) {
      return API::msg(202001,"not sysadmin");
    }
    
    $r=stee_user::get_admins();
    
    return API::data($r);
  }
  
   /**
   *  API:
   *    /steel_user/get_admin_of_fac
   *  获得一个fac的管理员
   */
  public static function get_admin_of_fac( ) {
    
    if(!self::userVerify()) {
      return API::msg(202001,'Error userVerify@get');
    }
    $facid=intval(API::INP('facid'));
    return stee_user::get_admin_of_fac($facid);
  }  

  public static function get_admin_of_obj( ) {
    
    if(!self::userVerify()) {
      return API::msg(202001,'Error userVerify@get');
    }
    $type=API::INP('type');
    $facid=intval(API::INP('facid'));
    return stee_user::get_admin_of_obj($type,$facid);
  }  

  
}
