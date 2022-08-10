<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>ID</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
                        <td  width="168"></td>
                        <td  width="168"></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("u_blinecode") ?>>Code</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_blinecode") ?> /></td>
                        <td></td>
                        <td></td>
		</tr>
		<tr>    <td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Name</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
                        <td></td>
                        <td></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("u_businessnature") ?> >Business Nature</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_businessnature") ?> /></select></td>
                    <td></td>
                        <td></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("u_compbased") ?> >Computation Based by</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_compbased") ?> /></select></td>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fixedamount") ?> >BTAX Fixed Amount</label></td>
                    <td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_fixedamount") ?> /></td>
		</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Fees and Charges">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="List of Requirements">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Remarks">	
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA>
					</td></tr>
				</table>  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addgridobject($objGrids[0],20,260) ?><?php $page->resize->addgridobject($objGrids[1],20,260) ?>	
<?php $page->resize->addinput("u_remarks",30,220); ?>	

