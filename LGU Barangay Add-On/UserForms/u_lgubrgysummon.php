<tr><td>
	
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Date</label></td>
                <td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
                <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
                <td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
            </tr>
            <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_incidentdate") ?>>Incident Date</label></td>
                <td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_incidentdate") ?>/></td>
                <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
                <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
            </tr>
            <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>Incident Place</label></td>
                <td  align=left rowspan="2">&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_approverremarks") ?>/><?php echo $page->getitemstring("u_approverremarks")?></textarea></td>
                <td ><label <?php $page->businessobject->items->draw->caption("u_appdate") ?> >Date</label></td>
                <td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
            </tr>
            <tr><td ></td>
                <td  align=left>&nbsp;</td>
                <td ></td>
                <td align=left>&nbsp;</td>
            </tr>
             
              </table>
              <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                  		<td> <label class="lblobjs"><b>COMPLAINANT NAME/S</b></label> </td>
                       	<td><label class="lblobjs"><b>VICTIM NAME/S</b></label> </td>
                  </tr>
                   <tr>
                  		<td><?php $objGrids[1]->draw(true) ?></td>
                       	<td><?php $objGrids[2]->draw(true) ?></td>
                  </tr>
                  <tr>
                  		<td> <label class="lblobjs"><b>RESPONDENT NAMES</b></label> </td>
                       	<td><label class="lblobjs"><b>FACTS</b></label> </td>
                  </tr>
                   <tr>
                  		<td><?php $objGrids[3]->draw(true) ?></td>
                       	<td><?php $objGrids[4]->draw(true) ?></td>
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
        
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,80); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,360) ?>
<?php $page->resize->addgridobject($objGrids[1],800,440) ?>
<?php $page->resize->addgridobject($objGrids[2],800,440) ?>	
<?php $page->resize->addgridobject($objGrids[3],800,440) ?>	
<?php $page->resize->addgridobject($objGrids[4],800,440) ?>				

