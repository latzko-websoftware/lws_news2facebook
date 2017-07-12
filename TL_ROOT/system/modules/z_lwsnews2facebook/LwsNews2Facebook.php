<?php
namespace LwsNews2Facebook;
if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Class LwsNews2Facebook
 *
 * PHP version 5
 * @copyright  Latzko Websoftware GmbH / mediabakery 2011
 * @author     Latzko Websoftware GmbH
 * @package    Lwsnews2facebook
 * @license    LGPL
 * @filesource
 */

class LwsNews2Facebook extends \Backend{

 	public function __construct() {
 		$this->Import('Database');
 		$this->Import('Environment');

 	}

 	public function checkQueue() {
 		$objQueue = $this->Database->prepare("SELECT pid FROM tl_lws_news2facebook_news WHERE facebookid=''")
			->execute();

		while($objQueue->next()) {

			$objNews = 	$this->Database->prepare("SELECT * FROM tl_news WHERE id=?")
				->limit(1)
				->execute($objQueue->pid);
			if ($objNews->published == '1' && $objNews->start <= time()) $this->transmitNews($objNews);
		}

 	}

 	// NEWS START
	public function socializeNews(\DataContainer $dc) {

		if (!$dc->activeRecord) return;

		if ($dc->activeRecord->addSocial == '1' && $dc->activeRecord->published == '1') {
			$parentArchive = $this->Database->prepare("SELECT socialNews FROM tl_news_archive WHERE id=?")
			->limit(1)
			->execute($dc->activeRecord->pid);
			if ($parentArchive->socialNews == 1){

				$socialNewsItem = $this->Database->prepare("SELECT id FROM tl_lws_news2facebook_news WHERE pid=?")
				->execute($dc->activeRecord->id);

				if ($socialNewsItem->numRows < 1){

					$set = array
						(   tstamp	  => time(),
							pid   	  => $dc->activeRecord->id
						);

					$this->Database->prepare("INSERT INTO tl_lws_news2facebook_news %s")
						->set ($set)
						->execute();
				}
			}
		}
		$this->checkQueue();
	}

	public function deleteNews(\DataContainer $dc) {
		$this->Database->prepare("DELETE FROM tl_lws_news2facebook_news WHERE facebookid=?")
        	->execute($dc->id);
        $this->log('Delete socialize Item '.$dc->id, 'LwsNews2Facebook, deleteNews', TL_GENERAL);
	}

