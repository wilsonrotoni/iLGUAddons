<tr><td>
        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
                <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >Document No.</label> </td>
                <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_apprefno") ?>>Reference No</label></td>
                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
                <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_accountno") ?>>Account No</label></td>
            </tr>
            <tr>
                <td >&nbsp;<select <?php $page->businessobject->items->draw->select("docseries", array("loaddocseries", $page->getobjectdoctype(), "-1:Manual")) ?> ></select>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                <td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_apprefno") ?>/></td>
                <td width="249">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
                <td width="249">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_accountno") ?>/></td>
            </tr>
            
            <tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Business Name</label></td>
                <td>&nbsp;</td>
                <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_authorizedby") ?>>Authorized By</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
                <td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_authorizedby") ?>/></td>
            </tr>
            <tr>
                <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>OR Number</label></td>
                <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
                <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_paymode") ?>>Payment Mode</label></td>
                <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalpaid") ?>>Paid Amount</label></td>
            </tr>
            <tr>
                <td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
                <td >&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
                <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymode",null,null,null,null, "width:150px") ?>></select></td>
                <td colspan="2">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_totalpaid") ?>/></td>
            </tr>

            <tr class="fillerRow5px">
                <td colspan="4"></td>
            </tr>
            <tr class="fillerRow5px">
                <td colspan="4">&nbsp;<a class="button" href="" onClick="ApplytoGrid();return false;">Apply to Grid</a></td>
            </tr>
             <tr class="fillerRow5px">
                <td colspan="4"></td>
            </tr>
        </table>    
        <?php $objGrids[0]->draw(true); ?>	

        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr class="fillerRow5px">
                <td width="399" colspan="5"></td>
            </tr>
        </table>
    </td></tr>		
<?php $page->resize->addgridobject($objGrids[0], 0, 255) ?>