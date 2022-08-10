<tr><td>
    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cashier") ?>>Cashier</label></td>
                    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_cashier") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dateissued") ?>>Issued Date</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_dateissued") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_time") ?>>Issued Time</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_time") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalnoofreceipt") ?>>Total Receipts</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_totalnoofreceipt") ?>/></td>
            </tr>
            <tr>
                    <td width="168"></td>
                    <td>&nbsp;</td>
            </tr>
          

    </table>
	
</td></tr>
  <?php $objGrids[0]->draw(true) ?>	  
<?php // $page->resize->addtab("tab1",-1,141); ?>
<?php // $page->resize->addtabpage("tab1","udf") ?>

<?php // $page->resize->addgridobject($objGrids[0],400,300) ?>	
		

