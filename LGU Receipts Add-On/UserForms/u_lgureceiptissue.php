<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="188"><label <?php $page->businessobject->items->draw->caption("code") ?>>Cashier/Barangay</label></td>
                    <td align="left" >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->draw->text("code") ?> />&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isbrgy",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isbrgy") ?>>For Barangay</label></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->draw->caption("name") ?> > Name</label></td>
                    <td >&nbsp;<input type="text" size="40" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
                <tr>
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_totalreceipts") ?>> Receipt on Hand</label></td>
                    <td>&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_totalreceipts") ?>/></td>
		</tr>
                
                <tr>
                    <td >&nbsp;</td>
                    <td></td>
		</tr>
          <tr>
              
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Issued/Return Receipts">	
			<?php $objGridB->draw(true) ?>	  
		</div>
<!--		<div class="tabbertab" title="Return Receipts">	
			<?php // $objGridC->draw(true) ?>	  
		</div>-->
       
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGridB,-1,220) ?>		
<?php // $page->resize->addgridobject($objGridC,-1,220) ?>		
<?php //$page->resize->addgridobject($objGridA,20,240) ?>		

