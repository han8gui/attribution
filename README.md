# 手机号码归属地库 (PHP版)

安装：

    composer require han8gui/attribution -vvv

使用：

    use han8gui\attribution\GeoPhone;

    $res = GeoPhone::find("13512345678");

    print_r($res);


输出：

    Array
    (
        [from] => 重庆移动
        [province] => 重庆
        [city] => 重庆
        [postcode] => 400000
        [area_code] => 023
        [op] => 移动
    )
				


特别声明：

    数据文件来自项目 https://github.com/xluohome/phonedata  
    最后更新：2019年11月

fun~