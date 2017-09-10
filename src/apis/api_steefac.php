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
  static function keys_req( ) {
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
    $keys=self::keys_req();
    $d= json_decode(API::INP('d'), true);
    api_g('query-d',$d);
    foreach ($keys as $k => $v){
      self::data_val($d,$k,$data);
    }
    
   
    return $data;
  }
  static function data_check(  $data ) {
    $keys=self::keys_req();
    $err='';
    foreach ($keys as $k => $v){
      if( isset($data[$k]) && strlen($data[$k])<$v ) {
        $err.="E:$k.";
      }
    }
    return $err;
  }
  static function keys_list(  $data ) {
    $keys=self::keys_req();
    $ky=[];
    foreach ($keys as $k => $v){
      $ky[]=$k;
    }
    return $ky;
  }
// \\=========================================================
   
   //=====【C---】==【Create】==============
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
    return API::data($data);
  }  


  //=====【-R--】==【Restrive】==============
   /**
   *  API:
   *    /steefac/detail
   */
  public static function detail( ) {
    $tblname=self::table_name();
    $db=api_g('db');
    
    //字段名
    $ky=self::keys_list();
    $ky[]='id';
    $ky[]='mark';
    $id=intval(API::INP('id'));
    if($id<1) {
      return API::msg(202001,'Error: id');
    }
    
    $r=$db->get($tblname, $ky,
      ['and' => ['id'=>$id,'or'=>['mark'=>null,'mark#'=>''] ] ] );

    return API::data($r);
  }  
   /**
   *  API:
   *    /steefac/search
   */
  public static function search( ) {
    $tblname=self::table_name();
    $db=api_g('db');
    
    //字段名
    $ky=self::keys_list();
    $ky[]='id';
    $ky[]='mark';
    
    //页数
    $count=intval(API::INP('count'));
    if($count<5)$count=5;
    if($count>500)$count=500;

    $page=intval(API::INP('page'));
    if($page<1)$page=1;
    
    $tik=0;
    $andArray=[];


    //坐标范围搜索： 纬度 ,经度(*1e7), 距离(m)
    //1米 = 0.00001度 近似
    $lat=intval(API::INP('lat'));
    $lng=intval(API::INP('lng'));
    $dist=intval(API::INP('dist'));
    if($lat>10E7 && $lat < 55e7 
      && $lng>70E7 && $lng < 140e7
      && $dist>100 && $dist < 999E3) {
        // 此条件下 假定其格式正确
      $lat1=$lat-$dist*100;
      $lng1=$lng-$dist*100;
      $lat2=$lat+$dist*100;
      $lng2=$lng+$dist*100;
      $posand=['lngE7[>]'=>$lng1,'lngE7[<]'=>$lng2,'latE7[>]'=>$lat1,'latE7[<]'=>$lat2 ];
      $tik++;
      $andArray["and#t$tik"]=$posand;
    }

    //搜索字符
    $search=API::INP('s');
    if(strlen($search)>0) {
      $k= preg_split("/[\s,;]+/",$search);
      $s_key=[
        "id",
        "license",
        "name",
        "addr",

        "province",
        "city",
        "district",
        "citycode",
        //"adcode",
        "formatted_address",

        "goodat"
      ];
      $w_or=[];
      for($i=count($k); $i--;  ) {
        $or_list=[];
        for($j=count($s_key); $j--; ) {
          $or_list[$s_key[$j].'[~]']=$k[$i];
        }
        $w_or["or#".$i]=$or_list;
      }
      $tik++;
      $andArray["and#t$tik"]=$w_or;
    }

    //正常标记的才返回
    $tik++;
    $andArray["and#t$tik"]=['or'=>['mark#1'=>null,'mark#2'=>'']];
    
    $where=["LIMIT" => [$page*$count-$count, $count] , "ORDER" => ["level ASC", "cap_y DESC","id DESC"]] ;
    if(count($andArray))
      $where['and'] = $andArray ;


    //var_dump($where);
    $r=$db->select($tblname, $ky,$where);
    $res['data']=$r;
    return API::data($r);

  } 


  //=====【--U-】==【Update】==============
   /**
   *  API:
   *    /steefac/update
   */
  public static function update( ) {

    $data=self::data_all();
    $err=self::data_check(  $data );
    if($err) {
      return API::msg(202001,'Error: '.$err);
      //return API::data([$err,$data]);
    }
    $db=api_g('db');
    $tblname=self::table_name();
    
    $id=intval(API::INP('id'));
    if(!$id) {
      return API::msg(202001,'Error: id');
    }
    unset($data['id']);
    
    $r=$db->update($tblname, $data, ['id'=>$id] );

    return API::data($r);
  }  
  //=====【---D】==【Delete】==============
   /**
   *  API:
   *    /steefac/delete
   */
  public static function delete( ) {

    $db=api_g('db');
    $tblname=self::table_name();
    
    $id=intval(API::INP('id'));
    if(!$id) {
      return API::msg(202001,'Error: id');
    }
    unset($data['id']);
    
    $r=$db->update($tblname, ['mark'=>'DEL'], ['id'=>$id] );

    return API::data($r);
  }  

}
