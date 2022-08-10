<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td nowrap="nowrap" width="1%"><label <?php genCaptionHtml(array("name"=>"datefrom")) ?> >Date Modified</label></td>
	<td nowrap="nowrap" width="1%">&nbsp;<input type"text" size="15" <?php genInputTextHtml(array("name"=>"datefrom","cfl"=>"Calendar","format"=>"date")) ?> />-<input type"text" size="15" <?php genInputTextHtml(array("name"=>"dateto","cfl"=>"Calendar","format"=>"date")) ?> /></td>
    <td nowrap="nowrap" width="1%">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap" width="1%"><label <?php genCaptionHtml(array("name"=>"u_menuid")) ?> >Menu ID</label></td>
	<td nowrap="nowrap">&nbsp;<select <?php genSelectHtml(array("name"=>"u_menuid"),array("loadudflinktable","u_audittrailsetup:code:name",":All")) ?> ></select></td>
</tr>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"branchcode")) ?> >Branch</label></td>
	<td nowrap="nowrap">&nbsp;<select <?php if($_SESSION["branchtype"]=="B") echo "disabled" ?> <?php genSelectHtml(array("name"=>"branchcode"),array("loadudflinktable","branches:branchcode:branchname",":All")) ?> ></select></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"u_code")) ?> >Code/Document No.</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml(array("name"=>"document")) ?> /></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"userid")) ?> >User</label></td>
	<td nowrap="nowrap">&nbsp;<select <?php genSelectHtml(array("name"=>"userid"),array("loadudflinktable","users:userid:username",":All")) ?> ></select></td>
</tr>
<tr><td>&nbsp;</td>
	<td>&nbsp;<a class="button" href="" onClick="formEdit();return false;" >View Logs</a></td>
</tr>
<tr><td colspan="6"><?php $objGrid->draw(true); ?></td></tr>
</table>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td colspan="6"><?php $page->writeRecordLimit(); ?></td></tr>
</table>