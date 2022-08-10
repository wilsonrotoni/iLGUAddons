<tr><td>
	<div class="tabber" id="tab1">
	  <div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
                                            <td >&nbsp;
                                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","NEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>New</label>&nbsp;
                                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","RENEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Renewal</label>&nbsp;
                                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","UPDATE") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Update/Transfer</label>
                                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","RETIRED") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Retire/Revoke</label>
                                            </td>
                    <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_special",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_special") ?>>Special Permit</label></td>
					  	<td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_type","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_type") ?>>Permit</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_type","2") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_type") ?>>Franchise</label></td>
                   		<td><label <?php $page->businessobject->items->userfields->draw->caption("u_vehicletype") ?>>Type of Vehicle</label>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_vehicletype") ?>></select></td>
					  	<td>&nbsp;</td>
					</tr>
                                    <tr>
					  <td >&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
                                    </tr>
					<tr>
                                        <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >Permit No.</label></td>
                                        <td><label <?php $page->businessobject->items->userfields->draw->caption("u_franchiseno") ?>>Franchise No.</label></td>
                                        <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Date</label></td>
                                        <td><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
                                        <td>&nbsp;</td>
                                    </tr>
					<tr>
					  <td >&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_franchiseseries",array("loaddocseries","U_MTOPAPPFRANCHISENO","-1:Manual")) ?> ></select>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_franchiseno") ?>/></td>
                                            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
					  <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
					  <td>&nbsp;</td>
		          </tr>
					<tr>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_toda") ?>>TODA</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_route") ?>>Route</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_caseno") ?>>Case No.</label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_toda") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_route") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_caseseries",array("loaddocseries","U_MTOPAPPCASENO","-1:Manual")) ?> ></select>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_caseno") ?>/></td>
					  <td>&nbsp;</td>
				  </tr>					<tr><td colspan="2"><label class="lblobjs"><b>Vehicle Details</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_brand") ?>>Brand</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_plateno") ?>>Plate No.</label></td>
						<td ></td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_brand") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_plateno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_chassisno") ?>>Chassis No.</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_engineno") ?>>Engine No.</label></td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_chassisno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_engineno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Name of Operator</b></label></td>
						<td>&nbsp;</td>
						<td colspan="2"><label class="lblobjs"><b>Name of Driver</b></label></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dlastname") ?>>Last Name</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dfirstname") ?>>First Name</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dmiddlename") ?>>Middle Name</label></td>
						
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dlastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dfirstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dmiddlename") ?>/></td>
			      </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Address</b></label></td>
						<td >&nbsp;</td>
						<td colspan="2"><label class="lblobjs"><b>Address</b></label></td>
                        <td >&nbsp;</td>
					</tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Street</label></td>
						<td>&nbsp;</td>
						<td >&nbsp;</td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dstreet") ?>>Street</label></td>
						
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
					  <td>&nbsp;</td>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_dstreet") ?>/></td>
					  
					  <td>&nbsp;</td>
			      </tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_brgy") ?>>Brgy.</label></td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dbrgy") ?>>Brgy.</label></td>
						<td ></td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_brgy") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					 <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dbrgy") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City/Municipality</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_province") ?>>Province</label></td>
						<td >&nbsp;</td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dcity") ?>>City/Municipality</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_dprovince") ?>>Province</label></td>
						
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dcity") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dprovince") ?>/></td>
					  
			      </tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_telno") ?>>Tel No.</label></td>
						<td>&nbsp;</td>
						<td >&nbsp;</td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dtelno") ?>>Tel No.</label></td>
						
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_telno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;&nbsp;</td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dtelno") ?>/></td>
					  
					  <td>&nbsp;</td>
			      </tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_email") ?>>Email Address</label></td>
						<td>&nbsp;</td>
						<td >&nbsp;</td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_demail") ?>>Email Address</label></td>
						<td>&nbsp;</td>
					</tr>
                                     
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_email") ?>/></td>
					  <td >&nbsp;&nbsp;</td>
                                          <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_demail") ?>/></td>
					  
                                    </tr>
                                    <tr>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcno") ?>>Ctc No.</label></td>
						<td>&nbsp;</td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                  <tr>
					  <td colspan="2">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_ctcno") ?>/></td>
					  <td >&nbsp;&nbsp;</td>
                                          <td colspan="2">&nbsp;</td>
                                  </tr>
				</table>
       	  </div>
      </div>            
		<div class="tabbertab" title="Requirements">
 			<?php $objGrids[1]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Assessments">
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr><td ></td>
					<td>&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_decisiondate") ?>>Decision Date</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_decisiondate") ?>/></td>
				</tr>
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
            </tr>
			</table>	
			<?php $objGrids[0]->draw(true); ?>
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
            </tr>
				<tr><td ></td>
					<td>&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_asstotal") ?>>Total Assessment</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_asstotal") ?>/></td>
				</tr>
			</table>	
      		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,81); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,195) ?>
<?php $page->resize->addgridobject($objGrids[1],20,140) ?>
