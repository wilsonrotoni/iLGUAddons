<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr>
            <td width="200"><label <?php $page->businessobject->items->userfields->draw->caption("u_purchaseddate") ?>>Purchase Date</label></td>
            <td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_purchaseddate") ?>/></td>
            <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
            <td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
        </tr>
        <tr>
            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_form") ?>>Accountable Form</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_form") ?>></select></td>
            <td><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
        </tr>
        <tr>
            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_receiptfrm") ?>>Receipt From / To</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_receiptfrm") ?>/> / <input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_receiptto") ?>/></td>
            <td></td>
            <td align=left>&nbsp;</td>
        </tr>
        <tr>
            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_noofreceipt") ?>>Total No of Receipt</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_noofreceipt") ?>/></td>
            <td></td>
            <td align=left>&nbsp;</td>
        </tr>
        <tr>
            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bundlecnt") ?>>Total Bundle Count</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bundlecnt") ?>/></td>
            <td></td>
            <td align=left>&nbsp;</td>
        </tr>
        <tr>
            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_cntperbundle") ?>>OR Count per Bundle</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_cntperbundle") ?>/></td>
            <td></td>
            <td align=left>&nbsp;</td>
        </tr>
        <tr><td>&nbsp;</td>
            <td>&nbsp;<a class="button" href="" onClick="formApplyReceipts();return false;">Apply to Grid</a></td>
        </tr>
        <tr><td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
	</table>
</td></tr>	
<tr><td>
			<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<?php // $page->resize->addtab("tab1",-1,141); ?>
<?php // $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],-1,260) ?>		

