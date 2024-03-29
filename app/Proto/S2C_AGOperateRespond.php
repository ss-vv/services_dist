<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pb_HTTP.pto

namespace App\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *调整结果返回//
 *
 * Generated from protobuf message <code>App.Proto.S2C_AGOperateRespond</code>
 */
class S2C_AGOperateRespond extends \Google\Protobuf\Internal\Message
{
    /**
     *调整结果，0表示成功//
     *
     * Generated from protobuf field <code>uint32 result = 1;</code>
     */
    private $result = 0;
    /**
     *调整后用户信息结果集//
     *
     * Generated from protobuf field <code>.App.Proto.S2C_AGGetListRespond tyRespond = 2;</code>
     */
    private $tyRespond = null;
    /**
     *调整失败原因//
     *
     * Generated from protobuf field <code>string szresult = 3;</code>
     */
    private $szresult = '';

    public function __construct() {
	    \App\Proto\GPBMetadata\PbHTTP::initOnce();
	    parent::__construct();
    }

    /**
     *调整结果，0表示成功//
     *
     * Generated from protobuf field <code>uint32 result = 1;</code>
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     *调整结果，0表示成功//
     *
     * Generated from protobuf field <code>uint32 result = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setResult($var)
    {
        GPBUtil::checkUint32($var);
        $this->result = $var;

        return $this;
    }

    /**
     *调整后用户信息结果集//
     *
     * Generated from protobuf field <code>.App.Proto.S2C_AGGetListRespond tyRespond = 2;</code>
     * @return \App\Proto\S2C_AGGetListRespond
     */
    public function getTyRespond()
    {
        return $this->tyRespond;
    }

    /**
     *调整后用户信息结果集//
     *
     * Generated from protobuf field <code>.App.Proto.S2C_AGGetListRespond tyRespond = 2;</code>
     * @param \App\Proto\S2C_AGGetListRespond $var
     * @return $this
     */
    public function setTyRespond($var)
    {
        GPBUtil::checkMessage($var, \App\Proto\S2C_AGGetListRespond::class);
        $this->tyRespond = $var;

        return $this;
    }

    /**
     *调整失败原因//
     *
     * Generated from protobuf field <code>string szresult = 3;</code>
     * @return string
     */
    public function getSzresult()
    {
        return $this->szresult;
    }

    /**
     *调整失败原因//
     *
     * Generated from protobuf field <code>string szresult = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setSzresult($var)
    {
        GPBUtil::checkString($var, True);
        $this->szresult = $var;

        return $this;
    }

}

