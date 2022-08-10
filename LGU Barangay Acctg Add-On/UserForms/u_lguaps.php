<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="178" align=left>&nbsp;<input type="text" size="21" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpcode") ?>>Supplier Code</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bpcode") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpname") ?>>Supplier Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bpname") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
						<td>&nbsp;<input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Reference No.</label></td>
						<td>&nbsp;<input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcentername") ?>>Profit Center Name</label></td>
	  <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_profitcentername") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevseries") ?>>JEV Series</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_jevseries") ?>></select></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_osno") ?>>Obligation Request No.</label></td>
						<td><?php genLinkedButtonHtml("u_osno","","OpenLnkBtnu_LGUObligationSlips()");?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_osno") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevno") ?>>JEV No.</label></td>
						<td><?php if($page->getitemstring("docstatus")!="D") genLinkedButtonHtml("u_jevno","","OpenLnkBtnJournalVouchers()"); else echo "&nbsp;"; ?><input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_jevno") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="G/L Accounts">	
        	<?php if ($page->getitemstring("u_apwtaxcategory") == "Invoice") { ?>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" >
                <tr><td width="130" >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                  <td >&nbsp;</td>
                    <td  width="240">&nbsp;<input type="checkbox" size="18" <?php $page->businessobject->items->userfields->draw->checkbox("u_vatable",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_vatable") ?>>Vatable</label></td>
                </tr>
                <tr>                    <td >&nbsp;</td>
                  <td >&nbsp;</td>

                    <td >&nbsp;</td>
                  <td >&nbsp;</td>
                    
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lvatamount") ?>>VAT Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_lvatperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_lvatperc") ?>>%</label>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_lvatamount") ?>/>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_lbaseamount") ?>/></td>
                </tr>
                <tr>                    <td >&nbsp;</td>
                  <td >&nbsp;</td>

                    <td >&nbsp;</td>
                  <td >&nbsp;</td>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_levatamount") ?>>1.) E-VAT Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_levatperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_levatperc") ?>>%</label>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_levatamount") ?>/>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_levatbaseamount") ?>/></td>
                </tr>
                <tr>
                                      <td >&nbsp;</td>
                  <td >&nbsp;</td>

                    <td >&nbsp;</td>
                  <td >&nbsp;</td>
                                      <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_levat2amount") ?>>2.) E-VAT Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_levat2perc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_levat2perc") ?>>%</label>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_levat2amount") ?>/>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_levat2baseamount") ?>/></td>
                </tr>
                <tr>                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
<td >&nbsp;</td>
                    <td >&nbsp;</td>
                  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lamount") ?>>Less: Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_lamount") ?>/></td>
                </tr>
                <tr class="fillerRow5px"><td>
                </td></tr>		
            </table>
			<?php } ?>
			<?php $objGrids[0]->draw(true) ?>	  
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
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_evat1") ?>>1.) E-VAT Base Amount</label></td>
            <td width="178">&nbsp;<input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_evat1") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_evat2") ?>>2.) E-VAT Base Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_evat2") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Due Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="21" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
        </tr>
    </table>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php 
	if ($page->getitemstring("u_apwtaxcategory") == "Invoice") {
		$page->resize->addgridobject($objGrids[0],20,435);
	} else {
		$page->resize->addgridobject($objGrids[0],20,335);
	}	
?>