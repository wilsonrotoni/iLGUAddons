<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		

	<tr>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_type") ?>>Type of Test</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_type") ?>/></select></td>
	  <td width="168" >&nbsp;</td>
	  <td width="168" align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Doctor</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>/></select></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Template</label></td>
	  <td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?>/></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Notes">	
			<?php if ($page->getitemstring("u_format")=="rtf") { ?>
			<div id="divtab1Editor"  style="overflow:scroll; border:thick; border-width:thick">
				<div id="divEditor" name="divEditor" class="editable"><?php echo $page->getitemstring("u_remarks") ?></div>
			</div>	
			<?php } else { ?>
				<textarea cols="120" rows="8" style="display:block" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks"); ?></textarea>	
			<?php } ?>	
		</div>
	</div>		
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,111); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php if ($page->getitemstring("u_format")=="rtf") $page->resize->addtabpage("tab1","tab1Editor") ?>
<?php //$page->resize->addgridobject($objGrids[0],20,134) ?>		
<?php //$page->resize->addgridobject($objGrids[1],-1,148) ?>		
<?php if ($page->getitemstring("u_format")=="rtf") $page->resize->addelement("divtab1Editor",30,152) ?>
<?php if ($page->getitemstring("u_format")=="normal") $page->resize->addinput("u_remarks",30,152) ?>
<?php //$page->resize->addelement("iframeRemarks",35,121) ?>		


