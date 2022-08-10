<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Class Code</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		    <td width="168">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_onhold",1) ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_onhold") ?> >On-Hold</label></td>
		    <td width="168">&nbsp;</td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Class Description</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		    <td >&nbsp;</td>
		    <td >&nbsp;</td>
		</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_prefixseries") ?>>Numbering Prefix</label></td>
					  <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_prefixseries") ?>/></td>
	  </tr>
					<tr><td width="168"><label class="lblobjs"><b>Acquisition:</b></label></td>
					  <td>&nbsp;</td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_numseries") ?>>Numbering Digits</label></td>
					  <td width="168">&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_numseries") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_purchacct") ?>>Purchase Account</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_purchacct") ?>/>&nbsp;<input type="text"  size="50" <?php $page->businessobject->items->draw->text("u_purchacctname") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td><label class="lblobjs"><b>Depreciation:</b></label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_depreacct") ?>>Depreciation Account</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_depreacct") ?>/>&nbsp;<input type="text"  size="50" <?php $page->businessobject->items->draw->text("u_depreacctname") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_accumdepreacct") ?>>Accumulated Dep. Account</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_accumdepreacct") ?>/>&nbsp;<input type="text"  size="50" <?php $page->businessobject->items->draw->text("u_accumdepreacctname") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td><label class="lblobjs"><b>Retirement:</b></label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lossonsaleacct") ?>>Loss on Sale Account</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_lossonsaleacct") ?>/>&nbsp;<input type="text"  size="50" <?php $page->businessobject->items->draw->text("u_lossonsaleacctname") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gainonsaleacct") ?>>Gain on Sale Account</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_gainonsaleacct") ?>/>&nbsp;<input type="text"  size="50" <?php $page->businessobject->items->draw->text("u_gainonsaleacctname") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lossonretireacct") ?>>Loss on Retirement</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_lossonretireacct") ?>/>&nbsp;<input type="text"  size="50" <?php $page->businessobject->items->draw->text("u_lossonretireacctname") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
	</table>
</td></tr>	
		

