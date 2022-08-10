<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Code (PAP)</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Program/Project</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>PMO/End-User</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter") ?>/></select></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_procmode") ?>>Mode of Procurement</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_procmode") ?>></select></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_sourcefunds") ?>>Source of Funds</label></td>
            <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_sourcefunds") ?>/></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><label class="lblobjs"><b>Schedule for Each Procurement Activity</b></label></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_adsdate") ?>>Ads/Post of IB/REI</label></td>
            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_adsdate") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_opendate") ?>>Sub/Open of Bids</label></td>
            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_opendate") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_noadate") ?>>Notice of Award</label></td>
            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_noadate") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_signdate") ?>>Contract Signing</label></td>
            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_signdate") ?>/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><label class="lblobjs"><b>Estimated Budget (PHP)</b></label></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_estbudget") ?>>Total</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_estbudget") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_estmooe") ?>>MOOE</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_estmooe") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_estco") ?>>CO</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_estco") ?>/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><label class="lblobjs"><b>Actual Budget (PHP)</b></label></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_actbudget") ?>>Total</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_actbudget") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_actmooe") ?>>MOOE</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_actmooe") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_actco") ?>>CO</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_actco") ?>/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td >&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="2" cols="50"></TEXTAREA></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bacchair") ?>>BAC Chairman</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bacchair") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bacvchair") ?>>BAC Vice Chairman</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bacvchair") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bacmem1") ?>>BAC Member</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bacmem1") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bacmem2") ?>>BAC Member</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bacmem2") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bacmem3") ?>>BAC Member</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bacmem3") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mayor") ?>>Municipal Mayor</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_mayor") ?>/></td>
        </tr>
	</table>
</td></tr>	
		