	private function transmitNews(\Contao\Database\Mysqli\Result $objNews) {

		$objNewsArchive = $this->Database->prepare("SELECT socialNews,socialNewsService,facebookTeaserLength,defaultPic,stdurl FROM tl_news_archive WHERE id=?")
				->limit(1)
				->execute($objNews->pid);

		if ($objNewsArchive->socialNews == '1') {

			$objSocialize = $this->Database->prepare("SELECT appid, secret, usersession, targetid FROM tl_lws_news2facebook WHERE id=?")
				->limit(1)
				->execute($objNewsArchive->socialNewsService);

			$message = $objNews->facebookTeaser;
			if ($message == '') $message = $this->shortenText($objNews->teaser, $objNewsArchive->facebookTeaserLength);
			if ($message == '') $message = $this->shortenText($objNews->text, $objNewsArchive->facebookTeaserLength);
			$message = $this->convertStringForFacebook($message);

			switch ($objNews->source) {
				// Link to external page
				case 'external':
					$this->import('String');
					if (substr($objNews->url, 0, 7) == 'mailto:') {
						$newsurl = $this->String->encodeEmail($objNews->url);
					} else {
						$newsurl = ampersand($objNews->url);
					}
					break;

				// Link to an internal page
				case 'internal':
					$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										 	  ->limit(1)
											  ->execute($objNews->jumpTo);
				if ($objPage->numRows) {
					$newsurl = $this->Environment->base.ampersand($this->generateFrontendUrl($objPage->row()));
				}
				break;

				// Link to an article
				case 'article':
					$objPage = $this->Database->prepare("SELECT a.id AS aId, a.alias AS aAlias, a.title, p.id, p.alias FROM tl_article a, tl_page p WHERE a.pid=p.id AND a.id=?")
											  ->limit(1)
											  ->execute($objNews->articleId);

					if ($objPage->numRows) {
						$newsurl = $this->Environment->base.ampersand($this->generateFrontendUrl($objPage->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objPage->aAlias)) ? $objPage->aAlias : $objPage->aId)));
					}
					break;
			}

			// Link to the default page
			if ($newsurl == '') $newsurl = $objNewsArchive->stdurl;

			$picture = $objNews->singleSRC;
			if ($picture == '') $picture = $objNewsArchive->defaultPic;
			$pictureurl =  $this->Environment->base.$picture;

			$name = $objNews->headline;
			$description = $objNews->subheadline;


			// FACEBOOK SDK
			try {

				$fb = new \Facebook\Facebook([
					'app_id' => $objSocialize->appid,
					'app_secret' => $objSocialize->secret,
					'default_graph_version' => 'v2.9',
				]);


				try {

					$postArray = array(
						'access_token' => $objSocialize->usersession,
	                    'message' => $message
	                    );
	                if ($newsurl != '') {
	                	$postArray['link'] 			= $newsurl;
	                    $postArray['picture'] 		= $pictureurl;
	                    $postArray['name']    		= $name;
	                    $postArray['description'] 	= $description;
					}


					$publishStream = $fb->post('/' . $objSocialize->targetid . '/feed', $postArray, $accesstoken);
					$publishStreamArray = $publishStream->getDecodedBody();

	                $this->Database->prepare("UPDATE tl_lws_news2facebook_news %s WHERE pid=?")
								->set(array(
									'tstamp'		=> time(),
									'facebookid'	=> $publishStreamArray['id']
								  ))
								->execute($objNews->id);

					$this->log('News transmitted to Facebook: '.$objNews->id,'LwsNews2Facebook, transmitNews', TL_GENERAL);
	            } catch (FacebookApiException $e) {
	                $this->log($e.'@News ID '.$objNews->id,'LwsNews2Facebook, transmitNews', TL_ERROR);
	            }





			} catch(exception $e) {
				$this->log($e,'LwsNews2Facebook, transmitNews', TL_ERROR);
			}

		}
	}

	// NEWS END



 	private function shortenText($text, $limit)
	{
		if ($limit == 0) return $text;

		$array = explode(" ", $text, $limit+1);

		if (count($array) > $limit)
		{
			unset($array[$limit]);
			return implode(" ", $array)." ...";
		}
		return $text;
	}


	private function convertStringForFacebook($val) {
		return str_replace('[nbsp]', ' ', strip_tags($val));
	}

	public function pageidfromurl($arrFragments) {
		$this->runcron();
		return array_unique($arrFragments);
	}

	private function runcron() {

		$minTime = 300;

		try{
			$fileCron = new File('system/modules/lwsnews2facebook/data/cronjob.txt');
			// if ($fileCron->hasError()) throw new Exception($fileCron->error);
			$lastCron = $fileCron->getContent();

			if (time() - $lastCron >= $minTime) {
				$fileCron->write(time());
				$this->checkQueue();
			}
		}catch(exception $e) {
			 $this->log($e,'LwsNews2Facebook, runcron', TL_ERROR);
		}
	}

	/**
	 * Return the "socialize/unsocialize element" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function iconSocializedNews($row, $href, $label, $title, $icon, $attributes)
	{

		$this->Import('Database');

		$objSocial = $this->Database->prepare("SELECT facebookid FROM tl_lws_news2facebook_news WHERE pid=?")
				->limit(1)
				->execute($row['id']);


		if ($objSocial->facebookid != '')
		{
			$icon = 'system/modules/lwsnews2facebook/html/socialize1.gif';
		}

		return $this->generateImage($icon, $label);
	}



}
?>
