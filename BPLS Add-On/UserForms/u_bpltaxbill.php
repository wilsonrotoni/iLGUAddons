<tr><td>
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td valign="top" width="680">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="180" ><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label</td>
                            <td width="208" align=left>&nbsp;<select name="select" <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                        </tr>
                        <tr>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?> >Tax for Year</label></td>
                            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?> /></td>
                        </tr>
                        <tr>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_assyearto") ?> >Assessment Up to Year</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_assyearto",array("loadenumyear","",":[Select]")) ?>/></select></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_assdate") ?>>Date</label></td>
                            <td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_assdate") ?>/></td>
                        </tr>
                        <tr>
                            <td ></td>
                            <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isonlineapp",1) ?> /> <label <?php $page->businessobject->items->userfields->draw->caption("u_isonlineapp") ?>>Online Application</label></td>
                        </tr>
                        <tr class="fillerRow5px">
                            <td ></td>
                            <td></td>
                        </tr>
                        <tr>
                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_envpaymode") ?>>Application Type</label></td>
                                <td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype", "NEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>New</label>&nbsp;
                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype", "RENEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Renewal</label>&nbsp;
                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype", "ADJUSTMENT") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Adjustment</label></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_acctno") ?>>Account No.</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_acctno") ?>/></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Reference No.</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reqappno") ?>>Serial No.</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_reqappno") ?>/></td>
                        </tr>
                        <tr>
                            <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_taxpayername") ?>>Tax Payer Name</label></td>
                            <td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_taxpayername") ?>/></td>
                        </tr>
                        <tr>
                            <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Business Name</label></td>
                            <td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
                        </tr>
                        <tr>
                            <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_owneraddress") ?>>Business Address</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_owneraddress") ?> rows="2" cols="35"><?php echo $page->getitemstring("u_owneraddress"); ?></TEXTAREA></td>
                        </tr>
                        <tr>
                            <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_orgtype") ?>>Business Type</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_orgtype",null,null,null,null, "width:150px") ?>></select></td>
                        </tr>
                        <tr>
                            <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_businesschar") ?>>Business Character / Kind</label></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_businesschar",null,null,null,null, "width:150px") ?>></select>&nbsp;&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_businesskind",null,null,null,null, "width:100px") ?>></select></td>
                        </tr>
                        <tr>
                            <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_mempcount") ?>>Employees</label></td>
                            <td>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_mempcount") ?>>Male</label>&nbsp;<input type="text" size="1" <?php $page->businessobject->items->userfields->draw->text("u_mempcount") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fempcount") ?>>Female</label>&nbsp;<input type="text" size="1" <?php $page->businessobject->items->userfields->draw->text("u_fempcount") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_empcount") ?>>Total</label>&nbsp;<input type="text" size="2" <?php $page->businessobject->items->userfields->draw->text("u_empcount") ?>/></td>
                        </tr>
                        <tr class="fillerRow5px">
                            <td ></td>
                            <td></td>
                        </tr>
                        <tr>
                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paymode") ?>>Mode of Payment (Business Tax)</label></td>
                                <td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode", "A") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode", "S") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Semi-Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode", "Q") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Quarterly</label></td>
                        </tr>
                        <tr>
                                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_envpaymode") ?>>Mode of Payment (Environmental)</label></td>
                                <td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_envpaymode", "A") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_envpaymode") ?>>Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_envpaymode", "S") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_envpaymode") ?>>Semi-Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_envpaymode", "Q") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_envpaymode") ?>>Quarterly</label></td>
                        </tr>
                        
                    <tr class="fillerRow5px">
                        <td ></td>
                        <td></td>
                    </tr>
                    <tr class="fillerRow5px">
					  <td ></td>
					  <td></td>
		  </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_capital") ?>>Total Capital</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_capital") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_nonessential") ?>>Total Gross Sales</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_nonessential") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_btaxamount") ?>>Business Tax</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_btaxamount") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcasstotal") ?>>Total Cedula</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ctcasstotal") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fireasstotal") ?>>Fire Safety Fee</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_fireasstotal") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_asstotal") ?>>Grand Total</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_asstotal") ?>/></td>
                </tr>
                <tr>
                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_prevappno") ?>>Previous Application</label></td>
                <td><?php genLinkedButtonHtml("u_prevappno", "", "OpenLnkBtnu_bplapps()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_prevappno") ?>/></td>
                </tr>
                <tr>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_nonessential") ?>>Last Paid Yr/Qtr</label></td>
                <td >&nbsp;<input type="text" size="6" <?php genInputTextHtml($schema["lastpayyear"]) ?> />/<input type="text" size="6" <?php genInputTextHtml($schema["lastpayqtr"]) ?> /></td>
                </tr>
                <tr><!--
                  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
                        <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?> rows="1" cols="25"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
		  </tr>-->
		</table>
                </td>
                <td valign="top" >
                    <div class="tabber" id="tab1">
                        <div class="tabbertab" title="Business Assessment">
                            <div id="divudf" style="overflow:auto;">
                                <?php $objGrids[0]->draw(true) ?>	
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr class="fillerRow5px"><td ></td></tr>
                                    <tr class="fillerRow5px"><td ></td></tr>
                                        <td width="130">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_withmiscellaneous",1) ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_withmiscellaneous") ?>>Include Miscellaneous</label></td>	

                                </table>	
                                <?php $objGrids[1]->draw(true) ?>	  
                            </div>
                        </div>
                        <div class="tabbertab" title="Tax Credit">
                            <div id="divudf" style="overflow:auto;">
                                <?php $objGrids[2]->draw(true) ?>	
                            </div>
                        </div>
                        <div class="tabbertab" title="Audited Gross History">
                            <div id="divaudited" style="overflow:auto;">
                                <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                 <tr>
                                    <td><?php $objGridA->draw(true); ?></td>
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
                    </div>
                    
                    
                </td>
                <td width="10"></td>
                
            </tr>
<?php $page->resize->addgridobject($objGrids[0], 620, 550) ?>
<?php $page->resize->addgridobject($objGrids[1], 620, 460) ?>
<?php $page->resize->addgridobject($objGrids[2], 620, 460) ?>
<?php $page->resize->addgridobject($objGridA, 620, 460) ?>
<?php $page->resize->addinput("u_remarks",640,265); ?>	
            