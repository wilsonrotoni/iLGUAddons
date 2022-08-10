<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;<label class="lblobjs">[</label><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_oldpatient","0") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_oldpatient") ?>>New</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_oldpatient","1") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_oldpatient") ?>>Old</label><label class="lblobjs">]</label>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>S.Citizen</label>
		&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_confidential",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_confidential") ?>>Confidential</label>&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_allergies",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_allergies") ?>>Allergies</label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
		<td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;-&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_arrivedby") ?>>Arrived By</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_arrivedby") ?>></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Admission Date /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_starttime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_admittedby") ?>>Admitting Clerk</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_admittedby") ?>></select></td>
	  <td ><?php if ($page->getitemstring("u_discharged")==1 && $formSubmitActionReturn) {?><label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>Discharged Date /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_endtime") ?>>Time</label><?php } ?></td>
	  <td align=left>&nbsp;<?php if ($page->getitemstring("u_discharged")==1 && $formSubmitActionReturn) {?><input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_endtime") ?>/><?php } ?></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Nursing Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code",":")) ?>></select></td>
	  <td ><?php if ($page->getitemstring("u_expired")==1 && $formSubmitActionReturn) {?><label <?php $page->businessobject->items->userfields->draw->caption("u_expiredate") ?>>Death Date /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_expiretime") ?>>Time</label><?php } ?></td>
	  <td align=left>&nbsp;<?php if ($page->getitemstring("u_expired")==1 && $formSubmitActionReturn) {?><input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_expiredate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_expiretime") ?>/><?php } ?></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pricelist") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pricelist",null,null,null,null,"width:148px") ?>></select></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_billno") ?>>Bill No.</label></td>
	  <td align=left>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_billno") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Diagnosis">	
			<div id="divdiagnosis" style="overflow:auto;">
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
           	 	<?php if ($_SESSION["roleid"]=="DOCTOR") { ?>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_complaint") ?>>Complaint</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_complaint") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_complaint"); ?></TEXTAREA></td>
			  </tr>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_pastmedhistory") ?>>Pertinent Past Medical History</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_pastmedhistory") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_pastmedhistory"); ?></TEXTAREA></td>
			  </tr>
				<tr>
				  <td valign="top" >&nbsp;</td>
				  <td colspan="4">&nbsp;</td>
			  </tr>
              <?php } ?>
				<tr>
				  <td width="168" valign="top" ><label class="lblobjs">OB/GYN History</label></td>
				  <td colspan="4"><label <?php $page->businessobject->items->userfields->draw->caption("u_ob_g") ?>>G</label>&nbsp;<input type="text"  size="10" <?php $page->businessobject->items->userfields->draw->text("u_ob_g") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ob_p") ?>>P</label>&nbsp;<input type="text"  size="10" <?php $page->businessobject->items->userfields->draw->text("u_ob_p") ?>/>&nbsp;<label class="lblobjs" >(</label>&nbsp;<input type="text"  size="5" <?php $page->businessobject->items->userfields->draw->text("u_ob_1") ?>/><label class="lblobjs" >-</label>&nbsp;<input type="text"  size="5" <?php $page->businessobject->items->userfields->draw->text("u_ob_1") ?>/><label class="lblobjs" >-</label>&nbsp;<input type="text"  size="5" <?php $page->businessobject->items->userfields->draw->text("u_ob_1") ?>/><label class="lblobjs" >-</label>&nbsp;<input type="text"  size="5" <?php $page->businessobject->items->userfields->draw->text("u_ob_1") ?>/>&nbsp;<label class="lblobjs" >)</label>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_ob_lmp") ?>>LMP</label>&nbsp;<input type="text"  size="20" <?php $page->businessobject->items->userfields->draw->text("u_ob_lmp") ?>/>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ob_na",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ob_na") ?>>NA</label></td>
			  </tr>
				<tr>
				  <td valign="top" >&nbsp;</td>
				  <td colspan="4">&nbsp;</td>
			  </tr>              
              <?php if ($_SESSION["roleid"]=="DOCTOR") { ?>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_historyillness") ?>>History of Illness</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_historyillness") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_historyillness"); ?></TEXTAREA></td>
			  </tr>
               <?php } ?>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendation") ?>>Recommendation</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_recommendation") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_recommendation"); ?></TEXTAREA></td>
			  </tr>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Initial Diagnosis</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
				</tr>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks2") ?>>Final Diagnosis</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks2") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_remarks2"); ?></TEXTAREA></td>
				</tr>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_orproc") ?>>Operations/Procedures</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_orproc") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_orproc"); ?></TEXTAREA></td>
			  </tr>
				<tr>
				  <td valign="top" >&nbsp;</td>
				  <td colspan="4">&nbsp;</td>
			  </tr>
              </table>
  <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
 					<tr>
					  <td width="168">&nbsp;<label class="lblobjs">Signs & Symptoms</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_ams",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_ams") ?>>Altered mental sensorium</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_diarrhea",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_diarrhea") ?>>Diarrhea</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_hematemesis",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_hematemesis") ?>>Hematemesis</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_palpitations",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_palpitations") ?>>Palpitations</label></td>
				  </tr>
					<tr>
					  <td width="168">&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_abdominalcramp",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_abdominalcramp") ?>>Abdominal cramp/pai</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_dizziness",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_dizziness") ?>>Dizziness</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_hematuria",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_hematuria") ?>>Hematuria</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_seizures",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_seizures") ?>>Siezures</label></td>
				  </tr>
					<tr>
					  <td width="168">&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_anorexia",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_anorexia") ?>>Anorexia</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_dysphagia",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_dysphagia") ?>>Dysphagia</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_hemoptysis",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_hemoptysis") ?>>Hemoptysis</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_skinrashes",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_skinrashes") ?>>Skin rashes</label></td>
				  </tr>
					<tr>
					  <td width="168">&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_bleedinggums",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_bleedinggums") ?>>Bleeding gums</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_dyspnea",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_dyspnea") ?>>Dyspnea</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_irritability",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_irritability") ?>>Irritability</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_stool",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_stool") ?>>Stool, bloody/black tarry/mucoid</label></td>
				  </tr>
					<tr>
					  <td width="168">&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_bodyweakness",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_bodyweakness") ?>>Body weakness</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_dysuria",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_dysuria") ?>>Dysuria</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_jaundice",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_jaundice") ?>>Jaundice</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_sweating",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_sweating") ?>>Sweating</label></td>
				  </tr>
					<tr>
					  <td width="168">&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_blurring",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_blurring") ?>>Blurring of vision</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_epistaxis",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_epistaxis") ?>>Epistaxis</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_lee",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_lee") ?>>Lower extremity edema</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_urgency",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_urgency") ?>>Urgency</label></td>
				  </tr>
					<tr>
					  <td width="168">&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_chestpain",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_chestpain") ?>>Chest pain/discomfort</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_fever",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_fever") ?>>Fever</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_myalgia",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_myalgia") ?>>Myalgia</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_vomiting",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_vomiting") ?>>Vomiting</label></td>
				  </tr>
					<tr>
					  <td width="168">&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_constipation",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_constipation") ?>>Constipation</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_fou",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_fou") ?>>Frequency of urination</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_orthopnea",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_orthopnea") ?>>Orthopnea</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_weightloss",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_weightloss") ?>>Weight loss</label></td>
				  </tr>
					<tr>
					  <td width="168">&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_cough",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_cough") ?>>Cough</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_headache",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_headache") ?>>Headache</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_pain",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_pain") ?>>Pain,</label><input type="text" <?php $page->businessobject->items->userfields->draw->text("u_ss_pain_site") ?>/><label class="lblobjs">(site)</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ss_others",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ss_others") ?>>Others:</label><input type="text" <?php $page->businessobject->items->userfields->draw->text("u_ss_others_note") ?>/></td>
				  </tr>
              </table>
              <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr>
				  <td>&nbsp;</td>
				  <td  align=left colspan="2">&nbsp;</td>
				  <td  align=left>&nbsp;</td>
				  <td  align=left>&nbsp;</td>
			    </tr>
				<tr>
				  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Attending Doctor</label></td>
		<td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>></select></td>
			  <td  align=left width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorservice") ?>>Type of Service</label></td>
			  <td  align=left width="268">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorservice") ?>></select></td>
			  </tr>
			</table>
		  </div>
		</div>
		<div class="tabbertab" title="Room">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Doctors">	
			<?php $objGrids[9]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Vital Signs & Dietary">	
			<div id="divdiet" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td >&nbsp;<label class="lblobjs"><b><u>Current</u>:</b></label></td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;<label class="lblobjs"><b><u>Previous</u>:</b></label></td>
					  <td width="224">&nbsp;</td>
				      <td width="168">&nbsp;</td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_diettype") ?>>Type of Diet</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_diettype") ?>></select></td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_prevdiettype") ?>>Type of Diet</label></td>
					  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_prevdiettype") ?>></select></td>
				  </tr>
					<tr>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dietremarks") ?>>Remarks</label></td>
						<td  align=left >&nbsp;<textarea cols="42" rows="1" <?php $page->businessobject->items->userfields->draw->textarea("u_dietremarks") ?>><?php echo $page->getitemstring("u_dietremarks"); ?></textarea></td>
						<td  width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_prevdietremarks") ?>>Remarks</label></td>
						<td colspan="2">&nbsp;<textarea cols="42" rows="1" <?php $page->businessobject->items->userfields->draw->textarea("u_prevdietremarks") ?>><?php echo $page->getitemstring("u_prevdietremarks"); ?></textarea></td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td >&nbsp;</td>
				      <td >&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs"><b><u>Vital Signs</u>:</b></label></td>
	  <td  align=left>&nbsp;<a href="" onClick="showPopupFrame('popupFrameVitalSigns',true);return false">[]</a></label></td>
					  <td colspan="2">&nbsp;<label class="lblobjs"><b><u>Body mass index</u>:</b></label></td>
				      <td >&nbsp;<label class="lblobjs"><b><u>Allergy to</u>:</b></label></td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_vsdate") ?>>Date /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_vstime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_vsdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_vstime") ?>/></td>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_height_ft") ?>>Height</label></td>
	  <td align=left>&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_height_ft") ?>/><label class="lblobjs">'</label><input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_height_in") ?>/><label class="lblobjs">"&nbsp;&nbsp;&nbsp;or</label>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_height_cm") ?>/>&nbsp;<label class="lblobjs">cm</label></td>
				      <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_allergy1") ?>></select></td>
				  </tr>
					<tr>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_bt_c") ?>>Temperature</label></td>
					  <td >&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_bt_c") ?>/>&nbsp;<label class="lblobjs">°C or</label>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_bt_f") ?>/>&nbsp;<label class="lblobjs">°F</label></td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_weight_kg") ?>>Weight</label></td>
					  <td >&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_weight_kg") ?>/>&nbsp;<label class="lblobjs">kg or</label>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_weight_lb") ?>/>&nbsp;<label class="lblobjs">Lb</label></td>
				      <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_allergy2") ?>></select></td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bp_s") ?>>Blood Pressure</label></td>
	  <td align=left>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_bp_s") ?>/><label class="lblobjs">&nbsp;/</label>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_bp_d") ?>/></td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_bmi") ?>>BMI</label></td>
					  <td >&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_bmi") ?>/>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bmistatus") ?>/></td>
				  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_allergy3") ?>></select></td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_hr") ?>>Heart Rate</label></td>
	  <td align=left>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_hr") ?>/></td>
					  <td  align=left>&nbsp;</td>
					  <td >&nbsp;</td>
				      <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_allergy4") ?>></select></td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rr") ?>>Respiratory Rate</label></td>
	  <td align=left>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_rr") ?>/></td>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_religion") ?>>Religion</label></td>
	  <td align=left>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_religion") ?>/></td>
				      <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_allergy5") ?>></select></td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_o2sat") ?>>Oxygen Saturation</label></td>
	  <td align=left>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_o2sat") ?>/><label class="lblobjs">%</label></td>
					   <td  align=left>&nbsp;</td>
					  <td >&nbsp;</td>
				  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_allergy6") ?>></select></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_allergy7") ?>></select></td>
				  </tr>
                 </table> 
                <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
  
					<tr>
					  <td width="168">&nbsp;<label class="lblobjs">General Survey</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_gs_awake",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_gs_awake") ?>>Awake and alert</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_gs_altered",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_gs_altered") ?>>Altered sensorium:</label>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_gs_altered_note") ?>/></td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs">HEENT:</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_heent_essentially_normal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_essentially_normal") ?>>Essentially normal</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_heent_abnormal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_abnormal") ?>>Abnormal pupillary reaction</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_heent_cervical",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_cervical") ?>>Cervical lymphadenopathy</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_heent_dry",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_dry") ?>>Dry mucous membrane</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_heent_icteric_sclerae",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_icteric_sclerae") ?>>Icteric sclerae</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_heent_conjunctivae",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_conjunctivae") ?>>Pale conjunctivae</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_heent_eyeballs",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_eyeballs") ?>>Sunken eyeballs</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_heent_fontanelle",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_fontanelle") ?>>Pale fontanelle</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_heent_others") ?>>Others:</label>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_heent_others") ?>/></td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs">CHEST/LUNGS:</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_chest_essentially_normal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_chest_essentially_normal") ?>>Essentially normal</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_chest_asymmetrical",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_chest_asymmetrical") ?>>Asymmetrical chest expansion</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_chest_decreased",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_chest_decreased") ?>>Decreased breath sounds</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_chest_wheezes",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_chest_wheezes") ?>>Wheezes</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_chest_lumps",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_chest_lumps") ?>>Lump/s over breast(s)</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_chest_rales",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_chest_rales") ?>>Rales/crackles/rhonchi</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_chest_intercostal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_chest_intercostal") ?>>Intercostal rib/clavicular retraction</label></td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_chest_others") ?>>Others:</label>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_chest_others") ?>/></td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs">CVS:</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_cvs_essentially_normal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cvs_essentially_normal") ?>>Essentially normal</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_cvs_displaced",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cvs_displaced") ?>>Displaced apex beat</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_cvs_heaves",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cvs_heaves") ?>>Heaves and/or thrills</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_cvs_pericardial",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cvs_pericardial") ?>>Pericardial bulge</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_cvs_rhythm",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cvs_rhythm") ?>>Irregular Rhythm</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_cvs_muffled",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cvs_muffled") ?>>Muffled heart sounds</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_cvs_murmur",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cvs_murmur") ?>>Murmur</label></td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_cvs_others") ?>>Others:</label>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_cvs_others") ?>/></td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs">ABDOMEN:</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_abs_essentially_normal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_abs_essentially_normal") ?>>Essentially normal</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_abs_abdominal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_abs_abdominal") ?>>Abdominal rigidity</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_abs_abdomen",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_abs_abdomen") ?>>Abdomen tenderness</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_abs_hyperactive",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_abs_hyperactive") ?>>Hyperactive</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_abs_palpable",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_abs_palpable") ?>>Palpable mass(es)</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_abs_tympanitic",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_abs_tympanitic") ?>>Tympanitic/dull abdomen</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_abs_uterine",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_abs_uterine") ?>>Uterine Contraction</label></td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_abs_others") ?>>Others:</label>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_abs_others") ?>/></td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs">GU (IE):</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_gu_essentially_normal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_gu_essentially_normal") ?>>Essentially normal</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_gu_blood",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_gu_blood") ?>>Blood stained in exam finger</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_gu_cervical",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_gu_cervical") ?>>Cervical dilatation</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_gu_presence",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_gu_presence") ?>>Presence of abnormal discharge</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_gu_others") ?>>Others:</label>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_gu_others") ?>/></td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs">SKIN/EXTREMITIES:</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_essentially_normal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_essentially_normal") ?>>Essentially normal</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_clubbing",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_clubbing") ?>>Clubbing</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_cold",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_cold") ?>>Cold clammy skin</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_cyahosis",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_cyahosis") ?>>Cyahosis/mottled skin</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_edema",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_edema") ?>>Edema/swelling</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_decreased",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_decreased") ?>>Decreased mobility</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_pale",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_pale") ?>>Pale nailbeds</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_poor",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_poor") ?>>Poor skin turgor</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_rashes",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_rashes") ?>>Rashes/petechiae</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_skin_weak",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_weak") ?>>Weak pulses</label></td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_skin_others") ?>>Others:</label>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_skin_others") ?>/></td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs">NEURO-EXAM:</label></td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_neuro_essentially_normal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_essentially_normal") ?>>Essentially normal</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_neuro_galt",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_galt") ?>>Abnormal galt</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_neuro_position",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_position") ?>>Abnormal position sense</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_neuro_sensation",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_sensation") ?>>Abnormal/decreased sensation</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_neuro_reflex",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_reflex") ?>>Abnormal reflex(es)</label></td>
					  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_neuro_memory",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_memory") ?>>Poor/altered memory</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_neuro_muscle",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_muscle") ?>>Poor muscle tone/strength</label></td>
					  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_neuro_coordination",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_coordination") ?>>Poor coordination</label></td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_neuro_others") ?>>Others:</label>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_neuro_others") ?>/></td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td >&nbsp;</td>
					  <td align=left>&nbsp;</td>
					  <td align=left>&nbsp;</td>
				  </tr>
				</table>
		  </div>	
			<?php //$objGridDietary->draw(false); ?>
		</div>
		<div class="tabbertab" title="Medications">	
			<div class="tabber" id="tab2-1">
				<div class="tabbertab" title="Medications">	
					<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
						<tr>
						  <td width="168" valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_medication") ?>>Medications</label></td>
						  <td colspan="2" rowspan="2" valign="top">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_medication") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_medication"); ?></TEXTAREA></td>
					  </tr>
						<tr>
						  <td>&nbsp;</td>
					  </tr>
					</table>
				</div>
				<div class="tabbertab" title="Allergic to Medicines">	
					<?php $objGrids[11]->draw(true) ?>	  
				</div>
				<div class="tabbertab" title="Drugs/Medicines">	
					<?php $objGrids[13]->draw(true) ?>	  
				</div>
				<div class="tabbertab" title="Course in the ward">	
					<?php $objGrids[14]->draw(true) ?>	  
				</div>
			</div>	
		</div>		
		<div class="tabbertab" title="Transactions">	
			<?php $objGrids[12]->draw(true) ?>	  
		</div>	
		<div class="tabbertab" title="Medical Record">	
			<div id="divmedrec" style="overflow:auto;">
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr>
				  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_icdcode") ?>>ICD</label></td>
					<td  align=left width="140">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_icdcode") ?>/>&nbsp;-</td>
			  <td  align=left rowspan="3" valign="top" colspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_icddesc") ?> rows="2" cols="50"><?php echo $page->getitemstring("u_icddesc"); ?></TEXTAREA></td>
			  </tr>
				<tr>
				   <td  align=left ><label <?php $page->businessobject->items->userfields->draw->caption("u_casetype") ?>>Case Type</label></td>
			  <td  align=left >&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_casetype") ?>/></td>
			  </tr>
				<tr>
				  <td>&nbsp;</td>
				  <td  align=left>&nbsp;</td>
			  </tr>
				<tr>
				  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_rvscode") ?>>RVS</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_rvscode") ?>/>&nbsp;-</td>
			  <td  align=left rowspan="3"  valign="top"  colspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_rvsdesc") ?> rows="2" cols="50"><?php echo $page->getitemstring("u_rvsdesc"); ?></TEXTAREA></td>
			  </tr>
				<tr>
				  <td  align=left ><label <?php $page->businessobject->items->userfields->draw->caption("u_rvu") ?>>RVU</label></td>
			  <td  align=left >&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_rvu") ?>/></td>
			  </tr>
				<tr>
				  <td>&nbsp;</td>
				  <td  align=left>&nbsp;</td>
			  </tr>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_finalhistoryillness") ?>>History of Illness</label></td>
				  <td  colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_finalhistoryillness") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_finalhistoryillness"); ?></TEXTAREA></td>
				</tr>
				<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_finalremarks") ?>>Final Diagnosis</label></td>
				  <td  colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_finalremarks") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_finalremarks"); ?></TEXTAREA></td>
				</tr>
				<tr>
				  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_finaldoctorid") ?>>Attending Doctor</label></td>
		<td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_finaldoctorid") ?>></select></td>
			  <td  align=left width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_finaldoctorservice") ?>>Type of Service</label></td>
			  <td  align=left width="268">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_finaldoctorservice") ?>></select></td>
			  </tr>
			</table>
			</div>
		</div>
		<div class="tabbertab" title="Billing">	
			<div id="divbillinfo" style="overflow:auto;">
	        <div class="tabber" id="tab1-2">
				<div class="tabbertab" title="Current">	
                    <div id="divbilling" style="overflow:auto;">
                        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                            <tr>
                              <td><label class="lblobjs"><b>Payment Terms:</b></label></td>
                              <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_billing",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_billing") ?>>w/ Billing</label>&nbsp;&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Charge</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Partial Payment</label></td>
                              <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_forcebilling",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_forcebilling") ?>>Forced Billing</label></td>
                              <td  align=left>&nbsp;</td>
                          </tr>
                            <tr>
                              <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_paymentterm") ?>>Term</label></td>
              <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymentterm") ?>></select>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_specialprice",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_specialprice") ?>>w/ Special Price</label></td>
                              <td>&nbsp;</td>
                              <td  align=left>&nbsp;</td>
                          </tr>
                            <tr>
                              <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_disccode") ?>>Discount</label></td>
              <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disccode") ?>></select></td>
                              <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_billtermby") ?>>Term Changed By</label></td>
              <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_billtermby") ?>></select></td>
                          </tr>
                            <tr>
                              <td width="268">&nbsp;</td>
                              <td >&nbsp;</td>
                              <td  align=left><label <?php $page->businessobject->items->userfields->draw->caption("u_billremarks") ?>>Remarks:</label></td>
                              <td  align=left>&nbsp;</td>
                          </tr>
                            <tr>
                              <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_creditlimit") ?>>Total Credit Limit</label></td>
                  			<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_creditlimit") ?>/></td>
                              <td colspan="2" rowspan="4" valign="top">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_billremarks") ?> rows="4" cols="50"><?php echo $page->getitemstring("u_billremarks"); ?></TEXTAREA></td>
                          </tr>
                            <tr>
                              <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalcharges") ?>>Total Charges</label></td>
                  			<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalcharges") ?>/></td>
                          </tr>				  
                            <tr>
                              <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_availablecreditlimit") ?>>Available Credit</label></td>
                  			  <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_availablecreditlimit") ?>/>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_availablecreditperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_availablecreditperc") ?>>%</label></td>
                          </tr>				  
                            <tr>
                             <td>&nbsp;</td>
                              <td >&nbsp;</td>
</tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td >&nbsp;</td>
                              <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_billby") ?>>Bill By</label></td>
              <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_billby") ?>></select></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td >&nbsp;</td>
                              <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_billdatetime") ?>>Bill Date/Time</label></td>
              <td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_billdatetime") ?>/></td>
                            </tr>				  
                        </table>			
                    </div>	
            	</div>        
				<div class="tabbertab" title="Audit Trails">	
	                <?php $objGrids[8]->draw(true) ?>
                </div>
			</div>                
			</div>
		</div>
		<div class="tabbertab" title="Informant/Health Benefits">	
			<div id="divhealthins" style="overflow:auto;">
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td colspan="2"><label class="lblobjs"><b>Informant:</b></label></td>
                  <td width="96" ><label <?php $page->businessobject->items->userfields->draw->caption("u_informantrelationship") ?>>Relation</label></td>
                  <td >&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_informantrelationship") ?>/></td>
                  <td width="72" >&nbsp;</td>
                  <td width="136" >&nbsp;</td>
                </tr>  
                <tr>
                  <td width="96" ><label <?php $page->businessobject->items->userfields->draw->caption("u_informantname") ?>>Name</label></td>
                  <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_informantname") ?>/></td>
                  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_informantaddress") ?>>Address</label></td>
                  <td >&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_informantaddress") ?>/></td>
                  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_informanttelno") ?>>Tel. No.</label></td>
                  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_informanttelno") ?>/></td>
                </tr>
                <tr>
                  <td colspan="2"><label class="lblobjs"><b>Referred/Transferred By:</b></label></td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                <tr>
                  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_healthcareins_fr") ?>>Health Care</label></td>
              <td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_healthcareins_fr") ?>></select></td>
              <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_healthcareins_reason") ?>>Reason for Transfer</label></td>
                  <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_healthcareins_reason") ?> rows="1" cols="50"><?php echo $page->getitemstring("u_healthcareins_reason"); ?></TEXTAREA></td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                <tr class="fillerRow5px">
                  <td colspan="6"></td>
              </tr>  
            </table>        
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td><?php $objGrids[7]->draw(true) ?></td>
					<td width="18">
						<div><a class="button2" href="" onClick="u_moveRowUpHealthBenefitsGPSHIS('T8');return false;" ><img src="imgs/asc_<?php echo $_SESSION["theme"] ?>.gif" border="0"></a></div>
						<div><a class="button2" href="" onClick="u_moveRowDnHealthBenefitsGPSHIS('T8');return false;" ><img src="imgs/desc_<?php echo $_SESSION["theme"] ?>.gif" border="0"></a></div>
					</td>
				</tr>
			</table>
			</div>			
		</div>
		<?php if (($page->getitemstring("u_expired")==1 || $page->getitemstring("u_discharged")==1) && $formSubmitActionReturn) {?>		
		<div class="tabbertab" title="Expired/Discharged">	
			<div id="divdischarge" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<?php if ($page->getitemstring("u_discharged")==1 && $formSubmitActionReturn) {?>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dischargedby") ?>>Discharge By</label></td>
	  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dischargedby") ?>></select></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_typeofdischarge") ?>>Discharge Type</label></td>
						<td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_typeofdischarge") ?>></select></td>
						<td  align=left width="168">&nbsp;</td>
						<td  align=left width="268">&nbsp;</td>
				  </tr>
					
					<tr>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_resultrecovered") ?>>Recovered</label></td>
					  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_resultrecovered") ?>></select></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_resultimproved") ?>>Improved</label></td>
					  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_resultimproved") ?>></select></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_healthcareins_to") ?>>Transferred To</label></td>
					  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_healthcareins_to") ?>></select></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td  align=left colspan="2">&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
				  <?php } ?>
				   <?php if ($page->getitemstring("u_expired")==1 && $formSubmitActionReturn) {?>
					<tr>
					  <td>&nbsp;<label class="lblobjs"><b>Expired</b>:</label></td>
					  <td  align=left colspan="2">&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
						<td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_typeofexpire") ?>>Expire Type</label></td>
						<td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_typeofexpire") ?>></select></td>
						<td  align=left width="168">&nbsp;</td>
						<td  align=left width="268">&nbsp;</td>
				  </tr>
				  	<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_expiredhours") ?>>No of Hours</label></td>
                  		<td colspan="2">&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_expiredhours") ?>/></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_resultautopsied") ?>>Autopsied</label></td>
					  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_resultautopsied") ?>></select></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
				  <?php } ?>
				</table>
			</div>	
		</div>	
		<?php } ?>
		<div class="tabbertab" title="Notifications">	
			<div id="divnotify" style="overflow:auto;">
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td colspan="2"><label class="lblobjs"><b>SMS:</b></label></td>
                  <td width="96" >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td width="72" >&nbsp;</td>
                  <td width="136" >&nbsp;</td>
                </tr>  
                <tr class="fillerRow5px">
                  <td colspan="6"></td>
              </tr>  
                <tr>
                  <td width="96" ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_smsalert",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_smsmobileno") ?>>Mobile No.</label></td>
                  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_smsmobileno") ?>/>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_smsnetwork") ?>>Network</label>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_smsnetwork") ?>></select></td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                
                <tr>
                  <td width="96" colspan="2"><input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_smsalertdailycharges",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_smsalertdailycharges") ?>>Daily Notification on Total Charges</label></td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                <tr>
                  <td width="96" colspan="2"><input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_smsalertbill",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_smsalertbill") ?>>Final Bill Notification</label></td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                <tr>
                  <td width="96" colspan="2"><input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_smsalertpayments",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_smsalertpayments") ?>>Payment Notification</label></td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                 <tr class="fillerRow5px">
                  <td colspan="6"></td>
              </tr>  
            </table>        
		  </div>			
		</div>        
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,241); ?>
<?php $page->resize->addtabpage("tab1","diagnosis"); ?>
<?php $page->resize->addtabpage("tab1","diet"); ?>
<?php $page->resize->addtabpage("tab1","medrec"); ?>
<?php $page->resize->addtabpage("tab1","billinfo"); ?>
<?php $page->resize->addtabpage("tab1","healthins"); ?>
<?php $page->resize->addtabpage("tab1","notify"); ?>
<?php if (($page->getitemstring("u_expired")==1 || $page->getitemstring("u_discharged")==1) && $formSubmitActionReturn) {
	$page->resize->addtabpage("tab1","discharge"); 
	}?>
