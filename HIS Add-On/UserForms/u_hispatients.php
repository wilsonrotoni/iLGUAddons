<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td >&nbsp;</td>
		  <td colspan="5">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_active",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_active") ?>>Active</label>&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_newbornstat",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_newbornstat") ?>>Newly Born</label>&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label>&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_expired",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_expired") ?>>Expired</label></td>
		  <td width="168" rowspan="5" align="center" valign="top"><img id="PhotoImg" height=110 src="<?php echo $photopath; ?>" width=138 align="absmiddle" border=1 <?php if (isEditMode()) { ?> onDblClick="uploadPhotoGPSHIS()" <?php } ?>></td>
	  </tr>
		<tr><td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="90"><label <?php $page->businessobject->items->draw->caption("code") ?> >Patient No.</label></td><td align="right"><?php if($page->settings->data["autogenerate"]==1) { ?><select <?php $page->businessobject->items->userfields->draw->select("u_series",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select><?php } ?></td></tr></table></td>
			<td width="128">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		    <td colspan="4">&nbsp;<input type="text" size="41" <?php $page->businessobject->items->draw->text("name") ?> /></td>
	    </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Name</label></td>
		  <td>&nbsp;<input type="text" size="15"  <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
		  <td width="128">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
		  <td width="48">&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_extname") ?>/></td>
		  <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
		  <td width="168">&nbsp;</td>
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
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
			<td colspan="4">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>/></select></td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_civilstatus") ?>>Civil Status</label></td>
			<td colspan="4">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_civilstatus") ?>/></select></td>
		  <td >&nbsp;</td>
          <td align="center"><img id="CameraImg" height=19 src="../Addons/GPS/HIS Add-On/UserPrograms/Images/camera.jpg" width=20 align="absmiddle" border=0 onClick="takePhotoGPSHIS()"></td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_birthdate") ?>>Date / Time of Birth</label></td>
			<td colspan="4">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_birthdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_birthtime") ?>/></td>
		  <td>&nbsp;</td>
			<td align="center"></td>
	  </tr>
		<tr>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_birthplace") ?>>Place of Birth</label></td>
			<td colspan="4">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_birthplace") ?>/></td>
		  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_religion") ?>>Religion</label></td>
			<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_religion") ?>/></td>
	  </tr>
	  
	      <tr>
	        <td><label <?php $page->businessobject->items->userfields->draw->caption("u_nationality") ?>>Nationality</label></td>
			<td colspan="4">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_nationality") ?>/></td>
	        <td><label <?php $page->businessobject->items->userfields->draw->caption("u_occupation") ?>>Occupation</label></td>
			<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_occupation") ?>/></td>
      </tr>
    </table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Visits">
			<?php $objGrids[0]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Examinations">
			<?php $objGridExams->draw(true); ?>
		</div>
		<div class="tabbertab" title="Background Details">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td >&nbsp;<label class="xlblobjs"><i>Address (Street)</i></label></td>
					  <td >&nbsp;<label class="xlblobjs"><i>Barangay</i></label></td>
					  <td >&nbsp;<label class="xlblobjs"><i>Town/City</i></label></td>
					  <td >&nbsp;<label class="xlblobjs"><i>Zip</i></label></td>
					  <td >&nbsp;<label class="xlblobjs"><i>State/Province</i></label></td>
					  <td>&nbsp;<label class="xlblobjs"><i>Tel. No.</i></label></td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td >&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_barangay") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
					  <td >&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_zip") ?>/></td>
					  <td >&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
					  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_telno") ?>/></td>
				  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fathername") ?>>Father</label></td>
					  <td>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_fathername") ?>/></td>
					  <td ><a class="links" title="Same as patient address" href="" onClick="setFatherAddressGPSHIS();return false;">^</a><input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_fatherstreet") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_fatherbarangay") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_fathercity") ?>/></td>
					  <td >&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_fatherzip") ?>/></td>
					  <td >&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_fatherprovince") ?>/></td>
					  <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_fathertelno") ?>/></td>
					</tr>  
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_mothername") ?>>Mother</label></td>
					  <td>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_mothername") ?>/></td>
					  <td ><a class="links" title="Same as patient address" href="" onClick="setMotherAddressGPSHIS();return false;">^</a><input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_motherstreet") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_motherbarangay") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_mothercity") ?>/></td>
					  <td >&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_motherzip") ?>/></td>
					  <td >&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_motherprovince") ?>/></td>
					  <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_mothertelno") ?>/></td>
					</tr>  
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_spousename") ?>>Spouse</label></td>
					  <td>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_spousename") ?>/></td>
					  <td ><a class="links" title="Same as patient address" href="" onClick="setSpouseAddressGPSHIS();return false;">^</a><input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_spousestreet") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_spousebarangay") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_spousecity") ?>/></td>
					  <td >&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_spousezip") ?>/></td>
					  <td >&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_spouseprovince") ?>/></td>
					  <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_spousetelno") ?>/></td>
					</tr>  
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_employername") ?>>Employer</label></td>
					  <td>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_employername") ?>/></td>
					  <td >&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_employerstreet") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_employerbarangay") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_employercity") ?>/></td>
					  <td >&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_employerzip") ?>/></td>
					  <td >&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_employerprovince") ?>/></td>
					  <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_employertelno") ?>/></td>
					</tr>  
					<tr>
					  <td colspan="2"><label class="lblobjs"><b>Whom to Notify in Case of Emergency</b></label></td>
					  <td  colspan="5">&nbsp;</td>
					  <td >&nbsp;</td>
					</tr>  
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_contactname") ?>>Name</label></td>
					  <td>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_contactname") ?>/></td>
					  <td ><a class="links" title="Same as patient address" href="" onClick="setContactAddressGPSHIS();return false;">^</a><input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_contactstreet") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_contactbarangay") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_contactcity") ?>/></td>
					  <td >&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_contactzip") ?>/></td>
					  <td >&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_contactprovince") ?>/></td>
					  <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_contacttelno") ?>/></td>
					</tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_contactrelationship") ?>>Relation</label></td>
					  <td >&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_contactrelationship") ?>/></td>
					  <td  colspan="5">&nbsp;</td>
					  <td >&nbsp;</td>
				  </tr>  
					<tr>
					  <td colspan="2"><label class="lblobjs"><b>Responsible for Hospital Account</b></label></td>
					  <td  colspan="5">&nbsp;</td>
					  <td >&nbsp;</td>
					</tr>  
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_responsiblename") ?>>Name</label></td>
					  <td>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_responsiblename") ?>/></td>
					  <td ><a class="links" title="Same as patient address" href="" onClick="setResponsibleAddressGPSHIS();return false;">^</a><input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_responsiblestreet") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_responsiblebarangay") ?>/></td>
					  <td >&nbsp;<input type="text" size="14" <?php $page->businessobject->items->userfields->draw->text("u_responsiblecity") ?>/></td>
					  <td >&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_responsiblezip") ?>/></td>
					  <td >&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_responsibleprovince") ?>/></td>
					  <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_responsibletelno") ?>/></td>
					</tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_responsibleemployer") ?>>Employer</label></td>
					  <td  >&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_responsibleemployer") ?>/></td>
					  <td  colspan="5">&nbsp;</td>
					  <td >&nbsp;</td>
				  </tr>  
				</table>
			</div>	
		</div>
		<div class="tabbertab" title="Allergic to Medicines">
			<?php $objGrids[2]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Final Diagnosis">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td><?php $objGrids[3]->draw(true) ?></td>
					<td ><textarea cols="75" rows="12" <?php $page->businessobject->items->userfields->draw->textarea("u_finaldiagnosis") ?>rows="2"><?php echo $page->getitemstring("u_finaldiagnosis"); ?></textarea></td>
				</tr>
			</table>			
		</div>
		<div class="tabbertab" title="Health Insurance">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td><?php $objGrids[1]->draw(true) ?></td>
					<td width="18">
						<div><a class="button2" href="" onClick="u_moveRowUpHealthBenefitsGPSHIS('T2');return false;" ><img src="imgs/asc_<?php echo $_SESSION["theme"] ?>.gif" border="0"></a></div>
						<div><a class="button2" href="" onClick="u_moveRowDnHealthBenefitsGPSHIS('T2');return false;" ><img src="imgs/desc_<?php echo $_SESSION["theme"] ?>.gif" border="0"></a></div>
					</td>
				</tr>
			</table>			
		</div>
	</div>
</td></tr>		

<?php  $page->resize->addtab("tab1",-1,280); ?>
<?php  $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,283) ?>	
<?php $page->resize->addgridobject($objGrids[1],28,319) ?>	
<?php $page->resize->addgridobject($objGrids[2],20,283) ?>	
<?php $page->resize->addgridobject($objGrids[3],-1,319) ?>	
<?php $page->resize->addgridobject($objGridExams,20,283) ?>	
