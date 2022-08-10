<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td  width="140">&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype",null,null,null,null,"width:126px") ?>/></select></td>
	    <td><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>
	  <td  align=left>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_icdcode") ?>>ICD</label></td>
		<td  align=left rowspan="2" >&nbsp;<textarea type="text" rows="3" cols="13" <?php $page->businessobject->items->userfields->draw->text("u_icdcode") ?>/><?php echo $page->getitemstring("u_icdcode") ?></textarea></td>
	    <td  align=left rowspan="2">&nbsp;<textarea type="text" rows="3" cols="45" <?php $page->businessobject->items->userfields->draw->text("u_icddesc") ?>/><?php echo $page->getitemstring("u_icddesc") ?></textarea></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_casetype") ?>>Case Type</label></td>
		<td  align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_casetype") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rvscode") ?>>RVS</label></td>
		<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_rvscode") ?>/></td>
	  <td  align=left>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_rvsdesc") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rvu") ?>>RVU</label></td>
		<td  align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_rvu") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_procedures") ?>>Procedures/Operations</label></td>
		<td  align=left>&nbsp;</td>
	    <td  align=left rowspan="2">&nbsp;<textarea type="text" rows="2" cols="45" <?php $page->businessobject->items->userfields->draw->text("u_procedures") ?>/><?php echo $page->getitemstring("u_procedures") ?></textarea></td>
	    <td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	
	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Attending Doctor</label></td>
	  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorservice") ?>>Type of Service</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorservice") ?>/></select></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Diagnosis/Medication">
        	<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
			<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_medication") ?>>Medication</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_medication") ?>rows="2" cols="114"><?php echo $page->getitemstring("u_medication"); ?></TEXTAREA></td>
			  </tr>
			<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_historyillness") ?>>History of Illness</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_historyillness") ?>rows="2" cols="114"><?php echo $page->getitemstring("u_historyillness"); ?></TEXTAREA></td>
			  </tr>
			<tr>
				  <td width="168" valign="top" ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Final Diagnosis</label></td>
				  <td colspan="4">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="<?php if ($page->getvarstring("formType")=="lnkbtn") {?>3<?php } else { ?>4<?php } ?>" cols="114"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
			  </tr>
			
            </table>
		</div>
		<div class="tabbertab" title="Operations/Procedures">
			<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_orproc") ?>rows="10" cols="140"><?php echo $page->getitemstring("u_orproc"); ?></TEXTAREA>
		</div>
		<div class="tabbertab" title="ICD">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td><?php $objGrids[1]->draw(true) ?></td>
					<td width="18">
						<div><a class="button2" href="" onClick="u_moveRowUpICDGPSHIS();return false;" ><img src="imgs/asc_<?php echo $_SESSION["theme"] ?>.gif" border="0"></a></div>
						<div><a class="button2" href="" onClick="u_moveRowDnICDGPSHIS();return false;" ><img src="imgs/desc_<?php echo $_SESSION["theme"] ?>.gif" border="0"></a></div>
					</td>
				</tr>
			</table>			
		</div>
		<div class="tabbertab" title="RVS">
			<?php $objGrids[2]->draw(true) ?>
		</div>
		<div class="tabbertab" title="Attachments">	
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					  <tr >
						<td width="300" valign="top"><?php $objGrids[0]->draw() ?></td>
						<td width="5" valign="top">&nbsp;</td>
						<td valign="top">
							<div id="divPictures" style="overflow-y:auto; overflow-x:auto;" >
								<img id="PictureImg" src="" align="absmiddle" border=1 alt="" style="display:none">
								<video id="video" name="video" src="" controls width="100%" height="100%" style="display:none">Your browser does not support the <code>video</code> element.</video>
								<object id="contentarea" name="contentarea" data="" type="application/pdf" width="100%" height="100%" style="display:none"><p>It appears you don't have a PDF plugin for this browser.
  No biggie... you can <a href="myfile.pdf">click here to download the PDF file.</a></p></object>
							</div>	
						</td>
					  </tr>
				</table>
			</div>		
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,351); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addgridobject($objGrids[0],-1,346) ?>
<?php $page->resize->addgridobject($objGrids[1],30,368) ?>
<?php $page->resize->addgridobject($objGrids[2],20,368) ?>
<?php $page->resize->addelement("divPictures",362,331) ?>
<?php 
	if ($page->getvarstring("formType")=="lnkbtn") {
		//$page->resize->addinput("u_medication",210,-1);
		//$page->resize->addinput("u_historyillness",210,-1);
		//$page->resize->addinput("u_remarks",210,-1);	
		//$page->resize->addinput("u_orproc",35,-1);
	} else {
		$page->resize->addinput("u_medication",210,-1);
		$page->resize->addinput("u_historyillness",210,-1);
		$page->resize->addinput("u_remarks",210,441);	
		$page->resize->addinput("u_orproc",35,321);
	}	
?>	
<?php //$page->resize->addinput("u_icddesc",680,-1) ?>		
<?php //$page->resize->addinput("u_rvsdesc",680,-1) ?>		
<?php //$page->resize->addinput("u_procedures",680,-1) ?>		


