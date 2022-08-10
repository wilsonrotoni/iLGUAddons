<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
	  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",null,null,null,null,"width:148px") ?>/></select></td>
		
	</tr>
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>
<tr><td>
	<?php $objGrids[0]->draw(true); ?>
</td></tr>		
<tr class="fillerRow5px"><td></td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td width="168" >&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td width="168" >&nbsp;</td>
		<td  width="168" >&nbsp;</td>
	</tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,301); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],0,135) ?>				
<?php //$page->resize->addgridobject($objGrids[1],20,340) ?>				
<?php //$page->resize->addgridobject($objGrids[2],20,340) ?>				
<?php //$page->resize->addgridobject($objGrid,20,178) ?>				
<?php //$page->resize->addgridobject($objGridPrices,-1,224) ?>
<?php //$page->resize->addgridobject($objGridPrices,-1,304) ?>

