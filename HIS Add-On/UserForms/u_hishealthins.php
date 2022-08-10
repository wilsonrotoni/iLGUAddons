<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left colspan="3">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_hmo",0) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_hmo") ?>>National Health Insurance</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_hmo",1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_hmo") ?>>HMO</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_hmo",3) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_hmo") ?>>LGU</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_hmo",4) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_hmo") ?>>Company</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_hmo",5) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_hmo") ?>>Collector</label>&nbsp;&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_hmo",6) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_hmo") ?>>Others</label>&nbsp;&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_hmo",7) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_hmo") ?>>Expense</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_hmo",2) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_hmo") ?>>Discount</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_postdoctorpayable",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_postdoctorpayable") ?>>Post Doctor's Payable</label></td>
	  </tr>
	<tr>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="80"><label <?php $page->businessobject->items->draw->caption("code") ?> > Code</label></td><td align="right"><?php if($page->settings->data["autogenerate"]==1) { ?><select <?php $page->businessobject->items->userfields->draw->select("u_series",array("loaddocseries",$page->objectcode,"-1:Manual"),null,null,null,"width:108px") ?> ></select><?php } ?></td></tr></table></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("code") ?>/></td>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_id") ?>>ID</label></td>
		<td  width="308" >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_id") ?>/></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Name/Description</label></td>
		<td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?>/></td>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_category") ?>>Category</label></td>
		<td  width="188" >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_category") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_glacctno") ?>>G/L Acct No.</label></td>
		<td  align=left>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_glacctno") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_category") ?>>Discount Priority</label></td>
	  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_priority",1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_priority") ?>>Apply First</label></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_glacctname") ?>>G/L Acct Name</label></td>
	  <td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_glacctname") ?>/></td>
	  <td >&nbsp;</td>
	  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_priority",0) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_priority") ?>>Apply Last</label></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
			<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department") ?>/></select></td>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_group") ?>>Group</label></td>
			<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_group") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_legalname") ?>>Legal Name</label></td>
	  <td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_legalname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_authrepname") ?>>Authorized Representative</label></td>
	  <td  align=left>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_authrepname") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_legaladdress") ?>>Legal Address</label></td>
	  <td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_legaladdress") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_authrepposition") ?>>Designation</label></td>
	  <td  align=left>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_authrepposition") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Packages">	
			<?php $objGrids[0]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Case Types">	
			<?php $objGrid->draw(false); ?>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,241); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,300) ?>				
<?php $page->resize->addgridobject($objGrid,20,278) ?>				


