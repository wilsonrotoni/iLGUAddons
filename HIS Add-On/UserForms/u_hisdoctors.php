<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td colspan="5">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_active",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_active") ?>>Active</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_resident",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_resident") ?>>Resident</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_vatable",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_vatable") ?>>Vatable</label></td>
		  <td width="168" rowspan="7" align="center"><?php if ($photopath!="") { ?><img id="PhotoImg" height=120 src="<?php echo $photopath; ?>" width=120 align="absmiddle" border=1 onDblClick="uploadPhotoGPSHIS()"><?php } ?></td>
	  </tr>
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Doctor No.</label></td>
			<td width="152">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		    <td colspan="3">&nbsp;<input type="text" size="47" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		    <td width="168">&nbsp;</td>
		</tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Name</label></td>
		  <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/>,</td>
		  <td width="152">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
		  <td width="50">&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_extname") ?>/></td>
		  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td>&nbsp;</td>
		  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
		  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
		  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_extname") ?>>Ext.</label></td>
		  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
		  <td valign="top">&nbsp;</td>
	  </tr>
		
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_titles") ?>>Title</label></td>
			<td colspan="4">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_titles") ?>/></td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_specialization") ?>>Specialization</label></td>
			<td colspan="4">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_specialization") ?>/></td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_mobileno") ?>>Mobile No.</label></td>
			<td colspan="4">&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_mobileno") ?>/></td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_birthdate") ?>>Date of Birth</label></td>
			<td colspan="4">&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_birthdate") ?>/></td>
		  <td ><label class="lblobjs"><b>Examination Reader:</b></label></td>
				<td >&nbsp;</td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_tinno") ?>>TIN No.</label></td>
			<td colspan="4">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tinno") ?>/></td>
		  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_pathologistflag"]) ?> >Pathologist</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_pathologistflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_medtechflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_pathologistflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_pathologistflag"]) ?> >Yes</label></td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_pmccno") ?>>PMCC No.</label></td>
			<td colspan="4">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_pmccno") ?>/></td>
		  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_radiologistflag"]) ?> >Radiologist</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_radiologistflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_medtechflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_radiologistflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_radiologistflag"]) ?> >Yes</label></td>
		 </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_prcno") ?>>PRC No.</label></td>
			<td colspan="4">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_prcno") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_prcexpiredate") ?>>/ Validity</label>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_prcexpiredate") ?>/></td>
		  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_cardiologistflag"]) ?> >Cardiologist</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cardiologistflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_medtechflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cardiologistflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cardiologistflag"]) ?> >Yes</label></td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_s2no") ?>>S2 No.</label></td>
			<td colspan="4">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_s2no") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_s2expiredate") ?>>/ Validity</label>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_s2expiredate") ?>/></td>
		  <td>&nbsp;</td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_pmano") ?>>PMA No.</label></td>
			<td colspan="4">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_pmano") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_pmaexpiredate") ?>>/ Validity</label>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_pmaexpiredate") ?>/></td>
		  <td colspan="2"><label class="lblobjs"><b>Default Professional Fee:</b></label></td>
		  
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_ptrno") ?>>PTR No.</label></td>
			<td colspan="4">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ptrno") ?>/></td>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_dfltprfcode") ?>>Specialization</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltprfcode") ?>/></select></td>
	  </tr>
      
		<tr>
		   <td><label <?php $page->businessobject->items->userfields->draw->caption("u_panexpiredate") ?>>PAN Validity</label></td>
			<td colspan="4">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_panexpiredate") ?>/></td>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_dfltprfamount") ?>>Amount</label></td>
			<td colspan="4">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_dfltprfamount") ?>/></td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_wtaxcode") ?>>WTax</label></td>
			<td colspan="4">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_wtaxcode") ?>/></select></td>
		  <td>&nbsp;</td>
			<td >&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Health Insurance">
			<?php $objGrids[0]->draw(true); ?>
		</div>
	</div>
</td></tr>		

<?php // $page->resize->addtab("tab1",-1,141); ?>
<?php // $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,463) ?>		
<?php //$page->resize->addgridobject($objGrids[1],20,383) ?>		

