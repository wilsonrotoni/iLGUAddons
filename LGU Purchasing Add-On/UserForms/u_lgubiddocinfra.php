
<tr><td>
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_philgepsno") ?>>Philgeps No</label></td>
                <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_philgepsno") ?>/></td>
                <td width="268" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries", array("loaddocseries", $page->objectcode, "-1:Manual")) ?> ></select></td></tr></table></td>
                <td width="268" align=left>&nbsp;<input type="text" size = "15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
            </tr>
            <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_title") ?>>Project Title</label></td>
                <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_title") ?>/></td>
                <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
                <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus", array("loadenumdocstatus", "", "")) ?> ></select></td>
            </tr>
            <tr>
                <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_barangay") ?>>Barangay</label></td>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_barangay") ?>/></td>
                <td width="268"></td>
                <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_istemplate", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_istemplate") ?>>Use as Template</label></td>
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
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_officehours") ?>>Office Hours</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_officehours") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_officehours"); ?></TEXTAREA></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
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
                        
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_opt5code") ?>>ITB Option 5</label></td>
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
<!--                        <tr>
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
                        </tr>-->
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
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_opt6code") ?>>BDS 273 Option 6</label></td>
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
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_bds282") ?>>BDS 20 Permits</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_bds282") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_bds282"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_bds314faddldocs") ?>>BDS 21 Contract Docs</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_bds314faddldocs") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_bds314faddldocs"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>

                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                        <tr>
                            <td width="268"></td>
                            <td><label class="lblobjs"><b>Key Personnel Requirements</b></label></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="268"></td>
                            <td  colspan="3">&nbsp;&nbsp;<?php $objGrids[0]->draw(true); ?></td>
                            <td>&nbsp; </td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                        <tr>
                            <td width="268"></td>
                            <td><label class="lblobjs"><b>Equipment Requirements</b></label></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="268"></td>
                            <td  colspan="3">&nbsp;&nbsp;<?php $objGrids[1]->draw(true); ?></td>
                            <td>&nbsp; </td>
                            <td >&nbsp;</td>
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
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc2") ?>>SCC 2</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc2") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc2"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc41") ?>>SCC 41</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc41") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc41"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc6") ?>>SCC 6</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc6") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc6"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc125warranty") ?>>SCC Warranty</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc125warranty") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc125warranty"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
<!--                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc212arbiter") ?>>SCC 212 Arbiter</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc212arbiter") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc212arbiter"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>-->
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc291dayswork") ?>>SCC Days Work</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc291dayswork") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc291dayswork"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc311pow") ?>>SCC POW</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc311pow") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc311pow"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
<!--                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc311powupd") ?>>SCC 311 POW UPD</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc311powupd") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc311powupd"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>-->
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc313cost") ?>>SCC 112 Cost</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc313cost") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc313cost"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc391adv") ?>>SCC Advance</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc391adv") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc391adv"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc401mats") ?>>SCC Materials</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc401mats") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc401mats"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
<!--                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc404") ?>>SCC 404</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc404") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc404"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>-->
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc511opman") ?>>SCC Operating Machine</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc511opman") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc511opman"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc511asbuilt") ?>>SCC as Built</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_scc511asbuilt") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_scc511asbuilt"); ?></TEXTAREA></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_scc511cost") ?>>SCC 152 Cost</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_scc511cost") ?>/></td>
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

<?php $page->resize->addtab("tab1", 20, 220); ?>
<?php $page->resize->addtabpage("tab1", "udf") ?>
<?php $page->resize->addtabpage("tab1", "bds") ?>
<?php $page->resize->addtabpage("tab1", "scc") ?>
<?php $page->resize->addtabpage("tab1", "abr") ?>
<?php $page->resize->addgridobject($objGrids[0], 800, 550) ?>
<?php $page->resize->addgridobject($objGrids[1], 800, 550) ?>
<?php // $page->resize->addgridobject($objGridA,-10,300) ?>
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

