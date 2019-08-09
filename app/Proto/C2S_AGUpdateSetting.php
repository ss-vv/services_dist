<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pb_HTTP.pto

namespace App\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *修改配置的通用结构体//
 *
 * Generated from protobuf message <code>App.Proto.C2S_AGUpdateSetting</code>
 */
class C2S_AGUpdateSetting extends \Google\Protobuf\Internal\Message
{
    /**
     *需要修改的字段列表，eg:{{BaseScore,100}，{OrderId,1}}//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrUpdates = 1;</code>
     */
    private $arrUpdates;
    /**
     *需要修改的字段条件列表，eg:{{AgentName,二人麻将}，{OrderId,2}}//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrWheres = 2;</code>
     */
    private $arrWheres;
    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>string szAdmin = 3;</code>
     */
    private $szAdmin = '';
    /**
     *操作原因//
     *
     * Generated from protobuf field <code>string szMsg = 4;</code>
     */
    private $szMsg = '';
    /**
     *验证码，某些操作需要验证码，比如修改支付宝//
     *
     * Generated from protobuf field <code>string szCode = 5;</code>
     */
    private $szCode = '';
    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>uint32 szAdminLv = 6;</code>
     */
    private $szAdminLv = 0;
    /**
     *此次链接的token，5分钟内不活跃token自动失效
     *
     * Generated from protobuf field <code>string szToken = 7;</code>
     */
    private $szToken = '';

    public function __construct() {
	    \App\Proto\GPBMetadata\PbHTTP::initOnce();
        parent::__construct();
    }

    /**
     *需要修改的字段列表，eg:{{BaseScore,100}，{OrderId,1}}//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrUpdates = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getArrUpdates()
    {
        return $this->arrUpdates;
    }

    /**
     *需要修改的字段列表，eg:{{BaseScore,100}，{OrderId,1}}//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrUpdates = 1;</code>
     * @param \App\Proto\tyAgentKeyPair[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setArrUpdates($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \App\Proto\tyAgentKeyPair::class);
        $this->arrUpdates = $arr;

        return $this;
    }

    /**
     *需要修改的字段条件列表，eg:{{AgentName,二人麻将}，{OrderId,2}}//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrWheres = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getArrWheres()
    {
        return $this->arrWheres;
    }

    /**
     *需要修改的字段条件列表，eg:{{AgentName,二人麻将}，{OrderId,2}}//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrWheres = 2;</code>
     * @param \App\Proto\tyAgentKeyPair[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setArrWheres($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \App\Proto\tyAgentKeyPair::class);
        $this->arrWheres = $arr;

        return $this;
    }

    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>string szAdmin = 3;</code>
     * @return string
     */
    public function getSzAdmin()
    {
        return $this->szAdmin;
    }

    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>string szAdmin = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setSzAdmin($var)
    {
        GPBUtil::checkString($var, True);
        $this->szAdmin = $var;

        return $this;
    }

    /**
     *操作原因//
     *
     * Generated from protobuf field <code>string szMsg = 4;</code>
     * @return string
     */
    public function getSzMsg()
    {
        return $this->szMsg;
    }

    /**
     *操作原因//
     *
     * Generated from protobuf field <code>string szMsg = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setSzMsg($var)
    {
        GPBUtil::checkString($var, True);
        $this->szMsg = $var;

        return $this;
    }

    /**
     *验证码，某些操作需要验证码，比如修改支付宝//
     *
     * Generated from protobuf field <code>string szCode = 5;</code>
     * @return string
     */
    public function getSzCode()
    {
        return $this->szCode;
    }

    /**
     *验证码，某些操作需要验证码，比如修改支付宝//
     *
     * Generated from protobuf field <code>string szCode = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setSzCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->szCode = $var;

        return $this;
    }

    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>uint32 szAdminLv = 6;</code>
     * @return int
     */
    public function getSzAdminLv()
    {
        return $this->szAdminLv;
    }

    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>uint32 szAdminLv = 6;</code>
     * @param int $var
     * @return $this
     */
    public function setSzAdminLv($var)
    {
        GPBUtil::checkUint32($var);
        $this->szAdminLv = $var;

        return $this;
    }

    /**
     *此次链接的token，5分钟内不活跃token自动失效
     *
     * Generated from protobuf field <code>string szToken = 7;</code>
     * @return string
     */
    public function getSzToken()
    {
        return $this->szToken;
    }

    /**
     *此次链接的token，5分钟内不活跃token自动失效
     *
     * Generated from protobuf field <code>string szToken = 7;</code>
     * @param string $var
     * @return $this
     */
    public function setSzToken($var)
    {
        GPBUtil::checkString($var, True);
        $this->szToken = $var;

        return $this;
    }

}
