<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left width="144">&nbsp;</td>
		<td align=left width="128">&nbsp;</td>
		<td align=left width="56">&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/></td>
		<td  align=left colspan="3">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	 <td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Name</label></td>
		  <td>&nbsp;<input type="text" size="15"  <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/>,</td>
		  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
		  <td >&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_extname") ?>/></td>
		  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Start Date</label><label <?php $page->businessobject->items->userfields->draw->caption("u_starttime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
		  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
		  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_extname") ?>>Ext.<br />(JR,III,<br />IV, V)</label></td>
		  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_hours") ?>>Hours</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_hours") ?>/></td>
	  </tr>
	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_roomno") ?>>Room No.</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_roomno") ?>/></td>
	  <td  align=left>&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>End Date</label><label <?php $page->businessobject->items->userfields->draw->caption("u_starttime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_endtime") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bedno") ?>>Bed No.</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bedno") ?>/></td>
	  <td  align=left>&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left colspan="4">&nbsp;</td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
	  <td  align=left colspan="4">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/></td>
	  <td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	


