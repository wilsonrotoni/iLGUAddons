<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="200" align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpcode") ?>>Collector Code</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bpcode") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpname") ?>>Collector Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bpname") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_datefrom") ?>>Date From</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_datefrom") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Reference No.</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dateto") ?>>Date To</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_dateto") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevseries") ?>>JEV Series</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_jevseries") ?>></select></td>
	  </tr>
	<tr>
	  <td width="168">&nbsp;</td>
						<td>&nbsp;<?php if ($page->getitemstring("docstatus")=="D") { ?> <a href="" onclick="getCollectionsGPSLGUAcctg();return false;" >[Retrieve]</a><?php } ?></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevno") ?>>JEV No.</label></td>
						<td><?php if($page->getitemstring("docstatus")!="D") genLinkedButtonHtml("u_jevno","","OpenLnkBtnJournalVouchers()"); else echo "&nbsp;"; ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_jevno") ?>/></td>
	  </tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="G/L Accounts">	
            <table width="100%" cellpadding="0" cellspacing="0" border="0" >
                <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cashglacctno") ?>>Cash G/L Account No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_cashglacctno") ?>/></td>
                        <td width="168">&nbsp;</td>
            			<td >&nbsp;</td>
                </tr>
                <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cashglacctname") ?>>Cash G/L Account Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_cashglacctname") ?>/></td>
                        <td width="168">&nbsp;</td>
            			<td >&nbsp;</td>
                </tr>
                <tr><td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_cashamount") ?>>Amount</label></td>
                    <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_cashamount") ?>/></td>
                        <td width="168">&nbsp;</td>
            			<td >&nbsp;</td>
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
                <tr><td width="168">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_advall",0) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_advbpcode") ?>>Employee No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_advbpcode") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_advbpname") ?>/></td>
                        <td width="168">&nbsp;</td>
            			<td >&nbsp;</td>
                </tr>
                
                <tr class="fillerRow5px"><td>
                </td></tr>		
            </table>
		  <?php $objGrids[2]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Bank Deposits">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<tr class="fillerRow5px"><td>
</td></tr>		
<tr><td>
    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td rowspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?> rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_variance") ?>>Short(-)/Over(+)</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_variance") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totaldeposit") ?>>Total Deposit</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totaldeposit") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
            <td>&nbsp;</td>
          <td width="168">&nbsp;</td>
            <td width="168">&nbsp;</td>
        </tr>
    </table>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,422) ?><?php $page->resize->addgridobject($objGrids[1],20,422) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,379) ?>		
