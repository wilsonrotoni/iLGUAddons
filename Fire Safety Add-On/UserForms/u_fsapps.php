<tr><td>
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>    <td width="188" ><label <?php $page->businessobject->items->userfields->draw->caption("u_appnature") ?>>Nature of Application</label></td>
                <td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature", "FSEC") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature", "FSEC") ?>>Fire Safety Evaluation Clearance</label>
                    &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature", "FSIC") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature", "FSIC") ?>>Fire Safety Inspection Certificate</label>
                    &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature", "Others") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature", "Others") ?>>Others</label>
                    &nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_appnatureothers") ?>/></td>
                <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries", array("loaddocseries", $page->objectcode, "-1:Manual")) ?> ></select></td></tr></table></td>
                <td width="168" align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
            </tr>
            <tr>    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgappno") ?>>Building Application No</label></td>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgappno") ?>/></td>
                <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
                <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus", array("loadenumdocstatus", "", "")) ?> ></select></td>
            </tr>
            <tr>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fsecno") ?>>FSEC No.</label></td>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_fsecno") ?>/>&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isonlineapp", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isonlineapp") ?>>Online Application</label></td>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Application Date</label></td>
                <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
            </tr>


            <tr>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fsicno") ?>>FSIC No</label></td>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_fsicno") ?>/></td>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Application No.</label></td>
                <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
            </tr>

        </table>
        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
                <td width="800" rowspan="8">
                    <div class="tabber" id="tab1">
                        <div class="tabbertab" title="General">
                            <div id="divgeneral" style="overflow:auto;">
                                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                              
                                <tr>
                                    <td><label class="lblobjs"><b>Year</b></label></td>
                                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><label class="lblobjs"><b>Classification</b></label></td>
                                    <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_classification",null,null,null,null, "width:320px") ?>></select></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td ><label class="lblobjs"><b>Project Owner/Applicant:</b></label></td>
                                    <td >&nbsp;</td>
                                     <td><label class="lblobjs"><b>Project Location</b></td>
                                    <td >&nbsp;<b>Google Map View</b>&nbsp;<img id="GoogleMap" height=19 src="../Addons/GPS/BPLS Add-On/UserPrograms/Images/googlemap.jpg" height=20 width=20 align="absmiddle" border=0 onClick="u_googlemapGPSLGUFireSafety()"></td>
                                    
                                </tr>
                                <tr>
                                    <td width="250">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
                                    <td width="500" align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
                                    <td width="200" ><label <?php $page->businessobject->items->userfields->draw->caption("u_bphaseno") ?>>Phase No</label></td>
                                    <td width="700" align=left>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bphaseno") ?>/> </td>
                                
                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bblock") ?>>Block</label></td>
                                    <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bblock") ?>/> </td>
                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_blotno") ?>>Lot No</label></td>
                                    <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_blotno") ?>/> </td>
                                </tr>
                                 <tr>
                                    <td ><label class="lblobjs"><b>Address</b></label></td>
                                    <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_owneraddress") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_owneraddress"); ?></TEXTAREA></td>
                               
                                 </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgname") ?>>Building Name</label></td>
                                    <td >&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bbldgname") ?>/></td                                </tr>
                                <tr>
                                <tr>
                                    <td ><label class="lblobjs"><b>Authorized Representative</b></label></td>
                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_authrep") ?>/></td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Building No</label></td>
                                    <td >&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/></td>
                                
                                </tr> 
                                <tr>
                                    <td ><label class="lblobjs"><b>Company Name</b></label></td>
                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_corpname") ?>/></td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Unit No</label></td>
                                    <td >&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bunitno") ?>/></td>
                                
                                </tr> 
                                <tr>
                                    <td ><label class="lblobjs"><b>Company Address</b></label></td>
                                    <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_corpaddress") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_corpaddress"); ?></TEXTAREA></td>
                                 </tr>
                               
                                <tr>
                                    <td>&nbsp;</td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Subdivision</label></td>
                                    <td >&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bvillage") ?>/></td                                </tr>
                                <tr>
                                    <td ><label class="lblobjs"><b>Project Details</b></label></td>
                                    <td>&nbsp;</td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Street</label></td>
                                    <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bstreet") ?>/></td>

                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_contractorname") ?>>Contractor Name</label></td>
                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_contractorname") ?>/></td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>Barangay</label></td>
                                    <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy",null,null,null,null, "width:200px") ?>></select></td>
                                    
                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_projectname") ?>>Project Name/Title</label></td>
                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_projectname") ?>/></td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bcity") ?>>City</label></td>
                                    <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bcity") ?>/></td> 

                                </tr>

                                <tr>
                                  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_totflrareabldg") ?>>Total Floor Area(sqm)</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totflrareabldg") ?>/></td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bprovince") ?>>Province</label></td>
                                    <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bprovince") ?>/></td>
                                  <td ></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                   <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_lotarea") ?>>Total Lot Area(sqm)</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lotarea") ?>/></td>
                                   </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_noofflrs") ?>>No of Storey</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_noofflrs") ?>/></td>
                                   <td><label <?php $page->businessobject->items->userfields->draw->caption("u_landtdno") ?>>Land TD No</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_landtdno") ?>/></td>
                                
                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgtdno") ?>>Building TD No</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgtdno") ?>/></td>

                                   </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tctno") ?>>TCT No.</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tctno") ?>/></td>

                                </tr>
                                            
                            </table>
                            </div>
                            
                                
                                </div>
                                <div class="tabbertab" title="Assessments">
                                    <div id="divassessment" style="overflow:auto;">
                                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr><td ></td>
                                            <td colspan="2">&nbsp;&nbsp;<?php $objGrids[0]->draw(true); ?></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                                <div class="tabbertab" title="Requirements">
                                     <div id="divrequirements" style="overflow:auto;">
                                         
                                        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr><td ></td>
                                                <td colspan="2">&nbsp;&nbsp;<?php $objGrids[1]->draw(true); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                     </div>
                                        
                                </div>
                                <div class="tabbertab" title="Approval">
                                    <div id="divapproval" style="overflow:auto;">
                                        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                            <tr>
                                              <td colspan="4"><label <?php $page->businessobject->items->userfields->draw->caption("u_encodedby") ?>><b>ENCODED BY:</b></label></td>
                                              <td colspan="4"><label <?php $page->businessobject->items->userfields->draw->caption("u_assessedby") ?>><b>ASSESSED BY:</b></label></td>
                                              <td>&nbsp;</td>
                                          </tr>
                                            <tr><td colspan="2" align="center"><select <?php $page->businessobject->items->userfields->draw->select("u_encodedby",null,null,null,null,"width:290px") ?>></select></td>
                                                <td colspan="2" align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_encodeddate") ?>/></td>
                                                <td colspan="2" align="center"><select <?php $page->businessobject->items->userfields->draw->select("u_assessedby",null,null,null,null,"width:290px") ?>></select></td>
                                                <td colspan="2" align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_assesseddate") ?>/></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_encodedby") ?>>Name</label></td>
                                              <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_encodeddate") ?>>Date</label></td>
                                              <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_assessedby") ?>>Name</label></td>
                                              <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_assesseddate") ?>>Date</label></td>
                                              <td>&nbsp;</td>
                                          </tr>
                                            <tr>
                                              <td colspan="8"><label class="lblobjs"><b>APPROVED BY:</b></label></td>
                                              <td>&nbsp;</td>
                                          </tr>
                                            <tr><td colspan="2" align="center"><select <?php $page->businessobject->items->userfields->draw->select("u_approvedby",null,null,null,null,"width:290px") ?>></select></td>
                                                <td colspan="2" align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_approveddate") ?>/></td>
                                                <td align="center"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                                <tr>
                                                  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_approvedby") ?>>Name</label></td>
                                                  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_approveddate") ?>>Date</label></td>
                                                  <td align="center"></td>
                                                  <td>&nbsp;</td>
                                                  <td>&nbsp;</td>
                                                  <td>&nbsp;</td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                            <tr>
                                              <td colspan="8"><label class="lblobjs"><b>RECEIVED BY:</b></label></td>
                                              <td>&nbsp;</td>
                                            </tr>
                                            <tr><td colspan="2" align="center"><input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_receivedby") ?>/></td>
                                                <td colspan="2" align="center"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_receiveddate") ?>/></td>
                                                <td align="center"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                                <tr>
                                                  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_receivedby") ?>>Name</label></td>
                                                  <td colspan="2" align="center"><label <?php $page->businessobject->items->userfields->draw->caption("u_receiveddate") ?>>Date</label></td>
                                                  <td align="center"></td>
                                                  <td>&nbsp;</td>
                                                  <td>&nbsp;</td>
                                                  <td>&nbsp;</td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                
                                           
                                            </table> 
                                        </div>
                                </div>
                               
                                <div class="tabbertab" title="Remarks">
                                    <div id="divremarks" style="overflow:auto;">
                                        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA>
                                                </td></tr>
                                        </table> 
                                    </div>
                                </div>
<!--                                <div class="tabbertab" title="Assessment">
                        <?php // $objGrids[0]->draw(true); ?>
                                </div>-->
                        </div>
                    </td>
                </tr>
                        
        </table>
        
         <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr class="fillerRow5px"><td></td></tr>
            <tr>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>><b>Total Amount</b></label></td>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
            </tr>
            
        </table>    
</td></tr>	

<?php $page->resize->addtab("tab1", -1, 240); ?>
<?php $page->resize->addtabpage("tab1", "general") ?>
<?php $page->resize->addtabpage("tab1", "assessment") ?>
<?php $page->resize->addtabpage("tab1", "requirements") ?>
<?php $page->resize->addtabpage("tab1", "approval") ?>
<?php // $page->resize->addtabpage("tab1", "approval") ?>
<?php $page->resize->addtabpage("tab1", "remarks") ?>
<?php $page->resize->addgridobject($objGrids[0], 550, 400) ?>
<?php $page->resize->addgridobject($objGrids[1], 200, 400) ?>
<?php $page->resize->addinput("u_remarks", 30, 240); ?>	




