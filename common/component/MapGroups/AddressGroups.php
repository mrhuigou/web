<?php
namespace common\component\MapGroups;
use Yii;

class AddressGroups {
    public function  __construct(){

    }

    //@param addresses Object Address(from jr_address)
   public function getHeatMapsData($addressses){
        if(!empty($addressses)){
            $return_data = array();
            foreach($addressses as $address){
                if(!empty($address->shipping_address_1)){
                    $address_info = "";
                    $address_1 = $address->shipping_address_1;

                    $address_1 =  preg_replace("/(.*)省/", "", $address_1);
                    $address_1 =  preg_replace("/，/", "", $address_1);
                    $address_1 =  preg_replace("/ /", "", $address_1);
                    $address_1 =  preg_replace("/青岛市/", "", $address_1);
                    $address_1 =  preg_replace("/(李沧区|市南区|市北区|崂山区|城阳区|黄岛区|开发区){1}/", "", $address_1);
                    if( preg_match("/(.*)路([0-9]*|一|二|三|四|五|六|七|八|九|十)号?/",$address_1,$address_info)){
                        $address_info = $address_info[0];
                    }
                    if(empty($address_info)){
                        if( preg_match("/((.*)大学(.*)校区)/",$address_1,$address_info)){
                            $address_info = $address_info[0];
                        }
                    }
                    if(empty($address_info)){
                        $address_info = $address_1;
                    }
                    if(!empty($address_info)){
                        $return = file_get_contents("http://api.map.baidu.com/geocoder/v2/?address='".$address_info."'&output=json&ak=qrDz4DGnKDfg0WtdDkOYn0Op&city='青岛市'");
                        $return = json_decode($return);
                        if($return->status == 0){
                            $key =  number_format($return->result->location->lng,6).'-'.number_format($return->result->location->lat,6);
                            //$return_data[$key][] = $return->result->location;
                            if(!$address->lng){
                                $address->lng = $return->result->location->lng;
                                $address->lat = $return->result->location->lat;
                                $address->save();
                            }
                        }

                    }
                }

            }
           // return $return_data;
        }
   }

}