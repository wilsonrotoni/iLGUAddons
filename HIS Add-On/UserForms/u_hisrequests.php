<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		 <td width="136" >&nbsp;</td>
		<td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_isstat",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isstat") ?>>Stat</label></td>
		  <td colspan="3">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Charge</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Partial Payment</label></td>
	  </tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_requestdepartment") ?>>From Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_requestdepartment",array("loadudflinktable","u_hissections:code:name:u_type <> ''",":")) ?>/></select></td>
		<td width="136" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	    <td width="168" rowspan="5" align="center" valign="top"><img id="PhotoImg" height=110 src="<?php echo $photopath; ?>" width=138 align="absmiddle" border=1 ></td>
	  </tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>To Section</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name:u_type<>''",":")) ?>/></select></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_requestdate") ?>>Request Date &</label><label <?php $page->businessobject->items->userfields->draw->caption("u_requesttime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_requestdate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_requesttime") ?>/></td>
	  	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_duedate") ?>>Due Date &</label><label <?php $page->businessobject->items->userfields->draw->caption("u_duetime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_duedate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_duetime") ?>/></td>
	  	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pricelist") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pricelist",null,null,null,null,"width:148px") ?>/></select></td>
	  	</tr>
	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_birthdate") ?>>Birth </label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>/></select>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_birthdate") ?>/>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_amountbefdisc") ?>>Total Before Discount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amountbefdisc") ?>/></td>
	    	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paymentterm") ?>>Payment Term</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymentterm") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_discamount") ?>>Discount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_discamount") ?>/></td>
	    <td  align=left>&nbsp;</td>
	</tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_disccode") ?>>Discount</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disccode",null,null,null,null,"width:148px") ?>/></select></td>
	   <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_vatamount") ?>>VAT Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_vatamount") ?>/></td>
	    <td  align=left>&nbsp;</td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Doctor ID</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Total Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
	    <td  align=left>&nbsp;</td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_requestby") ?>>Requested By</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_requestby",array("loaduserbyparam",$page->getitemstring("u_requestby"),"")) ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_payrefno") ?>>O.R. No.</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_payrefno","","OpenLnkBtnPayRefNoGPSHIS()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_payrefno") ?>/></td>
	  <td  align=left>&nbsp;</td>
	</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Items & Services">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Package Items">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Health Benefits">
			<?php $objGrids[2]->draw(true); ?>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr class="fillerRow5px">
	  <td ></td>
	  <td  align=left></td>
	  <td></td>
	  <td  align=left></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_otheramount") ?>>Total Health Benefits</label></td>
		<td  align=left width="168">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_otheramount") ?>/></td>
	  </tr>
	</table>
			
		</div>
		<?php if ($page->getitemstring("docstatus")=="CN") { ?>
		<div class="tabbertab" title="Cancellation Info">
			<div id="divcancel" style="overflow-y:auto; overflow-x:auto;" >
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr >
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledby") ?>>Cancelled By</label></td>
						<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cancelledby",array("loaduserbyparam",$page->getitemstring("u_cancelledby"),"")) ?>/></select></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr >
						<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledreason") ?>>Reason</label></td>
						<td align=left>&nbsp;<input type="text" size="70" <?php $page->businessobject->items->userfields->draw->text("u_cancelledreason") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr >
						<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledremarks") ?>>Remarks</label></td>
						<td align=left>&nbsp;<input type="text" size="70" <?php $page->businessobject->items->userfields->draw->text("u_cancelledremarks") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</div>		
		</div>				
		<?php } ?>
	</div>		
</td></tr>		
<?php $page->resize->addtab("tab1",-1,305); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php if ($page->getitemstring("docstatus")=="CN") $page->resize->addtabpage("tab1","cancel"); ?>
<?php $page->resize->addgridobject($objGrids[0],20,349) ?>		
<?php //$page->resize->addgridobject($objGrids[1],20,339) ?>		
<?php $page->resize->addgridobject($objGrids[1],20,308) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,379) ?>		
<?php //$page->resize->addgridobject($objGrids[1],-1,258) ?>		
<?php //$page->resize->addelement("iframeRemarks",35,245) ?>		
<?php //$page->resize->addgridobject($objGrid,20,258) ?>				


