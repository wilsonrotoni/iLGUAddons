<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpcode") ?>>Customer No.</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bpcode") ?>/></td>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Posting Date</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpname") ?>>Customer Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bpname") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_arinvno") ?>>JV No.</label></td>
						<td><?php genLinkedButtonHtml("u_arinvno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_arinvno") ?>/></td>
	  </tr>	
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_facode") ?>>Asset Code</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_facode") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_faname") ?>>Asset Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_faname") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_faclass") ?>>Asset Class</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_faclass") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_projcode") ?>>Project</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_projcode") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cost") ?>>Asset Cost</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_cost") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_accumdepre") ?>>Accumulated Dep.</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_accumdepre") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bookvalue") ?>>Book Value</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_bookvalue") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_price") ?>>Selling Price</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_price") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_taxcode") ?>>Tax Code</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_taxcode",array("loadouttaxes","",":[Select]")) ?>/></select></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_wtaxcode") ?>>WTax Code</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_wtaxcode",array("loadwtaxes","",":")) ?>/></select></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gainloss") ?>>Loss/Gain</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_gainloss") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
	</table>
</td></tr>	
		

