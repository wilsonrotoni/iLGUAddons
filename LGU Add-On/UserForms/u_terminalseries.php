	
<tr><td>
	
    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
         <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_isdefault") ?>>Default</label></td>
                    <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isdefault",1) ?>/></td>
            </tr>
<!--            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_terminalid") ?>>Terminal Id</label></td>
                    <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_terminalid") ?>/></td>
            </tr>-->
           <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cashier") ?>>Cashier Name</label></td>
                    <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cashier") ?>></select></td>
            </tr>
           
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_prefix") ?>>Prefix</label></td>
                    <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_prefix") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_startno") ?>>Start No</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_startno") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastno") ?>>End No</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_lastno") ?>/></td>
            </tr>
             <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_suffix") ?>>Suffix</label></td>
                    <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_suffix") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docseriesname") ?>>Series Name</label></td>
                    <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_docseriesname") ?>></select></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_numlen") ?>>Digits</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_numlen") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_nooflines") ?>>No of Lines</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_nooflines") ?>/></td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_autoseries") ?>>Auto Series</label></td>
                    <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_autoseries",1) ?>/></td>
            </tr>
    </table>
	
</td></tr>		
<?php // $page->resize->addtab("tab1",-1,141); ?>
<?php // $page->resize->addtabpage("tab1","udf") ?>
		

