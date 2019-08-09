<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: pb_HTTP.pto

namespace App\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>App.Proto.S2C_AGLoginResult</code>
 */
class S2C_AGLoginResult extends \Google\Protobuf\Internal\Message
{
	/**
	 *成功返回0，失败返回其他错误码//
	 *
	 * Generated from protobuf field <code>uint32 nCode = 1;</code>
	 */
	private $nCode = 0;
	/**
	 *错误消息//
	 *
	 * Generated from protobuf field <code>string szErr = 2;</code>
	 */
	private $szErr = '';
	/**
	 *用戶ID，eg：1//
	 *
	 * Generated from protobuf field <code>string szUser = 3;</code>
	 */
	private $szUser = '';
	/**
	 *代理等级
	 *
	 * Generated from protobuf field <code>uint32 nLv = 4;</code>
	 */
	private $nLv = 0;
	/**
	 *返点比例
	 *
	 * Generated from protobuf field <code>uint32 nPercent = 5;</code>
	 */
	private $nPercent = 0;
	/**
	 *绑定的游戏ID
	 *
	 * Generated from protobuf field <code>string szBindID = 6;</code>
	 */
	private $szBindID = '';
	/**
	 *当前可结算余额
	 *
	 * Generated from protobuf field <code>float fCurMoney = 7;</code>
	 */
	private $fCurMoney = 0.0;
	/**
	 *当前可转账余额
	 *
	 * Generated from protobuf field <code>uint32 bankMoney = 8;</code>
	 */
	private $bankMoney = 0;
	/**
	 *此次链接的token，5分钟内不活跃token自动失效
	 *
	 * Generated from protobuf field <code>string szToken = 9;</code>
	 */
	private $szToken = '';
	/**
	 *用昵称，eg：漂亮大田//
	 *
	 * Generated from protobuf field <code>string szNickName = 10;</code>
	 */
	private $szNickName = '';
	/**
	 *支付宝，eg：1&#64;163.com//
	 *
	 * Generated from protobuf field <code>string szAliPay = 11;</code>
	 */
	private $szAliPay = '';
	/**
	 *姓名，eg：张三//
	 *
	 * Generated from protobuf field <code>string szRealName = 12;</code>
	 */
	private $szRealName = '';

	public function __construct() {
		\App\Proto\GPBMetadata\PbHTTP::initOnce();
		parent::__construct();
	}

	/**
	 *成功返回0，失败返回其他错误码//
	 *
	 * Generated from protobuf field <code>uint32 nCode = 1;</code>
	 * @return int
	 */
	public function getNCode()
	{
		return $this->nCode;
	}

	/**
	 *成功返回0，失败返回其他错误码//
	 *
	 * Generated from protobuf field <code>uint32 nCode = 1;</code>
	 * @param int $var
	 * @return $this
	 */
	public function setNCode($var)
	{
		GPBUtil::checkUint32($var);
		$this->nCode = $var;

		return $this;
	}

	/**
	 *错误消息//
	 *
	 * Generated from protobuf field <code>string szErr = 2;</code>
	 * @return string
	 */
	public function getSzErr()
	{
		return $this->szErr;
	}

	/**
	 *错误消息//
	 *
	 * Generated from protobuf field <code>string szErr = 2;</code>
	 * @param string $var
	 * @return $this
	 */
	public function setSzErr($var)
	{
		GPBUtil::checkString($var, True);
		$this->szErr = $var;

		return $this;
	}

	/**
	 *用戶ID，eg：1//
	 *
	 * Generated from protobuf field <code>string szUser = 3;</code>
	 * @return string
	 */
	public function getSzUser()
	{
		return $this->szUser;
	}

	/**
	 *用戶ID，eg：1//
	 *
	 * Generated from protobuf field <code>string szUser = 3;</code>
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
	 *代理等级
	 *
	 * Generated from protobuf field <code>uint32 nLv = 4;</code>
	 * @return int
	 */
	public function getNLv()
	{
		return $this->nLv;
	}

	/**
	 *代理等级
	 *
	 * Generated from protobuf field <code>uint32 nLv = 4;</code>
	 * @param int $var
	 * @return $this
	 */
	public function setNLv($var)
	{
		GPBUtil::checkUint32($var);
		$this->nLv = $var;

		return $this;
	}

