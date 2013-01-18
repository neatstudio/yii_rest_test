yii_rest_test
=============

基于YII的测试小框架。专门用来测试接口用，目前支持POST和GET方式，支持上传文件功能的测试

##使用前注意事项
* 修改index.php，定义API_URL的路径
* 修改index.php, 定义配置文件main.php的路径

##使用时注意事项
* 目前假设API提供的数据返回是json格式，所以在TestBaseController控制器里，解析返回数据是json_decode，如果返回是xml或者其他格式，请修改此方法
* POST 方式时，不支持参数为数组，参考：/protected/controllers/site/IndexAction.php中的例子

##debug模式
* 当接口为内部接口时，建议接口中存在debug模式（根据需要修改TestBaseController中的sendData方法）
* 建议接口可以这样写
    * if(isset($_SERVER['HTTP_DEBUG']) && $_SERVER['HTTP_DEBUG'] == "true"){ .... debug mode is on}
    * 在请求时带上debug模式，可以方便的查看API输出信息（需API接口支持）

##最后
* 功能相对简单，其实phpstorm也自带了restful的测试接口，但我觉得php写的话，可以一一将所有的接口都模块化，而且能够存在本地，调试起来也比较方便
* phpstorm的restful测试插件的上传文件接口不太好
* 功能不多，但我想应该够用了。抛块砖，引点玉吧

