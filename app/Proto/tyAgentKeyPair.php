<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pb_HTTP.pto

namespace App\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *keypair结构体
 *
 * Generated from protobuf message <code>App.Proto.tyAgentKeyPair</code>
 */
class tyAgentKeyPair extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string szKey = 1;</code>
     */
    private $szKey = '';
    /**
     * Generated from protobuf field <code>string szValue = 2;</code>
     */
    private $szValue = '';
    /**
     *0:用等于判断，1大于，2大于等于，3小于，4小于等于,5模糊查询//
     *
     * Generated from protobuf field <code>uint32 nType = 3;</code>
     */
    private $nType = 0;

    public function __construct() {
	    \App\Proto\GPBMetadata\PbHTTP::initOnce();
	    parent::__construct();
    }

    /**
     * Generated from protobuf field <code>string szKey = 1;</code>
     * @return string
     */
    public function getSzKey()
    {
        return $this->szKey;
    }

    /**
     * Generated from protobuf field <code>string szKey = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setSzKey($var)
    {
        GPBUtil::checkString($var, True);
        $this->szKey = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string szValue = 2;</code>
     * @return string
     */
    public function getSzValue()
    {
        return $this->szValue;
    }

    /**
     * Generated from protobuf field <code>string szValue = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setSzValue($var)
    {
        GPBUtil::checkString($var, True);
        $this->szValue = $var;

        return $this;
    }

    /**
     *0:用等于判断，1大于，2大于等于，3小于，4小于等于,5模糊查询//
     *
     * Generated from protobuf field <code>uint32 nType = 3;</code>
     * @return int
     */
    public function getNType()
    {
        return $this->nType;
    }

    /**
     *0:用等于判断，1大于，2大于等于，3小于，4小于等于,5模糊查询//
     *
     * Generated from protobuf field <code>uint32 nType = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setNType($var)
    {
        GPBUtil::checkUint32($var);
        $this->nType = $var;

        return $this;
    }

}
