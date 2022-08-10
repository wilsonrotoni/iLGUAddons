<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="138" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_inscode") ?>>Health Benefit</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inscode",array("loadu_hishealthins5","",":")) ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td >&nbsp;</td>
	<td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_postdoctorpayable",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_postdoctorpayable") ?>>Post Doctor's Payable</label></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>	  </tr>
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>	
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<tr class="fillerRow5px"><td></td></tr>	
<tr><td>
	<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr>
		  <td width="138"><label <?php $page->businessobject->items->userfields->draw->caption("u_preparedby") ?>>Prepared By</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_preparedby",null,null,null,null,"width:238px") ?>></select></td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_directpfamount") ?>>Direct PF Payment</label></td>
			<td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_directpfamount") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_paidamount") ?>>Payment</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_paidamount") ?>/></td>
		</tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cmdocno") ?>>CM No.</label></td>
	<td><?php genLinkedButtonHtml("u_cmdocno","","OpenLnkBtnIncomingPayments()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_cmdocno") ?>/></td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_discamount") ?>>Discount</label></td>
			<td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_discamount") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_applied") ?>>Less: Applied</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_applied") ?>/></td>
	  </tr>
		<tr>
		  <td >&nbsp;</td>
		  <td>&nbsp;</td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_wtaxamount") ?>>W/Tax</label></td>
			<td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_wtaxamount") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_variance") ?>>Variance</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_variance") ?>/></td>
	  </tr>
	</table>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],0,230) ?>		

