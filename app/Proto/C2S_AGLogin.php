<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pb_HTTP.pto

namespace App\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *登录
 *
 * Generated from protobuf message <code>App.Proto.C2S_AGLogin</code>
 */
class C2S_AGLogin extends \Google\Protobuf\Internal\Message
{
    /**
     *用戶名，eg：admin//
     *
     * Generated from protobuf field <code>string szUser = 1;</code>
     */
    private $szUser = '';
    /**
     *密碼，eg：123456//
     *
     * Generated from protobuf field <code>string szPwd = 2;</code>
     */
    private $szPwd = '';
    /**
     *时间戳，eg：2018-1-1，客户端时间必须与北京时间一致，否则登录无效//
     *
     * Generated from protobuf field <code>string szTime = 3;</code>
     */
    private $szTime = '';
    /**
     *机器码，eg：123456
     *
     * Generated from protobuf field <code>string szMachineCode = 4;</code>
     */
    private $szMachineCode = '';
    /**
     *MD5（szUser+szPwd+szTime+szMachineCode+盐）//
     *
     * Generated from protobuf field <code>string szMd5 = 5;</code>
     */
    private $szMd5 = '';
    /**
     *验证码//
     *
     * Generated from protobuf field <code>string szCode = 6;</code>
     */
    private $szCode = '';

    public function __construct() {
	    \App\Proto\GPBMetadata\PbHTTP::initOnce();
        parent::__construct();
    }

    /**
     *用戶名，eg：admin//
     *
     * Generated from protobuf field <code>string szUser = 1;</code>
     * @return string
     */
    public function getSzUser()
    {
        return $this->szUser;
    }

    /**
     *用戶名，eg：admin//
     *
     * Generated from protobuf field <code>string szUser = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setSzUser($var)
    {
        GPBUtil::checkString($var, True);
        $this->szUser = $var;

        return $this;
    }

    /**
     *密碼，eg：123456//
     *
     * Generated from protobuf field <code>string szPwd = 2;</code>
     * @return string
     */
    public function getSzPwd()
    {
        return $this->szPwd;
    }

    /**
     *密碼，eg：123456//
     *
     * Generated from protobuf field <code>string szPwd = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setSzPwd($var)
    {
        GPBUtil::checkString($var, True);
        $this->szPwd = $var;

        return $this;
    }

    /**
     *时间戳，eg：2018-1-1，客户端时间必须与北京时间一致，否则登录无效//
     *
     * Generated from protobuf field <code>string szTime = 3;</code>
     * @return string
     */
    public function getSzTime()
    {
        return $this->szTime;
    }

    /**
     *时间戳，eg：2018-1-1，客户端时间必须与北京时间一致，否则登录无效//
     *
     * Generated from protobuf field <code>string szTime = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setSzTime($var)
    {
        GPBUtil::checkString($var, True);
        $this->szTime = $var;

        return $this;
    }

    /**
     *机器码，eg：123456
     *
     * Generated from protobuf field <code>string szMachineCode = 4;</code>
     * @return string
     */
    public function getSzMachineCode()
    {
        return $this->szMachineCode;
    }

    /**
     *机器码，eg：123456
     *
     * Generated from protobuf field <code>string szMachineCode = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setSzMachineCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->szMachineCode = $var;

        return $this;
    }

    /**
     *MD5（szUser+szPwd+szTime+szMachineCode+盐）//
     *
     * Generated from protobuf field <code>string szMd5 = 5;</code>
     * @return string
     */
    public function getSzMd5()
    {
        return $this->szMd5;
    }

    /**
     *MD5（szUser+szPwd+szTime+szMachineCode+盐）//
     *
     * Generated from protobuf field <code>string szMd5 = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setSzMd5($var)
    {
        GPBUtil::checkString($var, True);
        $this->szMd5 = $var;

        return $this;
    }

    /**
     *验证码//
     *
     * Generated from protobuf field <code>string szCode = 6;</code>
     * @return string
     */
    public function getSzCode()
    {
        return $this->szCode;
    }

    /**
     *验证码//
     *
     * Generated from protobuf field <code>string szCode = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setSzCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->szCode = $var;

        return $this;
    }

}
