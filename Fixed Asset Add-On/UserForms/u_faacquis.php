<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" ><label <?php genCaptionHtml($schema["sourcedocument"],"") ?>>Source Document</label></td>
		<td align=left>&nbsp;<input type="text"  size="25"<?php genInputTextHtml($schema["sourcedocument"]) ?> /></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr>    <td ></td>
		<td  align=left>&nbsp;<?php if (!isEditMode()){?><a class="button" href="" onClick="formSubmit('search');return false;" >Search</a> <?php } ?></td>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Posting Date</label></td>
                <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	  <td ><label <?php genCaptionHtml($schema["department"],"") ?>>Department</label></td>
          <td  align=left>&nbsp;<input type="text"  size="25"<?php genInputTextHtml($schema["department"]) ?> /></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jvno") ?>>JV No.</label></td>
        <td><?php genLinkedButtonHtml("u_jvno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jvno") ?>/></td>
        </tr>
        <tr>    <td ><label <?php genCaptionHtml($schema["employee"],"") ?>>Employee</label></td>
		<td  align=left>&nbsp;<input type="text"  size="25"<?php genInputTextHtml($schema["employee"]) ?> /></td>
		<td ><label <?php genCaptionHtml($schema["startproptag"],"") ?>>Property Tag Start Number</label></td>
		<td  align=left>&nbsp;<input type="text"  size="16"<?php genInputTextHtml($schema["startproptag"]) ?> /></td>
	</tr>
        
	
        
	
	<tr>    <td ></td>
		<td  align=left>&nbsp;<?php if (!isEditMode()){?><a class="button" href="" onClick="ApplytoGridDepartmentEmployee();return false;" >Apply To Grid</a> <?php } ?></td>
		<td width="168"></td>
                <td  align=left>&nbsp;<?php if (!isEditMode()){?><a class="button" href="" onClick="ApplytoGridPropertyTag();return false;" >Apply To Grid</a> <?php } ?></td>
	</tr>
        <tr><td>&nbsp;</td></tr>	
         
	</table>
</td></tr>	
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
 </tr>	
<?php $page->resize->addgridobject($objGrids[0],10,220) ?>		

