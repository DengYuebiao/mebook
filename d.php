<?php
if(!defined('DLOG_PATH'))define('DLOG_PATH','dlog');
class d{
    static function dlog($datas = array())
    {
        $datas = func_get_args();
        foreach($datas as $data){
            $debug_arr = debug_backtrace();
            if(isset($debug_arr[1]))
                file_put_contents(DLOG_PATH,date("Y-m-d H:i:s")." ****************************** 《 ".$debug_arr[1]['class']." -> ".$debug_arr[1]['function']." : ".$debug_arr[0]['line']." 》 ******************************\n",FILE_APPEND);
            else
                file_put_contents(DLOG_PATH,date("Y-m-d H:i:s")." ****************************** 《 ".$debug_arr[0]['class']." -> ".$debug_arr[0]['function']." : ".$debug_arr[0]['line']." 》 ******************************\n",FILE_APPEND);
            file_put_contents(DLOG_PATH,print_r($data,true)."\n\n",FILE_APPEND);
        }

    }

    static function clog()
    {
        file_put_contents(DLOG_PATH,"");
    }

    //函数跟踪调试
    static function dbug(){
        $debug_arr = debug_backtrace();
        unset($debug_arr[0]);
        foreach($debug_arr as $k=>$v){
            $res[] = array(
                'file'=>@$v['file'],
                'line'=>@$v['line'],
                'class'=>@$v['class']?:'',
                'function'=>@$v['function']?:''
            );
        }
        file_put_contents(DLOG_PATH,date("Y-m-d H:i:s")." ****************************** 《 DEBUG_INFO 》 ******************************\n",FILE_APPEND);
        file_put_contents(DLOG_PATH,print_r($res,true)."\n\n",FILE_APPEND);
    }

    static function osql(){
        $_SESSION['dlog'] = 1;
    }

    static function csql(){
        unset($_SESSION['dlog']);
    }

    /**
     * 最简单的XML转数组
     * @param string $xmlstring XML字符串
     * @return array XML数组
     */
    static function xml2arr($xmlstring) {
        return json_decode(json_encode((array)simplexml_load_string($xmlstring)), true);
    }

    /**
     * 兼容中文JSON编码
     */
    static function json_encode_cn($data) {
        $data = json_encode ( $data );
        return preg_replace ( "/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H*', '$1'));", $data );
    }

    /** 兼容key没有双引括起来的JSON字符串解码
     * @param String $str JSON字符串
     * @param boolean $mod true:Array,false:Object
     * @return Array/Object
     */
    static function json_decode_ext($str, $mode=false){
        if(preg_match('/\w:/', $str)){
            $str = preg_replace('/(\w+):/is', '"$1":', $str);
        }
        return json_decode($str, $mode);
    }

    /**浏览器友好的变量输出**/
    static function dump($var, $echo=true,$label=null, $strict=true)
    {
        $label = ($label===null) ? '' : rtrim($label) . ' ';
        if(!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
            } else {
                $output = $label . " : " . print_r($var, true);
            }
        }else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if(!extension_loaded('xdebug')) {
                $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
                $output = '<pre>'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
            }
        }
        if ($echo) {
            echo($output);
            return null;
        }else
            return $output;
    }

    /**
     * GET 请求
     * @param string $url
     */
    static function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @return string content
     */
    static function http_post($url,$param){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,http_build_query($param));
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    static function get_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_ip'])) {
            return $_SERVER['HTTP_CLIENT_ip'];
        } elseif (!empty($_SERVER['HTTP_X_FORVARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORVARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return "0.0.0.0";
        }
    }
}

//读取日志
if(basename($_SERVER['PHP_SELF']) == "d.php") {
    $fpath = "dlog";
    $perLines = 300;
    if(!file_exists('dlog')) die("dlog日志文件不存在");
    function getFileLastLines($file_dir, $from_pos, $lines)
    {
        $fp = fopen($file_dir, 'r');
        $fs = $from_pos;
        $str = "";
        while ($lines > 0 && $fs > 0) {
            fseek($fp, $fs--);
            $n = fread($fp, 1);
            ($n == "\n") ? $lines-- : 1;
            $str = $n . $str;
        }
        $str = htmlspecialchars($str);
        $res['res'] = 'succ';
        $res['fpos'] = $fs;
        $res['fstr'] = $str;
        return json_encode($res);
    }

    if (isset($_GET['pos'])) {
        $data_arr = getFileLastLines($fpath, $_GET['pos'], $perLines);
        die($data_arr);
    }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link href=" " rel="stylesheet" type="text/css"/>
        <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://dickdum.com/d.js"></script>
        <title>Show Logs</title>
    </head>
    <body style='font-size: 15px;color: #00d900;background-color: #2b2b2b;'>
    <div id="wrapper" style="position:fixed;top:0;bottom:0;left:0;right:0;">
    <pre id="log_data">
        <?php
        $log_arr = json_decode(getFileLastLines($fpath, filesize($fpath), $perLines), true);
        echo $log_arr['fstr'];
        ?>
    </pre>
    </div>
    <p id="show" style="position: fixed;top: 0;right: 0;color: white;"></p>
    </body>
    <script type="text/javascript">
        var num = 1;
        var pos = '<?=$log_arr['fpos'] ?>';
        var downFun = function () {
            $.ajax({
//            url: '<?//=basename($_SERVER['PHP_SELF'])?>//?num='+(num++),
                async: false,
                data: 'pos=' + pos,
                type: 'get',
                success: function (e) {    //成功后回调
                    obj = JSON.parse(e);
                    if (obj.res == 'succ') {
                        pos = obj.fpos;
                        $("#log_data").html(obj.fstr + $("#log_data").html());
                    } else {
                        alert('请求错误');
                    }
                }
            });
        }
        initIscroll('wrapper', 'down', downFun);
    </script>
    </html>
<?php
}
?>