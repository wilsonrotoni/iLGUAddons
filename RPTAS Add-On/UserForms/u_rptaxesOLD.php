<tr><td>
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                    <tr>
					  <td>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pin") ?>>PIN</label></td>
					  <td width="459"><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_pin") ?>/></td>
                                      <td>&nbsp;</td>
                                          <td width="4">&nbsp;</td>
                                          <td width="22">&nbsp;</td>
				    <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_assdate") ?>>&nbsp;&nbsp;Date</label>
                          <input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_assdate") ?>/>
                          <input type="checkbox" <?php genInputCheckboxHtml($schema["iscurdate"],"1") ?> />
                          <label <?php $page->businessobject->items->userfields->draw->caption("iscurdate") ?>>Today</label></td>
                                      <td width="150" >&nbsp;&nbsp;<label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td>
                                    <td width="161">
                                          &nbsp;
                                      <select name="select" <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                  </tr>
                                    
<tr>
                                      <td width="97"><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>*TIN /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_tdno") ?>>TD No. </label></td>
        <td width="459"><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/>&nbsp;<input type="text" size="22" <?php $page->businessobject->items->userfields->draw->text("u_tdno") ?>/>&nbsp;</td>
        <td width="18">&nbsp;</td>
        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">&nbsp;</td>
        <td width="150" >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Tax for Year </label></td>
<td width="161" > &nbsp;
        <input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_yearfrom") ?>/>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_yearto") ?>/></td>
			      </tr>
                    <tr>
                      <td width="97"><label <?php $page->businessobject->items->userfields->draw->caption("u_declaredowner") ?>>*Declared Owner</label></td>
        <td width="459"><input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_declaredowner") ?>/></td>
                      <td width="18">&nbsp;</td>
        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
				    </tr>
                    <tr>
                      <td width="97"><label <?php $page->businessobject->items->userfields->draw->caption("u_paidby") ?>>*Paid By</label></td>
        <td width="459"><input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_paidby") ?>/></td>
                      <td width="18">&nbsp;</td>
                      <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td width="5">&nbsp;</td>
                      <td width="320"></td>
   				      <td>&nbsp;</td>
				    </tr>
                    <tr>
                      <td width="97"><label <?php $page->businessobject->items->userfields->draw->caption("u_ornumber") ?>>*Receipt Number</label></td>
        <td width="459"><input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_ornumber") ?>/></td>
                      <td width="18">&nbsp;</td>
        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td width="5">&nbsp;</td>
                      <td></td>
				    </tr>
                   <tr>
					  <td width="97"><label <?php $page->businessobject->items->userfields->draw->caption("u_ordate") ?>>*Receipt Date</label></td>
				     <td><input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_ordate") ?>/>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_changeamount") ?>>Change Amount</label> </td>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
					  <td colspan="2">&nbsp;</td>
				     <td width="320"><label <?php $page->businessobject->items->userfields->draw->caption("u_paymode") ?>>Mode of Payment</label></td>
                     <td>&nbsp; <input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_nopenaltydisc",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_nopenaltydisc") ?>>No Penalty & Disc</label></td>
				  </tr>
					<tr>
					  <td width="97"><label <?php $page->businessobject->items->userfields->draw->caption("u_paidamount") ?>>*Amount Paid</label></td>
					  <td><input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_paidamount") ?>/>&nbsp;&nbsp;&nbsp; <input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_changeamount") ?>/></td>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
                                          <td width="5">&nbsp;</td>
<td><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode","A") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Annually</label>
                                          &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode","S") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Bi-Annual</label>
                                          &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode","Q") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Quarterly</label></td>
                                          <td>&nbsp; <input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_yearbreak",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_yearbreak") ?>>Collapse Years</label></td>
				  </tr>
                  <tr>
					  <td width="97"><label <?php $page->businessobject->items->userfields->draw->caption("u_partialpay") ?>>*Payment</label></td>
<td><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_partialpay","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Full</label>
                                          &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_partialpay","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_partialpay") ?>>Partial</label></td>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
                                          <td width="5">&nbsp;</td>
				    <td></td>
                                    <td>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_withfaas",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_withfaas") ?>>With Faas</label></td>
				  </tr>
					
             </table>  
                        <div class="tabber" id="tab1">
                            <div class="tabbertab" title="Tax Dues">
                                <div id="divudf" style="overflow:auto;">
                                    <?php $objGrids[0]->draw(true); ?>
                                </div>
                            </div>
                            <div class="tabbertab" title="Tax Credits">
                                <div id="divudf" style="overflow:auto;">
                                     <?php $objGrids[2]->draw(true); ?>
                                </div>
                            </div>
<!--                            <div class="tabbertab" title="Balances">
                                <div id="divudf" style="overflow:auto;">
                                    <?php // $objGrids[3]->draw(true); ?>
                                </div>
                            </div>-->
                        </div> 
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr class="fillerRow5px">
					  <td colspan="5"></td>
				  </tr>
                                    <tr>
					  <td colspan="2">&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_advancepay",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Advance Payment</label>&nbsp;&nbsp; <input type="text" size="2" <?php $page->businessobject->items->userfields->draw->text("u_noofadvanceyear") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_noofadvanceyear") ?>>Year/s</label></td>
					  <td width="4">&nbsp;</td>
					  <td width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_q1totaltax") ?>>Qtr 1 Total</label></td>
					  <td width="140"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_q1totaltax") ?>/></td>
					  <td width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_s1totaltax") ?>>SA 1 Total</label></td>
					  <td width="140"><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_s1totaltax") ?>/></td>
					  <td width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_tax") ?>>Tax Due</label></td>
					  <td width="169"><input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_tax") ?>/></td>
                                           <td width="100"><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_seftax") ?>>SEF</label></td>
                                          <td width="129"><input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_seftax") ?>/></td>
                                    </tr>
					<tr>
					  <td colspan="2" rowspan="6"><?php $objGrids[1]->draw(true); ?></td>
					  <td>&nbsp;</td>
					  
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_q2totaltax") ?>>Qtr 2 Total</label></td>
					  <td><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_q2totaltax") ?>/></td>
                                          <td><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_s2totaltax") ?>>&nbsp;SA 2 Total</label></td>
                                          <td><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_s2totaltax") ?>/></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_discamount") ?>>Discount</label></td>
					  <td><input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_discamount") ?>/></td>
                                          <td><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_sefdiscamount") ?>>SEF Discount</label></td>
                                          <td><input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_sefdiscamount") ?>/></td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_q3totaltax") ?>>Qtr 3 Total</label></td>
					  <td><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_q3totaltax") ?>/></td>
                                           <td>&nbsp;</td>
					   <td>&nbsp;</td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_disc") ?>>Penalty</label></td>
					  <td><input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_penalty") ?>/></td>
                                          <td><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_sefpenalty") ?>>SEF Penalty</label></td>
                                          <td><input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_sefpenalty") ?>/></td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_q4totaltax") ?>>Qtr 4 Total</label></td>
					  <td><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_q4totaltax") ?>/></td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
                                          <td><label <?php $page->businessobject->items->userfields->draw->caption("u_sefdiscamount") ?>>Grand Total</label></td>
					  <td><input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_totaltaxamount") ?>/></td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_penalty") ?>></label></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_sefpenalty") ?>></label></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_totaltaxamount") ?>></label></td>
					  <td>&nbsp;</td>
				  </tr>
				</table>
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],0,420) ?>
<?php $page->resize->addgridobject($objGrids[2],0,420) ?>
<?php // $page->resize->addgridobject($objGrids[3],0,420) ?>