	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="449" ><label <?php $page->businessobject->items->userfields->draw->caption("u_bplappno") ?>>BPLS Reference No.</label></td>
		<td width="859" align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bplappno") ?>/></td>
		<td  align=left>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
		<td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	</tr>	
    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_inspector") ?>>*Inspector Name</label></td>
		<td  align=left>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_expdate") ?>>Expiry Date</label></td>
		<td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_expdate") ?>/></td>
	</tr>	
    <tr><td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_inspector") ?>/></td>
		<td  align=left>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dateinspect") ?>>Inspection Date</label></td>
		<td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_dateinspect") ?>/></td>
	</tr>	
	</table>
               <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
               		<tr><td colspan="2"><label class="lblobjs">*Business Name</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
               		<tr><td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                    <tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Name of Tax Payer</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>*Last Name</label></td>
						<td width="238"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>*First Name</label></td>
						<td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
						<td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
					  <td>&nbsp;</td>
			      </tr>
                  
                   
					<tr><td colspan="2"><label class="lblobjs"><b>Business Address</b></label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bunittype","Commercial") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bunittype") ?>>Commercial</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bunittype","Residential") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bunittype") ?>>Residential</label></td>
						<td colspan="3"></td>
						
					</tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Bldg No.</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgname") ?>>Bldg Name</label></td>
						<td></td>
						<td></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgname") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Unit No.</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_bstreet") ?>>Street</label></td>
						<td width="170"></td>
						<td></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bunitno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bstreet") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Subdivision</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>*Brgy.</label></td>
						<td width="170"></td>
						<td ></td>
						<td >&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bvillage") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy") ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bcity") ?>>City/Municipality</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_bprovince") ?>>Province</label></td>
						<td width="170"></td>
						<td></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bcity") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bprovince") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_btelno") ?>>Tel No.</label></td>
						<td>&nbsp;</td>
						<td width="170"></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_btelno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bemail") ?>>Email Address</label></td>
						<td>&nbsp;</td>
						<td width="170"></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_bemail") ?>/></td>
					  <td colspan="3">&nbsp;</td>
				  </tr>
					
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					
				</table> 
               
			</div>
		</div>
		
            <div class="tabbertab" title="Requirements">
				
			<?php $objGrids[1]->draw(true); ?>
				
            </div>
            <div class="tabbertab" title="Assessments">
				
			<?php $objGrids[0]->draw(true); ?>
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
				<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
					<td rowspan="3" valign="top">&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks")?></textarea></td>
					<td width="168">&nbsp;</td>
					<td width="168">&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_asstotal") ?>>Total Assessment</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_asstotal") ?>/></td>
				</tr>
				<tr>
				  <td >&nbsp;</td>
				  <td width="168"></td>
					<td width="168">&nbsp;></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr>
				  <td>&nbsp;</td>
				  <td></td>
				  <td></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
			</table>	
	  </div>
     
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,101); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,244) ?>	
<?php $page->resize->addgridobject($objGrids[1],20,244) ?>
<?php //$page->resize->addgridobject($objGrids[2],20,284) ?>	

