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
	  <td  align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>
	  <td  align=left>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>
	

	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
		<td  align=left>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>/></select></td>
	  <td  align=left>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_doctime") ?>/></td>
	  </tr>
	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_roomno") ?>>Room /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_bedno") ?>>Bed No.</label></td>
	  <td  align=left colspan="2">&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_roomno") ?>/>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bedno") ?>/></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Attending Doctor</label></td>
	  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>/></select></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_alerttype") ?>>Type of Alert</label></td>
	  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_alerttype") ?>/></select></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Remarks">
			<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA>
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
<?php $page->resize->addtab("tab1",-1,341); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addgridobject($objGrids[0],-1,256) ?>
<?php $page->resize->addelement("divPictures",362,241) ?>
<?php $page->resize->addinput("u_remarks",35,241) ?>		
<?php //$page->resize->addinput("u_icddesc",680,-1) ?>		
<?php //$page->resize->addinput("u_rvsdesc",680,-1) ?>		
<?php //$page->resize->addinput("u_procedures",680,-1) ?>		


