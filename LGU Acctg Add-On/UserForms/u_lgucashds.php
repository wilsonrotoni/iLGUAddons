<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bptype","C") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bptype") ?>>Employee/Customer</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bptype","S") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bptype") ?>>Supplier</label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="200" align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpcode") ?>>Payee Code</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bpcode") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpname") ?>>Payee Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bpname") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Reference No.</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcentername") ?>>Profit Center Name</label></td>
	  <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_profitcentername") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevseries") ?>>JEV Series</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_jevseries") ?>></select></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_refundglacctno") ?>>Refund G/L Account No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refundglacctno") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_refundglacctname") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevno") ?>>JEV No.</label></td>
						<td><?php if($page->getitemstring("docstatus")!="D") genLinkedButtonHtml("u_jevno","","OpenLnkBtnJournalVouchers()"); else echo "&nbsp;"; ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_jevno") ?>/></td>
	  </tr>
	  <tr><td></td><td></td>
	  	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dateperiod") ?>>Date Period</label></td>
                <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_dateperiod") ?>/></td>
      </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_osno") ?>>Obligation Request No.</label></td>
						<td><?php genLinkedButtonHtml("u_osno","","OpenLnkBtnu_LGUObligationSlips()");?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_osno") ?>/></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;&nbsp;<input type="checkbox" size="18" <?php $page->businessobject->items->userfields->draw->checkbox("u_autonegatedvalues",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_autonegatedvalues") ?>>Auto (-) Values</label></td>
	  </tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="G/L Accounts">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Advances">	
            <table width="100%" cellpadding="0" cellspacing="0" border="0" >
                <tr><td width="168">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_advall",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_advall",1) ?>>Various Advances</label></td>
						<td>&nbsp;</td>
                        <td width="168">&nbsp;</td>
            			<td >&nbsp;</td>
                </tr>
                <tr><td width="168">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_advall",0) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_advbpcode") ?>>Same as Payee</label></td>
				  <td>&nbsp;&nbsp;</td>
                        <td width="168">&nbsp;</td>
            			<td >&nbsp;</td>
                </tr>
                
                <tr class="fillerRow5px"><td>
                </td></tr>		
            </table>
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Payables">	
            <table width="100%" cellpadding="0" cellspacing="0" border="0" >
                
                <tr><td width="168">&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_apbpcode") ?>>Supplier</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_apbpcode") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_apbpname") ?>/></td>
                        <td width="168">&nbsp;</td>
            			<td >&nbsp;</td>
                </tr>
                
                <tr class="fillerRow5px"><td>
                </td></tr>		
            </table>
			<?php $objGrids[2]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Multiple Obligation Nos">	
			<?php $objGrids[3]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<tr class="fillerRow5px"><td>
</td></tr>		
<tr><td>
    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>					</tr>
        
        <tr>					</tr>
        
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td rowspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?> rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_spentamount") ?>>Spent Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_spentamount") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_advanceamount") ?>>Advance Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_advanceamount") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_refundamount") ?>>Refund Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_refundamount") ?>/></td>
        </tr>
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_receivedby") ?>>Received By</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_receivedby") ?>/></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Due Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
        </tr>
    </table>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,385) ?>
<?php $page->resize->addgridobject($objGrids[1],20,379) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,377) ?>
<?php $page->resize->addgridobject($objGrids[3],20,385) ?>

