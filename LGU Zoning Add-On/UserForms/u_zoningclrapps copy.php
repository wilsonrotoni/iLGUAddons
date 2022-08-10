<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="188" ><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
		<td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Name of Business</label></td>
                <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_natureofbusiness") ?>>Nature of Business</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_natureofbusiness") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Application Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>
		
       
        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Location</label></td>
            <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_address") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_address"); ?></TEXTAREA></td>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_insno") ?>>Inspection Order No.</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_insno") ?>/></td>
        </tr>
        <tr><td width="168">&nbsp;</td>
            <td width="168"></td>
             <td>&nbsp;</td>
        </tr>
        
        <tr><td ><label class="lblobjs">Project Owner/Applicant:</label></td>
            <td>&nbsp;</td>
             <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bplappno") ?>>Business Application No.</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_bplappno") ?>/></td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
            <td width="168"></td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
            <td width="168"></td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
            <td width="168"></td>
            <td>&nbsp;</td>
        </tr>
       <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_owneraddress") ?>>Address</label></td>
            <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_owneraddress") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_owneraddress"); ?></TEXTAREA></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
         <tr><td>&nbsp;</td>
            <td width="168"></td>
            <td>&nbsp;</td>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_authrep") ?>>Authorized Representative</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_authrep") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_contactno") ?>>Contact No.</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_contactno") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
       <tr class="fillerRow5px">
          <td colspan="4"></td>
        </tr>
        <tr>
          <td><label <?php $page->businessobject->items->userfields->draw->caption("u_appnature") ?>>Nature of Application</label></td>
          <td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature","New Application") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature","New Application") ?>>New Application</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature","Renewal") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature","Renewal") ?>>Renewal</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_appnature","Others") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_appnature","Others") ?>>Others</label>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_appnatureothers") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_istup") ?>>TUP</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_istup") ?>></select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px">
          <td colspan="4"></td>
        </tr>
        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_orsfamt") ?>>Zoning Fee</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_orsfamt") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px">
          <td colspan="4"></td>
        </tr>
        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px">
          <td colspan="4"></td>
        </tr>
        <tr><td ></td>
            <td colspan="3">&nbsp;&nbsp;<?php $objGrids[0]->draw(true); ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
<!--        <tr><td ><label class="lblobjs">Building Description:</label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_noofunits") ?>>No. of Units</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_noofunits") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_noofflrs") ?>>No. of Storey/Floors
</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_noofflrs") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_lotarea") ?>>Lot Area</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lotarea") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_totflrareabldg") ?>>Total Floor Area of Building</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totflrareabldg") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_businessarea") ?>>Floor Area of Business</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_businessarea") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px">
          <td colspan="4"></td>
        </tr>
        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_capital") ?>>Capitalization</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_capital") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px">
          <td colspan="4"></td>
        </tr>
        <tr>
          <td>&nbsp;<label class="lblobjs">No. of Employee</label></td>
          <td><label <?php $page->businessobject->items->userfields->draw->caption("u_mempcount") ?>>Male:</label>&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_mempcount") ?>/>&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_fempcount") ?>>Female:</label>&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_fempcount") ?>/>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px">
          <td colspan="4"></td>
        </tr>
        <tr>
          <td><label <?php $page->businessobject->items->userfields->draw->caption("u_rightoverland") ?>>Right Over Land</label></td>
          <td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_rightoverland","Owner") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_rightoverland","Owner") ?>>Owner</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_rightoverland","Lease") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_rightoverland","Lease") ?>>Lease</label>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_leaserate") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_leaserate") ?>>Rate/sqm</label></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>-->
	</table>
</td></tr>	
 <?php $page->resize->addgridobject($objGrids[0], 800, 600) ?>
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