<?php $page->resize->addtab("tab1-2",-1,286); ?>
<?php $page->resize->addtabpage("tab1-2","billing"); ?>
<?php //$page->resize->addgridobject($objGrids[0],20,369) ?>
<?php $page->resize->addgridobject($objGrids[1],20,280) ?>		
<?php //$page->resize->addgridobject($objGrids[2],30,288) ?>		
<?php //$page->resize->addgridobject($objGrids[3],30,288) ?>		
<?php //$page->resize->addgridobject($objGrids[4],30,288) ?>		
<?php //$page->resize->addgridobject($objGrids[5],30,288) ?>		
<?php //$page->resize->addgridobject($objGrids[6],30,288) ?>		
<?php $page->resize->addgridobject($objGrids[7],32,410) ?>
<?php $page->resize->addgridobject($objGrids[8],30,289) ?>
<?php $page->resize->addgridobject($objGrids[9],20,280) ?>
<?php $page->resize->addgridobject($objGrids[11],30,324) ?>		
<?php $page->resize->addgridobject($objGrids[12],20,244) ?>		
<?php $page->resize->addgridobject($objGrids[13],30,344) ?>	
<?php $page->resize->addgridobject($objGrids[14],30,344) ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn" &&  $_SESSION["roleid"]=="DOCTOR") $page->resize->addinput("u_complaint",220,-1) ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn" &&  $_SESSION["roleid"]=="DOCTOR") $page->resize->addinput("u_pastmedhistory",220,-1) ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn" &&  $_SESSION["roleid"]=="DOCTOR") $page->resize->addinput("u_historyillness",220,-1) ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_recommendation",220,-1) ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_orproc",220,-1) ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_remarks",220,-1) ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_remarks2",220,610) //418 ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_medication",220,287) ;//-1?>	
<?php if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_finalhistoryillness",220,-1) //384 ?>	
<?php if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_finalremarks",220,454) //384 ?>	
<?php //$page->resize->addinput("u_icddesc",400,-1) ?>	
<?php //$page->resize->addinput("u_rvsdesc",210,-1) ?>	
<?php //$page->resize->addgridobject($objGridDietary,20,245) ?>
