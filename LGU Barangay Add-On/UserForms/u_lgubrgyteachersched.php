<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("u_schoolyr") ?>>School Year</label></td>
		  <td width="712" >&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_schoolyr") ?>/></td>
          <td width="139" align="right"><label <?php $page->businessobject->items->draw->caption("u_datefrm") ?>>Date From</label></td>
		  <td width="234" >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_datefrm") ?> /></td>
	  </tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("u_daycare") ?> >Daycare Center</label></td>
			<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_daycare") ?>></select></td>
            <td width="139" align="right"><label <?php $page->businessobject->items->draw->caption("u_dateto") ?>>Date To</label></td>
		  <td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_dateto") ?> /></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Teachers Schedule">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,180) ?>		

