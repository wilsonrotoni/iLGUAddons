


<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>License No.</label></td>  </td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /> </td>
		</tr>
		
				
		<tr><td ><label <?php $page->businessobject->items->draw->caption("u_appdate") ?> >Date of Registration<label></td>
			<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
		</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="Violator's Details">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_birthday") ?>>Birthday</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_birthday") ?>/></td>
					</tr>
					
					<tr><td colspan="2"></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
					<tr>
					 <tr><td colspan="2"><label class="lblobjs"><b>Address</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
					
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_houseno") ?>>House No.</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_houseno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Street</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
					</tr>
						<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_barangay") ?>>Barangay</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_barangay") ?>></select></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_municipality") ?>>Municipality</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_municipality") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_province") ?>>Province</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
					</tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_zipcode") ?>>Zipcode</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_zipcode") ?>/></td>
					</tr>
					<tr><td colspan="2"></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
					<tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Alert & Notifications</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mobileno") ?>>MobileNo</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_mobileno") ?>/></td>
					</tr>
					<tr><td width="168"></td>
						<td>&nbsp;<input type="TEXT" size="50" <?php $page->businessobject->items->userfields->draw->text("u_licenseno") ?>/></td>
						
					</tr>
					<tr><td width="168"></td>
						<td >&nbsp;<input type="TEXT" <?php $page->businessobject->items->draw->text("name") ?> /></td>
					</tr>
					
		
				</table>
			</div>
		</div>
		<div class="tabbertab" title="Record of Violations">
			<div id="divrecords" style="overflow:auto;">
			
			  <!--<table class="tableFreeForm" width="45%" cellpadding="0" cellspacing="0" border="0">
        <tr>
		<td><td>
			
		</tr>
        <tr>
          <td width="138" ><label <?php genCaptionHtml($schema["u_feedesc"],"") ?>>Violation:</label></td>
          <td width="212" align=left><input type="text" <?php genInputTextHtml($schema["u_feedesc"]) ?> /></td>
		  <td width="138" ><label <?php genCaptionHtml($schema["u_appdate"],"") ?>>Date:</label></td>
          <td width="212" align=left><input type="text" <?php genInputTextHtml($schema["u_appdate"]) ?> /></td>
		   <td> <a class="button" href="" onClick="formSearchViolationName();return false;">Search</a></td>
          </tr>
        <tr>
		<tr>
		<td><td>
		<td></td>
		<td></td>
		</tr>
		</table>-->
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					 <tr>
				<td> <?php $objGridB->draw(true); ?>	</td>
            <td>&nbsp; </td>
            <td >&nbsp;</td>
        </tr>
				
					
		
				</table>
			</div>
		</div>
	</div>
</td></tr>	
	
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addtabpage("tab1","records") ?>	
<?php $page->resize->addgridobject($objGridB,20,0) ?>
 
 

