<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_apptype") ?>>Application For</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_apptype") ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bpno") ?>>Business Permit No.</label></td>
	  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bpno") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Application Date</label></td>
          <td>&nbsp;<input type="text" size="18"  <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Name of Business</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_insno") ?>>Inspection Order No.</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_insno") ?>/></td>
	  </tr>	
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>Official Receipt No.</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ordate") ?>>Official Receipt Date</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ordate") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_orfcamt") ?>>Fire Code fees</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_orfcamt") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
						<td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
					    <td >&nbsp;</td>
					    <td >&nbsp;</td>
					</tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

