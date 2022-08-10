


<tr><td>

        <div class="tabber" id="tab1">
            <div class="tabbertab" title="General">
                <div id="divudf" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td width="175">&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_municipality") ?>>Municipality Name/Code</label></td>
                            <td>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_municipality") ?>/>/<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_municipalitycode") ?>/></td>
                            <td width = "168">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City Name/Code</label></td>
                            <td>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/>/<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_citycode") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_province") ?>>Province Name/Code</label></td>
                            <td>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/>/<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_provincecode") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_queueingprinter") ?>>Queuing Printer</label></td>
                            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_queueingprinter") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gisexportfilename") ?>>RPT GIS Export Filename</label></td>
                            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_gisexportfilename") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplexportfilename") ?>>BPL GIS Export Filename</label></td>
                            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bplexportfilename") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                         <tr><td width="168">&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_requiredreceiptopeningregister", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_requiredreceiptopeningregister") ?>>Required Receipts Opening Balance</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_burialpermitfee") ?>>Burial Permit Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_burialpermitfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcbasicfee") ?>>CTC Basic Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_ctcbasicfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcbrgyfee") ?>>CTC Barangay Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_ctcbrgyfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcgrossfee") ?>>CTC Gross Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_ctcgrossfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_transfertaxfee") ?>>Transfer Tax Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_transfertaxfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_transfertaxintfee") ?>>Transfer Tax Interest Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_transfertaxintfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_franchisetaxfee") ?>>Franchise Tax Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_franchisetaxfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_franchisetaxintfee") ?>>Franchise Tax Interest Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_franchisetaxintfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_processingfee") ?>>Processing Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_processingfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cutofftime") ?>>Cashier Cutoff Time</label></td>
                            <td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_cutofftime") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="tabbertab" title="Business Permit">
                <div id="divbpl" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td width="175">&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_annualtax") ?>>Gross Sales/Annual Taxes</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_annualtax", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mayorspermit") ?>>Mayor's Permit</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_mayorspermit", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_sanitaryinspectionfee") ?>>Sanitary Inspection Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_sanitaryinspectionfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_garbagefee") ?>>Garbage Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_garbagefee", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplbarangayfee") ?>>Barangay Clearance Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplbarangayfee", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpldueday") ?>>Due Day</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_bpldueday") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168">&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_bplkindcharlink", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bplkindcharlink") ?>>Link Kind & Character of Business</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <!--<td><label <?php // $page->businessobject->items->userfields->draw->caption("u_bpltaxclearance")  ?>>Tax Clearance</label></td>-->
                            <td></td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_bplcombinereqassessment", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bplcombinereqassessment") ?>>Combine Application & Requirements Assessments</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplsanitarypermit") ?>>Sanitary Permit</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplsanitarypermit", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplfireinsfee") ?>>Fire Inspection Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplfireinsfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplplatefee") ?>>Plate Fee</label></td>
                            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_bplplatefeeamt") ?>/>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplplatefee", null, null, null, null, "width:275px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplpfofee") ?>>PFO Fee</label></td>
                            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_bplpfofeeamt") ?>/>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplpfofee", null, null, null, null, "width:275px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplsfhefee") ?>>SFHE Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplsfhefee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_bplcategsanitarypermitlink", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bplcategsanitarypermitlink") ?>>Link Sanitary & Category of Business</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <!--<td><label <?php // $page->businessobject->items->userfields->draw->caption("u_bpltaxclearance")  ?>>Tax Clearance</label></td>-->
                            <!--<td>&nbsp;<select <?php // $page->businessobject->items->userfields->draw->select("u_bpltaxclearance",null,null,null,null,"width:367px")  ?>/></select></td>-->
                            <td width="168">&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_bplcategfireinsfeelink", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bplcategfireinsfeelink") ?>>Link Fire Inspection Fee & Category of Business</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_incfirebusiness", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_incfirebusiness") ?>>Include Fire to Business Assessment</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_incfirestartyear") ?>>Include Fire Start Year</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_incfirestartyear") ?>/></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bplcontractorstax") ?>>Contractors Tax Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplcontractorstax", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                       

                    </table>
                </div>
            </div>
            <div class="tabbertab" title="Real Property Tax">
                <div id="divrpt" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                      
                        <tr><td width="175">&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rpproptax") ?>>Real Property Tax</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_rpproptax", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rpsef") ?>>SEF</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_rpsef", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_epsf") ?>>EPSF</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_epsf", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_epsfamt") ?>>EPSF Amount</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_epsfamt") ?>/></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rphousing") ?>>Socialized Housing Tax</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_rphousing", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rpidleland") ?>>Idle Land</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_rpidleland", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
						<tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rppenstartmonth") ?>>Penalty Start Month</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_rppenstartmonth", null, null, null, null, "width:167px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_amnestyyear") ?>>Amnesty Year</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_amnestyyear") ?>/></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isactiveidleland", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_isactiveidleland") ?>>Is Active Idle Land</label></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isvalidamnesty", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_isvalidamnesty") ?>>Is Active Amnesty</label></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isactivesht", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_isactivesht") ?>>Is Active Socialized Housing Tax</label></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_rptasessortreasurylink", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_rptasessortreasurylink") ?>>Link Assessor and Treasury</label></td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="tabbertab" title="Public Market">
                <div id="divpmr" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td width="175">&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        
                        <tr>
                            <td colspan="2">&nbsp;<label class="lblobjs"><b>Fees & Charges</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pmrrentalfee") ?>>Rental Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pmrrentalfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pmrrightsfee") ?>>Rights Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pmrrightsfee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;<label class="lblobjs"><b>Mode of Payment</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pmrrentalpaymode") ?>>Stall Rental</label></td>
                            <td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_pmrrentalpaymode", "A") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_pmrrentalpaymode", "M") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_pmrrentalpaymode") ?>>Monthly</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pmrrightspaymode") ?>>Stall Rights</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pmrrightspaymode", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;<label class="lblobjs"><b>Settings</b></label></td>
                        </tr>
                        <tr><td width="168">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pmrdueday") ?>>Due Day</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_pmrdueday") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168">&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_pmrfiscal", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_pmrfiscal") ?>>Fiscal Year</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="tabbertab" title="Motorized Vehicle">
                <div id="divmtop" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td width="175">&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mtopfranchisefee") ?>>Franchise</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_mtopfranchisefee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mtopfranchisefeeamt") ?>>Franchise Fee</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_mtopfranchisefeeamt") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mtopminfare") ?>>Minimum Fare</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_mtopminfare") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mtopminkm") ?>>Minimum Km</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_mtopminkm") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mtopfarekm") ?>>Fare/Km</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_mtopfarekm") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr> 
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mtopvalidity") ?>>Franchise Validity</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_mtopvalidity") ?>/>&nbsp;<label  class="lblobjs">Year(s)</label><label <?php $page->businessobject->items->userfields->draw->caption("u_mtopfranchiserevokemonth") ?>>, Revoke On</label>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_mtopfranchiserevokemonth") ?>/></select></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mtopdueday") ?>>Due Day</label></td>
                            <td>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_mtopdueday") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_mtopcombinepermitfranchise", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_mtopcombinepermitfranchise") ?>>Combined Permit/Franchise</label></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        
                       
                    </table>
                </div>
            </div>
            <div class="tabbertab" title="Cash Ticket Denominations">
                <div id="divmtop" style="overflow:auto;">
                         <?php $objGrids[0]->draw(true) ?>	  
                </div>
            </div>
            <div class="tabbertab" title="Office of the Building">
                <div id="divobo" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                      
                        <tr><td width="175">&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_buildingpermitfeecode") ?>>Building Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_buildingpermitfeecode", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mechanicalfeecode") ?>>Mechanical Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_mechanicalfeecode", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_plumbingfeecode") ?>>Plumbing Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_plumbingfeecode", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_electricalfeecode") ?>>Electrical Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_electricalfeecode", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_signagefeecode") ?>>Signage</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_signagefeecode", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_obosharefee") ?>>OBO Share Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_obosharefee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dpwhsharefee") ?>>DPWH Share Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dpwhsharefee", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        
                    </table>
                </div>
            </div>
            <div class="tabbertab" title="Zoning">
                <div id="divzoning" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                      
                        <tr><td width="175">&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_zoningclearancefeecode") ?>>Zoning Clearance Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_zoningclearancefeecode", null, null, null, null, "width:367px") ?>/></select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_zoningprocessingfeecode") ?>>Processing Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_zoningprocessingfeecode", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_zoningsurveyfeecode") ?>>Survey Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_zoningsurveyfeecode", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_zoninglandusefeecode") ?>>Land Use Fee</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_zoninglandusefeecode", null, null, null, null, "width:367px") ?>/></select></td>
                           <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        
                        
                    </table>
                </div>
            </div>
        </div>

    </td></tr>	
    <?php $page->resize->addgridobject($objGrids[0], 400, 265) ?>   
    <?php $page->resize->addtab("tab1", -1, 81); ?>
    <?php $page->resize->addtabpage("tab1", "udf") ?>
    <?php $page->resize->addtabpage("tab1", "bpl") ?>
    <?php $page->resize->addtabpage("tab1", "pmr") ?>
    <?php $page->resize->addtabpage("tab1", "mtop") ?>
    <?php $page->resize->addtabpage("tab1", "rpt") ?>
    <?php $page->resize->addtabpage("tab1", "obo") ?>
    <?php $page->resize->addtabpage("tab1", "zoning") ?>

