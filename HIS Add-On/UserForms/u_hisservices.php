<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_active",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_active") ?>>Active</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_vatable",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_vatable") ?>>Vatable</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_allowdiscount",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_allowdiscount") ?>>Cash Discount</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_billdiscount",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_billdiscount") ?>>Bill Discount</label></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_group") ?>>Group</label></td>
		<td  width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_group",null,null,null,null,"width:148px") ?> ></select></td>
	  </tr>
	<tr>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="80"><label <?php $page->businessobject->items->draw->caption("code") ?> > Code</label></td><td align="right"><?php if($page->settings->data["autogenerate"]==1) { ?><select <?php $page->businessobject->items->userfields->draw->select("u_series",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual"),null,null,null,"width:108px") ?> ></select><?php } ?></td></tr></table></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("code") ?>/></td>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_class") ?>>Class</label></td>
		<td  width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_class",null,null,null,null,"width:148px") ?> ></select></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Description</label></td>
		<td  align=left>&nbsp;<input type="text" size="80" <?php $page->businessobject->items->draw->text("name") ?>/></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?> >Section</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",null,null,null,null,"width:148px") ?>/></select></td>
	</tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?> >Profit Center</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_soacategory") ?> >SOA Category</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_soacategory",null,null,null,null,"width:148px") ?>/></select></td>
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
					  <td >&nbsp;<label class="lblobjs"><b>Procedure:</b></label></td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_proctype") ?> >Type</label></td>
						<td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_proctype") ?>/></select></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="188" >&nbsp;<label class="lblobjs"><b>Senior Citizen:</b></label></td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_scdiscamount") ?>>Discount</label></td>
	  <td  align=left >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_scdiscamount") ?>/></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td >&nbsp;</td>
					  <td  align=left >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
				 </table>
				 	</td>
					<td width="420"><?php $objGridPrices->draw(true); ?></td>
				</tr>
				</table>		
			</div>	  
		</div>
		<div class="tabbertab" title="Insurance Benefits">	
			<?php $objGrids[0]->draw(true); ?>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,181); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,220) ?>				
<?php //$page->resize->addgridobject($objGrid,20,158) ?>				
<?php $page->resize->addgridobject($objGridPrices,-1,184) ?>

