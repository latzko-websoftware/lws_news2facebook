<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Latzko Websoftware GmbH / mediabakery 2011
 * @author     Latzko Websoftware GmbH
 * @package    Lwsnews2facebook
 * @license    LGPL
 * @filesource
 */


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_archive']['lws_news2facebook_legend'] = 'Latzko Websoftware news2facebook Einstellungen';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_archive']['socialNews'] = array('news2facebook aktivieren','MB Socialize aktivieren.');
$GLOBALS['TL_LANG']['tl_news_archive']['socialNewsService'] = array('Service','news2facebook Service wählen.');
$GLOBALS['TL_LANG']['tl_news_archive']['facebookTeaserLength'] = array('maximale Textlänge','maximale Textlänger, falls Teaser oder Text der Nachricht für Facebook genutzt wird. Bei "0" wird der Text nicht gekürzt.');
$GLOBALS['TL_LANG']['tl_news_archive']['defaultPic'] = array('Standard Bild','Wenn kein Bild in der Nachricht vorhanden ist wird dieses Bild auf Facebook gepostet.');
$GLOBALS['TL_LANG']['tl_news_archive']['stdurl'] = array('Standard URL','Wenn keine URL angegeben wird, wohin soll weitergeleitet werden?');

?>
