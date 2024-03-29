<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pb_HTTP.pto

namespace App\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *通用的请求查询消息结构//
 *
 * Generated from protobuf message <code>App.Proto.C2S_AGGetList</code>
 */
class C2S_AGGetList extends \Google\Protobuf\Internal\Message
{
    /**
     *查询条件//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrWheres = 1;</code>
     */
    private $arrWheres;
    /**
     *查询使用第几列的值排序//
     *
     * Generated from protobuf field <code>uint32 orderIndex = 2;</code>
     */
    private $orderIndex = 0;
    /**
     *查询记录范围//
     *
     * Generated from protobuf field <code>uint32 minIndex = 3;</code>
     */
    private $minIndex = 0;
    /**
     *查询记录范围//
     *
     * Generated from protobuf field <code>uint32 maxIndex = 4;</code>
     */
    private $maxIndex = 0;
    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>string szAdmin = 5;</code>
     */
    private $szAdmin = '';
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

	private $szOrderBy = '';

    public function __construct() {
	    \App\Proto\GPBMetadata\PbHTTP::initOnce();
        parent::__construct();
    }

    /**
     *查询条件//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrWheres = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getArrWheres()
    {
        return $this->arrWheres;
    }

    /**
     *查询条件//
     *
     * Generated from protobuf field <code>repeated .App.Proto.tyAgentKeyPair arrWheres = 1;</code>
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
     *查询使用第几列的值排序//
     *
     * Generated from protobuf field <code>uint32 orderIndex = 2;</code>
     * @return int
     */
    public function getOrderIndex()
    {
        return $this->orderIndex;
    }

    /**
     *查询使用第几列的值排序//
     *
     * Generated from protobuf field <code>uint32 orderIndex = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setOrderIndex($var)
    {
        GPBUtil::checkUint32($var);
        $this->orderIndex = $var;

        return $this;
    }

    /**
     *查询记录范围//
     *
     * Generated from protobuf field <code>uint32 minIndex = 3;</code>
     * @return int
     */
    public function getMinIndex()
    {
        return $this->minIndex;
    }

    /**
     *查询记录范围//
     *
     * Generated from protobuf field <code>uint32 minIndex = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setMinIndex($var)
    {
        GPBUtil::checkUint32($var);
        $this->minIndex = $var;

        return $this;
    }

    /**
     *查询记录范围//
     *
     * Generated from protobuf field <code>uint32 maxIndex = 4;</code>
     * @return int
     */
    public function getMaxIndex()
    {
        return $this->maxIndex;
    }

    /**
     *查询记录范围//
     *
     * Generated from protobuf field <code>uint32 maxIndex = 4;</code>
     * @param int $var
     * @return $this
     */
    public function setMaxIndex($var)
    {
        GPBUtil::checkUint32($var);
        $this->maxIndex = $var;

        return $this;
    }

    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>string szAdmin = 5;</code>
     * @return string
     */
    public function getSzAdmin()
    {
        return $this->szAdmin;
    }

    /**
     *操作人,无需赋值，服务器自动填写//
     *
     * Generated from protobuf field <code>string szAdmin = 5;</code>
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

	/**
	 *查询排序关键key， eg: RecordTime desc, RecordTime asc//
	 *
	 * Generated from protobuf field <code>string szOrderBy = 8;</code>
	 * @return string
	 */
	public function getSzOrderBy()
	{
		return $this->szOrderBy;
	}

	/**
	 *查询排序关键key， eg: RecordTime desc, RecordTime asc//
	 *
	 * Generated from protobuf field <code>string szOrderBy = 8;</code>
	 * @param string $var
	 * @return $this
	 */
	public function setSzOrderBy($var)
	{
		GPBUtil::checkString($var, True);
		$this->szOrderBy = $var;
		return $this;
	}

}

