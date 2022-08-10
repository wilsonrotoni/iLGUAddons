<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td nowrap="nowrap" width="1%"><label <?php genCaptionHtml(array("name"=>"datecreated")) ?> >Date Modified</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" size="30" <?php genInputTextHtml(array("name"=>"datecreated")) ?> /></td>
    <td nowrap="nowrap" width="1%"><label <?php genCaptionHtml($page->settings->boschema["T0"]["u_menucommand"]) ?> >Menu</label></td>
	<td nowrap="nowrap">&nbsp;<select <?php genSelectHtml($page->settings->boschema["T0"]["u_menucommand"],array("loadudflinktable","u_audittrailsetup:code:name",":All")) ?> ></select></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml($page->settings->boschema["T0"]["u_action"]) ?> >Action</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml($page->settings->boschema["T0"]["u_action"]) ?> /></td>
</tr>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"branch")) ?> >Branch</label></td>
	<td nowrap="nowrap">&nbsp;<select <?php genSelectHtml(array("name"=>"branch"),array("loadudflinktable","branches:branchcode:branchname",":All"),"",null,false,"width:250px") ?> ></select></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml($page->settings->boschema["T0"]["u_objectcode"]) ?> >Object Code</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" size="30" <?php genInputTextHtml($page->settings->boschema["T0"]["u_objectcode"]) ?> /></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml($page->settings->boschema["T0"]["u_progid2"]) ?> >Program ID</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml($page->settings->boschema["T0"]["u_progid2"]) ?> /></td>
</tr>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"createdby")) ?> >User</label></td>
	<td nowrap="nowrap">&nbsp;<select <?php genSelectHtml(array("name"=>"createdby"),array("loadudflinktable","users:userid:username",":All"),"",null,false,"width:250px") ?> ></select></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml($page->settings->boschema["T0"]["u_sessionid"]) ?> >Session ID</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" size="30" <?php genInputTextHtml($page->settings->boschema["T0"]["u_sessionid"]) ?> /></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml($page->settings->boschema["T0"]["u_ip"]) ?> >IP Address</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml($page->settings->boschema["T0"]["u_ip"]) ?> /></td>
</tr>

<?php
$objRs = new recordset(NULL,$objConnection);
$objAudittrailfields = new documentlinesschema_br(NULL,$objConnection,"u_audittrailfields");
$objAudittrailtables = new documentlinesschema_br(NULL,$objConnection,"u_audittrailtables");
$objAudittrailtables->queryopen($objAudittrailtables->selectstring(0)." WHERE COMPANY='".$objMaster->company."' AND BRANCH='".$objMaster->branch."' AND DOCID='".$objMaster->docid."'");
while($objAudittrailtables->queryfetchrow()) {
	$httpVars = array_merge($httpVars,$objAudittrailtables->sethttpfields("T1"));
?>

<tr><td colspan="6"><hr /></td></tr>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"u_tablenameT1")) ?> >Tablename</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml(array("name"=>"u_tablenameT1")) ?> /></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"u_codeT1")) ?> >Code/Document No.</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml(array("name"=>"u_codeT1")) ?> /></td>
    <?php if($httpVars["df_u_documentdateT1"]<>"") { ?>
    <td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"u_documentdateT1")) ?> >Document Date</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml(array("name"=>"u_documentdateT1")) ?> /></td>
    <?php } ?>
</tr>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"u_actionT1")) ?> >Action</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml(array("name"=>"u_actionT1")) ?> /></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"u_docidT1")) ?> >Document ID</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml(array("name"=>"u_docidT1")) ?> /></td>
    <td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"u_lineidT1")) ?> >Line ID</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" <?php genInputTextHtml(array("name"=>"u_lineidT1")) ?> /></td>
</tr>

