<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Code</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> />&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isshared",1) ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_isshared") ?> >Ward</label></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_roomtype") ?>>Type of Room</label></td>
		    <td width="168">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_roomtype",null,null,null,null,"width:148px") ?> /></select></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Name</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_chargingrate") ?>>Rate Unit</label></td>
		    <td width="168">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_charginguom") ?> /></select></td>
		</tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
		    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name:u_type in ('NURSE','ER','SPLROOM')",":[All]")) ?> /></select></td>
	      <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_pricelist") ?>>Price List</label></td>
		    <td width="168">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pricelist") ?> /></select></td>
	  </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_miscfees") ?>>Miscellaneous Fees</label></td>
		    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_miscfees") ?> /></select></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Beds">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,181); ?>
<?php $page->resize->addgridobject($objGrids[0],20,220) ?>		

