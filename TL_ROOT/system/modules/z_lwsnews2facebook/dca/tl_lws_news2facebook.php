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


$GLOBALS['TL_DCA']['tl_lws_news2facebook'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback'			  => array(array('tl_lws_news2facebook','checkState')),
		'onsubmit_callback'			  => array(array('tl_lws_news2facebook','getUserSession'))
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'                    => 1
		),
		'label' => array
		(
			'fields'                  => array('name', 'appid', 'secret', 'targetid', 'authorid')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(

			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(''),
	        'default'                     => 'name,appid,secret,targetid,authorid,permissions'
	),

	// Subpalettes
	'subpalettes' => array
	(

	),

	// Fields
	'fields' => array
	(
		'name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['name'],
            'exclude'                 => false,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true)
        ),

        'appid' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['appid'],
            'exclude'                 => false,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true)
        ),

        'secret' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['secret'],
            'exclude'                 => false,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true)
        ),

		'targetid' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['targetid'],
            'exclude'                 => false,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true)
        ),
        'authorid' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lws_news2facebook']['authorid'],
            'exclude'                 => false,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>false)
        )

	)
);

/**
 * Class tl_lws_news2facebook
 *
 * PHP version 5
 * @copyright  Latzko Websoftware GmbH / mediabakery 2011
 * @author     Latzko Websoftware GmbH
 * @package    Lwsnews2facebook
 * @license    LGPL
 * @filesource
 */

class tl_lws_news2facebook extends System{

	public function checkState() {

		$this->Import('Database');

		if (strlen($this->Input->get('state'))) {

			$objFacebook = $this->Database->prepare("SELECT id,name,appid,secret,targetid,authorid FROM tl_lws_news2facebook WHERE id=?")
											->limit(1)
											->execute($this->Input->get('id'));

			try {
				$fb = new \Facebook\Facebook([
					'app_id' => $objFacebook->appid,
					'app_secret' => $objFacebook->secret,
					'default_graph_version' => 'v2.9',
				]);
				$helper = $fb->getRedirectLoginHelper();
				$accesstoken = $helper->getAccessToken();

				$response = $fb->get('/me', $accesstoken);
				$authorname = $response->getDecodedBody()['name'];

				$accounts = $fb->get('/me/accounts', $accesstoken)->getDecodedBody();

				foreach($accounts['data'] as $account){
					if($account['id'] == $objFacebook->authorid){
						$accesstoken = $account['access_token'];
						$authorname = $account['name'];
						break;
					}
				}

				$this->Database->prepare("UPDATE tl_lws_news2facebook %s WHERE id=?")
								->set(array(
									'tstamp'		=> time(),
									'usersession'	=> $accesstoken
								  ))
								->execute($this->Input->get('id'));
				$this->log($authorname.' AccessToken set for Socialize Item '.$objFacebook->id.' ('.$objFacebook->name.')' ,'tl_lws_news2facebook, checkState', TL_GENERAL);

			} catch(exception $e) {
				$this->log($e,'tl_lws_news2facebook, checkState', TL_ERROR);
			}



			$this->redirect($this->Environment->url.$this->Environment->scriptName.'?do=lws_news2facebook',301);
		}
	}

	public function getUserSession(DataContainer $dc) {

		try {

			$fb = new \Facebook\Facebook([
				'app_id' => $dc->activeRecord->appid,
				'app_secret' => $dc->activeRecord->secret,
				'default_graph_version' => 'v2.9',
			]);

			$permissions = ['manage_pages','publish_pages','publish_actions'];

			$redirectUrl = $this->Environment->url . $this->Environment->requestUri;

			$helper = $fb->getRedirectLoginHelper();
			$loginUrl = $helper->getLoginUrl($redirectUrl, $permissions);
			$this->redirect($loginUrl,301);


		} catch(exception $e) {
			$this->log($e,'tl_lws_news2facebook, getUserSession', TL_ERROR);
		}
	}



}

?>
