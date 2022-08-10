<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Kiosk Account</label></td>
			<td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->draw->text("code") ?> />
            	 &nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
	</table>
	<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_userid") ?>>H.R Account</label></td>
			<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_userid") ?>/>
				&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_username") ?>/></td>
		</tr>
	</table>	
				
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="User Restriction (Cost Center)">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],20,180) ?>		

