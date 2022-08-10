<?php if (!isset($page->settings->datafields["u_age"])) { ?><input type="hidden" <?php genInputHiddenDFHtml("u_age") ?> ><?php } ?>
<input type="hidden" <?php genInputHiddenDFHtml("u_gender") ?> >
<?php if (!isset($page->settings->datafields["u_startdate"])) { ?><input type="hidden" <?php genInputHiddenDFHtml("u_startdate") ?> ><?php } ?>
<?php if (!isset($page->settings->datafields["u_enddate"])) { ?><input type="hidden" <?php genInputHiddenDFHtml("u_enddate") ?> ><?php } ?>
<?php if (!isset($page->settings->datafields["u_dpamount"])) { ?><input type="hidden" <?php genInputHiddenDFHtml("u_dpamount") ?> ><?php } ?>
<?php if (!isset($page->settings->datafields["u_dueamount"])) { ?><input type="hidden" <?php genInputHiddenDFHtml("u_dueamount") ?> ><?php } ?>
<input type="hidden" <?php genInputHiddenDFHtml("u_mgh") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_icdcode") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_billcount") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_billasone") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("docstatus") ?> >
<?php include_once("../Addons/GPS/HIS Add-On/Userforms/utils/u_hispatientpendingitems.php"); ?>
<div <?php genPopWinHDivHtml("popupFrameCancel","Cancel",10,30,550,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("userid"),"T51") ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml(createSchema("userid"),"T51") ?> /></td>
		</tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("password"),"T51") ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml(createSchema("password"),"T51") ?> /></td>
		</tr>
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("cancelreason"),"T51") ?> >Reason</label></td>
			<td >&nbsp;<select <?php genSelectHtml(createSchema("cancelreason"),array("loadudflinktable","u_hiscancelreasons:code:name",":"),"T51",null,null,"width:350px") ?> ></select></td>
		</tr>
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("remarks"),"T51") ?> >Remarks</label></td>
			<td >&nbsp;<TEXTAREA <?php genTextAreaHtml(createSchema("remarks"),"T51") ?>  rows="5" cols="47"></TEXTAREA></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="formSubmit('cnd');return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameQueue","Queueing",10,13,230,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_queue") ?>>Current Number</label></td>
			<td >&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_queue") ?>/></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
        <tr><td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_autoqueue",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_autoqueue") ?>>Auto</label></td>
            <td align="right"><a class="button" href="" onClick="getNextQueGPSHIS();return false;" >Get Next Number</a></td>
        </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>