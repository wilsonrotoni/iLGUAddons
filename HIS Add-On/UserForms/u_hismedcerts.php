<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td  width="140">&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype",null,null,null,null,"width:126px") ?>/></select></td>
	    <td><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>
	  <td  align=left>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Address</label></td>
	  <td  align=left colspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_address") ?>rows="2" cols="65"><?php echo $page->getitemstring("u_address"); ?></TEXTAREA></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender / </label><label <?php $page->businessobject->items->userfields->draw->caption("u_civilstatus") ?>>Status</label></td>
		<td  align=left colspan="2">&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>/></select>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_civilstatus") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Start Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/></td>
	  </tr>
	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_roomno") ?>>Room</label></td>
	  <td  align=left colspan="2">&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_roomno") ?>/>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>Discharged Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_initialremarks") ?>>Diagnosis</label></td>
	  <td  align=left colspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_initialremarks") ?>rows="3" cols="65"><?php echo $page->getitemstring("u_initialremarks"); ?></TEXTAREA></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_endtime") ?>>Discharged Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_endtime") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_orproc") ?>>Operation/Procedure</label></td>
	  <td  align=left colspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_orproc") ?>rows="3" cols="65"><?php echo $page->getitemstring("u_orproc"); ?></TEXTAREA></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
	  <td  align=left colspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="65"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Attending Doctor</label></td>
	  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>/></select></td>
		<td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prcno") ?>>PRC No.</label></td>
	  <td  align=left colspan="2">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_prcno") ?>/>&nbsp;</td>
		<td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ptrno") ?>>PTR No.</label></td>
	  <td  align=left colspan="2">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_ptrno") ?>/>&nbsp;</td>
		<td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
