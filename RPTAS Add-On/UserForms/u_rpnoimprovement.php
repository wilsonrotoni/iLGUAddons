<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr><td width="168" >&nbsp;</td>
            <td align=left>&nbsp;</td>
            <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
            <td width="168" align=left>&nbsp;<input type="text" size = "15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
            <td >&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
            <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
            <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tdno") ?>>ARP Number</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tdno") ?>/> &nbsp;&nbsp;
                <input type="radio" <?php genInputRadioHtml($schema["searchby"],"L") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("searchby") ?>>Land</label>
                <input type="radio" <?php genInputRadioHtml($schema["searchby"],"B") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("searchby") ?>>Building</label>
                <input type="radio" <?php genInputRadioHtml($schema["searchby"],"M") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("searchby") ?>>Machinery</label>
            </td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Application Date</label></td>
            <td>&nbsp;<input type="text" size="15"  <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
        </tr>
	<tr>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ownername") ?>>Owner Name</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_ownername") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_barangay") ?>>Barangay</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_barangay") ?>/></td>
             <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_kind") ?>>Kind</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_kind") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_area") ?>>Area</label></td>
            <td>&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_area") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_assvalue") ?>>Assessed Value</label></td>
            <td>&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_assvalue") ?>/></td>
           
        </tr>
         <tr class="fillerRow5px"><td colspan="9"></td></tr>
        <tr>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_requestby") ?>>Request By</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_requestby") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_verifiedby") ?>>Verified By</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_verifiedby") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px"><td colspan="9"></td>
        </tr>
        <tr>
            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>OR Number</label></td>
            <td>&nbsp;<input type="text" size="17"<?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docstamporno") ?>>DocStamp OR Number</label></td>
            <td  align=left>&nbsp;<input type="text" size="17"<?php $page->businessobject->items->userfields->draw->text("u_docstamporno") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px"><td colspan="9"></td>
        </tr>       
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_reason") ?>>Remarks</label></td>
                <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_reason") ?>rows="4" cols="50"><?php echo $page->getitemstring("u_reason"); ?></TEXTAREA></td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
        </tr>
	</table>
</td></tr>	
 <?php // $page->resize->addgridobject($objGrids[0], 800, 550) ?>
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

