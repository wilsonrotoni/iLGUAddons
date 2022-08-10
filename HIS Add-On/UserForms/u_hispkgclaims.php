<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
	  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_billno") ?>>Bill No.</label></td>
	  <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_billno") ?>/></td>	
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_guarantorcode") ?>>Health Benefit</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_guarantorcode",array("loadu_hishealthins8","",":"),null,null,null,"width:266") ?>/></select></td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
		<td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><select <?php $page->businessobject->items->userfields->draw->select("u_reftype",null,null,null,null,"width:112px") ?>/></select>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td width="168" >&nbsp;</td>
		<td width="168" >&nbsp;</td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->draw->caption("u_patientname") ?>>Patient ID / Name</label></td>
	  <td>&nbsp;<input type="text" size="18"  <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td width="168">&nbsp;</td>
		<td>&nbsp;</td></tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_memberid") ?>>Member ID / Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_memberid") ?>/>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_membername") ?>/></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General Info">
			<div id="divbenefits" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					<tr>
					  <td colspan="2">&nbsp;<label class="lblobjs"><b>Hospital Fees</b></label></td>
					  <td colspan="2">&nbsp;<label class="lblobjs"><b>Professional Fees/Materials</b></label></td>
					  <td width="138">&nbsp;<label class="lblobjs"><b>Total</b></label></td>
				      <td width="138">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_acthc") ?>>Net Charges</label></td>
						<td width="118">&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_acthc") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_actpf") ?>>Net Charges</label></td>
						<td width="118">&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_actpf") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_act") ?>>Net Charges</label></td>
						<td width="118">&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_act") ?>/></td>
				  </tr>
					
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_pkghc") ?>>Package</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_pkghc") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_pkgpf") ?>>Package</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_pkgpf") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_pkg") ?>>Package</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_pkg") ?>/></td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_balhc") ?>>Balance</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_balhc") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_balpf") ?>>Balance</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_balpf") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bal") ?>>Balance</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_bal") ?>/></td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				      <td>&nbsp;</td>
				  </tr>
				</table>
				<?php $objGrids[0]->draw(true) ?>	  
			</div>
		</div>
	</div>
</td></tr>		
<?php if ($page->getvarstring("formType")=="lnkbtn") $page->resize->addtab("tab1",-1,191); 
	  else $page->resize->addtab("tab1",-1,211); 	?>
<?php $page->resize->addtabpage("tab1","benefits") ?>
<?php $page->resize->addgridobject($objGrids[0],20,352);  ?>		

