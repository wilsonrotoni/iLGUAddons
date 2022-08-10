<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	
	
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Establishment</label></td>
						<td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
	                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_category") ?>>Category</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_category") ?>></select></td>
        <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	  </tr>	
         <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_owner") ?>>Name of Owner</label></td>
            <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_owner") ?>/></td>
            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_manager") ?>>Name of Manager</label></td>
            <td>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_manager") ?>/></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Application No.</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Address</label></td>
            <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_address") ?>rows="1" cols="42"><?php echo $page->getitemstring("u_address"); ?></TEXTAREA></td>
            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_noofpersonnel") ?>>No. of Personnel</label></td>
            <td>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_noofpersonnel") ?>/></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Application Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_nowithhealthcert") ?>>No. w/ Health Cert.</label></td>
            <td>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_nowithhealthcert") ?>/></td>
          <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectbystatus") ?>>Inspection Status</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inspectbystatus") ?>></select></td>
        </tr>
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspector") ?>>Name of Inspector</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inspector") ?>></select></td>
          <td >&nbsp;</td>
          <td>&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectdate") ?>>Inspection Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_inspectdate") ?>/></td>
        </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">
            <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                <tr class="fillerRow10px">
                  <td></td>
                  </tr>
                <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_inspectorremarks") ?>>Inspection Report</label></td>
                    </tr>
                <tr><td width="168"><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_inspectorremarks") ?>rows="7" cols="100"><?php echo $page->getitemstring("u_inspectorremarks"); ?></TEXTAREA></td>
                    </tr>
                <tr class="fillerRow10px">
                  <td></td>
                  </tr>
                <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendation") ?>>Recommendation</label></td>
                    </tr>
                <tr><td width="168"><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_recommendation") ?>rows="5" cols="100"><?php echo $page->getitemstring("u_recommendation"); ?></TEXTAREA></td>
                    </tr>
            </table>            
	  </div>
		<div class="tabbertab" title="Inspection Report History">	
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_inspectorremarkhistory") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_inspectorremarkhistory") ?></TEXTAREA>
                </td></tr>
            </table>
		</div>
   	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php 
	if ($page->getvarstring("formType")!="lnkbtn") {
		$page->resize->addinput("u_inspectorremarks",30,-1);
		$page->resize->addinput("u_recommendation",30,490);
		$page->resize->addinput("u_inspectorremarkhistory",30,288);
	} 
?>	