	/**
	 *返点比例
	 *
	 * Generated from protobuf field <code>uint32 nPercent = 5;</code>
	 * @return int
	 */
	public function getNPercent()
	{
		return $this->nPercent;
	}

	/**
	 *返点比例
	 *
	 * Generated from protobuf field <code>uint32 nPercent = 5;</code>
	 * @param int $var
	 * @return $this
	 */
	public function setNPercent($var)
	{
		GPBUtil::checkUint32($var);
		$this->nPercent = $var;

		return $this;
	}

	/**
	 *绑定的游戏ID
	 *
	 * Generated from protobuf field <code>string szBindID = 6;</code>
	 * @return string
	 */
	public function getSzBindID()
	{
		return $this->szBindID;
	}

	/**
	 *绑定的游戏ID
	 *
	 * Generated from protobuf field <code>string szBindID = 6;</code>
	 * @param string $var
	 * @return $this
	 */
	public function setSzBindID($var)
	{
		GPBUtil::checkString($var, True);
		$this->szBindID = $var;

		return $this;
	}

	/**
	 *当前可结算余额
	 *
	 * Generated from protobuf field <code>float fCurMoney = 7;</code>
	 * @return float
	 */
	public function getFCurMoney()
	{
		return $this->fCurMoney;
	}

	/**
	 *当前可结算余额
	 *
	 * Generated from protobuf field <code>float fCurMoney = 7;</code>
	 * @param float $var
	 * @return $this
	 */
	public function setFCurMoney($var)
	{
		GPBUtil::checkFloat($var);
		$this->fCurMoney = $var;

		return $this;
	}

	/**
	 *当前可转账余额
	 *
	 * Generated from protobuf field <code>uint32 bankMoney = 8;</code>
	 * @return int
	 */
	public function getBankMoney()
	{
		return $this->bankMoney;
	}

	/**
	 *当前可转账余额
	 *
	 * Generated from protobuf field <code>uint32 bankMoney = 8;</code>
	 * @param int $var
	 * @return $this
	 */
	public function setBankMoney($var)
	{
		GPBUtil::checkUint32($var);
		$this->bankMoney = $var;

		return $this;
	}

	/**
	 *此次链接的token，5分钟内不活跃token自动失效
	 *
	 * Generated from protobuf field <code>string szToken = 9;</code>
	 * @return string
	 */
	public function getSzToken()
	{
		return $this->szToken;
	}

	/**
	 *此次链接的token，5分钟内不活跃token自动失效
	 *
	 * Generated from protobuf field <code>string szToken = 9;</code>
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
	 *用昵称，eg：漂亮大田//
	 *
	 * Generated from protobuf field <code>string szNickName = 10;</code>
	 * @return string
	 */
	public function getSzNickName()
	{
		return $this->szNickName;
	}

	/**
	 *用昵称，eg：漂亮大田//
	 *
	 * Generated from protobuf field <code>string szNickName = 10;</code>
	 * @param string $var
	 * @return $this
	 */
	public function setSzNickName($var)
	{
		GPBUtil::checkString($var, True);
		$this->szNickName = $var;

		return $this;
	}

	/**
	 *支付宝，eg：1&#64;163.com//
	 *
	 * Generated from protobuf field <code>string szAliPay = 11;</code>
	 * @return string
	 */
	public function getSzAliPay()
	{
		return $this->szAliPay;
	}

	/**
	 *支付宝，eg：1&#64;163.com//
	 *
	 * Generated from protobuf field <code>string szAliPay = 11;</code>
	 * @param string $var
	 * @return $this
	 */
	public function setSzAliPay($var)
	{
		GPBUtil::checkString($var, True);
		$this->szAliPay = $var;

		return $this;
	}

	/**
	 *姓名，eg：张三//
	 *
	 * Generated from protobuf field <code>string szRealName = 12;</code>
	 * @return string
	 */
	public function getSzRealName()
	{
		return $this->szRealName;
	}

	/**
	 *姓名，eg：张三//
	 *
	 * Generated from protobuf field <code>string szRealName = 12;</code>
	 * @param string $var
	 * @return $this
	 */
	public function setSzRealName($var)
	{
		GPBUtil::checkString($var, True);
		$this->szRealName = $var;

		return $this;
	}

}
