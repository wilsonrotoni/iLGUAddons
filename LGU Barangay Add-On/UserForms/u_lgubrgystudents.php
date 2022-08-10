<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
       <td width="163" ><label  <?php $page->businessobject->items->draw->caption("docno") ?> >Student No.</label><select style="margin-left:3em" <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td>
		<td width="508"  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
		<td width="307" ></td>
		<td width="249" align="center" rowspan="6">&nbsp;<img id="PhotoImg" height=120 src="<?php echo $photopath; ?>" width=155 align="absmiddle" border=1 onDblClick="uploadPhoto()"></td>
	</tr>
        <tr>
        <td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Lastname</label></td>
		<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
		<td width="307" ></td>
		<td width="9" >&nbsp;</td>
	</tr>
	<tr>
    	<td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>Firstname</label></td>
		<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
		<td ></td>
		<td width="9" align=left>&nbsp;</td>
	</tr>
   
    <tr>
    	<td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middlename</label></td>
		<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>	
    <tr>
    	<td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_regdate") ?>>Registration Date</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_regdate") ?>/></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr> 
     <tr>
    	<td width="163"></td>
		<td>&nbsp;</td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr> 
   
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
					</tr>
					<tr><td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_bday") ?>>Brithday</label></td>
					  <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bday") ?>/></td>
					</tr>
				  <tr><td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
				    <td >&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
					</tr>
                    <tr><td colspan="2"></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="314">&nbsp;</td>
				  </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Address</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="314">&nbsp;</td>
				  </tr>
                  <tr><td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgno") ?>>Bldg No.</label></td>
				    <td width="282"><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgname") ?>>Bldg Name</label></td>
					<td width="271"></td>
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
                  <tr><td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_unitno") ?>>Unit No.</label></td>
				    <td width="282"><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Street</label></td>
					<td width="271"></td>
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
                  <tr><td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_village") ?>>Subdivision</label></td>
				    <td width="282"><label <?php $page->businessobject->items->userfields->draw->caption("u_brgy") ?>>Barangay</label></td>
					<td width="271"></td>
					<td width="206"></td>
						<td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_village") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_brgy") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  <tr><td width="163"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City/Municipality</label></td>
				    <td width="282"></td>
					<td width="271"></td>
					<td width="206"></td>
						<td>&nbsp;</td>
					</tr>
                    <tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                  
		
				</table>
			</div>
		</div>
		<div class="tabbertab" title="Student Guardians">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,260) ?>		

