<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bptype","C") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bptype") ?>>Employee/Customer</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bptype","S") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bptype") ?>>Supplier</label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="220" align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
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
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Reference No.</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcentername") ?>>Profit Center Name</label></td>
	  <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_profitcentername") ?> /></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevseries") ?>>JEV Series</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_jevseries") ?>></select></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_osno") ?>>Obligation Request No.</label></td>
						<td><?php genLinkedButtonHtml("u_osno","","OpenLnkBtnu_LGUObligationSlips()");?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_osno") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevno") ?>>JEV No.</label></td>
						<td><?php if($page->getitemstring("docstatus")!="D") genLinkedButtonHtml("u_jevno","","OpenLnkBtnJournalVouchers()"); else echo "&nbsp;"; ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_jevno") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="G/L Accounts">	
            <table width="100%" cellpadding="0" cellspacing="0" border="0" >
                <tr><td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_fundtype") ?>>Type of Fund</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_fundtype") ?>></select></td>
                  <td  width="168">&nbsp;</td>
                    <td  width="250">&nbsp;<input type="checkbox" size="18" <?php $page->businessobject->items->userfields->draw->checkbox("u_vatable",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_vatable") ?>>Vatable</label></td>
                </tr>
                <tr>                  <td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_fundamount") ?>>Amount</label></td>
                    <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_fundamount") ?>/></td>

                  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lvatamount") ?>>VAT Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_lvatperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_lvatperc") ?>>%</label>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_lvatamount") ?>/>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_lbaseamount") ?>/></td>
                </tr>
                <tr>                <td>&nbsp;</td>
                  <td>&nbsp;</td>
<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_levatamount") ?>>1.) E-VAT Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_levatperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_levatperc") ?>>%</label>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_levatamount") ?>/>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_levatbaseamount") ?>/></td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_levat2amount") ?>>2.) E-VAT Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_levat2perc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_levat2perc") ?>>%</label>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_levat2amount") ?>/>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_levat2baseamount") ?>/></td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                  <td>&nbsp;</td>
<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lamount") ?>>Less: Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_lamount") ?>/></td>
                </tr>
                <tr class="fillerRow5px"><td>
                </td></tr>		
            </table>
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
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td rowspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?> rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Due Amount</label></td>
            <td width="220">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_advanceamount") ?>>Advance Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_advanceamount") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168">&nbsp;</td>
            <td width="168">&nbsp;</td>
        </tr>
        <tr>
          <td><label <?php $page->businessobject->items->userfields->draw->caption("u_requestedbyname") ?>>Head</label></td>
	  <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_requestedbyname") ?> /></td>
          <td width="168">&nbsp;</td>
            <td width="168">&nbsp;</td>
        </tr>
        <tr>
          <td><label <?php $page->businessobject->items->userfields->draw->caption("u_requestedbyposition") ?>>Position</label></td>
	  <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_requestedbyposition") ?> /></td>
          <td width="168">&nbsp;</td>
            <td width="168">&nbsp;</td>
        </tr>
    </table>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,482) ?>
<?php $page->resize->addgridobject($objGrids[1],20,379) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,367) ?>
<?php $page->resize->addgridobject($objGrids[3],20,369) ?>
