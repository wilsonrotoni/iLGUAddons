<tr>
  <td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
                    <td width="168">&nbsp;</td>
                    <td width="168">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_taxable","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_taxable") ?>>Taxable</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_taxable","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_taxable") ?>>Exempt</label></td>
                    <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_arpno") ?>>ARP No.</label></td>
                    <td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_arpno") ?>/></td>
                </tr>
		<tr>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_actualuse") ?>>Actual Use</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_actualuse") ?>></select></td>
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gryear") ?>>GR Year</label></td>
                    <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gryear") ?>></select></td>
                </tr>
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_machine") ?>>Kind of Machine</label></td>
		  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_machine",null,null,null,null,"width:300px") ?>></select></td>
       <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_orgcost") ?>>Original Cost</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_orgcost") ?>/></td>
	  </tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_brand") ?>>Brand</label></td>
		  <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_brand") ?>/></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cnvfactor") ?>>Conversion Factor</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_cnvfactor") ?>/></td>
 	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_model") ?>>Model</label></td>
		  <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_model") ?>/></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_quantity") ?>>No. of Units</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_quantity") ?>/></td>
	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_capacity") ?>>Capacity/HP</label></td>
		  <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_capacity") ?>/></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rcn") ?>>RCN</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_rcn") ?>/></td>
	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_acqdate") ?>>Year Acquired</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_acqdate") ?>/></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_useyear") ?>>No. of Years Used</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_useyear") ?>/></td>
	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_condition") ?>>Condition When Acquired</label></td>
		  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_condition") ?>></select></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_depreratefr") ?>>Depreciation Rate From</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_depreratefr") ?>/></td>
	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_estlife") ?>>Estimated Life</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_estlife") ?>/></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_deprerateto") ?>>Depreciation Rate To</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_deprerateto") ?>/></td>
	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remlife") ?>>Remaining Life</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_remlife") ?>/></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_depreperc") ?>>% Depreciation</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_depreperc") ?>/></td>
	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_insyear") ?>>Year Installed</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_insyear") ?>/></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_deprevalue") ?>>Depreciation Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_deprevalue") ?>/></td>
	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inityear") ?>>Year of Initial Operation</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_inityear") ?>/></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remvalue") ?>>Depreciated Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_remvalue") ?>/></td>
	</tr>
    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_withdecimal") ?>>Assessed Value Include Decimal</label></td>
		  <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_withdecimal",1) ?>/></td>
		  <td width="168">&nbsp;</td>
		  <td width="168">&nbsp;</td>
		</tr>
    </table>
</td></tr>	
<?php //$page->resize->addgridobject($objGrids[0],-1,-1) ?>
<?php //$page->resize->addgridobject($objGrids[1],-1,-1) ?>		

