<?php
//WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
/*
 
*/
class class_steefac{
    
    
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
  static function table_name( $item='steelfactory' ) {
    $prefix=api_g("api-table-prefix");
    return $prefix.$item;
  }
  static function all_keys( ) {
    return [
      "level"=>1,
      "license"=>4,
      "name"=>4,
      "addr"=>4,
      "latE7"=>5,
      "lngE7"=>5,
      "province"=>2,
      "city"=>0,
      "district"=>0,
      "citycode"=>2,
      "adcode"=>0,
      "formatted_address"=>4,
      
      "cap_y"=>0,
      "cap_1m"=>0,
      "cap_2m"=>0,
      "cap_3m"=>0,
      "cap_6m"=>0,
      "workers"=>0,
      "workers_hangong"=>0,
      "workers_maogong"=>0,
      "workers_gongyi"=>0,
      "workers_xiangtu"=>0,
      "workers_other"=>0,
      "goodat"=>0,
      "area_factory"=>0,
      "area_duichang"=>0,
      "max_hangche"=>0,
      "max_paowan"=>0,
      "max_duxin"=>0,
      "dist_port"=>0,
      "dist_expressway"=>0
    ];
  }
  static function data_val($d, $key, & $data ) {
    if(false === $d[$key]) return;
    $data[$key]=$d[$key];
  }
  //TODO: 有效性检查
  //这些是用于 update API 中 能直接通过参数能修改的字段
  //其他字段不可用参数修改，比如 del flag access 等字段
  static function data_all( ) {
    $data=[];
    $keys=self::all_keys();
    $d= json_decode(API::INP('d'), true);
    api_g('query-d',$d);
    foreach ($keys as $k => $v){
      self::data_val($d,$k,$data);
    }
    
   
    return $data;
  }
  static function data_check(  $data ) {
    $keys=self::all_keys();
    $err='';
    foreach ($keys as $k => $v){
      if( isset($data[$k]) && strlen($data[$k])<$v ) {
        $err.="E:$k.";
      }
    }
    return $err;
  }
// \\=========================================================
   
   /**
   *  API:
   *    /steefac/add
   */
  public static function add( ) {
    $data=self::data_all();
    $err=self::data_check(  $data );
    if($err) {
      return API::msg(202001,'Error: '.$err);
      //return API::data([$err,$data]);
    }
    $db=api_g('db');
    $tblname=self::table_name();
    $r=$db->insert($tblname,$data );
    //var_dump($db);
    if(!$r) {
      return API::msg(202001,'Error: Create data.');
    }
    $data['id']=$r;
    return API::data([$tblname,$db,$data]);
  }  
 
  
}