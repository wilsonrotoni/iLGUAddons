<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_yr") ?>>Year</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_yr",array("loadenumyear","",":[Select]")) ?>/></select></td>
		    <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter") ?>/></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->draw->text("docstatus") ?>/></td>
		</tr>
	</table>
</td></tr>	
<tr><td>&nbsp;</td></tr>
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<tr><td>&nbsp;</td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_preparedby") ?>>Prepared By</label></td>
			<td>&nbsp;<input type="text" size="50"<?php $page->businessobject->items->userfields->draw->text("u_preparedby") ?>/></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ps") ?>>Total PS</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_ps") ?>/></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mooe") ?>>Total MOOE</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_mooe") ?>/></td>
	  </tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_co") ?>>Total CO</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_co") ?>/></td>
	  </tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],10,243) ?>		
<?php //$page->resize->addgridobject($objGrids[1],20,293) ?>		
<?php //$page->resize->addinput("u_remarks",30,245); ?>
<?php //if ($page->getitemstring("docstatus")=="For Approval") $page->resize->addinput("u_decisionremarks",30,245); ?>
<?php //if ($page->getitemstring("docstatus")!="Draft") $page->resize->addinput("u_history",30,245); ?>

