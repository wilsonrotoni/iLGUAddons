<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td colspan="2"><label class="lblobjs"><b>NATURE OF INSPECTION CONDUCTED:</b></label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_classification") ?>>Classification</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_classification") ?>></select></td>
		                <td width="250">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_noic_buc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_noic_buc") ?>>Building Under Construction</label></td>
		                <td width="330">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_noic_pioo",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_noic_buc") ?>>Periodic Inspection of Occupancy</label></td>
        <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspector") ?>>Name of Inspector</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inspector") ?>></select></td>
	                    <td width="168">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_noic_afop",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_noic_afop") ?>>Application for Occupancy Permit</label></td>
	                    <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_noic_vioctntcv",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_noic_vioctntcv") ?>>Verification Inspection of Compliance to NTCV</label></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_issdate") ?>>Date Issued</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_issdate") ?>/></td>
	  </tr>
	<tr>
<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectdate") ?>>Inspection Date</label></td>
<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_inspectdate") ?>/></td>
<td width="168">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_noic_afbp",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_noic_afbp") ?>>Application for Business Permit</label></td>
<td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_noic_viocr",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_noic_viocr") ?>>Verification Inspection of Compliant Received</label></td>
<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Application No.</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
                        </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectbystatus") ?>>Inspector Status</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inspectbystatus") ?>></select></td>
	                    <td colspan="2">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_noic_os",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_noic_os") ?>>Others (Specify)</label>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_noic_osr") ?>/></td>
	                    
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Date of Application</label></td>
<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td width="230"><label <?php $page->businessobject->items->userfields->draw->caption("u_nameofbldg") ?>>Name of Building</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_nameofbldg") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_businessname") ?>>Name of Business</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_gi_businessname") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Address</label></td>
						<td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_address") ?>rows="2" cols="48"><?php echo $page->getitemstring("u_address"); ?></TEXTAREA></td>
					    <td >&nbsp;</td>
					    <td >&nbsp;</td>
					    <td >&nbsp;</td>
					    <td >&nbsp;</td>
					</tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_natureofbusiness") ?>>Nature of Business</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_gi_natureofbusiness") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td  ><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_owneroccupantname") ?>>Name of Owner/Occupant</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_gi_owneroccupantname") ?>/></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_owneroccupantno") ?>>Contact No.</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_owneroccupantno") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_representativename") ?>>Name of Representative</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_gi_representativename") ?>/></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_representativeno") ?>>Contact No.</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_representativeno") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td  ><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_noofstorey") ?>>No. of Storey</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_noofstorey") ?>/></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_heightofbldg") ?>>Height of Bldg.</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_heightofbldg") ?>/><label class"lblobjs">(m)</label></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_portionoccupied") ?>>Portion Occupied</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_portionoccupied") ?>/><label class"lblobjs">(%)</label></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_areaperflr") ?>>Area per Flr.</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_areaperflr") ?>/><label class"lblobjs">(sqm)</label></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_totalflrarea") ?>>Total Flr. Area</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalflrarea") ?>/><label class"lblobjs">(sqm)</label></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td  ><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_bldgpermitno") ?>>Building Permit No./Date</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_bldgpermitno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_bldgpermitdt") ?>/></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_opno") ?>>Occupancy Permit No./Date</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_opno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_opdt") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_lstfsicno") ?>>Latest FSIC Issued Control No./Date</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstfsicno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstfsicdt") ?>/></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_lstfsicfee") ?>>FC Fee</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstfsicfee") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td  ><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_cofdno") ?>>Certificate of Fire Drill/Date</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_cofdno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_cofddt") ?>/></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_cofdfee") ?>>FC Fee</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_cofdfee") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_lstntcvno") ?>>Latest NTCV Control No./Date</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstntcvno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstntcvdt") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td  ><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_fireinsco") ?>>Name of Fire Insurance Co/Co-Insurer</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_fireinsco") ?>/></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_fireinsno") ?>>Policy No./Date</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_fireinsno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_fireinsdt") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr>
					  <td  >&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td  ><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_lstbpno") ?>>Latest Mayor's/Bus Permit/Date</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstbpno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstbpdt") ?>/></td>
					    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_munlicno") ?>>Municipal License No./Date</label></td>
					    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_munlicno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_munlicdt") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td  ><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_lstfcoeino") ?>>Latest Certificate of Electrical Inspection No./Date</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstfcoeino") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gi_lstfcoeino") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td  ><label <?php $page->businessobject->items->userfields->draw->caption("u_gi_otherinfo") ?>>Other Information</label></td>
						<td >&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_gi_otherinfo") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
				</table>
		  </div>
		</div>
		<div class="tabbertab" title="Checklist">
		</div>
	</div>
</td></tr>		
<td><tr>		
    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr class="fillerRow10px">
          <td></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendations") ?>>Recommendations</label></td>
            <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_recommendations") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_recommendations"); ?></TEXTAREA></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendby") ?>>Recommended By</label></td>
            <td width="428">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_recommendby") ?>/></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendbystatus") ?>>Status/Date</label></td>
						<td width="168">&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_recommendbystatus") ?>/>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_recommenddate") ?>/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
         <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dispositionby") ?>>Disposition By</label></td>
          <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_dispositionby") ?>/></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dispositionbystatus") ?>>Status/Date</label></td>
						<td >&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_dispositionbystatus") ?>/>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_dispositiondate") ?>/></td>
        </tr>
    </table>
</td></tr>		

<?php $page->resize->addtab("tab1",-1,261); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
		
<?php //if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_recommendations",230,-1) ;//-1?>	
