<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>    <td width="188" ><label <?php $page->businessobject->items->userfields->draw->caption("u_appnature") ?>>Nature of Application</label></td>
                        <td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature","New Application") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature","New Application") ?>>New Application</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature","Renewal") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature","Renewal") ?>>Renewal</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature","Others") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature","Others") ?>>Others</label>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_appnatureothers") ?>/></td>
                        <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
                        <td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                </tr>
                <tr>    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_istup") ?>>TUP</label></td>
                        <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_istup",null,null,null,null, null) ?>></select></td>
                        <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
                        <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
                </tr>
                <tr>
                    <td ></td>
                    <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isonlineapp",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isonlineapp") ?>>Online Application</label></td>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Application Date</label></td>
                    <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
                </tr>


                <tr>
                    <td ></td>
                    <td>&nbsp;</td>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_insno") ?>>Inspection Order No.</label></td>
                    <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_insno") ?>/></td>
                </tr>
                <tr>
                    <td ></td>
                    <td>&nbsp;</td>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_acctno") ?>>Business Account No</label></td>
                    <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_acctno") ?>/></td>
                </tr>
                <tr> 
                    <td ></td>
                    <td>&nbsp;</td>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplappno") ?>>Business Application No</label></td>
                    <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bplappno") ?>/></td>
                </tr>
        </table>
         <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                    <td width="800" rowspan="8">
                        <div class="tabber" id="tab1">
                                <div class="tabbertab" title="General">
                                        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
                                                <td >&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
                                                <td colspan="2"><label class="lblobjs"><b>Business Address</b></td>
                                            </tr>
                                            <tr>
                                                    <td width="138"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Business Name</label></td>
                                                    <td width="68" align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
                                                    <td width="158" ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>Barangay</label></td>
                                                    <td width="700" align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy",null,null,null,null, "width:150px") ?>></select></td>
                                                
                                            </tr>
                                            <tr>
                                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_authrep") ?>>Authorized Representative</label></td>
                                                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_authrep") ?>/></td>
                                                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Prefix Name</label></td>
                                                    <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/> </td>
                                            </tr>
                                            <tr>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_natureofbusiness") ?>>Nature of Business</label></td>
                                                <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_natureofbusiness") ?>/></td>
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bblock") ?>>Block</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bblock") ?>/> </td>
                                              </tr>
                                            <tr>
                                                <td ><label class="lblobjs"><b>Project Owner/Applicant:</b></label></td>
                                                <td>&nbsp;</td>
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_blotno") ?>>Lot No</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_blotno") ?>/></td>
                                            </tr>
                                            <tr>
												<td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
                                                <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Subdivision</label></td>
                                                <td >&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bvillage") ?>/></td>
                                            </tr>
                                            <tr>
                                                <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
                                                <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Street</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bstreet") ?>/></td>
                                            </tr>
                                            <tr>
                                                <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
                                                <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bphaseno") ?>>Phase No</label></td>
                                                <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bphaseno") ?>/></td>
                                            </tr>
											<tr>
                                                <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_owneraddress") ?>>Address</label></td>
                                                <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_owneraddress") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_owneraddress"); ?></TEXTAREA></td>
                                              
                                            </tr>
											<tr>
                                                <td >&nbsp;&nbsp;</td>
                                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_baddressno") ?>>Address No</label></td>
                                                <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_baddressno") ?>/></td>
                                                
                                            </tr>
											<tr>
                                                <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
                                                <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgname") ?>>Building Name</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bbldgname") ?>/></td>
                                            </tr>
											<tr>
												<td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_contactno") ?>>Contact No.</label></td>
                                                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_contactno") ?>/></td>
                                                 <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Unit No</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bunitno") ?>/></td>
											</tr>
											
                                            <tr>
                                                <td ></td>
                                                <td>&nbsp;</td>
												<td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>Floor No</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bfloorno") ?>/></td>
                                            </tr>
                                            <tr>
                                                <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_orsfamt") ?>>Total Amount</label></td>
                                                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_orsfamt") ?>/></td>
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bcity") ?>>City</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bcity") ?>/></td>
                                            </tr>
                                            <tr>
                                                <td ></td>
                                                <td>&nbsp;</td>
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bprovince") ?>>Province</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bprovince") ?>/></td>
                                            </tr>
                                            <tr>
                                                <td ><label class="lblobjs"><b>Zoning Assessment</b></td>
                                                <td>&nbsp;</td>
                                                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_btelno") ?>>Business Contact No</label></td>
                                                <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_btelno") ?>/></td>
                                            </tr>
                                            
                                            
                                        </table>
										 <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
											<tr><td ></td>
                                                <td colspan="2">&nbsp;&nbsp;<?php $objGrids[0]->draw(true); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
										 </table>
                                </div>
                                <div class="tabbertab" title="Remarks">
                                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA>
                                            </td></tr>
                                    </table> 
                                </div>
<!--                                <div class="tabbertab" title="Assessment">
                                    <?php // $objGrids[0]->draw(true); ?>
                                </div>-->
                        </div>
                    </td>
                </tr>
                        
        </table>
</td></tr>	

 <?php $page->resize->addgridobject($objGrids[0], 1024, 600) ?>
 <?php $page->resize->addinput("u_remarks",30,265); ?>	




