<?php
echo "[] Input No HP = ";
$nohp=trim(fgets(STDIN));
$koderef='REFHOUJMKX';
$nama = gen_nama();
$domain=array("@yahoo.com","@hotmail.com");
$email=$nama['f'].''.$nama['l'].''.mt_rand(100,999).''.$domain[mt_rand(0,1)];
$lat=mt_rand(10000,99999);
$long=mt_rand(10000,99999);
$link='https://api.fitco.id/user/register?user_longitude=106.'.$long.'&user_latitude=-6.'.$lat;
$password=$nama['f']."".mt_rand(10,99);
$headers=explode("\n","accept:application/json\nauthorization:Bearer null\napi_version:1\nuser_lang:en\nx-custom-fitco-shop-guest-authentication:FITCO_SHOP\nContent-Type:application/json\nHost:api.fitco.id\nConnection:Keep-Alive\nAccept-Encoding:gzip\nUser-Agent:okhttp/3.12.1");
$regotp=request($link,'{"data":{"c_password":"'.$password.'","email":"'.$email.'","first_name":"'.$nama['f'].'","last_name":"'.$nama['l'].'","password":"'.$password.'","phone":"'.$nohp.'","promo_code":"'.$koderef.'"}}',$headers,'POST');
$link2='https://api.fitco.id/user/activation?user_longitude=106.'.$long.'&user_latitude=-6.'.$lat;
echo "[] Input OTP = ";
$otp=trim(fgets(STDIN));
$verifotp=request($link2,'{"data":{"phone":"'.$nohp.'","email":"'.$email.'","otp_code":"'.$otp.'"}}',$headers,'POST');
echo "Sukses\n";
function gen_nama(){
$c = curl_init("https://randomuser.me/api/?inc=name&nat=us");
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($c, CURLOPT_MAXREDIRS, 15);
    curl_setopt($c, CURLOPT_TIMEOUT, 30);
    curl_setopt($c, CURLOPT_ENCODING, "");
    curl_setopt($c, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($c, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_HEADER, true);
    $response = curl_exec($c);
    $f=str_replace(' ', '', get_between($response, '"first":"', '"'));
    $l=str_replace(' ', '', get_between($response, '"last":"', '"'));
    return array('f' => $f, 'l' => $l);
}
 
function request($url, $param, $headers, $request = 'POST') {
        $ch = curl_init($url);
        //curl_setopt($ch, CURLOPT_URL, );
        if($param!=null){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        elseif($request=="GET"){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        }
        if($headers!=null){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        }
       
       
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $execute = curl_exec($ch);
        $cookies = array();
        preg_match_all('/Set-Cookie:(?<cookie>\s{0,}.*)$/im', $execute, $cookies);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($execute, 0, $header_size);
        $body = substr($execute, $header_size);
        curl_close($ch);
        return [$body, $header, $cookies['cookie'],$execute];
}
function get_between($string, $start, $end){
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
}