<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
	<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
		<td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><select <?php $page->businessobject->items->userfields->draw->select("u_reftype",null,null,null,null,"width:112px") ?>/></select></td>
		<td  align=left>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td  align=left>&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td width="138"><label <?php $page->businessobject->items->userfields->draw->caption("u_pan") ?>>PAN of HCI</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_pan") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->draw->caption("u_patientname") ?>>Name of Patient</label></td>
	  <td width="128">&nbsp;<input type="text" size="13"  <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/>,</td>
	  <td width="138">&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
	  <td width="128">&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>	  
	  <td >&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_extname") ?>/></td>
		<td width="138"><label <?php $page->businessobject->items->userfields->draw->caption("u_phicno") ?>>Philhealth ID No.</label></td>
	  <td width="138">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_phicno") ?>/></td>	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
	  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
	  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
	  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_extname") ?>>Ext.</label></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<?php if ($page->getvarstring("edtopt")!="integrated") { ?>
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_birthdate") ?>>Date of Birth</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_birthdate") ?>/></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_informantname") ?>>Informant's Name</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_informantname") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
						<td>&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_informantrelationship") ?>>Relationship</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_informantrelationship") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_informantreasonsign") ?>>Reason for Signing</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_informantreasonsign") ?>/></td>
					</tr>
					<tr>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_membertype") ?>>Member Type</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_membertype") ?>></select></td>
					    <td >&nbsp;</td>
					    <td >&nbsp;</td>
					</tr>
					<tr><td width="168"></td>
						<td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_ismember",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ismember") ?>>Patient is the Member</label></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_memberid") ?>>Member ID</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_memberid") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_membername") ?>>Member Name</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_membername") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="tabbertab" title="Confinement">
			<div id="divconfinement" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="168"></td>
						<td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_istransferred",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_istransferred") ?>>Transferred</label></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_fr_name") ?>>Referring HCI Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_hci_fr_name") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_fr_street") ?>>Address</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_hci_fr_street") ?>/></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_fr_province") ?>>Province</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_hci_fr_province") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_fr_city") ?>>Town/City</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_hci_fr_city") ?>/></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_fr_zip") ?>>Zip</label></td>
						<td width="168">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_hci_fr_zip") ?>/></td>
					</tr>
					<tr class="fillerRow5px"><td colspan="4"></td></tr>
					<tr>
					  <td>&nbsp;<label class="lblobjs"><b>Confinement:</b></label></td>
					  <td >&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Admission Date &</label><label <?php $page->businessobject->items->userfields->draw->caption("u_starttime") ?>>Time</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>Discharged Date &</label><label <?php $page->businessobject->items->userfields->draw->caption("u_endtime") ?>>Time</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_endtime") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_typeofdischarge") ?>>Disposition</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_typeofdischarge") ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_expiredate") ?>>Expired Date &</label><label <?php $page->businessobject->items->userfields->draw->caption("u_expiretime") ?>>Time</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_expiredate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_expiretime") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_to_name") ?>>Referred To HCI</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_hci_to_name") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_to_street") ?>>Address</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_hci_to_street") ?>/></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_to_province") ?>>Province</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_hci_to_province") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_to_city") ?>>Town/City</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_hci_to_city") ?>/></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_hci_to_zip") ?>>Zip</label></td>
						<td width="168">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_hci_to_zip") ?>/></td>
					</tr>
					<tr class="fillerRow5px"><td colspan="4"></td></tr>
					<tr>
					  <td>&nbsp;<label class="lblobjs"><b>Admission Details:</b></label></td>
					  <td >&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_roomtype") ?>>Type of Accomodation</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_roomtype") ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorname") ?>>Attending Physician</label></td>
						<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_doctorname") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_initialremarks") ?>>Initial Diagnosis</label></td>
					  <td rowspan="3" valign="top">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_initialremarks") ?>rows="2" cols="48"><?php echo $page->getitemstring("u_initialremarks"); ?></TEXTAREA></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
				</table>
			</div>
		</div>
		<?php } ?>
		<div class="tabbertab" title="Diagnosis">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Special Consideration">
			<div id="divspecial" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					<tr class="fillerRow5px">
					  <td colspan="4"></td>
				  </tr>
					
					<tr class="fillerRow5px">
					  <td colspan="4"></td>
				  </tr>
					<tr>
					  <td width="368" ><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_b_code") ?>>b. Z - Benefit Package Code</label></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_oth_b_code",null,null,null,null,"width:300px") ?>></select></td>                    
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_c_code") ?>>c. Maternity Care Package</label></td>
					  <td>&nbsp;</td>                    
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_c_date1") ?>>Dates</label></td>
					  <td>&nbsp;<input name="text" type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_c_date1") ?>/></td>
					  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_c_date3") ?>>Dates</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_c_date3") ?>/></td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_c_date2") ?>>Dates</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_c_date2") ?>/></td>
					  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_c_date4") ?>>Dates</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_c_date4") ?>/></td>
				  </tr>
					
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_d_code") ?>>d. TB Dots Package</label></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_oth_d_code",null,null,null,null,"width:300px") ?>></select></td>                    
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_e_code") ?>>e. Animal Bite</label></td>
					  <td>&nbsp;</td>                    
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_e_day0") ?>>Day 0</label></td>
					  <td>&nbsp;<input name="text" type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_e_day0") ?>/></td>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_e_rig") ?>>RIG</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_e_rig") ?>/></td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_e_day3") ?>>Day 3</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_e_day3") ?>/></td>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_e_others") ?>>Others</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_e_others") ?>/></td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_oth_e_day7") ?>>Day 7</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_e_day7") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_code") ?>>f. Newborn Care</label></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_oth_f_code",null,null,null,null,"width:300px") ?>></select></td>                    
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt1",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt1") ?>>Immediate drying of New Born</label></td>
					  <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt4",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt4") ?>>Eye Prophylaxis</label></td>
					  <td colspan="2">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt7",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt7") ?>>BCG Vaccination'</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt2",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt2") ?>>Early Skin to Skin contact</label></td>
					  <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt5",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt5") ?>>Weighing of the New Born</label></td>
					  <td colspan="2">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt8",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt8") ?>>Non Separation of M/B for early screening</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt3",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt3") ?>>Timely Cord Clamping</label></td>
					  <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt6",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt6") ?>>Vitamin K Administration</label></td>
					  <td colspan="2">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_oth_f_opt9",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_f_opt9") ?>>Hepatitis B Vaccination</label></td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_oth_g_code") ?>>g. HIV / AIDS Treatment Laboratory Number</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_oth_g_number") ?>/></td>                    
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
				</table>
			</div>
		</div>
		<div class="tabbertab" title="Philhealth Benefits">
			<div id="divbenefits" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>                    
					  <td>&nbsp;<label class="lblobjs">Case Rate</label></td>
					  <td>&nbsp;<label class="lblobjs">PF</label></td>
					  <td>&nbsp;<label class="lblobjs">HCI</label></td>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_1stcrcode") ?>>1st Case Rate</label></td>
						<td width="118">&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_1stcrcode") ?>/>
					    <td width="168">&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_1stcr") ?>/></td>
					    <td width="118">&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_1stcrpf") ?>/></td>
					    <td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_1stcrhc") ?>/></td>
					    <td width="138">&nbsp;</td>
					    <td width="138">&nbsp;</td>
					</tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_2ndcrcode") ?>>2nd Case Rate</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_2ndcrcode") ?>/></td>
					  <td>&nbsp;<input type="text"  <?php $page->businessobject->items->userfields->draw->text("u_2ndcr") ?>/></td>
					  <td>&nbsp;<input type="text"  size="13" <?php $page->businessobject->items->userfields->draw->text("u_2ndcrpf") ?>/></td>
					  <td>&nbsp;<input type="text" size="13"  <?php $page->businessobject->items->userfields->draw->text("u_2ndcrhc") ?>/></td>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				  </tr>
					
					<tr>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamt") ?>>Total Benefits allowed</label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_totalamt") ?>/></td>
					  <td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_totalpf") ?>/></td>
					  <td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_totalhc") ?>/></td>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_consumedamt") ?>>Consumed</label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_consumedamt") ?>/></td>
					  <td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_consumedpf") ?>/></td>
					  <td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_consumedhc") ?>/></td>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_acthc") ?>>HCI Actual Charge</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_acthc") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_actpf") ?>>PF Actual Charge</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_actpf") ?>/></td>
					  <td>&nbsp;</td>
				      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_billno") ?>>Bill No.</label></td>
	  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_billno") ?>/></td>	
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bdischc") ?>>Discounts before PHIC</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_bdischc") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bdiscpf") ?>>Discounts before PHIC</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_bdiscpf") ?>/></td>
					  <td>&nbsp;</td>
				      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_xmtalno") ?>>Transmittal No.</label></td>
	  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_xmtalno") ?>/></td>	
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_claimhc") ?>>Benefits to Claim</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_claimhc") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_claimpf") ?>>Benefits to Claim</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_claimpf") ?>/></td>
					  <td>&nbsp;</td>
				      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_xmtaldate") ?>>Transmittal Date</label></td>
	  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_xmtaldate") ?>/></td>	
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_balhc") ?>>Balance</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_balhc") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_balpf") ?>>Balance</label></td>
						<td>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->userfields->draw->text("u_balpf") ?>/></td>
					  <td>&nbsp;</td>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				  </tr>
				</table>
				<?php $objGrids[1]->draw(true) ?>	  
			</div>
		</div>
	</div>
</td></tr>		
<?php if ($page->getvarstring("formType")=="lnkbtn") $page->resize->addtab("tab1",-1,141); 
	  else $page->resize->addtab("tab1",-1,161); 	?>
<?php if ($page->getvarstring("edtopt")!="integrated") $page->resize->addtabpage("tab1","udf") ?>
<?php if ($page->getvarstring("edtopt")!="integrated") $page->resize->addtabpage("tab1","confinement") ?>
<?php $page->resize->addtabpage("tab1","special") ?>
<?php $page->resize->addtabpage("tab1","benefits") ?>
<?php $page->resize->addgridobject($objGrids[0],20,200) ?>
<?php //if ($page->getvarstring("formType")=="lnkbtn") $page->resize->addgridobject($objGrids[1],20,450); 
	  //else 
	  $page->resize->addgridobject($objGrids[1],20,440);  ?>		

