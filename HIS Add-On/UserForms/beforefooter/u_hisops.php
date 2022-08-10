<?php if (!isset($page->settings->datafields["u_gender"])) { ?><input type="hidden" <?php genInputHiddenDFHtml("u_gender") ?> ><?php } ?>
<?php if (!isset($page->settings->datafields["u_religion"])) { ?><input type="hidden" <?php genInputHiddenDFHtml("u_religion") ?> ><?php } ?>
<?php if ($page->getitemstring("u_expired")=="0" && !isset($page->settings->datafields["u_typeofexpire"])) { ?>
<div <?php genPopWinHDivHtml("popupFrameExpired","Expired Details",10,30,800,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_expiredate") ?>>Date / </label><label <?php $page->businessobject->items->userfields->draw->caption("u_expiretime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_expiredate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_expiretime") ?>/></td>
		</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_typeofexpire") ?>>Expire Type</label></td>
	<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_typeofexpire") ?>></select></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_resultautopsied") ?>>Autopsied</label></td>
	<td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_resultautopsied") ?>></select></td>
	  </tr>
	
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="u_expiredGPSHIS();return false;" >Expired</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<?php } ?>
<?php if ($page->getitemstring("u_discharged")=="0" && !isset($page->settings->datafields["u_typeofdischarge"])) { ?>
<div <?php genPopWinHDivHtml("popupFrameDischarged","Discharge Details",10,30,800,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>Date / </label><label <?php $page->businessobject->items->userfields->draw->caption("u_endtime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_endtime") ?>/></td>
		</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dischargedby") ?>>Discharge By</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dischargedby") ?>></select></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_typeofdischarge") ?>>Discharge Type</label></td>
		<td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_typeofdischarge") ?>></select></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_resultrecovered") ?>>Recovered</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_resultrecovered") ?>></select></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_resultimproved") ?>>Improved</label></td>
		<td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_resultimproved") ?>></select></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_healthcareins_to") ?>>Transferred To</label></td>
		<td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_healthcareins_to") ?>></select></td>
	  </tr>
	
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="u_dischargedGPSHIS();return false;" >Discharged</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<?php } ?>
<div <?php genPopWinHDivHtml("popupFrameAdmit","Admit Patient",10,30,450,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfdepartment") ?>>Nursing Station</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_tfdepartment",array("loadudflinktable","u_hissections:code:name:u_type in ('IP')",":")) ?>></select></td>
		</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfroomno") ?>>Room No.</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_tfroomno") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfroomdesc") ?>>Room Description</label></td>
	  <td  align=left>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_tfroomdesc") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfbedno") ?>>Bed No.</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_tfbedno") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfrate") ?>>Rate/Unit</label></td>
	  <td  align=left>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_tfrate") ?>/>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_tfrateuom") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfpricelist") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_tfpricelist") ?>></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfstartdate") ?>>Start Date</label><label <?php $page->businessobject->items->userfields->draw->caption("u_tfstarttime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_tfstartdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_tfstarttime") ?>/></td>
	  </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="u_admitpatientGPSHIS();return false;" >Admit Patient</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameVitalSigns","Vital Signs",10,30,760,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td colspan="2"><?php $objGrids[9]->draw(false); ?></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameAuthentication","Authentication",10,30,350,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="128"><label <?php genCaptionHtml(createSchema("userid"),"T50") ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml(createSchema("userid"),"T50") ?> /></td>
		</tr>
		<tr><td width="128"><label <?php genCaptionHtml(createSchema("password"),"T50") ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml(createSchema("password"),"T50") ?> /></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="formSubmit();return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameDoctorsDiagnosis","Doctor's diagnosis",10,30,960,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="208" valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_complaint") ?>>Complaint</label></td>
	  <td align=left>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_complaint","T100") ?>rows="3" cols="100" readonly><?php echo $page->getitemstring("u_complaint"); ?></TEXTAREA></td>
		</tr>
	<tr>
	  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_pastmedhistory") ?>>Pertinent Past Medical History</label></td>
	  <td  align=left>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_pastmedhistory","T100") ?>rows="3" cols="100" readonly><?php echo $page->getitemstring("u_pastmedhistory"); ?></TEXTAREA></td>
	  </tr>
	<tr>
	  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_historyillness") ?>>History of Illness</label></td>
		<td  align=left >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_historyillness","T100") ?>rows="3" cols="100" readonly><?php echo $page->getitemstring("u_historyillness"); ?></TEXTAREA></td>
	  </tr>
	
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="hidePopupFrame('popupFrameDoctorsDiagnosis');return false;" >Close</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<?php include_once("../Addons/GPS/HIS Add-On/Userforms/utils/u_hispatientpendingitems.php"); ?>
