<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr><td width="188"><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>PFO Fee per Employee</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
					</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="No of Employees">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,121); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,160) ?>		

