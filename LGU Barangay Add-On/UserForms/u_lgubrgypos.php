<tr><td>&nbsp;</td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td valign="top" width="550">
			<div class="popupwin" style="background-color:#FFFFFF;">
				<table class="popupwinTbl" border="0" width="100%" cellspacing="0" cellpadding="0" height="18">
					<tr class="popupwinheadrow">
						<td width="10" align="right">&nbsp;</td>
						<td width="168"><label id="cf_trxtype"><b>TOTAL SALES</b></label></td>
						<td align="center">&nbsp;<?php if($page->getitemstring("u_status")=="CN") echo "*** CANCELLED ***";?></td>
						<td width="100" align="right"><label ><b>TOTAL ITEMS:</b></label></td>
						<td width="80" align="right"><label id="cf_totalquantity">0.00</label></td>
						<td width="10" align="right">&nbsp;</td>
					</tr>
				</table>
				<table cellpadding="0" cellspacing="0" border="0" height="77">
					<tr>
						<td width="10" align="right">&nbsp;</td>	
						<td align="right"><input type="text" size=12 disabled style="font-size:60px;height:69px;text-align:right;" <?php genInputTextHtml(array("name"=>"totalamountdisplay"),"") ?> /></td>
						<td width="10" align="right">&nbsp;</td>
					</tr>
				</table>					
			</div>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr class="fillerRow5px"><td ></td></tr>
				</table>	
			<?php $objGrids[0]->draw(true) ?>	  
		</td>
		<td width="10"></td>
		<td width="276" valign="top">
		<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="108" ><label <?php $page->businessobject->items->draw->caption("docno") ?> >O.R. No.</label></td>
			<td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
			<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
		</tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>PPF No.</label></td>
		  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
		  </tr>	
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_custno") ?>>Customer No.</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_custno") ?>/></td>
					</tr>
					<tr><td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_custname") ?>>Customer Name</label></td>
						<td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_custname") ?> rows="1" cols="17"><?php echo $page->getitemstring("u_custname"); ?></TEXTAREA></td>
					</tr>
					<tr>
					  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Address</label></td>
					  <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_address") ?> rows="1" cols="17"><?php echo $page->getitemstring("u_address"); ?></TEXTAREA></td>
		  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_email") ?>>Email Address</label></td>
		  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_email") ?>/></td>
		  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_contactno") ?>>Contact</label></td>
		  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_contactno") ?>/></td>
		  </tr>
					<tr>
					   <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tinno") ?>>TIN No.</label></td>
		  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_tinno") ?>/></td>
		  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_collector") ?>>Collector</label></td>
		  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_collector",null,null,null,null,"width:158px") ?>/></select></td>
		  </tr>
                <tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cashierapptype") ?>>Applications</label></td>
		  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cashierapptype",null,null,null,null,"width:158px") ?>/></select></td>
		  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
					  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter",null,null,null,null,"width:158px") ?>/></select></td>
		  </tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_billno") ?>>Bill No.</label></td>
		  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_billno") ?>/></td>
		  <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bplapprefno") ?>>BPLS Refno</label></td>
		  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bplsseries",array("loaddocseries","U_LGUBPLAPPSREFNO","-1:Manual")) ?> ></select>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_bplapprefno") ?>/></td>
		  </tr>
					<tr class="fillerRow5px">
					  <td ></td>
					  <td></td>
		  </tr>
					<tr>
					  <td colspan="2" align="center"><div class="popupwin" style="background-color:#FFFFFF;"><table class="popupwinTbl" border="0" width="274" cellspacing="0" cellpadding="0" height="18">
					<tr class="popupwinheadrow">
						<td width="10" align="right">&nbsp;</td>
						<td align="center">Product Info</td>
						<td width="10" align="right">&nbsp;</td>
					</tr>
					<tr>
					<td colspan="3"><img id="PictureImg" src="" align="absmiddle" border=0 alt="" width="274" height="55"></td>
					</tr>
					
				</table></div></td>
		  </tr>
					<tr class="fillerRow5px">
					  <td ></td>
					  <td></td>
		  </tr>
					
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalbefdisc") ?>>Sub-Total</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalbefdisc") ?>/></td>
					</tr>
					
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
						<td><?php if ( $page->getitemstring("u_status")!="P" && $page->getitemstring("u_trxtype")=="S") genLinkedButtonHtml("u_totalamount","","showPopupFrame('popupFrameTotalAmount',true);setInputAmount('u_totalamount2',getInputNumeric('u_totalamount'));focusInput('u_totalamount2')",null,true);  else echo "&nbsp;" ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
					</tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paidamount") ?>>Payments</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_paidamount") ?>/></td>
					</tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Due Amount</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
					</tr>
					<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_partialpay") ?>>Payment</label></td>
					  <td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_partialpay","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_partialpay") ?>>Partial</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_partialpay","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_partialpay") ?>>Full</label></td>
		  </tr>
					<tr>
					  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
						<td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?> rows="1" cols="17"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
		  </tr>
					<tr>
					  <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_docnos") ?>>OR No/s</label></td>
						<td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_docnos") ?> rows="1" cols="17"><?php echo $page->getitemstring("u_docnos"); ?></TEXTAREA></td>
		  </tr>
		</table>
	</td></tr>
<?php $page->resize->addgridobject($objGrids[0],293,195) ?>