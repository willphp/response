<?php
/*--------------------------------------------------------------------------
 | Software: [WillPHP framework]
 | Site: www.113344.com
 |--------------------------------------------------------------------------
 | Author: no-mind <24203741@qq.com>
 | WeChat: www113344
 | Copyright (c) 2020-2022, www.113344.com. All Rights Reserved.
 |-------------------------------------------------------------------------*/
namespace willphp\response\build;
use willphp\middleware\Middleware;
/**
 * 响应处理
 * Class Base
 * @package willphp\response\build
 */
class Base {	
	protected $code; //响应状态码	
	protected $content; //响应内容
	protected $output; //待输出响应内容
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
	public function setOutput($content = '') {
		$this->output = $content;
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
				$this->output = $res;
				Middleware::web('response_output', $this->output);
				echo $this->output;
			}
		} elseif (is_null($res)) {
			return;
		} else {
			header('Content-type: application/json;charset=utf-8');
			echo json_encode($res, JSON_UNESCAPED_UNICODE);
			exit();
		}
	}	
	/**
	 * 获取状态码
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}
	/**
	 * 设置状态码
	 */
	public function setCode($code) {
		$this->code = $code;
	}
	public function __toString() {
		$content = $this->getContent();
		if (preg_match('/^http(s?):\/\//', $content)) {
			header('location:'.$content);
		}		
		return $content ? $content : '';
	}	
	/**
	 * 返回json数据
	 * @param string|array $data 数据
	 * @return string
	 */
	public function json($data = '') {
		header('Content-type: application/json;charset=utf-8');
		$res = json_encode($data, JSON_UNESCAPED_UNICODE);
		return $res;
	}
	/**
	 * 发送HTTP状态码
	 * @param $code
	 * @return $this
	 */
	public function sendHttpStatus($code) {
		$status = [				
				//Informational
				100 => 'Continue',
				101 => 'Switching Protocols',
				//Success
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				//Redirection
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',  // 1.1
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				307 => 'Temporary Redirect',
				//Client Error
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				//Server Error
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported',
				509 => 'Bandwidth Limit Exceeded',
		];
		if (isset($status[$code])) {
			$this->setCode($status[$code]);
			header('HTTP/1.1 '.$code.' '.$status[$code]);
			header('Status:' .$code.' '.$status[$code]);			
			return true;
		}
		return false;
	}	
}
