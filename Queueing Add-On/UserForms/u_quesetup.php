<tr><td>
	<div id="divsetup" style="overflow:auto;">
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
		<tr>
		  <td width="168" ><label class="lblobjs"><b>Queueing:</b></label></td>
		  <td >&nbsp;<input type="checkbox" size="20" <?php $page->businessobject->items->userfields->draw->checkbox("u_genlink",1) ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_genlink") ?>>Link Generator</label></td>
	    </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_printer") ?>>Printer</label></td>
	      <td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_printer") ?> /></td>
	    </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_filepath") ?>>Display Video</label></td>
	      <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_filepath") ?> /></td>
	  </tr>
	</table>
	</div>
</td></tr>
<?php //$page->resize->addelement("divsetup",20,60) ?>	
	
<?php //$page->resize->addtab("tab1",-1,181); ?>
<?php //$page->resize->addgridobject($objGrids[0],20,240) ?>		

