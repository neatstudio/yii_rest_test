<?php
/**
 *
 *
 * @category
 * @package
 * @author   gouki <gouki.xiao@gmail.com>
 * @version $Id$
 * @created 12-12-22 上午11:45
 */
class TestBaseController extends Controller
{
    /**
     * 定义controller对应的Action目录，需要在每个Action里定义
     * 如果没有定义，默认取控制器的名称
     * @var string
     */
    public $controlPath = '';

    /**
     * 自动将目录下面的Action文件定
     * @return array
     */
    public function actions()
    {
        $path = $this->controlPath;
        if (!$path) {
            $path = $this->getId();
        }
        $dirname = Yii::getPathOfAlias("application.controllers.{$path}");
        $result = array();
        foreach (glob($dirname . "/*Action.php") as $name) {
            //echo basename($name) ," <br />";
            $name = basename($name);
            $key = strtolower(str_replace("Action.php", "", $name));
            $value = str_replace(".php", "", $name);
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * 使用POST方式请求接口
     * @param $act
     * @param array $params
     * @param bool $debug
     * @return mixed
     */
    public function runAppPost($act, $params = array(), $debug = false)
    {
        return $this->sendData($act, $params, "POST", $debug);
    }

    /**
     * 使用GET方法请求接口
     * @param $act
     * @param array $params
     * @param bool $debug
     * @return mixed
     */
    public function runAppGet($act, $params = array(), $debug = false)
    {
        return $this->sendData($act, $params, "GET", $debug);
    }

    /**
     * 解析，输出接口的返回值
     * 这里假设接口输出为JSON，因此如果不是JSON格式的数据，需要重写本方法
     * @param $content
     */
    public function parseContent($content)
    {
        $jsondata = json_decode($content, true);
        if (!$jsondata) {
            echo $content;
            exit;
        }
        if (isset($jsondata['error']['errormsg'])) {
            $msg = json_decode($jsondata['error']['errormsg']);
            $jsondata['error']['errormsg'] = $msg ? $msg : $jsondata['error']['errormsg'];
        }
        echo "<pre>";
        print_r($jsondata);
        echo "</pre>";
    }

    /**
     * 使用CURL发送数据（主要目的是因为curl可以上传文件，而且很方便）
     *
     * @param $act
     * @param $params
     * @param $type
     * @param bool $debug
     * @return mixed
     */
    protected function sendData($act, $params, $type, $debug = false)
    {
        $request_url = rtrim(API_URL, "/") . "/" . $act;
        $handle = curl_init();
        $tmpCookefile = sprintf("/tmp/cookie/%s.log", parse_url($request_url, PHP_URL_HOST));
        if (!is_dir(dirname($tmpCookefile))) {
            mkdir(dirname($tmpCookefile), 0777, true);
        }
        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: utf-8,gbk,gb2312,x-gbk;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Accept-Encoding: gzip, deflate";
        $header[] = "Pragma: "; // browsers keep this blank.
        $header[] = "Host: " . parse_url($request_url, PHP_URL_HOST);
        if ($debug == false) {
            $header[] = "debug: false";
        }
        else {
            $header[] = "debug: true";
        }
        curl_setopt_array($handle, array(
            CURLOPT_USERAGENT => 'Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5',
            CURLOPT_FOLLOWLOCATION => (ini_get('open_basedir') != '' ? false : true), //这个，如果设置了open_basedir就用不了了。。
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => true,
            //CURLOPT_GETFIELDS      => null,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_COOKIEFILE => $tmpCookefile,
            CURLOPT_COOKIEJAR => $tmpCookefile,
            CURLOPT_ENCODING => 'gzip,deflate',
            CURLOPT_HTTPHEADER => $header,
        ));
        switch (strtoupper($type)) {
            case "POST":
                curl_setopt($handle, CURLOPT_URL, $request_url);
                curl_setopt($handle, CURLOPT_POST, TRUE);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
                break;
            case "GET":
                curl_setopt($handle, CURLOPT_URL, $request_url . "?" . http_build_query($params));
                break;
        }
        $content = curl_exec($handle);
        return $content;
    }

    /**
     * 取得API的路径
     * @return string
     */
    public function getApiPath()
    {
        //根据需求，如果能够确认，则使用getUniquieId()
        //return $this->getUniqueId();
        return $this->getId() . "/" . $this->getAction()->getId();
    }
}
