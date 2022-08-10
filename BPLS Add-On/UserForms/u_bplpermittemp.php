<tr><td>

	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
          <td width="200" ><label <?php $page->businessobject->items->userfields->draw->caption("u_apptype") ?>>Application For</label></td>
          <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_apptype") ?>></select></td>
          <td width="200" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
          <td width="200" align=left>&nbsp;<input type="text"  size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
      </tr>
      <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgno") ?>>Building Permit No.</label></td>
          <td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgno") ?>/></td>
          <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
          <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
      </tr>
      <tr>
       <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
       <td>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
       <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Application Date</label></td>
       <td>&nbsp;<input type="text" size="16"  <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
   </tr>
</table>
<div class="tabber" id="tab1" >  
    <div class="tabbertab" title="General">
        <div id="divudf" style="overflow:auto;">
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_contractorname") ?>>Name of Contractor</label></td>
                    <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_contractorname") ?>/></td>

                </tr>   
                <tr>
                  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_contractoraddress") ?>>Address of Contractor</label></td>
                  <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_contractoraddress") ?>/></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
              </tr> 
              <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_contractortelno") ?>>Contractor's Tel No</label></td>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_contractortelno") ?>/></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr> <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr><tr>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ownername") ?>>Name of Owner</label></td>
                <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_ownername") ?>/></td>
                <td rowspan="9"><?php $objGrids[0]->draw(true); ?></td>
                <td>&nbsp;</td>
            </tr>   
            <tr>
              <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_owneraddress") ?>>Address of Owner</label></td>
              <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_owneraddress") ?>/></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
          </tr> 
          <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ownertelno") ?>>Owner's Tel No</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ownertelno") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr> <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr><tr>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_projname") ?>>Name of Project</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_projname") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>   
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_area") ?>>Floor Area (sqm)</label></td>
          <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_area") ?>/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr> 
      <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_projaddress") ?>>Project Location</label></td>
        <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_projaddress") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_projaddress"); ?></TEXTAREA></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr> <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_duration") ?>>Duration</label></td>
        <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_duration") ?>/></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Total Bill of Materials</label></td>
        <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/>&nbsp; <a class="button" href="" onClick="computeContractorsTax();return false;" >Compute</a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    
</table>
</div>
</div>
<div class="tabbertab" title="Remarks">
    <div id="divremarks" style="overflow:auto;">
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA>
            </td></tr>
        </table>  
    </div>
</div>
</div>
        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr class="fillerRow5px"><td></td></tr>
            <tr>
                 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
            </tr>
            
        </table>    
</td></tr>	
<?php $page->resize->addtab("tab1",-1,200); ?>
<?php //$page->resize->addgridobject($objGrids[0], 100, 400) ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addinput("u_remarks",30,240); ?>


