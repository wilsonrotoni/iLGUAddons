<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Resident No</label></td>
		<td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Customer Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/><label <?php $page->businessobject->items->draw->caption("u_lastname") ?> >,</label><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
    <tr><td ><label <?php $page->businessobject->items->draw->caption("docno") ?> >Type of Application</label></td>
		<td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","I") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Individual</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","C") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Corporation</label></td>
		<td ><label <?php $page->businessobject->items->draw->caption("u_date") ?> >Date Issued</label></td>
		<td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	</tr>
  </table>
</td></tr>


<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
                        <td  align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
                    <tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>Tin</label></td>
                        <td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
                    <tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_citizenship") ?>>Citizenship</label></td>
                        <td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_citizenship") ?>/></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
                    <tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_icrno") ?>>ICR No(if Alien)</label></td>
                        <td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_icrno") ?>/></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
                    <tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_height") ?>>Height</label></td>
                        <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_height") ?>/></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
                    <tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_weight") ?>>Weight</label></td>
                        <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_weight") ?>/></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
                    <tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_placeofbirth") ?>>Place of Birth</label></td>
                        <td  align=left>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_placeofbirth") ?>/></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
                    <tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dateofbirth") ?>>Date of Birth</label></td>
                        <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_dateofbirth") ?>/></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
                    <tr>
                    	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_sitio") ?>>Purok/Sitio</label></td>
                        <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_sitio") ?>></select></td>
                        <td ></td>
                        <td align=left>&nbsp;</td>
					</tr>
          			<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
          		    </tr>
			<tr>
                <td colspan="2"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			
			
            <tr>  
			    <td width="159"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City/Mun.</label></td>
		      <td width="510"><label <?php $page->businessobject->items->userfields->draw->caption("u_province") ?>>Province</label></td>
	   	      <td width="294"></td>
			  <td width="261"></td>
    		</tr>
			<tr>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
                <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>  
                <td width="159"><label <?php $page->businessobject->items->userfields->draw->caption("u_address") ?>>Address</label></td>
              <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
			<tr>
                <td colspan="4">&nbsp;<textarea rows=2 cols=40 <?php $page->businessobject->items->userfields->draw->text("u_address") ?>/><?php echo $page->getitemstring("u_address")?></textarea></td>
                <td width="4">&nbsp;</td>
              <td width="4">&nbsp;</td>
              <td width="4">&nbsp;</td>
			</tr>
				</table>
			</div>
		</div>
		<div class="tabbertab" title="Payment">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>Receipt Number</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_paiddate") ?>>Paid Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_paiddate") ?>/></td>
					</tr>
                    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Due Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_paidamount") ?>>Paid Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_paidamount") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_changeamount") ?>>Change Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_changeamount") ?>/></td>
					</tr>
                    <tr><td width="168" colspan="2"><?php $objGrids[0]->draw(true) ?>	</td>
						<td>&nbsp;</td>
					</tr>
				 
				</table>
			</div>
		</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,300) ?>		

