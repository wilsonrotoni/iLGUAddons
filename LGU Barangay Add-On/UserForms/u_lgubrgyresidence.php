	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
      
                <tr><td width="252"><label <?php $page->businessobject->items->draw->caption("docno") ?> >Resident No.</label></td>
				  <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Application Date</label></td>
				  <td width="243"></td>
				  <td width="206"></td>
						<td rowspan="5" align="center">&nbsp;<?php if ($photopath!="") { ?><img id="PhotoImg" height=120 src="<?php echo $photopath; ?>" width=120 align="absmiddle" border=1 onDblClick="uploadPhoto()"><?php } ?></td>
					</tr>
                <tr><td width="252">&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
				  <td width="221">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
				  <td width="243"></td>
				  <td width="206"></td>
					</tr>
                    <tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_residentsince") ?>>Resident Since</label></td>
					  <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_idissueddate") ?>>Brgy. ID Date Issued</label></td>
					  <td width="243"><label <?php $page->businessobject->items->userfields->draw->caption("u_idexpdate") ?>>Brgy. ID Date Expiry</label></td>
					  <td width="206"></td>
					</tr>
                    <tr><td width="252">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_residentsince") ?>/></td>
					  <td width="221">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_idissueddate") ?>/></td>
					  <td width="243">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_idexpdate") ?>/></td>
					  <td width="206"></td>
					</tr>
                <tr><td colspan="2"><label class="lblobjs"><b>Resident Name</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
				  </tr>
					<tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>*Last Name</label></td>
					  <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>*First Name</label></td>
					  <td width="243"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
					  <td width="206"></td>
					  <td align="center"><?php if ($photopath!="") { ?><img id="CameraImg" height=19 src="../Addons/GPS/BPLS Add-On/UserPrograms/Images/camera.jpg" width=20 align="absmiddle" border=0 onClick="takePhoto()"><?php } ?></td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  <tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_dateofbirth") ?>>Birthdate</label></td>
				    <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
					<td width="243"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
					<td width="206"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dateofbirth") ?>/></td>
					  <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  <tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_citizenship") ?>>Citizenship</label></td>
				    <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_bloodtype") ?>>Blood Type</label></td>
					<td width="243"><label <?php $page->businessobject->items->userfields->draw->caption("u_civilstatus") ?>>Civil Status</label></td>
					<td width="206"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_citizenship") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bloodtype") ?>></select></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_civilstatus") ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  <tr><td colspan="2"><label class="lblobjs"><b>Resident Address</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="314">&nbsp;</td>
				  </tr>
                  <tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgno") ?>>Bldg No.</label></td>
				    <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgname") ?>>Bldg Name</label></td>
					<td width="243"></td>
					<td width="206"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgname") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  <tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_unitno") ?>>Unit No.</label></td>
				    <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Street</label></td>
					<td width="243"></td>
					<td width="206"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_unitno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  <tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_village") ?>>Subdivision</label></td>
				    <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_sitio") ?>>Purok/Sitio</label></td>
					<td width="243"></td>
					<td width="206"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_village") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_sitio") ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  <tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_brgy") ?>>Barangay</label></td>
				    <td width="221"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City/Municipality</label></td>
					<td width="243"></td>
					<td width="206"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_brgy") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  
                  <tr><td colspan="2"><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isvoter",1) ?>/><label class="lblobjs"><b>Voter's Information</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="314">&nbsp;</td>
				  </tr>
					
					
					<tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_voterid") ?>>Voters ID No.</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_precinctno") ?>>Precinct</label></td>
					</tr>
					<tr><td width="252"><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_voterid") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_precinctno") ?>/></td>
					</tr>
					<tr><td width="252"><label <?php $page->businessobject->items->userfields->draw->caption("u_voteraddress") ?>>Voters Address</label></td>
					  <td >&nbsp;</td>
					</tr>
                    <tr><td colspan="2"><textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_voteraddress") ?>/><?php echo $page->getitemstring("u_voteraddress")?></textarea></td>
					  <td >&nbsp;</td>
					</tr>
				</table>
			</div>
		</div>
        <div class="tabbertab" title="Contact Person">
			<div id="divudf" style="overflow:auto;">
            </div>
        </div>
        <div class="tabbertab" title="Transaction History">
			<div id="divudf" style="overflow:auto;">
                             <?php $objGrids[0]->draw(true); ?>
            </div>
        </div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,60); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,-1) ?>

