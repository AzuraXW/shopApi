<?php
namespace App\Facades\Express;

use Illuminate\Support\Facades\Http;

class express
{
    // 商户ID
    protected $EBusinessID;
    // API KEY
    protected $ApiKey;
    // 模式
    protected $mode;
    public function __construct() {
        $config = config('express');
        $this->EBusinessID = $config['EBusinessID'];
        $this->ApiKey = $config['ApiKey'];
        $this->mode = $config['mode'] ?? 'product';
    }

    // 快递足迹查询
    public function track ($ShipperCode, $LogisticCode) {
        // 组装应用级参数
        $requestData = "{".
            "'OrderCode': '41231645',".
            "'ShipperCode': '{$ShipperCode}',".
            "'LogisticCode': '{$LogisticCode}'".
            "}";

        $response = Http::asForm()->post($this->url('track'), $this->formatReqData($requestData, '1002'));
        return $this->formatResData($response);
    }

    // 格式化返回数据
    public function formatResData ($result) {
        $result = json_decode($result, true);
        if ($result['Success'] == false) {
            return $result;
        }
        return json_decode($result['ResponseData'], true);
    }

    // 格式化请求参数
    public function formatReqData ($requestData, $requestType) {
        // 组装系统级参数
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => $requestType, //快递查询接口指令8002/地图版快递查询接口指令8004
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData);

        return $datas;
    }

    // 根据配置返回请求地址
    public function url ($type) {
        $url = [
            'track' => [
                'product' => '',
                'dev' => 'https://kdniao.com/UserCenter/v2/SandBox/SandboxHandler.ashx?action=CommonExcuteInterface'
            ]
        ];
        return $url[$type][$this->mode];
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param ApiKey ApiKey
     * @return DataSign签名
     */
    function encrypt($data) {
        return urlencode(base64_encode(md5($data.$this->ApiKey)));
    }
}
