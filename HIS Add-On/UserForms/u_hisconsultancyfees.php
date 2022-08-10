<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td >&nbsp;</td>
		  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label></td>
		  <td colspan="2">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Charged</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Downpayment</label></td>
	  </tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name",":")) ?>/></select></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	 <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Date / </label><label <?php $page->businessobject->items->userfields->draw->caption("u_starttime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_birthdate") ?>>Date of Birth /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_age_y") ?>>Age</label></td>
	  <td  align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_birthdate") ?>/>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_age_y") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_requestno") ?>>Request No.</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_requestno") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paymentterm") ?>>Payment Term</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymentterm") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_unitprice") ?>>Service Fee</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_unitprice") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_disccode") ?>>Discount</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disccode") ?>/></select></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Service Fee After Disc</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>	  
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pricelist") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pricelist") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_vatamount") ?>>VAT Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_vatamount") ?>/></td>
	  </tr>
	<tr>
	    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Doctor ID</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_itemamount") ?>>Items Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_itemamount") ?>/></td>
	  </tr>
	<tr>
	   <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_itemcode") ?>>Type of Service</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_itemcode") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
	  </tr>	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctortype") ?>>Service Category</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctortype") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prepaid") ?>>O.R. No.</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_payrefno") ?>/></td>
	  </tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Materials">
			<?php $objGrids[0]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Medications">
			<?php $objGrids[1]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Remarks">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td ><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,321); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addgridobject($objGrids[0],20,360) ?>		
<?php $page->resize->addgridobject($objGrids[1],20,360) ?>		
<?php $page->resize->addinput("u_remarks",35,325) ?>		


