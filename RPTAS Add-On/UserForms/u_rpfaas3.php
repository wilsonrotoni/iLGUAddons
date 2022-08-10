<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                        <td width="187" ><label <?php $page->businessobject->items->userfields->draw->caption("u_prefix") ?>>Property Index No</label></td>
                        <td width="1142" align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_prefix") ?>/><label class="lblobjs">-</label><input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_suffix") ?>/>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_pinchanged",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_pinchanged") ?>>New PIN</label> &nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_temporary",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_temporary") ?>>Temporary PIN</label></td>
                        <td width="200" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
                        <td width="200" align=left>&nbsp;<input type="text"  size="17" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tdno") ?>>Tax Declaration No</label></td>
                        <td  align=left>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_tdno") ?>/> &nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_temporarytdn",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_temporarytdn") ?>>Temporary TD</label></td>
                        <td ></td>
                        <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_cancelled",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cancelled") ?>>Cancelled</label></td>
                </tr>
                <tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_varpno") ?>>ARP Number</label></td>
                        <td align=left>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_varpno") ?>/></td>
                        <td ></td>
                       <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isauction",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isauction") ?>>Auction</label></td>
                </tr>
			   <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_proposedpin") ?>>Proposed PIN</label></td>
                        <td  align=left>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_proposedpin") ?>/></td>
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
					<tr>
					  <td colspan="4">&nbsp;</td>
                                          <td><label <?php $page->businessobject->items->userfields->draw->caption("u_revisionyear") ?>>Revision Year</label></td>
                                          <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_revisionyear",null,null,null,null,null) ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
                                                <td colspan="4"><label class="lblobjs"><b>OWNER</b></label></td> 
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_trxcode") ?>>Transaction Code</label></td>
                                               <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_trxcode",null,null,null,null,"width:200px") ?>></select> &nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_trxcodeothers",null,null,null,null,"width:150px") ?>></select></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="168"><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_ownertype","C") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_ownercompanyname") ?>>Name/s</label></td>
						<td width="168" colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_ownercompanyname") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ownertelno") ?>>Tel No.</label></td>
						<td colspan="3">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_ownertelno") ?>/></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_owneraddress") ?>>Address</label></td>
						<td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_owneraddress") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ownertin") ?>>Local TIN</label></td>
						<td colspan="3">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_ownertin") ?>/>&nbsp;<a class="button" href="" onClick="AddNewTin();return false;">Add New</a></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_email") ?>>Email</label></td>
						<td colspan="3">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_email") ?>/></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_ownerkind") ?>>Owner Type</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_ownerkind") ?>></select></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td colspan="3">&nbsp;</td>
					  <td >&nbsp;</td>
					  <td colspan="3">&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td colspan="4"><label class="lblobjs"><b>ADMINISTRATOR</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                         <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_adminname") ?>>Name/s</label></td>
                                            <td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_adminname") ?>/></td>
                                            <td ></td>
                                            <td colspan="3">&nbsp;</td>
                                            <td>&nbsp;</td>
                                         </tr>
					<tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_adminlastname") ?>>Last/First/Middle Name</label></td>
						<td colspan="3">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_adminlastname") ?>/><label class="lblobjs">,&nbsp;</label><input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_adminfirstname") ?>/>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_adminmiddlename") ?>/></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_admintelno") ?>>Tel No.</label></td>
						<td colspan="3">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_admintelno") ?>/></td>
						<td>&nbsp;</td>
					</tr>
					<tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_adminaddress") ?>>Address</label></td>
						<td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_adminaddress") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_admintin") ?>>TIN</label></td>
						<td colspan="3">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_admintin") ?>/></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="4">&nbsp;</td>
					  <td colspan="3">&nbsp;</td>
					  <td>&nbsp;</td>
					  <td colspan="3">&nbsp;</td>
				  </tr>
                                    <tr>
                                        <td ><label class="lblobjs"><b>PROPERTY LOCATION</b></label></td>
                                        <td colspan="4" >&nbsp;</td>
                                        <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_location") ?>/></td>
                                        <td><label <?php $page->businessobject->items->userfields->draw->caption("u_subdivision") ?>>Subdivision</label></td>
                                        <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_subdivision",null,null,null,null,"width:200px") ?>></select></td>
                                        <td>&nbsp;</td> 
				  </tr>
					<tr>
                                            <td width="188"><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>No./Street</label></td>
                                            <td colspan="3">&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
                                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_oldbarangay") ?>>Old Barangay</label></td>
                                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_oldbarangay") ?>></select></td>
                                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_barangay") ?>>New Barangay</label></td>
                                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_barangay") ?>></select></td>
                                            <td>&nbsp;</td>
					</tr>
					<tr>
                                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_municipality") ?>>Municipality</label></td>
                                            <td colspan="3">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_municipality") ?>/></td>
                                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City/Province</label></td>
                                            <td colspan="3">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/><label class="lblobjs">&nbsp;/&nbsp;</label><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
                                            <td>&nbsp;</td>
					</tr>
					<tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_landowner") ?>>Land Owner</label></td>
						<td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_landowner") ?>/></td>
                        <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_landpin") ?>>Land PIN</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_landpin") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgowner") ?>>Building Owner</label></td>
						<td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_bldgowner") ?>/></td>
                        <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgpin") ?>>Building PIN</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgpin") ?>/></td>
                        <td >&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td></td>
						<td colspan="3">&nbsp;</td>
                        <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_landtdno") ?>>Land Td#</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_landtdno") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td></td>
						<td colspan="3">&nbsp;</td>
                        <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgtdno") ?>>Bldg Td#</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgtdno") ?>/></td>
                        <td >&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="4">&nbsp;</td>
					  <td colspan="3">&nbsp;</td>
					  <td>&nbsp;</td>
					  <td colspan="3">&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
				   </table> 
      </div></div>             
 	  <div class="tabbertab" title="Machinery Assessment">
          <div id="divass" style="overflow:auto;">
                  <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" > 
					<tr><td colspan="9"><label class="lblobjs"><b>PROPERTY APPRAISAL</b></label></td>
					</tr>
                 </table>   
         		<?php $objGridA->draw(true); ?>	
                  <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" > 
					<tr class="fillerRow5px"><td colspan="9"></td>
					</tr>
                 </table>   
         		<?php $objGridB->draw(true); ?>	
                  <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" > 
					<tr><td colspan="9"><label class="lblobjs"><b>PROPERTY ASSESSMENT</b></label></td>
					</tr>
                 </table>   
         		<?php $objGridC->draw(true); ?>	
                  <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" > 
                            <tr class="fillerRow5px"><td colspan="9"></td></tr>
                             <tr>
                                <td colspan="2"><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_taxable","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_taxable") ?>>Taxable</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_taxable","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_taxable") ?>>Exempt</label></td>
                                <td colspan="2" align="right"><label <?php $page->businessobject->items->userfields->draw->caption("u_effdate") ?>>Effectivity of Assessment/Reassessment:</label></td>
                                <td align="center"><input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_effyear") ?>/></td>
                                <td ><select <?php $page->businessobject->items->userfields->draw->select("u_effqtr") ?>></select></td>
                                <td align="right"><label <?php $page->businessobject->items->userfields->draw->caption("u_effdate") ?>>End Year:</label></td>
                                <td align="center"><input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_expyear") ?>/></td>
                                <td ><input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_expqtr") ?>/></td>
                                <td align="right"><label <?php $page->businessobject->items->userfields->draw->caption("u_bilyear") ?>>Last Paid:</label></td>
                                <td align="center">&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_bilyear") ?>/></td>
                                <td align="left">&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_bilqtr") ?>/></td>
                                <td align="center">&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_lastpaymode") ?>/></td>
                             </tr>
                            <tr>
                              <td></td>
                              <td>&nbsp;</td>
                              <td colspan="2">&nbsp;</td>
                              <td align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_effyear") ?>>Year</label></td>
                              <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_effqtr") ?>>Qtr</label></td>
                              <td>&nbsp;</td>
                              <td align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_expyear") ?>>Year</label></td>
                              <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_expqtr") ?>>Qtr</label></td>
                              <td>&nbsp;</td>
                              <td align="center">&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_effyear") ?>>Year</label></td>
                              <td align="left">&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_bilqtr") ?>>Qtr</label></td>
                              <td align="center">&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_lastpaymode") ?>>Mode</label></td>
                            </tr>	
                    </table>
        </div></div>          
	  <div class="tabbertab" title="Machinery Depreciation">
      <?php $objGridD->draw(true); ?>	
      </div>  
	  <div class="tabbertab" title="Superseded Assessment/s">
      <?php $objGrids[1]->draw(true) ?>	
      </div>  
	  <div class="tabbertab" title="Assessors" >
			<div id="divby" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td colspan="4"><label <?php $page->businessobject->items->userfields->draw->caption("u_assessedby") ?>><b>APPRAISED/ASSESSED BY:</b></label></td>
					  <td colspan="4"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendby") ?>><b>RECOMMENDING APPROVAL:</b></label></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td colspan="2" align="center"><select <?php $page->businessobject->items->userfields->draw->select("u_assessedby",null,null,null,null,"width:290px") ?>></select></td>
						<td colspan="2" align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_assesseddate") ?>/></td>
						<td colspan="2" align="center"><select <?php $page->businessobject->items->userfields->draw->select("u_recommendby",null,null,null,null,"width:290px") ?>></select></td>
						<td colspan="2" align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_recommenddate") ?>/></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_assessedby") ?>>Name</label></td>
					  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_assesseddate") ?>>Date</label></td>
					  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendby") ?>>Assistant City/Municipal Assessor</label></td>
					  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommenddate") ?>>Date</label></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td colspan="8"><label class="lblobjs"><b>APPROVED BY:</b></label></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td colspan="2" align="center"><select <?php $page->businessobject->items->userfields->draw->select("u_approvedby",null,null,null,null,"width:290px") ?>></select></td>
						<td colspan="2" align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_approveddate") ?>/></td>
						<td align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_releaseddate") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_approvedby") ?>>City/Provincial Assessor</label></td>
					  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_approveddate") ?>>Date</label></td>
					  <td align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_releaseddate") ?>>Released Date</label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_memoranda") ?>>Memoranda:</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_memoranda") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_memoranda") ?></TEXTAREA></td>
						
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_annotation") ?>>Annotation:</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_annotation") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_annotation") ?></TEXTAREA></td>
						
					</tr>
                                        <tr class="fillerRow5px"><td colspan="9"></td></tr>
					<tr><td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_recordeddate") ?>>Date of Entry in the Record of Assessment:</label></td>
						<td colspan="2" align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_recordeddate") ?>/></td>
						<td width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_recordedby") ?>>By:</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_recordedby") ?>/></td>
						<td width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_bookno") ?>>Book no:</label></td>
						<td colspan="3">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_bookno") ?>/></td>
					</tr>
                   </table> 
                                <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td colspan="8"></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td colspan="2"><label class="lblobjs"><b>Certified True Copy</b></label></td>
					  <td colspan="4"><label class="lblobjs"><b>Transfer Tax</b></label></td>
					  <!--<td>&nbsp;</td>-->
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rpt") ?>>RPT</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_rpt") ?>/></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tt") ?>>TT</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_tt") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
                                                <td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rptor") ?>>OR/Date</label></td>
						<td >&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_rptor") ?>/></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ttor") ?>>OR/Date</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_ttor") ?>/></td>
                                                <td>&nbsp;</td>
						<td>&nbsp;</td>
                                                <td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="168"></td>
						<td>&nbsp;</td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_car") ?>>CAR</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_car") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
                                                <td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
		
				</table>
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td colspan="8"></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td colspan="8"><label class="lblobjs"><b>RECORD OF SUPERSEDED ASSESSMENT</b></label></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_prevpin") ?>>PIN</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_prevpin") ?>/></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prevarpno") ?>>Reference No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_prevarpno") ?>/></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prevtdno") ?>>TD No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_prevtdno") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prevowner") ?>>Owner</label></td>
						<td >&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_prevowner") ?>/></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prevvalue") ?>>Total Value</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_prevvalue") ?>/></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prevarpno2") ?>>ARP No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_prevarpno2") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_preveffdate") ?>>Effectivity</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_preveffdate") ?>/></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prevrecordedby") ?>>Recording Person</label></td>
						<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_prevrecordedby") ?>/></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prevrecordeddate") ?>>Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_prevrecordeddate") ?>/></td>
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
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
		
				</table>
			</div>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,160); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addtabpage("tab1","ass") ?>
<?php $page->resize->addtabpage("tab1","by") ?>
<?php $page->resize->addinput("u_annotation",60,-1) ?>
<?php $page->resize->addinput("u_memoranda",60,-1) ?>
<?php $page->resize->addgridobject($objGridA,40,-1) ?>
<?php $page->resize->addgridobject($objGridC,40,-1) ?>
<?php $page->resize->addgridobject($objGridB,40,-1) ?>
<?php $page->resize->addgridobject($objGridD,40,-1) ?>
<?php $page->resize->addgridobject($objGrids[1],20,350) ?>