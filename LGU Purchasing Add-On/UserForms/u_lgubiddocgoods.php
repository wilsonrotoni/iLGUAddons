
<tr><td>
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_philgepsno") ?>>Philgeps No</label></td>
                    <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_philgepsno") ?>/></td>
                    <td width="268" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
                    <td width="268" align=left>&nbsp;<input type="text" size = "15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                </tr>
                <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_title") ?>>Project Title</label></td>
                        <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_title") ?>/></td>
                        <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
                        <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
                </tr>
                <tr>
                    <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_euiu") ?>>EUIU</label></td>
                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_euiu") ?>/></td>
                    <td width="268"></td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_abc") ?>>Approved Budget Contract</label></td>
                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_abc") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_abcwords") ?>/></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr> 
                <tr class="fillerRow5px"><td colspan="2"></td></tr>
        </table>
        <div class="tabber" id="tab1">
            <div class="tabbertab" title="Invitation to Bid">
                <div id="divudf" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                         <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_budgetsource") ?>>Budget Source</label></td>
                            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_budgetsource") ?>/> </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_opt1code") ?>>ITB Option 1</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_opt1code") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"></td>
                            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_itbopt1") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_itbopt1"); ?></TEXTAREA></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr><td width="268"></td>
                            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_itbopt1desc") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_itbopt1desc"); ?></TEXTAREA></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_dayduration") ?>>Duration (Days)</label></td>
                            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_dayduration") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_relperiod") ?>>Relevant Period</label></td>
                            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_relperiod") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_opt2code") ?>>ITB Option 2</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_opt2code") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"></td>
                            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_itbopt2") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_itbopt2"); ?></TEXTAREA></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_officehours") ?>>Office Hours</label></td>
                            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_officehours") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_officehours"); ?></TEXTAREA></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_itb1stday") ?>>ITB 1st Day</label></td>
                            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_itb1stday") ?>/></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_biddocsfee") ?>>BidDocs Fee</label></td>
                            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_biddocsfee") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_biddocsfeewords") ?>/></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>	
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_prebid") ?>>Pre Bid</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_prebid") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_prebid"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>	
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_prebiddate") ?>>Pre Bid Date</label></td>
                            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_prebiddate") ?>/>&nbsp;&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_prebidtime") ?>/>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_prebidtime") ?>>Time</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_zoomid") ?>>Zoom Id</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_zoomid") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_zoompass") ?>>Zoom Password</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_zoompass") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_deadlinedate") ?>>Deadline Date</label></td>
                            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_deadlinedate") ?>/>&nbsp;&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_deadlinetime") ?>/>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_deadlinetime") ?>>Time</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_openingdate") ?>>Opening Date</label></td>
                            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_openingdate") ?>/>&nbsp;&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_openingtime") ?>/>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_openingtime") ?>>Time</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_bidevalpostqual") ?>>Bidding Post Qualification</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_bidevalpostqual") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_bidevalpostqual"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    </table>
                </div>
            </div>
<!--            <div class="tabbertab" title="Instruction to Bidders">	
                
            </div>-->
            <div class="tabbertab" title="Bid Data Sheet">
                <div id="divbds" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_ib1items") ?>>IB1 Items</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_ib1items") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_ib1items"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>	
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_lots") ?>>Lots</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_lots") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_lots"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>	
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_opt3code") ?>>BDS 52 Option 3</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_opt3code") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"></td>
                            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_itbopt3") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_itbopt3"); ?></TEXTAREA></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_opt4code") ?>>BDS 54 Option 4</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_opt4code") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"></td>
                            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_itbopt4") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_itbopt4"); ?></TEXTAREA></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                       
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_opt5code") ?>>BDS 81 Option 5</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_opt5code") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"></td>
                            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_itbopt5") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_itbopt5"); ?></TEXTAREA></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_bidvaliduntil") ?>>Bidding Valid Until</label></td>
                            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bidvaliduntil") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_opt6code") ?>>BDS 283 Option 6</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_opt6code") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"></td>
                            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_itbopt6") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_itbopt6"); ?></TEXTAREA></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_similarcontract") ?>>Similar Contract</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_similarcontract") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_similarcontract"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>	 	
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_subconcriteria") ?>>SubCon Criteria</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_subconcriteria") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_subconcriteria"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_bscash") ?>>Bid Surety Cash</label></td>
                            <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_bscash") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bscashwords") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>	
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_bstotal") ?>>Bid Surety Total</label></td>
                            <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_bstotal") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bstotalwords") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_incoterms") ?>>BDS 193 Lots</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_incoterms") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_incoterms"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                       
                        
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_bds292") ?>>BDS 202 PQ Docs</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_bds292") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_bds292"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_bds324faddldocs") ?>>BDS 212 Additional Docs</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_bds324faddldocs") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_bds324faddldocs"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    </table>
                </div>
            </div>
<!--            <div class="tabbertab" title="General Condition of Contract">	
                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">

                </table>
            </div>-->
            <div class="tabbertab" title="Special Conditions of Contract">
                <div id="divscc" style="overflow:auto;">
                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc62head") ?>>SCC 62 Head</label></td>
                            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_scc62head") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_incidental") ?>>Incidental</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_incidental") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_incidental"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc62exstock") ?>>SCC1 Ex Stock</label></td>
                            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_scc62exstock") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc62spareparts") ?>>SCC1 Spare Parts</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc62spareparts") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc62spareparts"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc161tests") ?>>SCC2</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc161tests") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc161tests"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    </table>
                </div>
            </div>
            <div class="tabbertab" title="Abstract of Bid">
                <div id="divabr" style="overflow:auto;">
                      <?php $objGridA->draw(true); ?>	
                </div>
            </div>
            
        </div>
	
</td></tr>	

<?php $page->resize->addtab("tab1",20,180); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addtabpage("tab1","bds") ?>
<?php $page->resize->addtabpage("tab1","scc") ?>
<?php $page->resize->addtabpage("tab1", "abr") ?>
 <?php // $page->resize->addgridobject($objGrids[0], 800, 550) ?>
 <?php // $page->resize->addgridobject($objGrids[1], 800, 550) ?>
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

