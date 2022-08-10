	
<tr><td>

				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td colspan="4"><label class="lblobjs"><b>APPLICANT INFORMATIONS</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_applicanttype") ?>>Type of Applicant</label></td>
						<td colspan="3">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_applicanttype","OWNER") ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_applicanttype") ?>>Owner</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_applicanttype","LESSEE") ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_applicanttype") ?>>Lessee</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ownerlastname") ?>>Last/First/Middle Name</label></td>
						<td colspan="3">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/><label class="lblobjs">,&nbsp;</label><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
						<td width="168"></td>
						<td colspan="3">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Address</label></td>
						 <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_address") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_address"); ?></TEXTAREA></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr><td width="168"></td>
						<td colspan="3">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_contactno") ?>>Tel No.</label></td>
						<td colspan="3">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_contactno") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr>
                                            <td width="168"><label <?php $page->businessobject->items->draw->caption("docno") ?> >Applicant Number</label></td>
                                            <td colspan="3">&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
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
					<tr><td colspan="4"><label class="lblobjs"><b>CORPORATION DETAILS</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_corpname") ?>>Corporation Name</label></td>
						<td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_corpname") ?>/></td>
						<td ></td>
						<td colspan="3">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_corpaddress") ?>>Address</label></td>
						 <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_corpaddress") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_corpaddress"); ?></TEXTAREA></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					<tr><td></td>
						<td colspan="3">&nbsp;</td>
						<td width="168"></td>
						<td colspan="3">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_corptelno") ?>>Tel No.</label></td>
						<td colspan="3">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_corptelno") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>TIN No.</label></td>
						<td colspan="3">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcno") ?>>CTC No.</label></td>
						<td colspan="3">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ctcno") ?>/></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
</td></tr>		
		

