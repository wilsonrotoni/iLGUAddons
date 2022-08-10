<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_active",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_active") ?>>Active</label></td>
	      <td width="168" >&nbsp;</td>
		<td  width="168" >&nbsp;</td>
	  </tr>
		<tr><td width="168"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="80"><label <?php $page->businessobject->items->draw->caption("code") ?> >Code</label></td><td align="right"><?php if($page->settings->data["autogenerate"]==1) { ?><select <?php $page->businessobject->items->userfields->draw->select("u_series",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select><?php } ?></td></tr></table></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		    <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_class") ?>>Class</label></td>
		<td  width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_class",null,null,null,null,"width:148px") ?> ></select></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Name</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		    <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?> >Section</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name:u_type in ('XRAY','RADIOLOGY','CTSCAN','ULTRASOUND','LABORATORY','HEARTSTATION')",":"),null,null,null,"width:148px") ?>/></select></td>
		</tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_specimen") ?> >Specimen</label></td>
		<td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_specimen") ?> /></td>
		  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_printformat") ?> >Print Format</label></td>
		<td  align=left width="168" >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_printformat",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Test Cases">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Notes Template">
			<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr ><td><?php $objGrid->draw(false); ?></td>
					<td>
						<div id="divEditor"  style=" overflow:scroll"><?php echo $page->getitemstring("remarks") ?></div>
						<textarea cols="120" rows="8" style="display:none" <?php genTextAreaHtml(createSchema("remarks")) ?>/></textarea>		
					</td>
				</tr>
			</table>		
		</div>		
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,181); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,220) ?>
<?php //$page->resize->addgridobject($objGrids[1],20,280) ?>		
<?php $page->resize->addgridobject($objGrid,-1,198) ?>		
<?php $page->resize->addelement("divEditor",560,184) ?>
<?php $page->resize->addinput("remarks",560,184) ?>
<?php //$page->resize->addgridobject($objGridPrices,-1,244) ?>