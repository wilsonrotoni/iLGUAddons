<tr><td>
	<div class="tabber" id="tab1">
	  <div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td colspan="5">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","NEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>New</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","RENEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Renewal</label></td>
					</tr>
					<tr>
					  <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >Reference No.</label></td>
					  <td>&nbsp;</td>
					  <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Date of Application</label></td>
				  </tr>
					<tr>
					  <td colspan="2">&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
					  <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
		          </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Public Market Details</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_brand") ?>>Building</label></td>
						<td width="249">&nbsp;</td>
						<td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
						<td width="325">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_publicmarket") ?>></select></td>
					 <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_stallno") ?>>Stall No. /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_section") ?>>Section</label></td>
						<td width="249">&nbsp;</td>
						<td width="249">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td >&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_stallno") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_section") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>					
					<tr><td width="249" colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_stallno") ?>>Rental Fee (</label><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode","A") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode","M") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Monthly )</label></td>
						<td width="325">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_rentalfee") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>					
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_rights") ?>>Rights</label></td>
						<td width="249">&nbsp;</td>
						<td width="249">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_rights") ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>					
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_rightspaymode") ?>>Rights (Mode of Payment)</label></td>
						<td width="249">&nbsp;</td>
						<td width="249">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="3">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_rightspaymode") ?>></select></td>
					  <td>&nbsp;</td>
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
					<tr><td colspan="2"><label class="lblobjs"><b>Lessee Details</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bpno") ?>>Business Permit No</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_tradename") ?>>Business Name</label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bpno") ?>/></td>
					  <td colspan="3">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_tradename") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessnature") ?>>Nature of Business</label></td>
						<td>&nbsp;</td>
						<td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcno") ?>>CTC No.</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcdate") ?>>CTC Date</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcplace") ?>>CTC Place</label></td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_businessnature") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ctcno") ?>/></td>
					  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_ctcdate") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ctcplace") ?>/></td>
			      </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
						<td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
						<td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
						<td width="325"><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
						<td width="325"><label <?php $page->businessobject->items->userfields->draw->caption("u_civilstatus") ?>>Civil Status</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
					  <td>&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_civilstatus") ?>></select></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Address</b></label></td>
						<td colspan="3">&nbsp;</td>
						
					</tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Street</label></td>
						<td>&nbsp;</td>
						<td width="249">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_brgy") ?>>Brgy.</label></td>
						<td >&nbsp;</td>
						<td width="249">&nbsp;</td>
						<td ></td>
						<td ></td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_brgy") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City/Municipality</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_province") ?>>Province</label></td>
						<td width="249">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_telno") ?>>Tel No.</label></td>
						<td>&nbsp;</td>
						<td width="249">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_telno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_email") ?>>Email Address</label></td>
						<td>&nbsp;</td>
						<td width="249">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_email") ?>/></td>
					  <td colspan="3">&nbsp;&nbsp;</td>
				  </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Spouse Details</b></label></td>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_sslastname") ?>>Last Name</label></td>
						<td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_sfirstname") ?>>First Name</label></td>
						<td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_smiddlename") ?>>Middle Name</label></td>
						<td width="325">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_slastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_sfirstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_smiddlename") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
				  
				  <tr><td colspan="2"><label class="lblobjs"><b>Transfer Details</b></label></td>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_tlastname") ?>>Last Name</label></td>
						<td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_tfirstname") ?>>First Name</label></td>
						<td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_tmiddlename") ?>>Middle Name</label></td>
						<td width="325">&nbsp;</td>
						<td width="325">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tlastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tfirstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tmiddlename") ?>/></td>
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
