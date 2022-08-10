<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td >&nbsp;</td>
		  <td align=left>&nbsp;</td>
		  <td colspan="2">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Charged</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Downpayment</label></td>
	  </tr>
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fromdepartment") ?>>From Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_fromdepartment",array("loadudflinktable","u_hissections:code:name:u_type in ('".iif($page->getitemstring("u_trxtype")=="OP","OP','ER','ADMITTING','DIALYSIS",$page->getitemstring("u_trxtype") . "','ER")."')",":")) ?>/></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	 <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_todepartment") ?>>To Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_todepartment",array("loadudflinktable","u_hissections:code:name:u_type in ('PHARMACY','LABORATORY','CSR','RADIOLOGY','DIALYSIS')",":")) ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Date /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_starttime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paymentterm") ?>>Payment Term</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymentterm",null,null,null,null,"width:158px") ?>/></select></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_disccode") ?>>Discount</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disccode",null,null,null,null,"width:158px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_pricelist") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pricelist") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_patientname") ?>>Name</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_vatamount") ?>>VAT Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_vatamount") ?>/></td>
	  </tr>
	<tr>
	  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
	  <td  align=left rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Total Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_payrefno") ?>>O.R. No.</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_payrefno") ?>/></td>
	  </tr>	
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>
<tr><td>
	<?php $objGrids[0]->draw(true); ?>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,241); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addgridobject($objGrids[0],10,303) ?>		
<?php //$page->resize->addgridobject($objGrids[1],-1,258) ?>		
<?php //$page->resize->addinput("u_remarks",35,245) ?>		


