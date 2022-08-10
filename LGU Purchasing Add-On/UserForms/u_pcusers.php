<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
                    <td width="168"><label <?php $page->businessobject->items->draw->caption("u_user") ?>>User ID</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_user") ?>></select></td>
                    <td width="168" align ='right' >&nbsp;</td>
                    <td width="168" align=center>&nbsp;</td>
		</tr>
	</table>
</td></tr>
<tr class="fillerRow5px"><td>
</td></tr>
<tr><td>
	
			<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,160) ?>		

