<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_template") ?>>Template</label></td>
	  <td  align=left>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_template") ?>/></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Start Date</label><label <?php $page->businessobject->items->userfields->draw->caption("u_starttime") ?>>Time</label></td>
	  <td width="168" align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
	  </tr>
	
	<tr>
	  <td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Descripion</label></td>
	  <td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>End Date</label><label <?php $page->businessobject->items->userfields->draw->caption("u_endtime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_endtime") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Test Cases">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Attachments">	
			<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
				  <tr >
					<td width="300" valign="top"><?php $objGrids[1]->draw() ?></td>
					<td width="5" valign="top">&nbsp;</td>
					<td valign="top">
						<div id="divPictures" style="overflow-y:auto; overflow-x:auto;" >
							<img id="PictureImg" src="" align="absmiddle" border=1 alt="" onDblClick="uploadAttachment()" style="display:none">
							<video id="video" name="video" src="" controls width="100%" height="100%" style="display:none">Your browser does not support the <code>video</code> element.</video>
						</div>	
					</td>
				  </tr>
			</table>
		</div>
		<div class="tabbertab" title="Notes">
			<div id="divtab1Editor"  style=" overflow:scroll">
				<div id="divEditor" name="divEditor" class="editable"><?php echo $page->getitemstring("u_remarks") ?></div>
			</div>	
		</div>		
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,111); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addtabpage("tab1","tab1Editor") ?>
<?php $page->resize->addgridobject($objGrids[0],20,134) ?>		
<?php $page->resize->addgridobject($objGrids[1],-1,148) ?>		
<?php $page->resize->addelement("divPictures",362,132) ?>
<?php //$page->resize->addelement("iframeRemarks",35,121) ?>		


