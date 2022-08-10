<tr><td>
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr><td width="168" >&nbsp;</td>
                <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isonlinepayment",1) ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_isonlinepayment") ?>>Online Payment</label></td>
                <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries", array("loaddocseries", $page->objectcode, "-1:Manual")) ?> ></select></td></tr></table></td>
                <td width="168" align=left>&nbsp;<input type="text" size = "15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_apptype") ?>>Application For</label></td>
                <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_apptype") ?>></select></td>
                <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
                <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus", array("loadenumdocstatus", "", "")) ?> ></select></td>
            </tr>
            <tr>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_custname") ?>>Customer Name</label></td>
                <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_custname") ?>/></td>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Application Date</label></td>
                <td>&nbsp;<input type="text" size="15"  <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Customer Address</label></td>
                <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_address") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_address"); ?></TEXTAREA></td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dateofbirth") ?>>Date of Birth</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dateofbirth") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
	<tr>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_civilstatus") ?>>Civil Status</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_civilstatus") ?>></select></td>
            <td width="168"></td>
            <td>&nbsp;</td>
	  </tr>
	<tr>
	    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
            <td width="168"></td>
            <td>&nbsp;</td>
	  </tr>	
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_placeofbirth") ?>>Place of Birth</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_placeofbirth") ?>/></td>
	   <td>&nbsp;</td>
            <td>&nbsp;</td>
	  </tr>	
	<tr>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_occupation") ?>>Occupation</label></td>
            <td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_occupation") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
	  </tr>	
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_citizenship") ?>>Citizenship</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_citizenship") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gross") ?>>Gross Amount</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_gross") ?>/> <?php if (!isEditMode()) { ?>&nbsp;<a class="button" href="" onClick="setCTCFees();
                    return false;" >Compute</a> <?php } ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
       
         <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docseries") ?>>Official Receipt Series</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_docseries", array("loadu_lguposterminalseries", $_SESSION["userid"], ":")) ?>></select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>Official Receipt No.</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ordate") ?>>Official Receipt Date</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ordate") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
            <td>&nbsp;</td>
            <td colspan="2"><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_ischeque", "0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_ischeque") ?>>Cash</label>&nbsp;
                  <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_ischeque", "1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_ischeque") ?>>Check</label>&nbsp;
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_checkno") ?>>Check No</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_checkno") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_checkdate") ?>>Check Date</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_checkdate") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_checkbank") ?>>Bank</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_checkbank") ?>></select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        
        <tr class="fillerRow5px"><td colspan="2"></td></tr>
        <tr><td width="168"></td>
            <td  colspan="3">&nbsp;&nbsp;<?php $objGrids[0]->draw(true); ?></td>
            <td>&nbsp; </td>
            <td >&nbsp;</td>
        </tr>
	</table>
</td></tr>	
<?php $page->resize->addgridobject($objGrids[0], 800, 650) ?>
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

