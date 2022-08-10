<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
		<tr><td >&nbsp;</td>
          <td>&nbsp;</td>
		    <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="300" align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
		</tr>
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_yr") ?>>Year</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_yr",array("loadenumyear","",":[Select]")) ?>/></select></td>
		  <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	  </tr>
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appcode") ?>>APP Code</label></td>
            <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_appcode") ?>/></td>
	        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_annex") ?>>Annex</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_annex") ?>/></select></td>
	  </tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_description") ?> >Program/Project</label></td>
			<td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_description") ?>rows="1" cols="50"><?php echo $page->getitemstring("name") ?></TEXTAREA></td>
		    <td>&nbsp;</td>
          <td>&nbsp;</td>
		</tr>
        <tr>
          <td>&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_estmooe") ?>>Estimated Budget (MOOE)</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_estmooe") ?>/></td>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>PMO/End-User</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter") ?>/></select></td>

	        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_estco") ?>>Estimated Budget (CO)</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_estco") ?>/></td>
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_procmode") ?>>Mode of Procurement</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_procmode") ?>></select></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_estbudget") ?>>Estimated Budget (Total)</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_estbudget") ?>/></td>
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_sourcefunds") ?>>Source of Funds</label></td>
            <td>&nbsp;<input type="text" SIZE="50" <?php $page->businessobject->items->userfields->draw->text("u_sourcefunds") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        <tr>
          <td ><label c<?php $page->businessobject->items->userfields->draw->caption("u_schedule") ?>>Schedule</label></td>
          <td>&nbsp;<input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_schedule") ?>/></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_revmooe") ?>>Revised Budget (MOOE)</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_revmooe") ?>/></td>
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_revco") ?>>Revised Budget (CO)</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_revco") ?>/></td>
        <tr>
          <td >&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_revbudget") ?>>Revised Budget (Total)</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_revbudget") ?>/></td>
        <tr>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
    </table>
</td></tr>	
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,340) ?>		

