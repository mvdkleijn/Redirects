<?php

class RedirectsController extends PluginController {

	public function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/redirects/views/sidebar'));
	}

	public function index() {
		$this->display('redirects/views/index');
	}

	public function edit($id) {
		$this->display('redirects/views/edit', array('id'=>$id));
	}

	public function add() {
		$oldurl		=	mysql_escape_string($_POST['oldurl']);
		$newurl		=	mysql_escape_string($_POST['newurl']);
		$type		=	mysql_escape_string($_POST['type']);
		$active		=	mysql_escape_string($_POST['active']);
		
		global $__LUDUS_CONN__;

		$checkredirect = "SELECT * FROM ".TABLE_PREFIX."page_redirects WHERE oldurl='$oldurl'";
		$checkredirect = $__LUDUS_CONN__->prepare($checkredirect);
		$checkredirect->execute();
		$count = $checkredirect->rowCount();

		if($count >= 1) {
			Flash::set('error', __('There is already a redirect for this URL!'));
			redirect(get_url('plugin/redirects/'));		
		}
		else {
			$insertredirect = "
				INSERT INTO ".TABLE_PREFIX."page_redirects
				VALUES (
					'',
					'$oldurl',
					'$newurl',
					'$type',
					'$active',
					'0'
				)";
			$insertredirect = $__LUDUS_CONN__->prepare($insertredirect);
			$insertredirect->execute();
			Flash::set('success', __('This server redirect has been added to the site'));
			redirect(get_url('plugin/redirects/'));		
		}
	}

	public function editredirect() {
		global $__LUDUS_CONN__;
		$id			= mysql_escape_string($_POST['id']);
		$oldurl		= mysql_escape_string($_POST['oldurl']);
		$newurl		= mysql_escape_string($_POST['newurl']);
		$type		= mysql_escape_string($_POST['type']);
		$active		= mysql_escape_string($_POST['active']);
		
		$sql = "
			UPDATE ".TABLE_PREFIX."page_redirects
			SET	
				`oldurl`='$oldurl',
				`newurl`='$newurl',
				`type`='$type',
				`active`='$active'
			WHERE id='$id'";
		$pdo = $__LUDUS_CONN__->prepare($sql);
		$pdo->execute();
		Flash::set('success', __('This redirection has been updated'));
		redirect(get_url('plugin/redirects'));
	}

	function delete($id) {
		global $__LUDUS_CONN__;
		$sql = "DELETE FROM ".TABLE_PREFIX."page_redirects WHERE id='".$id."'";
		$pdo = $__LUDUS_CONN__->prepare($sql);
		$pdo->execute();
		Flash::set('success', __('This redirect has been removed'));
		redirect(get_url('plugin/redirects'));
	}

	function activate($id) {
		global $__LUDUS_CONN__;
		$sql = "
			UPDATE ".TABLE_PREFIX."page_redirects
			SET `active`='1'
			WHERE id='".$id."'";
		$pdo = $__LUDUS_CONN__->prepare($sql);
		$pdo->execute();
		Flash::set('success', __('This redirect has been activated'));
		redirect(get_url('plugin/redirects'));
	}

	function deactivate($id) {
		global $__LUDUS_CONN__;
		$sql = "
			UPDATE ".TABLE_PREFIX."page_redirects
			SET `active`='0'
			WHERE id='".$id."'";
		$pdo = $__LUDUS_CONN__->prepare($sql);
		$pdo->execute();
		Flash::set('success', __('This redirect has been deactivated'));
		redirect(get_url('plugin/redirects'));
	}


}