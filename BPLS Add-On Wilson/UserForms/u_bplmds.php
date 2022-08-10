	
<tr><td>

				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td colspan="4"><label class="lblobjs"><b>Business Details</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("u_orgtype") ?>>Ownership</label></td>
						<td colspan="3"><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "SINGLE") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Single</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "PARTNERSHIP") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Partnership</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "CORPORATION") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Corporation</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "COOPERATIVE") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Cooperative</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "FOUNDATION") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Foundation</label></td>
						<td width="168"></td>
						<td colspan="3">&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("name") ?>>Business Name</label></td>
						<td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->draw->text("name") ?>/></td>
						<td width="168"></td>
						<td colspan="3">&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last/First/Middle Name</label></td>
						<td colspan="3">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/><label class="lblobjs">,&nbsp;</label><input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
						<td width="168"></td>
						<td colspan="3">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tradename") ?>>Trade Name</label></td>
						<td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_tradename") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_btelno") ?>>Tel No.</label></td>
						<td colspan="3">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_btelno") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                    <tr>
                    <td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?> >Account No</label></td>
						<td colspan="3">&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
					  <td width="168"></td>
						<td colspan="3">&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td colspan="3">&nbsp;</td>
					  <td >&nbsp;</td>
					  <td colspan="3">&nbsp;</td>
					  <td>&nbsp;</td>
                                     </tr>
					<tr><td colspan="4"><label class="lblobjs"><b>Business Address</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Building No.</label></td>
                                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgname") ?>>Building Name</label></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/></td>
                                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgname") ?>/></td>
                                           <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Unit No.</label></td>
                                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bstreet") ?>>Street</label></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bunitno") ?>/></td>
                                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bstreet") ?>/></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Floor No</label></td>
                                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bstreet") ?>>Block</label></td>
                                           <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bfloorno") ?>/></td>
                                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bblock") ?>/></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Subdivision</label></td>
                                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>Barangay</label></td>
                                             <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bvillage",null,null,null,null, "width:150px") ?>></select></td>
                                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy",null,null,null,null, "width:150px") ?>></select></td>
                                           <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
				</table>
</td></tr>		
		

