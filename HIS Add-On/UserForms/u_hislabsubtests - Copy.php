<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
	<tr>
	  <td width="168" >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
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
		<div class="tabbertab" title="Remarks">
			<iframe name="iframeRemarks" id="iframeRemarks" style="overflow-y:hidden; overflow-x:hidden;" src="mainbody.php" frameborder="0" marginwidth="0" marginheight="0" width="100%"></iframe>
		</div>
		<div class="tabbertab" title="Attachments">	
			<div id="divtab1Pictures" style="overflow-y:auto; overflow-x:auto;" >
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					  <tr >
						<td width="300" valign="top"><?php $objGrids[1]->draw() ?></td>
						<td width="5" valign="top">&nbsp;</td>
						<td valign="top">
							<img id="PictureImg" src="" align="absmiddle" border=1 alt="" onDblClick="uploadAttachment()">
						</td>
					  </tr>
				</table>
			</div>		
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,101); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addgridobject($objGrids[0],20,124) ?>		
<?php $page->resize->addgridobject($objGrids[1],-1,138) ?>		
<?php $page->resize->addelement("iframeRemarks",35,121) ?>		


