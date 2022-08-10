<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Code</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		</tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_option") ?>>Option</label></td>
                    <td  align=left>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_option") ?>rows="5" cols="50"><?php echo $page->getitemstring("u_option") ?></TEXTAREA></td>
		</tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_optiondesc") ?>>Description</label></td>
                    <td  align=left>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_optiondesc") ?>rows="5" cols="50"><?php echo $page->getitemstring("u_optiondesc") ?></TEXTAREA></td>
		</tr>
	</table>
</td></tr>	
<tr><td>&nbsp;
</td></tr>		
<tr><td>
		  
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php // $page->resize->addgridobject($objGrids[0],10,150) ?>		

