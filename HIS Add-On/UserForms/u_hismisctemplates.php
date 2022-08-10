<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_type") ?>>Type</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_type") ?>/></select></td>
		<td width="168" ></td>
		<td width="168" align=left>&nbsp;</td>
	</tr>
	
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department") ?>/></select></td>
	  <td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
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
	  <td width="168" valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
	  <td rowspan="2">&nbsp;<textarea cols="50" rows="2" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks"); ?></textarea></td>
	  <td width="168" >&nbsp;</td>
		<td width="168"  align=left>&nbsp;</td>
	  </tr>	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,261); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addgridobject($objGrids[0],10,208) ?>		
<?php //$page->resize->addgridobject($objGrids[1],-1,258) ?>		
<?php //$page->resize->addinput("u_remarks",35,265) ?>		


