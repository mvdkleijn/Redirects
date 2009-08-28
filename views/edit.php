<h1>Redirect Controller</h1>

<?php

	global $__CMS_CONN__;
	$checkforurl = "SELECT * FROM ".TABLE_PREFIX."page_redirects WHERE id='$id'";
	$checkforurl = $__CMS_CONN__->prepare($checkforurl);
	$checkforurl->execute();
	while ($requrl = $checkforurl->fetchObject()) {
		$oldurl	= $requrl->oldurl;
		$newurl	= $requrl->newurl;
		$type	= $requrl->type;
		$active	= $requrl->active;
		$hits	= $requrl->hits;
	}
?>



	<form action="<?php echo get_url('plugin/redirects/editredirect'); ?>" method="POST" name="edit_redirect">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><small>Old URL</small></td>
				<td><input length="64" name="oldurl" type="text" value="<?php echo $oldurl; ?>" /></td>
			</tr>
			<tr>
				<td><small>Target URL</small></td>
				<td><input length="64" name="newurl" type="text" value="<?php echo $newurl; ?>" /></td>
			</tr>
			<tr>
				<td><small>Type</small></td>
				<td><select name="type">
						<option value="1"<?php if ($type == '1') { echo ' selected="selected"'; } ?>>Permanent Redirect</option>
						<option value="2"<?php if ($type == '2') { echo ' selected="selected"'; } ?>>Temporary Redirect</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><small>Activate?</small></td>
				<td><input name="active" type="checkbox" value="1"<?php if ($active == '1') { echo ' checked'; } ?> /></td>
			</tr>
			<tr>
				<td><p>&nbsp;</p></td>
				<td><input class="button" name="edit_redirect" type="submit" value="Edit Redirect"> or <a href="<?php echo get_url('plugin/redirects/'); ?>">Cancel Changes</a></td>
			</tr>
		</table>
	</form>
