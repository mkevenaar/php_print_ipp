<?php
/* @(#) $Header: /sources/phpprintipp/phpprintipp/php_classes/http_class.php,v 1.7 2010/08/22 15:45:17 harding Exp $ */
/* vim: set expandtab tabstop=2 shiftwidth=2 foldmethod=marker: */
/* ====================================================================
 * GNU Lesser General Public License
 * Version 2.1, February 1999
 *
 * Class http_class - Basic http client with "Basic" and Digest/MD5
 * authorization mechanism.
 * handle ipv4/v6 addresses, Unix sockets, http and https
 * have file streaming capability, to cope with php "memory_limit"
 *
 *   Copyright (C) 2006,2007,2008  Thomas HARDING
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * $Id: http_class.php,v 1.7 2010/08/22 15:45:17 harding Exp $
 */
/**
 *  This class is intended to implement a subset of Hyper Text Transfer Protocol
 *  (HTTP/1.1) on client side  (currently: POST operation), with file streaming
 *  capability.
 *
 *  It can perform Basic and Digest authentication.
 *
 *  References needed to debug / add functionnalities:
 *  - RFC 2616
 *  - RFC 2617
 *
 *
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getErrorFormatted()
 * - getErrno()
 * - __construct()
 * - GetRequestArguments()
 * - Open()
 * - SendRequest()
 * - ReadReplyHeaders()
 * - ReadReplyBody()
 * - Close()
 * - _StreamRequest()
 * - _ReadReply()
 * - _ReadStream()
 * - _BuildDigest()
 * Classes list:
 * - httpException extends Exception
 * - http_class
 */
/***********************
 *
 * httpException class
 *
 ************************/
namespace mkevenaar\PhpPrintIpp\Lib;

use mkevenaar\PhpPrintIpp\Lib\ippException;

class httpException extends ippException
{
	protected $errno;

	public function __construct($msg, $errno = null)
	{
		parent::__construct($msg);
		$this->errno = $errno;
	}

	public function getErrorFormatted()
	{
		return sprintf("[http_class]: %s -- " . _(" file %s, line %s"),
			$this->getMessage(), $this->getFile(), $this->getLine());
	}

	public function getErrno()
	{
		return $this->errno;
	}
}

function error2string($value)
{
	$level_names = array(
		E_ERROR => 'E_ERROR',
		E_WARNING => 'E_WARNING',
		E_PARSE => 'E_PARSE',
		E_NOTICE => 'E_NOTICE',
		E_CORE_ERROR => 'E_CORE_ERROR',
		E_CORE_WARNING => 'E_CORE_WARNING',
		E_COMPILE_ERROR => 'E_COMPILE_ERROR',
		E_COMPILE_WARNING => 'E_COMPILE_WARNING',
		E_USER_ERROR => 'E_USER_ERROR',
		E_USER_WARNING => 'E_USER_WARNING',
		E_USER_NOTICE => 'E_USER_NOTICE'
	);
	if (defined('E_STRICT')) {
		$level_names[E_STRICT] = 'E_STRICT';
	}
	$levels = array();
	if (($value & E_ALL) == E_ALL)
	{
		$levels[] = 'E_ALL';
		$value &= ~E_ALL;
	}
	foreach ($level_names as $level => $name)
	{
		if (($value & $level) == $level)
		{
			$levels[] = $name;
		}
	}
	return implode(' | ', $levels);
}