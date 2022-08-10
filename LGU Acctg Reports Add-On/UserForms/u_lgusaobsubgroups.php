
<tr><td>
	
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_yr") ?>>Year</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_yr",array("loadenumyear","",":[Select]")) ?>/></select></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter") ?>></select></td>
					</tr>
		
				</table>

			
			<?php $objGrids[0]->draw(true) ?>	  
	
	
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,180) ?>		

