<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168">&nbsp;</td>
	  <td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_type","Credit Memo") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_type") ?>>Credit Memo</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_type","Promissory Note") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_type") ?>>Promissory Note</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_type","Debit Memo") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_type") ?>>Debit Memo</label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_billno") ?>>Bill No.</label></td>
	  <td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_billno") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_guarantorcode") ?>>Health Benefit</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_guarantorcode",array("loadu_hishealthins6","",":"),null,null,null,"width:266") ?>/></select></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_btrefno") ?>>Balance TF Ref No.</label></td>
	  <td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_btrefno") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><select <?php $page->businessobject->items->userfields->draw->select("u_reftype",null,null,null,null,"width:147px") ?>/></select>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID / Name</label></td>
		<td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_membertype") ?>>Member Type</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_membertype",null,null,null,null,"width:112px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Member ID / Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_memberid") ?>/>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_membername") ?>/></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_feetype") ?>>Type of Fee /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Doctor</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_feetype",null,null,null,null,"width:147px") ?>/></select>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid",array("loadu_hisbilldoctors",$page->getitemstring("u_billno"),":"),null,null,null,"width:287px") ?>/></select></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_exclaim",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_exclaim") ?>>Excess Claim</label></td>
	  </tr>
	<tr>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
		<td  align=left rowspan="2">&nbsp;<textarea cols="60" rows="1" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks")?></textarea></td>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Actual Charges</label></td>
	  <td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pnamount") ?>>This Amount</label></td>
	  <td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_pnamount") ?>/></td>
	  </tr>
	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_xmtalno") ?>>Transmittal No./Date</label></td>
	  <td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_xmtalno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_xmtaldate") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_netamount") ?>>Net Charges</label></td>
	  <td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_netamount") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_jvdocno") ?>>JV No.</label></td>
	  <td  align=left><?php genLinkedButtonHtml("u_jvdocno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_jvdocno") ?>/><?php genLinkedButtonHtml("u_jvcndocno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_jvcndocno") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_btamount") ?>>Balance Transfered </label></td>
	  <td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_btamount") ?>/></td>
	  </tr>
	</table>
</td></tr>	
	
