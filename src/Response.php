<?php
/*--------------------------------------------------------------------------
 | Software: [WillPHP framework]
 | Site: www.113344.com
 |--------------------------------------------------------------------------
 | Author: 无念 <24203741@qq.com>
 | WeChat: www113344
 | Copyright (c) 2020-2022, www.113344.com. All Rights Reserved.
 |-------------------------------------------------------------------------*/
namespace willphp\response;
use willphp\config\Config;
use willphp\debug\Debug;
class Response {
	protected static $link;
	public static function single()	{
		if (!self::$link) {
			self::$link = new ResponseBuilder();
		}
		return self::$link;
	}
	public function __call($method, $params) {
		return call_user_func_array([self::single(), $method], $params);
	}
	public static function __callStatic($name, $arguments) {
		return call_user_func_array([self::single(), $name], $arguments);
	}
}
class ResponseBuilder {
	protected $content; //响应内容
	/**
	 * 设置响应内容
	 * @param $content
	 * @return $this
	 */
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}
	/**
	 * 获取响应内容
	 * @return mixed
	 */
	public function getContent() {
		if (is_array($this->content)) {
			header('Content-type: application/json;charset=utf-8');
			return json_encode($this->content, JSON_UNESCAPED_UNICODE);
		}
		return is_numeric($this->content) ? strval($this->content) : $this->content;
	}
	/**
	 * 直接响应内容
	 * @param $content
	 * @return $this
	 */
	public function make($content) {
		$this->setContent($content);
		return $this;
	}
	public function __toString() {
		$content = $this->getContent();
		if (preg_match('/^http(s?):\/\//', $content)) {
			header('location:'.$content);
		}
		return $content ? $content : '';
	}
	/**
	 * 输出响应内容
	 * @param $res 响应内容
	 */
	public function output($res = null) {
		if (is_null($res)) {
			$res = $this->content;
		}
		if (is_object($res) && method_exists($res, '__toString')) {
			$res = $res->__toString();
		}
		if (is_scalar($res)) {
			if (preg_match('/^http(s?):\/\//', $res)) {
				header('location:'.$res);
			} else {
				$trace_show = Config::get('debug.trace_show', true);
				if ($trace_show) {
					$res = Debug::appendTrace($res);
				}
				echo $res;
			}
		} elseif (is_null($res)) {
			return;
		} else {
			header('Content-type: application/json;charset=utf-8');
			echo json_encode($res, JSON_UNESCAPED_UNICODE);
			exit();
		}
	}		
	public function json($data = '') {
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
}