<?php

/* @(#) $Header: /sources/phpprintipp/phpprintipp/php_classes/BasicIPP.php,v 1.7 2012/03/01 17:21:04 harding Exp $
 *
 * Class BasicIPP - Send Basic IPP requests, Get and parses IPP Responses.
 *
 *   Copyright (C) 2005-2009  Thomas HARDING
 *   Parts Copyright (C) 2005-2006 Manuel Lemos
 *
 *   This library is free software; you can redistribute it and/or
 *   modify it under the terms of the GNU Library General Public
 *   License as published by the Free Software Foundation; either
 *   version 2 of the License, or (at your option) any later version.
 *
 *   This library is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *   Library General Public License for more details.
 *
 *   You should have received a copy of the GNU Library General Public
 *   License along with this library; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *   mailto:thomas.harding@laposte.net
 *   Thomas Harding, 56 rue de la bourie rouge, 45 000 ORLEANS -- FRANCE
 *
 */
/*

   This class is intended to implement Internet Printing Protocol on client side.

   References needed to debug / add functionnalities:
   - RFC 2910
   - RFC 2911
   - RFC 3380
   - RFC 3382
 */

//require_once ("http_class.php");
namespace Stiwl\PhpPrintIpp\Lib;

class ippException extends \Exception
{
	protected $errno;

	public function __construct($msg, $errno = null)
	{
		parent::__construct($msg);
		$this->errno = $errno;
	}

	public function getErrorFormatted()
	{
		$return = sprintf("[ipp]: %s -- " . _(" file %s, line %s"),
			$this->getMessage(), $this->getFile(), $this->getLine());
		return $return;
	}

	public function getErrno()
	{
		return $this->errno;
	}
}


