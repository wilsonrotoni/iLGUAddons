

<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Approved Code</label></td>
            <td >&nbsp;<input type="text" size="25" <?php $page->businessobject->items->draw->text("code") ?> /></td>
        </tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_noofapp") ?>>No. of Approver</label></td>
            <td>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_noofapp") ?>/>
            <input type="hidden" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_admin") ?>>Admin ID</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_admin") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_adminname") ?>>Admin Name</label></td>
            <td>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_adminname") ?>/></td>
        </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="List of Approver">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
        <div class="tabbertab" title="List of CC ( SMS Module )">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
	</div>
</td></tr>

