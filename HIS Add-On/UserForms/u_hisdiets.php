<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name:u_type in ('IP','OP')",":")) ?>/></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	 <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_requestdate") ?>>Date /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_requesttime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_requestdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_requesttime") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bedno") ?>>Room/Bed No.</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bedno") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_religion") ?>>Religion</label></td>
	  <td  align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_religion") ?>/></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_meal") ?>>Meal</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_meal") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
	  <td  align=left>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_diettype") ?>>Type of Diet</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_diettype") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_height_ft") ?>>Height (ft/in)</label></td>
	  <td  align=left>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_height_ft") ?>/>&nbsp;/&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_height_in") ?>/></td>
	  </tr>	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
	  <td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_weight_kg") ?>>Weight (kg)</label></td>
	  <td  align=left>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_weight_kg") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,281); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php //$page->resize->addgridobject($objGrids[0],20,318) ?>		
<?php //$page->resize->addgridobject($objGrids[1],-1,258) ?>		
<?php //$page->resize->addinput("u_remarks",35,285) ?>		


