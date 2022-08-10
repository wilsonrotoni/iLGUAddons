<tr><td>
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>    <td width="188" ><label <?php $page->businessobject->items->userfields->draw->caption("u_appnature") ?>>Nature of Application</label></td>
                <td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature", "New Construction") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature", "New Construction") ?>>New Construction</label>
                    &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature", "Renovation") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature", "Renovation") ?>>Renovation</label>
                    &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature", "Extension") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature", "Extension") ?>>Extension</label>
                    &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature", "Fence with Gate") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature", "Fence with Gate") ?>>Fence with Gate</label>
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
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
                <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Application Date</label></td>
                <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
            </tr>


            <tr>
                <td ></td>
                <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isonlineapp", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isonlineapp") ?>>Online Application</label></td>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_decisionno") ?>>Decision No.</label></td>
                <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_decisionno") ?>/></td>
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
                                    <td ><label class="lblobjs"><b>Project Owner/Applicant:</b></label></td>
                                    <td >&nbsp;</td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_applicantno") ?>>Applicant No</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_applicantno") ?>/> <a class="button" href="" onClick="AddZoningApplicant();return false;">Add New</a></td>
                                    <td><label class="lblobjs"><b>Location of Construction</b></td>
                                    <td >&nbsp;<b>Google Map View</b>&nbsp;<img id="GoogleMap" height=19 src="../Addons/GPS/BPLS Add-On/UserPrograms/Images/googlemap.jpg" height=20 width=20 align="absmiddle" border=0 onClick="u_googlemapGPSLGUZoning()"></td>
                                    </tr>
                                <tr>
                                    <td width="200">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
                                    <td width="68" align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
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
                                    <td ><label class="lblobjs"><b>Company Name</b></label></td>
                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_corpname") ?>/></td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Building No</label></td>
                                    <td >&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/></td>
                                
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
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_projectname") ?>>Project Name/Title</label></td>
                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_projectname") ?>/></td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>Barangay</label></td>
                                    <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy",null,null,null,null, "width:200px") ?>></select></td>
                                    
                                </tr>

                                <tr>
                                   <td>&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_projtype") ?>>Project Type</label></td>
                                    <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_projtype",null,null,null,null, "width:200px") ?>></select></td>
                                   
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bcity") ?>>City</label></td>
                                    <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bcity") ?>/></td> 

                                </tr>
                                <tr>
                                    <td>&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_class") ?>>Classification</label></td>
                                    <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_class",null,null,null,null, "width:200px") ?>></select></td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bprovince") ?>>Province</label></td>
                                    <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bprovince") ?>/></td>
                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_totflrareabldg") ?>>Total Floor Area(sqm)</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totflrareabldg") ?>/></td>
                                    <td ></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_lotarea") ?>>Total Lot Area(sqm)</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lotarea") ?>/></td>
                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_landtdno") ?>>Land TD No</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_landtdno") ?>/></td>
                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_noofflrs") ?>>No of Storey</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_noofflrs") ?>/></td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgtdno") ?>>Building TD No</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgtdno") ?>/></td>

                                </tr>

                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_surveycount") ?>>Survey Count</label></td>
                                    <td> &nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_surveycount") ?>/></td>
                                     <td ></td>
                                    <td>&nbsp;</td>

                                </tr>
                                <tr>
                                    <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_rightoverland") ?>>Right Over Land</label></td>
                                    <td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_rightoverland", "OWNER") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_rightoverland", "OWNER") ?>>OWNER</label>
                                        &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_rightoverland", "LESSEE") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_rightoverland", "LESSEE") ?>>LESSEE</label>    </td>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tctno") ?>>TCT No.</label></td>
                                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tctno") ?>/></td>

                                </tr>
                                <tr>
                                    <td ></td>
                                    <td>&nbsp;</td>
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
                                <div class="tabbertab" title="Evaluation">
                                     <div id="divevaluation" style="overflow:auto;">
                                         <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr >
                                                <td width="350"><label class="lblobjs"><b>Distances of surrounding properties</b></label></td>
                                                <td width="30"><label <?php $page->businessobject->items->userfields->draw->caption("u_front") ?>>Front</label></td>
                                                <td width="118">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_front") ?>/></td>
                                                <td width="30"><label <?php $page->businessobject->items->userfields->draw->caption("u_left") ?>>Left</label></td>
                                                <td width="118">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_left") ?>/></td>
                                                <td width="30"><label <?php $page->businessobject->items->userfields->draw->caption("u_right") ?>>Right</label></td>
                                                <td width="118">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_right") ?>/></td>
                                                <td width="30"><label <?php $page->businessobject->items->userfields->draw->caption("u_back") ?>>Back</label></td>
                                                <td width="900">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_back") ?>/></td>
                                            </tr>
                                            <tr >
                                                <td ><label class="lblobjs"><b>Existing Land Uses Abutting Land Boundaries of Project</b></label></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_north") ?>>North</label></td>
                                                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_north") ?>/></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_south") ?>>South</label></td>
                                                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_south") ?>/></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_east") ?>>East</label></td>
                                                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_east") ?>/></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_west") ?>>West</label></td>
                                                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_west") ?>/></td>
                                            </tr>
                                            <tr >
                                                <td ><label class="lblobjs"><b>Geographical Coordinates</b></label></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_latitude") ?>>Latitude</label></td>
                                                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_latitude") ?>/></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_longitude") ?>>Longitude</label></td>
                                                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_longitude") ?>/></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_altitude") ?>>Altitude</label></td>
                                                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_altitude") ?>/></td>
                                                <td ></td>
                                                <td >&nbsp;</td>
                                            </tr>
                                           
                                        </table>
                                         
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
<?php $page->resize->addtabpage("tab1", "evaluation") ?>
<?php $page->resize->addtabpage("tab1", "approval") ?>
<?php $page->resize->addtabpage("tab1", "remarks") ?>
<?php $page->resize->addgridobject($objGrids[0], 550, 400) ?>
<?php $page->resize->addgridobject($objGrids[1], 200, 400) ?>
<?php $page->resize->addinput("u_remarks", 30, 240); ?>	