<?php if($objAudittrailtables->getudfvalue("u_notes")<>"") { ?>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml(array("name"=>"u_notesT1")) ?> >Notes</label></td>
	<td nowrap="nowrap" colspan="5">&nbsp;<textarea cols="65" style="height:40px; width:90%" <?php genInputTextHtml(array("name"=>"u_notesT1")) ?> ><?php echo $httpVars['df_u_notesT1'] ?></textarea></td>
</tr>
<?php } ?>

<?php
	$arrModifications = array();
	$arrColumns = array();
	
	$objRs->queryopen("SELECT A.COLUMN_NAME,IF(B.FIELDDESC is NULL,A.COLUMN_COMMENT,B.FIELDDESC) FROM INFORMATION_SCHEMA.COLUMNS A LEFT JOIN OBJECTCAPTIONS B ON OBJECTCODE='".$objMaster->getudfvalue("u_objectcode")."' AND A.COLUMN_NAME=B.FIELDNAME WHERE A.TABLE_SCHEMA='".$_SESSION['dbname']."' AND A.TABLE_NAME='".$objAudittrailtables->getudfvalue("u_tablename")."'");
	while($objRs->queryfetchrow()) {
		$arrColumns[strtolower($objRs->fields[0])] = $objRs->fields[1];
	}
	
	$objAudittrailfields->queryopen($objAudittrailfields->selectstring(0)." WHERE COMPANY='".$objMaster->company."' AND BRANCH='".$objMaster->branch."' AND DOCID='".$objMaster->docid."' AND U_LINEID='".$objAudittrailtables->lineid."'");
	while($objAudittrailfields->queryfetchrow()) {
		$arrModifications[$objAudittrailfields->getudfvalue("u_fieldname")]["OLD"] = $objAudittrailfields->getudfvalue("u_oldvalue");
		$arrModifications[$objAudittrailfields->getudfvalue("u_fieldname")]["NEW"] = $objAudittrailfields->getudfvalue("u_newvalue");
	} # end while $objAudittrailtables
	
	if(count($arrModifications)>0) {
		print ' <tr><td colspan="6">';
		print ' 	<div style="width:950px; overflow-x:scroll;">';
		print '		<table class="tableBox" cellpadding="0" cellspacing="0" border="0">';
		print '		<tr class="tableBoxHead">';
		print '			<td nowrap="nowrap">&nbsp;</td>';
		foreach($arrModifications as $fieldname => $fielddata) {
			if(strlen($fielddata["OLD"])>30 || strlen($fielddata["NEW"])>30) print '		<td nowrap="nowrap">&nbsp;'.str_pad($arrColumns[$fieldname],120+strlen($arrColumns[$fieldname]),"&nbsp;").'&nbsp;</td>';
			else print '		<td nowrap="nowrap">&nbsp;'.$arrColumns[$fieldname].'&nbsp;</td>';
		}
		print '		</tr>';
		print '		<tr class="tableBoxRow">';
		print '			<td nowrap="nowrap">&nbsp;Old Value&nbsp;</td>';
		foreach($arrModifications as $fieldname => $fielddata) {
			$style = (strlen($fielddata["OLD"])>30 || strlen($fielddata["NEW"])>30) ? '' : 'nowrap="nowrap"';
			print '		<td '.$style.'>&nbsp;'.$fielddata["OLD"].'&nbsp;</td>';
		}
		print '		</tr>';
		print '		<tr class="tableBoxRow">';
		print '			<td nowrap="nowrap">&nbsp;New Value&nbsp;</td>';
		foreach($arrModifications as $fieldname => $fielddata) {
			$style = (strlen($fielddata["OLD"])>30 || strlen($fielddata["NEW"])>30) ? '' : 'nowrap="nowrap"';
			print '		<td '.$style.'>&nbsp;'.$fielddata["NEW"].'&nbsp;</td>';
		}
		print '		</tr>';
		print '		</table>';
		print ' 	</div>';
		print '</td></tr>';
	}
} # end while $objAudittrailtables
?>

</table>