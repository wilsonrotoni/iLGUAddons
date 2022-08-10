<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_active",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_active") ?>>Active</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_ispackage",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ispackage") ?>>Package</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_vatable",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_vatable") ?>>Vatable</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_isgeneric",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isgeneric") ?>>Generic</label></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_type") ?>>Type</label></td>
		<td  width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_type",null,null,null,null,"width:148px") ?> ></select></td>
	  </tr>
	<tr>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="80"><label <?php $page->businessobject->items->draw->caption("code") ?> >Item Code</label></td><td align="right"><?php if($page->settings->data["autogenerate"]==1) { ?><select <?php $page->businessobject->items->userfields->draw->select("u_series",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select><?php } ?></td></tr></table></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("code") ?>/>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_barcode") ?>>Barcode</label>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_barcode") ?>/></td>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_group") ?>>Group</label></td>
		<td  width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_group",null,null,null,null,"width:148px") ?> ></select></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Description</label></td>
		<td  align=left>&nbsp;<input type="text" size="60" <?php $page->businessobject->items->draw->text("name") ?>/></td>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_class") ?>>Class</label></td>
		<td  width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_class",null,null,null,null,"width:148px") ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_brandname") ?>>Brand Name</label></td>
		<td  align=left valign="top">&nbsp;<input type="text" size="60" <?php $page->businessobject->items->userfields->draw->text("u_brandname") ?>/></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_subclass") ?>>Sub-Class</label></td>
		<td  width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_subclass",null,null,null,null,"width:148px") ?> ></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_genericname") ?>>Generic Name</label></td>
		<td  align=left valign="top">&nbsp;<input type="text" size="60" <?php $page->businessobject->items->userfields->draw->text("u_genericname") ?>/></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?> >Section</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_drugclass") ?> >Drug Classification</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_drugclass") ?>/></select></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_template") ?> >Result Template</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_template",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_make") ?> >Manufacturer</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_make") ?>/></select></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_soacategory") ?> >SOA Category</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_soacategory",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_uom") ?>>Unit</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_uom") ?>/></select></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?> >Profit Center</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter",array("loadudflinktable","profitcenters:profitcenter:profitcentername",":"),null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_isstat",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isstat") ?>>Stat</label></td>
		  <td >&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_statperc") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_statperc") ?>>% or</label><label <?php $page->businessobject->items->userfields->draw->caption("u_statamount") ?>>Amount</label>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_statamount") ?> /></td>
	  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_isstock",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isstock") ?> >Stock /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_manageby") ?> >Manage By</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_manageby",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_isrf",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isrf") ?>>Reader's Fee</label></td>
		  <td >&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_rfperc") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_rfperc") ?>>% or</label><label <?php $page->businessobject->items->userfields->draw->caption("u_rfamount") ?>>Amount</label>&nbsp;<input type="text" size="9" <?php $page->businessobject->items->userfields->draw->text("u_rfamount") ?> />&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_rfallowscdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_rfallowscdisc") ?>>Apply SC Disc</label></td>
	  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_isfixedasset",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_faclass") ?> >Fixed Asset</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_faclass",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">	
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td valign="top">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td width="188">&nbsp;<label class="lblobjs"><b>Purchasing:</b></label></td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_preferredsuppno") ?>>Preferred Supplier</label></td>
	  <td  align=left >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_preferredsuppno") ?>/>&nbsp;<label id="cf_u_preferredsuppname" class="lblobjs"><?php echo $page->getitemstring("u_preferredsuppname"); ?></label></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_uompu") ?>>Unit /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_numperuompu") ?>>Qty</label></td>
	  <td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_uompu") ?>/></select>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_numperuompu") ?>/></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_packqty") ?>>Packing Qty</label></td>
	  <td  align=left >&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_packqty") ?>/></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_salespricing") ?>><b>Sales:</b></label></td>
					  <td  align=left >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_nonvatcs",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_nonvatcs") ?>>Non-VAT Cash Sales</label></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_salespricing") ?> >Pricing</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_salespricing") ?>/></select></td>
					<td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label class="lblobjs">Markup (%)</label></td>
					  <td  align=left >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_salesmarkup2") ?>>OPD:</label><input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_salesmarkup2") ?>/>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_salesmarkup") ?>>IPD:</label><input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_salesmarkup") ?>/></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;<label class="lblobjs"><b>Discount:</b></label></td>
					  <td  align=left >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_allowdiscount",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_allowdiscount") ?>>Cash Discount</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_billdiscount",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_billdiscount") ?>>Bill Discount</label></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_disctype") ?> >Type</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disctype") ?>/></select></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_scdiscamount") ?>><b>Senior Citizen:</b></label></td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_scdiscamount") ?>>Discount</label></td>
	  <td  align=left >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_scdiscamount") ?>/></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_scstatdiscamount") ?>>Stat Discount</label></td>
	  <td  align=left >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_scstatdiscamount") ?>/></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
				 </table>
				 	</td>
					<td width="420" valign="top"><?php $objGridPrices->draw(true); ?></td>
				</tr>
				</table>		
			</div>	  
		</div>
		<div class="tabbertab" title="Package Items">	
			<?php $objGrids[2]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Sections">	
			<?php $objGrids[1]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Stock Level">	
			<?php $objGridStocks->draw(true); ?>
		</div>
		<div class="tabbertab" title="Insurance Benefits">	
			<?php $objGrids[0]->draw(true); ?>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,301); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,340) ?>				
<?php $page->resize->addgridobject($objGrids[1],20,340) ?>				
<?php $page->resize->addgridobject($objGrids[2],20,340) ?>				
<?php $page->resize->addgridobject($objGridStocks,20,326) ?>				
<?php //$page->resize->addgridobject($objGrid,20,178) ?>				
<?php //$page->resize->addgridobject($objGridPrices,-1,224) ?>
<?php $page->resize->addgridobject($objGridPrices,-1,304) ?>

