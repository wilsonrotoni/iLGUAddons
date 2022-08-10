<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_facode") ?>>Asset Code</label></td>
		<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_facode") ?>/></td>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Transfer Date</label></td>
		<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_faname") ?>>Asset Name</label></td>
		<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_faname") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("tf_refno") ?>>Reference No.</label></td>
		<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("tf_refno") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jvno") ?>>JV No.</label></td>
		<td><?php genLinkedButtonHtml("u_jvno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jvno") ?>/></td>
	  </tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					<tr>					</tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_faclass") ?>>Asset Class</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_faclass") ?>/></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_itemdesc") ?>>Item Description</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_itemdesc") ?>/></td>
					</tr>
					

					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_acquidate") ?>>Acquisition Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_acquidate") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_serialno") ?>>Serial No.</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_serialno") ?>/></td>
					</tr>
					
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_depredate") ?>>Depreciation Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_depredate") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mfrserialno") ?>>Mfr Serial No.</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_mfrserialno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cost") ?>>Asset Cost</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_cost") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property1") ?>>Property 1</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property1") ?>/></td>
					</tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_accumdepre") ?>>Accumulated Dep.</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_accumdepre") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property2") ?>>Property 2</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property2") ?>/></td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_salvagevalue") ?>>Salvage Value</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_salvagevalue") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property3") ?>>Property 3</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property3") ?>/></td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bookvalue") ?>>Book Value</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_bookvalue") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property4") ?>>Property 4</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property4") ?>/></td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remainlife") ?>>Remaining Life</label></td>
						<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_remainlife") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property5") ?>>Property 5</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property5") ?>/></td>
				  </tr>
					
					<tr class="fillerRow5px">
					  <td>&nbsp;</td>
					  <td></td>
					  <td></td>
					  <td></td>
				  </tr>
					<tr>
					  <td colspan="2"><label class="lblobjs"><b>Currently Assigned To:</b></label></td>
					  <td colspan="2"><label class="lblobjs"><b>Transfer To:</b></label></td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_branch") ?>>Branch</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_branch",array("loadbrancheslist","",":[Select]")) ?>/></select></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tftobranch") ?>>Branch</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_tftobranch",array("loadbrancheslist","",":[Select]")) ?>/></select></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Department</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loaddepartments","",":[Select]")) ?>/></select></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tftodepartment") ?>>Department</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_tftodepartment",array("loaddepartments","",":[Select]")) ?>/></select></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_empid") ?>>Employee</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_empid") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tftoempid") ?>>Employee</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_tftoempid") ?>/></td>
					</tr>
					
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_empname") ?>>Employee Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_empname") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tftoempname") ?>>Employee Name</label></td>
						<td>&nbsp;<input type="text" size="50"<?php $page->businessobject->items->userfields->draw->text("u_tftoempname") ?>/></td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tftoprofitcenter") ?>>Profit Center</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_tftoprofitcenter") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_projcode") ?>>Project</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_projcode") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tftoprojcode") ?>>Project</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_tftoprojcode") ?>/></td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tftype") ?>>Type of Transfer</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_tftype") ?>/></select></td>
				  </tr>
				</table>
			</div>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,191); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
		

