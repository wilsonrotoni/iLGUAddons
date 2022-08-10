<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jvno") ?>>JV No.</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_jvno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Posting Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
					</tr>
		
				</table>
			</div>
		</div>
		<div class="tabbertab" title="Fixed Asset Dist - A/P Invoice(Service) - Items">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,180) ?>		

