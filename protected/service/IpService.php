<?php
class IpService
{
    

    public static function ipCheck(){
        $ipFilters = '118.150.168.122,119.77.129.231,203.69.216.186,111.250.87.245,220.135.48.168,220.135.48.164,114.32.137.240,39.9.67.254,223.137.144.231,111.71.47.168,125.227.187.55,203.69.216.186,180.218.14.225,114.136.189.102,27.52.7.219,119.77.208.243,203.69.216.186,49.158.23.126,36.226.151.159';
        //$ip = Yii::app()->request->getUserHostAddress();
        if (!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $ip = $_SERVER["REMOTE_ADDR"];
        }
             
        //echo $ip;
        $filters = explode(',',$ipFilters);
        if(in_array($ip,$filters)){
            return true;
        }else{
            if(IpService::ipIsPrivate($ip)==true){
                return true;
            }
            return false;
        }

    }

    public static function ipIsPrivate ($ip) {
        $pri_addrs = array (
            '10.0.0.0|10.255.255.255', // single class A network
            '172.16.0.0|172.31.255.255', // 16 contiguous class B network
            '192.168.0.0|192.168.255.255', // 256 contiguous class C network
            '127.0.0.0|127.255.255.255' // localhost
        );

        $long_ip = ip2long ($ip);
        if ($long_ip != -1) {

            foreach ($pri_addrs AS $pri_addr) {
                list ($start, $end) = explode('|', $pri_addr);

                // IF IS PRIVATE
                if ($long_ip >= ip2long ($start) && $long_ip <= ip2long ($end)) {
                    return true;
                }
            }
        }
        return false;
    }
}
?>
