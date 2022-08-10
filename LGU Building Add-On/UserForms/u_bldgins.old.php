<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Name of Business</label></td>
            <td width="428">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
        <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspector") ?>>Name of Inspector</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inspector") ?>></select></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_issdate") ?>>Date Issued</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_issdate") ?>/></td>
	  </tr>
	<tr>
<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectdate") ?>>Inspection Date</label></td>
<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_inspectdate") ?>/></td>
<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Application No.</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
                        </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectbystatus") ?>>Inspector Status</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inspectbystatus") ?>></select></td>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Date of Application</label></td>
<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;"> </div>
	  </div>
		<div class="tabbertab" title="Checklist">
		</div>
	</div>
</td></tr>		
<td><tr>		
    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr class="fillerRow10px">
          <td></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendations") ?>>Recommendations</label></td>
            <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_recommendations") ?>rows="1" cols="35"><?php echo $page->getitemstring("u_recommendations"); ?></TEXTAREA></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendby") ?>>Recommended By</label></td>
            <td width="428">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_recommendby") ?>/></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendbystatus") ?>>Status/Date</label></td>
						<td width="168">&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_recommendbystatus") ?>/>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_recommenddate") ?>/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
         <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dispositionby") ?>>Disposition By</label></td>
          <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_dispositionby") ?>/></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dispositionbystatus") ?>>Status/Date</label></td>
						<td >&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_dispositionbystatus") ?>/>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_dispositiondate") ?>/></td>
        </tr>
    </table>
</td></tr>		

<?php $page->resize->addtab("tab1",-1,261); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
		
<?php //if ($page->getvarstring("formType")!="lnkbtn") $page->resize->addinput("u_recommendations",230,-1) ;//-1?>	
