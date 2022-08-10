<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td colspan="4"><label <?php $page->businessobject->items->userfields->draw->caption("u_pl") ?>><b>Pregnancy Location</b></label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_pl","Uterine") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_pl","Uterine") ?>>Uterine</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_pl","Ectopic") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_pl","Ectopic") ?>>Ectopic</label></td>
	  </tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_pl_site") ?>>Specify Site</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_pl_site") ?>/></td>
	  </tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_pl_tubal",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_pl_tubalpart") ?>>If tubal, which part of the tube</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_pl_tubalpart") ?>/>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_pl_ruptured","Ruptured") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_pl_ruptured","Ruptured") ?>>Ruptured</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_pl_ruptured","Unruptured") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_pl_ruptured","Unruptured") ?>>Unruptured</label></td>
	  </tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_pl_embryo",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_pl_embryostate") ?>>If embryo is identified, state if with cardiac activity</label>&nbsp;<input type="type" size="10" <?php $page->businessobject->items->userfields->draw->text("u_pl_embryostate") ?>/></td>
	  </tr>
	
	

	<tr>
	  <td colspan="4"><label class="lblobjs"><b>Obstetric Diagnosis</b></label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_od_aog") ?>>Age of Gestation</label>&nbsp;<input type="type" size="10" <?php $page->businessobject->items->userfields->draw->text("u_od_aog") ?>/></td>
	  </tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_od_ad") ?>>Admitting Diagnosis: (based on LNMP/Ultrasound)</label>&nbsp;<input type="type" size="93" <?php $page->businessobject->items->userfields->draw->text("u_od_ad") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_od_fd") ?>>Final Diagnosis: (based on Ballard's Score)</label>&nbsp;<input type="type" size="100" <?php $page->businessobject->items->userfields->draw->text("u_od_fd") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label class="lblobjs">*If </label><input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_preterm","Preterm") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_preterm","Preterm") ?>>Preterm/</label><input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_preterm","Postterm") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_preterm","Postterm") ?>>Postterm</label><label <?php $page->businessobject->items->userfields->draw->caption("u_od_pa") ?>>, write Pediatric aging</label>&nbsp;<input type="type" size="10" <?php $page->businessobject->items->userfields->draw->text("u_od_pa") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_od_presentation") ?>>Presentation</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_presentation","Cephalic") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_presentation","Cephalic") ?>>Cephalic</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_presentation","Breech") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_od_presentationbreech") ?>>Breech</label><label class="lblobjs">&nbsp;(</label><input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_presentationbreech","Complete") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_presentationbreech","Complete") ?>>Complete</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_presentationbreech","Incomplete") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_presentationbreech","Incomplete") ?>>Incomplete</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_presentationbreech","Footling") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_presentationbreech","Footling") ?>>Footling</label><label class="lblobjs">&nbsp;)</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_presentation","Transverse") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_presentation","Transverse") ?>>Transverse</label></td>
	  </tr>
	
	
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_od_laborstatus") ?>>Labor Status</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_laborstatus","In Labor") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_laborstatus","In Labor") ?>>In Labor</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_laborstatus","Not In Labor") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_laborstatus","Not In Labor") ?>>Not In Labor</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_laborstatus","In Pre Term Labor / Controlled") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_laborstatus","In Pre Term Labor / Controlled") ?>>In Pre Term Labor / Controlled</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_laborstatus","Delivered") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_laborstatus","Delivered") ?>>Delivered</label></td>
	  </tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_od_omsc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_od_omsc") ?>>Obstetrical / Medical / Surgical Complication</label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_od_stmsc") ?>>*State the medical / surgical complication</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_od_stmsc") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_od_stsotc") ?>>*State the status of the condition/s</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_od_stsotc") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3"><label class="lblobjs">Management</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_od_mannerofdelivery") ?>>Manner of Delivery</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;&nbsp;&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_mannerofdelivery","Vaginal Spontaneous Delivery") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_mannerofdelivery","Vaginal Spontaneous Delivery") ?>>Vaginal Spontaneous Delivery</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_mannerofdelivery","Vaginal Assisted Delivery") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_od_vaginal") ?>>Vaginal Assisted Delivery</label><label class="lblobjs">&nbsp;(</label><input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_vaginal","Indicated") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_vaginal","Indicated") ?>>Indicated</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_vaginal","Elective") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_vaginal","Elective") ?>>Elective</label><label class="lblobjs">&nbsp;)</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;&nbsp;&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_mannerofdelivery","Cesarean Section") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_od_cesarean") ?>>Cesarean Section (should include the INDICATION)</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_od_cesarean_info") ?>/><label class="lblobjs">&nbsp;(</label><input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_cesarean","Classical") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_cesarean","Classical") ?>>Classical</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_cesarean","Low Segment Transverse") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_cesarean","Low Segment Transverse") ?>>Low Segment Transverse</label><label class="lblobjs">&nbsp;)</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;&nbsp;&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_mannerofdelivery","Repeat Cesarean Section") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_mannerofdelivery","Repeat Cesarean Section") ?>>Repeat Cesarean Section (must include indication of the Primary CS)</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_od_repeatcesarean_info") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;&nbsp;&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_mannerofdelivery","Other") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_mannerofdelivery","Other") ?>>MUST indicate the manner of delivery</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_od_mod") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_od_anesthesia") ?>>MUST indicate the type of anesthesia used</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_anesthesia","Epidural") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_anesthesia","Epidural") ?>>Epidural</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_anesthesia","Pudendal") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_anesthesia","Pudendal") ?>>Pudendal Block</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_anesthesia","Sab") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_anesthesia","Sab") ?>>Sab</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_anesthesia","Spinal") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_anesthesia","Spinal") ?>>Spinal</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_od_anesthesia","Local") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_od_anesthesia","Local") ?>>Local</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_od_mm",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_od_mm") ?>>Medical Management: NOT to be included in the diagnosis</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_od_mm_info") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_od_sm",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_od_sm") ?>>Surgical Management: NOT to be included in the Final Diagnosis</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_od_sm_info") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_od_bt",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_od_bt") ?>>Blood Transfusion: MUST be indicated together with the diagnosis and cause of anemia</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_od_bt_info") ?>/></td>
	  </tr>
	
 	<tr>
	  <td colspan="4"><label class="lblobjs"><b>Fetal Outcome</b></label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_general") ?>>General Outcome</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_fo_general","Term") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_fo_general","Term") ?>>Term</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_fo_general","Preterm/Postterm") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_generalstillbirth") ?>>Preterm/Postterm</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_fo_general","Stillbirth") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_fo_general","Stillbirth") ?>>Stillbirth</label><label class="lblobjs">&nbsp;(</label><input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_fo_generalstillbirth","Fresh Stillbirth") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_fo_generalstillbirth","Fresh Stillbirth") ?>>Fresh Stillbirth</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_fo_generalstillbirth","Macerated") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_fo_generalstillbirth","Macerated") ?>>Macerated</label><label class="lblobjs">&nbsp;)</label></td>
	  </tr>
	
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_sex") ?>>SEX (Note: MUST include)</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_fo_sex","Male") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_fo_sex","Male") ?>>Male</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_fo_sex","Female") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_fo_sex","Female") ?>>Female</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_fo_snc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_snc") ?>>Significant Neonatal Complication/s</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_fo_snc_info") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_fo_spa",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_spa") ?>>Significant Placental Abnormality</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_fo_spa_info") ?>/></td>
	  </tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label class="lblobjs">Detailed Outcome:</label>&nbsp;<input type="type" size="5" <?php $page->businessobject->items->userfields->draw->text("u_fo_weight") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_weight") ?>>Birth weight (actual weight is optional)</label>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_fo_sga",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_sga") ?>>SGA</label>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_fo_lga",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_lga") ?>>LGA</label></td>
	  </tr>
	
	  <td >&nbsp;</td>
	  <td colspan="3"><label class="lblobjs">NOTE: Optional</label>&nbsp;&nbsp;<input type="type" size="5" <?php $page->businessobject->items->userfields->draw->text("u_fo_length") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_length") ?>>Birth Length</label>&nbsp;&nbsp;&nbsp;<input type="type" size="5" <?php $page->businessobject->items->userfields->draw->text("u_fo_placentalweight") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_placentalweight") ?>>Placental Weight</label>&nbsp;&nbsp;&nbsp;<input type="type" size="5" <?php $page->businessobject->items->userfields->draw->text("u_fo_apgarscore") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_apgarscore") ?>>APGAR Score</label>&nbsp;&nbsp;&nbsp;<input type="type" size="5" <?php $page->businessobject->items->userfields->draw->text("u_fo_ballardscore") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fo_ballardscore") ?>>Ballard Score</label></td>
	  </tr>
	<tr>
	  <td width="25">&nbsp;</td>
	  <td width="50">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
      </table>
</td></tr>	
