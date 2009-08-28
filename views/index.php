<h1>Redirect Controller</h1>

<table id="redirect-list" class="index" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th>Depricated URL</th>
			<th></th>
			<th>Target URL</th>
			<th>Type</th>
			<th>Active</th>
			<th>Hits</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
<?php

	global $__CMS_CONN__;
	$checkforurl = "SELECT * FROM ".TABLE_PREFIX."page_redirects ORDER BY oldurl";
	$checkforurl = $__CMS_CONN__->prepare($checkforurl);
	$checkforurl->execute();
	while ($requrl = $checkforurl->fetchObject()) {
		$id		= $requrl->id;
		$oldurl	= $requrl->oldurl;
		$newurl	= $requrl->newurl;
		$type	= $requrl->type;
		$active	= $requrl->active;
		$hits	= $requrl->hits;
		if ($type == 1) {
			$type = 'Permanent';
		}
		elseif ($type == 2) {
			$type = 'Temporary';
		}

?>
		<tr>
			<td><?php echo $oldurl; ?></td>
			<td><img width="16" height="16" src="images/redirect-lr.png" alt="redirects to" /></td>
			<td><?php echo $newurl; ?></td>
			<td><?php echo $type; ?></td>
			<td><?php if ($active == 1) { echo '<a href="'.get_url('plugin/redirects/deactivate/'.$id).'" onclick="return confirm(\'You are about to DEACTIVATE this redirect.\n\nContinue?\');"><img src="'.URL_PUBLIC.'admin/images/active.png" align="middle"" /></a>'; } else { echo '<a href="'.get_url('plugin/redirects/activate/'.$id).'" onclick="return confirm(\'You are about to ACTIVATE this redirect.\n\nContinue?\');"><img src="'.URL_PUBLIC.'admin/images/inactive.png" align="middle"" /></a>'; } ?></td>
			<td><p><strong><?php echo $hits; ?></strong></p></td>
			<td><a href="<?php echo get_url('plugin/redirects/edit/'.$id); ?>"><img width="16" height="16" src="images/edit.png" alt="Edit" /></a>
				<a href="<?php echo get_url('plugin/redirects/delete/'.$id); ?>" onclick="return confirm('Are you sure you wish to delete the redirect from:\n\n<?php echo $oldurl ?>\n\nto:\n\n<?php echo $newurl ?>');"><img width="16" height="16" src="images/delete.png" alt="remove icon" /></a> </td>
		</tr>
<?php } ?>
	</tbody>
</table>

<div class="popup" id="add-redirect" style="display:none;">
	<h2>Add a new Redirect</h2>
	<form action="<?php echo get_url('plugin/redirects/add'); ?>" method="POST" name="add_redirect">
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><small>Old URL</small></td>
				<td><input length="64" name="oldurl" type="text" value="" /></td>
			</tr>
			<tr>
				<td><small>Target URL</small></td>
				<td><input length="64" name="newurl" type="text" value="" /></td>
			</tr>
			<tr>
				<td><small>Type</small></td>
				<td><select name="type">
						<option value="1">Permanent (301)</option>
						<option value="2">Temporary (307)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><small>Activate?</small></td>
				<td><input name="active" type="checkbox" value="1" /></td>
			</tr>
			<tr>
				<td><p>&nbsp;</p></td>
				<td><input class="button" name="add_redirect" type="submit" value="Add Redirect"> or <a class="close-link" href="#" onclick="Element.hide('add-redirect'); return false;">cancel</a></td>
			</tr>
		</table>
	</form>
</div>