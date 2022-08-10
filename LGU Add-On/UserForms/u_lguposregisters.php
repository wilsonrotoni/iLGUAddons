<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<!--		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_terminalid") ?>>Terminal ID</label></td>
			<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_terminalid") ?>/></td>
		    <td width="168">&nbsp;</td>
		    <td width="208">&nbsp;</td>
		</tr>-->
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_userid") ?>>User ID</label></td>
			<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_userid") ?>/></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_daily",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_daily") ?>>Daily</label></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Open Date/Time</label></td>
			<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_time") ?>/></td>
		    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_startseqno") ?>>Start Trx. No.</label></td>
		    <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_startseqno") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_closedate") ?>>Closed Date/Time</label></td>
			<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_closedate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_closetime") ?>/></td>
		    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_endseqno") ?>>End Trx. No.</label></td>
		    <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_endseqno") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_postingdate") ?>>Remittance Date</label></td>
			<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_postingdate") ?>/></td>
		    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_seqnocount") ?>>Total Transactions</label></td>
		    <td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_seqnocount") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_status") ?>>Status</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_status") ?>/></select></td>
		    <td></td>
		    <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isremitted",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isremitted") ?>>Remitted</label></td>
		</tr>
	</table>
</td></tr>	
<tr><td>&nbsp;</td></tr>
<tr><td>
        <div class="tabber" id="tab1">
            <div class="tabbertab" title="General">
                <div id="divudf" style="overflow:auto;">
                        <div style="display:<?php if (isEditMode()) echo "none"; else echo "block"; ?>">
                            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td width="190">&nbsp;<b><label class="lblobjs">Denominations</label></b></td>
                                    </tr>
                                    <tr>
                                        <td width="190"><?php $objGrids[0]->draw(true) ?></td> 
                                    </tr>
                            </table>
                        </div>
                        <div style="display:<?php if (isEditMode()) echo "block"; else echo "none"; ?>">
                                <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                                 <td width="190">&nbsp;<b><label class="lblobjs">Denominations</label></b></td>
                                                 <td >&nbsp;<b><label class="lblobjs">Payments</label></b></td>
                                                 <td >&nbsp;</td>
                                                 <td >&nbsp;</td>
                                        </tr>
                                          <tr>
                                                <td width="250"><?php $objGrids[1]->draw(true) ?></td>
                                                <td width="480"><?php $objGrids[2]->draw(true) ?></td>
                                                <td>
                                        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_openamount") ?>>Starting Drawer</label></td>
                                                        <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_openamount") ?>/></td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_salesamount") ?>>Total Sales</label></td>
                                                        <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_salesamount") ?>/></td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_closeamount") ?>>Ending Drawer</label></td>
                                                        <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_closeamount") ?>/></td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cashvariance") ?>>Cash Variance</label></td>
                                                        <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_cashvariance") ?>/></td>
                                                </tr>
                                        </table>				
                                                </td>
                                        </tr>
                                </table>
                        </div>
                </div>
            </div>
            <div class="tabbertab" title="OR Series">
                <div id="divudf" style="overflow:auto;">
                    <?php $objGridA->draw(true) ?>
                </div>
            </div>
        </div>
        
        
	
        
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],-1,300) ?>		
<?php $page->resize->addgridobject($objGrids[1],-1,300) ?>		
<?php $page->resize->addgridobject($objGrids[2],-1,300) ?>		
<?php $page->resize->addgridobject($objGridA,-1,300) ?>		
<?php // $page->resize->addgridobject($objGrids[3],-1,300) ?>		
<?php // $page->resize->addgridobject($objGrids[4],-1,300) ?>		

