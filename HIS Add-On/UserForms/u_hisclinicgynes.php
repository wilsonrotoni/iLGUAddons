<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td colspan="4"><label class="lblobjs"><b>Current Gynecologic Condition / Diagnosis</b></label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_cgcd_location") ?>>Location / Organ Involvement</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_cgcd_location","Solitary") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_cgcd_location","Solitary") ?>>Solitary</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_cgcd_location","Bilateral") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_cgcd_location","Bilateral") ?>>Bilateral</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_cgcd_location","Metastasis") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_cgcd_location","Metastasis") ?>>Metastasis</label></td>
	  </tr>
	
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_cgcd_activity") ?>>Activity</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_cgcd_activity","Benign") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_cgcd_activity","Benign") ?>>Benign</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_cgcd_activity","Malignant, include Stage (FIGO Staging)") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_cgcd_activity","Malignant, include Stage (FIGO Staging)") ?>>Malignant, include Stage (FIGO Staging)</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_cgcd_activity","Histologic Type - if available, if none yet, may write probably Benign/Malignant") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_cgcd_activity","Histologic Type - if available, if none yet, may write probably Benign/Malignant") ?>>Histologic Type - if available, if none yet, may write probably Benign/Malignant</label></td>
	  </tr>
	
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_cgcd_note") ?>>NOTE: Concomittant Medical/Surgical Condition and Status</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_cgcd_note","Controlled") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_cgcd_note","Controlled") ?>>Controlled</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_cgcd_note","Uncontrolled") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_cgcd_note","Uncontrolled") ?>>Uncontrolled</label></td>
	  </tr>
	
	<tr>
	  <td colspan="4"><label class="lblobjs"><b>Management / Intervention</b></label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_mi_scpsp") ?>>State complete primary surgical procedure</label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="type" size="50" <?php $page->businessobject->items->userfields->draw->text("u_mi_scpsp") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_mi_asps") ?>>Additional surgical procedure should also be included</label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="type" size="50" <?php $page->businessobject->items->userfields->draw->text("u_mi_asps") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_mi_asps2","Adhesioslysis") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_mi_asps2","Adhesioslysis") ?>>Adhesioslysis</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_mi_asps2","Cystorrhaphy") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_mi_asps2","Cystorrhaphy") ?>>Cystorrhaphy</label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_mi_fs") ?>>Frozen Section</label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3">&nbsp;<input type="type" size="50" <?php $page->businessobject->items->userfields->draw->text("u_mi_fs") ?>/></td>
	  </tr>
	
	<tr>
	  <td colspan="4"><label class="lblobjs"><b>Pertinent Previous Surgeries</b></label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_pps_history") ?>>* History of the previous pertinent surgeries and its indication must be part of the diagnosis</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="type" size="100" <?php $page->businessobject->items->userfields->draw->text("u_pps_history") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_pps_yrprev") ?>>* Year of the previous surgery is optional</label></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="type" size="4" <?php $page->businessobject->items->userfields->draw->text("u_pps_yrprev") ?>/></td>
	  </tr>
	<tr>
	  <td colspan="4"><label class="lblobjs"><b>Surgical Morbidities</b></label></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_sm") ?>>*Surgical morbidities MAY OR MAY NOT be part of the diagnosis</label>&nbsp;<input type="type" size="30" <?php $page->businessobject->items->userfields->draw->text("u_sm_info") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td align=left colspan="3">&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_sm","Ureteral transection") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_sm","Ureteral transection") ?>>Ureteral transection</label>&nbsp;<input type="radio" size="13" <?php $page->businessobject->items->userfields->draw->radio("u_sm","Urinary bladder tear") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_sm","Urinary bladder tear") ?>>Urinary bladder tear</label></td>
	  </tr>	
	<tr>
	  <td width="25">&nbsp;</td>
	  <td width="50">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
      </table>
</td></tr>	
