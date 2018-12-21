<?php

/**
 *
 * File:  Head.php
 * Author: Skiychan <dev@skiy.net>
 * Created: 2018/12/21
 */

declare(strict_types=1);

namespace ChinaGnss\Structure;

use ChinaGnss\Format;

class Head implements Data {
    public $data; //源内容

    public $msg_id; //消息ID
    public $body_prop; //消息体属性
    public $device_mobile; //设备手机号
    public $msg_number; //消息流水号
    public $msg_items; //包封装项

    public function __construct($data) {
        $this->data = $data;

        $this->split();
    }

    /**
     * 切割
     */
    public function split() : void {
        $this->msg_id = Format::subByte($this->data, 0, 2);
        $this->body_prop = Format::subByte($this->data, 2, 2);
        $this->device_mobile = Format::subByte($this->data, 4, 6);
        $this->msg_number = Format::subByte($this->data, 10, 2);
        $this->msg_items = Format::subByte($this->data, 12);
    }

    /**
     * 解析消息头
     * @return array
     */
    public function analyze() : array {
        $msg_items = [];
        if (mb_strlen($this->msg_items) > 0) {
            $msg_items['count'] = base_convert(Format::subByte($this->msg_items, 0, 2), 16, 10); //包总数
            $msg_items['num'] = base_convert(Format::subByte($this->msg_items, 2, 2), 16, 10); //包序号
        }

        $msg = [
            'msg_id' => $this->msg_id,
            'body_prop' => [], //消息体属性
            'device_mobile' => $this->device_mobile,
            'msg_number' => base_convert($this->msg_number, 16, 10), //流水号
            'msg_items' => $msg_items,
        ];

        return $msg;
    }
}