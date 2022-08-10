<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	
	
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Establishment</label></td>
						<td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
	                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_category") ?>>Category</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_category") ?>></select></td>
        <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	  </tr>	
         <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_owner") ?>>Name of Owner</label></td>
            <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_owner") ?>/></td>
            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_manager") ?>>Name of Manager</label></td>
            <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_manager") ?>/></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Application No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Address</label></td>
            <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_address") ?>rows="1" cols="42"><?php echo $page->getitemstring("u_address"); ?></TEXTAREA></td>
            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_noofpersonnel") ?>>No. of Personnel</label></td>
            <td>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_noofpersonnel") ?>/></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Application Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_nowithhealthcert") ?>>No. w/ Health Cert.</label></td>
            <td>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_nowithhealthcert") ?>/></td>
          <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectbystatus") ?>>Inspection Status</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inspectbystatus") ?>></select></td>
        </tr>
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspector") ?>>Name of Inspector</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inspector") ?>></select></td>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectdate") ?>>Inspection Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_inspectdate") ?>/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="center"><label class="lblobjs"><b>Items</b></label></td>
          <td align="center" width="168"><label class="lblobjs"><b>Demerit (&#x2713)</b></label></td>
          <td >&nbsp;<label class="lblobjs"><b>Recommended Corrective Measures</b></label></td>
          <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ratingperc") ?>>Percentage Rating</label></td>
            <td>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_ratingperc") ?>/>&nbsp;<label class="lblobjs">%</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td width="350">&nbsp;&nbsp;<label class="lblobjs">1.</label><label <?php $page->businessobject->items->userfields->draw->caption("u_sf1") ?>></label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth1",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_oth1rcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td width="350">&nbsp;&nbsp;<label class="lblobjs">2.</label><label <?php $page->businessobject->items->userfields->draw->caption("u_sf2") ?>></label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth2",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_oth2rcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td width="350">&nbsp;&nbsp;<label class="lblobjs">3.</label><label <?php $page->businessobject->items->userfields->draw->caption("u_sf3") ?>></label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth3",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_oth3rcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td width="350">&nbsp;&nbsp;<label class="lblobjs">4. Construction of Premises</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_cop",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_coprcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;&nbsp;<label class="lblobjs">5. Maintenance of Premises</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_mop",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_moprcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;&nbsp;<label class="lblobjs">6. Toilet Provision</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_tp",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_tprcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;&nbsp;<label class="lblobjs">7. Handwashing Facilities</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_hf",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_hfrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;&nbsp;<label class="lblobjs">8. Water Supply</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_ws",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_wsrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;&nbsp;<label class="lblobjs">9. Liquid Waste Management</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_lwm",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_lwmrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">10. Solid Waste Management</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_swm",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_swmrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">11. Wholesomeness of Food</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_wof",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_wofrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">12. Protection of Foods</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_pof",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_pofrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">13. Vermin Control</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_vc",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_vcrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">14. Cleanliness and Tidiness</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_cnt",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_cntrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">15. Personal Cleanliness</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_pc",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_pcrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">16. Housekeeping and Management</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_ham",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_hamrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">17. Condition of Appliance and Utensils</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_coau",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_coaurcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">18. San. Condition of Appliance and Utensils</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_scoau",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_scoaurcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">19. Disease Control</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_dc",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_dcrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;<label class="lblobjs">20. Miscellaneous</label></td>
          <td align="center" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_mic",1) ?>/></td>
          <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_micrcm") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="center"><label class="lblobjs"><b>Total Demerits</b></label></td>
          <td align="center"><input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_totaldemerits") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

