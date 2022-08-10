
<tr><td>
	<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
               	
                <tr><td width="200"><label <?php $page->businessobject->items->userfields->draw->caption("docno") ?>>Queuing No.</label> &nbsp; <select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->draw->text("docno") ?>/></td>
					</tr>
                                        
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_billno") ?>>Billno</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_billno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cashiername") ?>>Cashier Name</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_cashiername") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_custname") ?>>Customer Name</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_custname") ?>/></td>
					</tr>
		
				</table>
	
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="Queuing Info">
			<div id="divudf" style="overflow:auto;">
			
            <?php $objGridA->draw() ?>
      
            <?php $page->writeRecordLimit(); ?>	
			</div>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
		

