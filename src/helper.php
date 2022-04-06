<?php
if (!function_exists('json')) {
	/**
	 * 返回json数据
	 * @param string|array $data 数据
	 * @return string
	 */
	function json($data = '') {
		return \willphp\response\Response::json($data);
	}
}
