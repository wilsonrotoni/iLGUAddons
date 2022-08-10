<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
                    <td ><label <?php $page->businessobject->items->draw->caption("u_revisionyear") ?>>Revision Year</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_revisionyear") ?>></select></td>
                    <td width="168"></td>
                    <td width="168" align=left>&nbsp;</td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->draw->caption("u_kind") ?>>Kind of Building</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_kind") ?>></select></td>
                    <td width="168"></td>
                    <td width="168" align=left>&nbsp;</td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->draw->caption("u_structuretype") ?>>Structure Type</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_structuretype") ?>></select></td>
                    <td width="168"></td>
                    <td width="168" align=left>&nbsp;</td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->draw->caption("u_subclass") ?>>Class</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_subclass") ?>></select></td>
                    <td width="168"></td>
                    <td width="168" align=left>&nbsp;</td>
		</tr>
		<tr>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_unitvalue") ?>>Unit Value</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_unitvalue") ?>/></td>
                    <td width="168"></td>
                    <td width="168" align=left>&nbsp;</td>
		</tr>
	</table>
</td></tr>	
		
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php // $page->resize->addgridobject($objGrids[0],20,200) ?>		

