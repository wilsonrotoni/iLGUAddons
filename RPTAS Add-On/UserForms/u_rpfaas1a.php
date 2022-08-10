<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
                <td width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_gryear") ?>>GR Year</label></td>
                <td  >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gryear") ?>></select></td>
                <td width="100" >&nbsp;</td>
                <td width="168" >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_taxable","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_taxable") ?>>Taxable</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_taxable","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_taxable") ?>>Exempt</label></td>
        </tr>
        <tr>
                <td width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_class") ?>>Class</label></td>
                <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_class",array("loadu_faas1aclass", $page->getitemstring("u_gryear"),":")) ?>></select></td>
		<td width="100" ><label <?php $page->businessobject->items->userfields->draw->caption("u_arpno") ?>>&nbsp;ARP No.</label></td>
		<td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_arpno") ?>/></td>
	</tr>
	<tr>
		<td  align=left>&nbsp;</td>
		<td >&nbsp;</td>
		<td align=left>&nbsp;&nbsp;</td>
	</tr>	
	</table>
        <div class="tabber" id="tab1">
		
		<div class="tabbertab" title="LAND APPRAISAL">
                    <div id="divudf" style="overflow:auto;">
			<?php $objGrids[0]->draw(true) ?>
                    </div>
                </div>
		<div class="tabbertab" title="OTHER LAND IMPROVEMENTS">
                    <div id="divudf" style="overflow:auto;">
                        
			<?php $objGrids[4]->draw(true) ?>
                    </div>
                </div>
		<div class="tabbertab" title="PLANTS AND TREES">
                    <div id="divudf" style="overflow:auto;">
                        
			<?php $objGrids[2]->draw(true) ?>
                    </div>
                </div>
		<div class="tabbertab" title="VALUE ADJUSTMENT">
                    <div id="divudf" style="overflow:auto;">
                        
			<?php $objGrids[1]->draw(true) ?>
                    </div>
                </div>
		<div class="tabbertab" title="LAND STRIPPING">
                    <div id="divudf" style="overflow:auto;">
                        
			<?php $objGrids[3]->draw(true) ?>
                    </div>
                </div>
        </div>
                  	  
      <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" > 
          <tr><td colspan="9">&nbsp;</td>
        </tr>
        <tr><td colspan="9"><label class="lblobjs"><b>PROPERTY ASSESSMENT</b></label></td>
        </tr>
     </table>   
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_basevalue") ?>>Base Market Value</label></td>
		  <td width="189">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_basevalue") ?>/></td>
		  <td width="318" >&nbsp;</td>
		  <td width="969" >&nbsp;</td>
		</tr>
	  <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_adjvalue") ?>>Adjustment Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_adjvalue") ?>/></td>
		  <td>&nbsp;</td>
		  <td >&nbsp;</td>
		</tr>
	  <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_marketvalue") ?>>Market Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_marketvalue") ?>/></td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		</tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_actualuse") ?>>Actual Use</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_actualuse") ?>></select></td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_asslvl") ?>>Assessment Level</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_asslvl") ?>/></td>
                    <td>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema["isasslvlclass"],"1") ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("isasslvlclass") ?>>Assessment Level by Class</label></td>
                    <td>&nbsp;</td>
            </tr>
            <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_assvalue") ?>>Assesed Value</label></td>
                    <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_assvalue") ?>/></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            </tr>
	</table>
</td></tr>	
<?php $page->resize->addgridobject($objGrids[0],20,360) ?>
<?php $page->resize->addgridobject($objGrids[1],20,360) ?>
<?php $page->resize->addgridobject($objGrids[2],20,360) ?>
<?php $page->resize->addgridobject($objGrids[3],20,360) ?>

