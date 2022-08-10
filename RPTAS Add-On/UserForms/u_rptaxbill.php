<tr><td>
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td valign="top" width="680">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="130" ><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label</td>
                            <td width="168" align=left>&nbsp;<select name="select" <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select>&nbsp;<input type="text" size="13" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                        </tr>
                        <tr>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_yearfrom") ?> >Tax for Year</label></td>
                            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_yearfrom") ?> />&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_yearto") ?> /></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_assdate") ?>>Date</label></td>
                            <td align=left>&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_assdate") ?>/></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_duedate") ?>>Due Date</label></td>
                            <td align=left>&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_duedate") ?>/></td>
                        </tr>
                        <tr class="fillerRow5px">
                            <td ></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_searchby") ?>>Search By</label></td>
                            <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_searchby", null, null, null, null, "width:170px") ?>/></select></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_arpno") ?>>ARP No.</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_arpno") ?>/></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tdno") ?>>TD No.</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tdno") ?>/></td>
                        </tr>
                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>TIN No.</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/></td>
                        </tr>
                         <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pin") ?>>PIN No.</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_pin") ?>/></td>
                        </tr>
                        <tr>
                            <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_declaredowner") ?>>Declared Owner</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_declaredowner") ?> rows="2" cols="25"><?php echo $page->getitemstring("u_declaredowner"); ?></TEXTAREA></td>
                        </tr>
                        <tr>
                            <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_owneraddress") ?>>Address</label></td>
                            <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_owneraddress") ?> rows="2" cols="25"><?php echo $page->getitemstring("u_owneraddress"); ?></TEXTAREA></td>
                        </tr>
                    <tr>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paymode") ?>>Payment Mode</label></td>
                            <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymode", null, null, null, null, "width:170px") ?>/></select></td>
                    </tr>
                        <tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_advancepay") ?>>Advance Payment</label></td>
		  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_advancepay",1) ?> />&nbsp;&nbsp;<input type="text" size="2" <?php $page->businessobject->items->userfields->draw->text("u_noofadvanceyear") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_noofadvanceyear") ?>> Year/s</label></td>
<!--		  <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bplapprefno") ?>>BPLS Refno</label></td>
		  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplsseries", array("loaddocseries", "U_LGUBPLAPPSREFNO", "-1:Manual")) ?> ></select>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_bplapprefno") ?>/></td>
		  </tr>-->
                    <tr class="fillerRow5px">
					  <td ></td>
					  <td></td>
		  </tr>
                    <tr class="fillerRow5px">
					  <td ></td>
					  <td></td>
		  </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tax") ?>>Basic Tax</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_tax") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_discamount") ?>>Basic Discount</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_discamount") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_penalty") ?>>Basic Penalty</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_penalty") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_seftax") ?>>SEF Tax</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_seftax") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_sefdiscamount") ?>>SEF Discount</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_sefdiscamount") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_sefpenalty") ?>>SEF Penalty</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_sefpenalty") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_linetotal") ?>>Line Total</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_linetotal") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_epsftotal") ?>>EPSF Total</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_epsftotal") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_shttotal") ?>>SHT Total</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_shttotal") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_shtpenaltytotal") ?>>SHT Penalty</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_shtpenaltytotal") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_idlelandtotal") ?>>IDLE Total</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_idlelandtotal") ?>/></td>
                </tr>
                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totaltaxamount") ?>>Grand Total</label></td>
                        <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totaltaxamount") ?>/></td>
                </tr>
                <tr>
                  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_partialpay") ?>>Payment</label></td>
                  <td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_partialpay", "1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_partialpay") ?>>Partial</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_partialpay", "0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_partialpay") ?>>Full</label></td>
</tr>
            <tr><!--
                  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
                        <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?> rows="1" cols="25"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
		  </tr>-->
		</table>
                </td>
                <td valign="top" >
                    <div class="tabber" id="tab1">
                        <div class="tabbertab" title="Tax Due">
                            <div id="divudf" style="overflow:auto;">
                                <?php $objGrids[1]->draw(true) ?>	
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr class="fillerRow5px"><td ></td></tr>
                                    <tr class="fillerRow5px"><td ></td></tr>
                                    <tr class="fillerRow5px">
                                        <td width="130">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_nodisc",1) ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_nodisc") ?>>No Discount</label></td>
                                        <td width="130">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_nopenalty",1) ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_nopenalty") ?>>No Penalty</label></td>
                                        <td width="130">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_noepsf",1) ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_noepsf") ?>>No EPSF</label></td>
                                        <td width="130">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isamnesty",1) ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_isamnesty") ?>>Amnesty</label></td>
                                        <td width="150">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_ismanualposting",1) ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_ismanualposting") ?>>Manual Posting</label></td>
                                        <td width="200">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_nopenaltycuryear",1) ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_nopenaltycuryear") ?>>No Penalty(Current Year)</label></td>
                                        <td >&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema["noshtpenalty"],"1") ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("noshtpenalty") ?>>No SHT Penalty</label></td>
                                    
                                    </tr>
                                </table>	
                                <?php $objGrids[0]->draw(true) ?>	  
                            </div>
                        </div>
                        <div class="tabbertab" title="Tax Credits">
                            <div id="divudf" style="overflow:auto;">
                                  <?php $objGrids[2]->draw(true) ?>	  
                            </div>
                        </div>
                    </div>
                    
                    
                </td>
                <td width="10"></td>
                
            </tr>
<?php $page->resize->addgridobject($objGrids[0], 400, 450) ?>
<?php $page->resize->addgridobject($objGrids[1], 400, 500) ?>
<?php $page->resize->addgridobject($objGrids[2], 400, 300) ?>
            