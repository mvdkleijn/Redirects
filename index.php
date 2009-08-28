<?php

Plugin::setInfos(array(
	'id'					=>	'redirects',
	'title'					=>	'Redirects',
	'description'			=>	'Set up redirection for old pages',
	'version'				=>	'0.1.0'
));

Plugin::addController('redirects', 'Redirects', 'administrator,developer');

Behavior::add('page_not_found', '');

Observer::observe('page_requested', 'check_requested_url');
Observer::observe('page_not_found', 'behavior_page_not_found');

function check_requested_url($page) {

	$url		=	$page;								//	==	PAGE=programme/events
	$url		=	str_replace('PAGE=', '', $url);		//	==	programme/events

	global $__CMS_CONN__;
	$checkforurl = "SELECT * FROM ".TABLE_PREFIX."page_redirects WHERE oldurl='$url'";
	$checkforurl = $__CMS_CONN__->prepare($checkforurl);
	$checkforurl->execute();
	$count = $checkforurl->rowCount();

	if($count >= 1) {
		$checkforurl = "SELECT * FROM ".TABLE_PREFIX."page_redirects WHERE oldurl='$url'";
		$checkforurl = $__CMS_CONN__->prepare($checkforurl);
		$checkforurl->execute();
		while ($requrl = $checkforurl->fetchObject()) {
			$id		= $requrl->id;
			$newurl	= $requrl->newurl;
			$type	= $requrl->type;
			$active	= $requrl->active;
			$hits	= $requrl->hits;
			
			if($active == '1') {
				$newhits = $hits+1;
				$updatehits = "UPDATE ".TABLE_PREFIX."page_redirects SET hits='$newhits' WHERE id='$id'";
				$updatehits = $__CMS_CONN__->prepare($updatehits);
				$updatehits->execute();
				if ($type == '1') {
					header('HTTP/1.1 301 Moved Permanently');
				}
				elseif ($type == '2') {
					header('HTTP/1.1 307 Moved Temporarily');
				}
				header('Location: '.URL_PUBLIC.''.$newurl.'');	
			}
		}			
	}
}

function behavior_page_not_found() {

	global $__CMS_CONN__;

	$url = $_SERVER['REQUEST_URI'];
	$url = substr($url, 1);

	$checkforurl = "SELECT * FROM ".TABLE_PREFIX."page_redirects WHERE oldurl='$url'";
	$checkforurl = $__CMS_CONN__->prepare($checkforurl);
	$checkforurl->execute();
	$count = $checkforurl->rowCount();
	
	if ($count >= 1) {
		exit();
	}

	else {
		$sql = 'SELECT * FROM '.TABLE_PREFIX."page WHERE behavior_id='page_not_found'";
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();

		if ($page = $stmt->fetchObject()) {
			$page = find_page_by_uri($page->slug);
			if (is_object($page)) {
				header("HTTP/1.0 404 Not Found");
				header("Status: 404 Not Found");
				$page->_executeLayout();
				exit();
			}
		}
	}
}