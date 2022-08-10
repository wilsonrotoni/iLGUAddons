<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
                <td colspan="2"><label class="lblobjs"><b>FLOOR DETAILS</b></label></td>
                <td width="100" ></td>
		<td width="168" align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_taxable","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_taxable") ?>>Taxable</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_taxable","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_taxable") ?>>Exempt</label></td>
	  </tr>
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_floor") ?>>Building No / Floor No</label></td>
		  <td width="168">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bldgno") ?>></select>&nbsp;/&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_floor") ?>/></td>
                  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_gryear") ?>>GR Year</label></td>
                  <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gryear") ?>></select></td>
	  </tr>
      <tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_kind") ?>>Kind of Building</label></td>
		  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_kind",null,null,null,null,"width:200px") ?>></select></td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_arpno") ?>>ID No.</label></td>
		  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_arpno") ?>/></td>
	  </tr>
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_structuretype") ?>>Structure Type/Class</label></td>
		  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_structuretype") ?>></select> &nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_subclass") ?>></select></td>
		  <td >&nbsp;</td>
		  <td align=left>&nbsp;</td>
	  </tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgdescriptions") ?>>Descriptions</label></td>
		  <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_bldgdescriptions") ?>/></td>
        <td >&nbsp;</td>
		  <td align=left>&nbsp;</td>
	</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_class") ?>>Classification</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_class") ?>></select> </td>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_asslvlby") ?>>Assessment Level By</label></td>
                    <td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_asslvlby","AC") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_asslvlby") ?>>Actual Use</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_asslvlby","C") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_asslvlby") ?>>Classification</label></td>
	</tr>
    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_actualuse") ?>>Actual Use</label></td>
        <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_actualuse") ?>></select></td>
        <td colspan="2"><label class="lblobjs"><b>FLOOR APPRAISAL</b></label></td>
    </tr>
	  <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_sqm") ?>>Area (sqm.)</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_sqm") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_floorbasevalue") ?>>Base Market Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_floorbasevalue") ?>/></td>
		</tr>
	  <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_startyear") ?>>Year Constructed/Renovated</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_startyear") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_flooradjperc") ?>>% Adjustment</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_flooradjperc") ?>/></td>
		</tr>
	  <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_floordeprevalue") ?>>Depreciation Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_floordeprevalue") ?>/></td>
		</tr>
	  <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_unitvalue") ?>>Unit Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_unitvalue") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_flooradjvalue") ?>>Adjustment Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_flooradjvalue") ?>/></td>
		</tr>
	  <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_completeperc") ?>>% Completed</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_completeperc") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_flooradjmarketvalue") ?>>Adjusted Market Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_flooradjmarketvalue") ?>/></td>
		</tr>
	  <tr>
	    </tr>
    </table>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="Additional Items & Appraisal">
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Materials">
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
   </div> 
    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" > 
        <tr><td colspan="9"><label class="lblobjs"><b>PROPERTY ASSESSMENT</b></label></td>
        </tr>
     </table>   
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr><td width="250"><label <?php $page->businessobject->items->userfields->draw->caption("u_basevalue") ?>>Base Market Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_basevalue") ?>/></td>
		  <td width="168">&nbsp;</td>
		  <td width="168">&nbsp;</td>
		</tr>
	  <tr>    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_deprevalue") ?>>Depreciation Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_deprevalue") ?>/></td>
		  <td >&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
	  <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_adjvalue") ?>>Adjustment Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_adjvalue") ?>/></td>
		  <td >&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
	  <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_adjmarketvalue") ?>>Adjusted Market Value</label></td>
		  <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_adjmarketvalue") ?>/></td>
		  <td >&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_withdecimal") ?>>Assessed Value Include Decimal</label></td>
		  <td>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_withdecimal",1) ?>/></td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		</tr>
	</table>
</td></tr>	
<?php $page->resize->addgridobject($objGrids[0],20,450) ?>
<?php //$page->resize->addgridobject($objGrids[1],-1,-1) ?>		

