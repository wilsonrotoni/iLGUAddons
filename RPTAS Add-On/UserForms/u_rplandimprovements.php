<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
                    <td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?> >Name</label></td>
                    <td align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("code") ?> /></td>
                    <td width="168" >&nbsp;</td>
                    <td width="168" align=left>&nbsp;</td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->draw->caption("u_gryear") ?>>GR Year</label></td>
                    <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gryear") ?>></select></td>
                    <td >&nbsp;</td>
                    <td align=left>&nbsp;</td>
		</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Items">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,200) ?>		
