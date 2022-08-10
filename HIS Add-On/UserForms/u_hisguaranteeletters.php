<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inscode") ?>>Health Insurance</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inscode") ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Document Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_patientname") ?>>Patient Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Start Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>End Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Reference No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_srcoffunds") ?>>Source of Funds</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_srcoffunds") ?>/></td>
	  </tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Charges">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Summary">	
			<?php $objGridSummary->draw(true) ?>	  
		</div>
	</div>
</td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr class="fillerRow5px"><td colspan="4"></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td rowspan="3" valign="top">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="2" cols="50"></TEXTAREA></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
        </tr>
        <tr><td ></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalcharges") ?>>Total Charges</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_totalcharges") ?>/></td>
        </tr>
        <tr><td width="168"></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_balance") ?>>Balance</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_balance") ?>/></td>
        </tr>
	</table>
</td></tr>	

<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,350) ?>		
<?php $page->resize->addgridobject($objGridSummary,20,325) ?>		

