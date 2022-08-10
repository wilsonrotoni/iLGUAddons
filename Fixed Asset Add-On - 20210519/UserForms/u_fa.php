<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Asset Code</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("code") ?> /></td>
			<td width="168">&nbsp;</td>
		    <td width="168" rowspan="6" align="center"><?php if ($photopath!="") { ?><img id="PhotoImg" height=120 src="<?php echo $photopath; ?>" width=120 align="absmiddle" border=1 onDblClick="uploadPhoto()"><?php } ?></td>
		    
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Asset Description</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_itemdesc") ?>>Item Description</label></td>
			<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_itemdesc") ?>/></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_branch") ?>>Branch/Department</label></td>
		  <td>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema["u_branchname"]) ?>/><input type="text" size="34" <?php genInputTextHtml($schema["u_departmentname"]) ?>/></td>
			<td>&nbsp;</td>
	  </tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_empid") ?>>Employee</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_empid") ?>/><input type="text" size="34" <?php $page->businessobject->items->userfields->draw->text("u_empname") ?>/></td>
			<td>&nbsp;</td>
	</tr>
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cost") ?>>Asset Cost</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_cost") ?>/></td>
			<td>&nbsp;</td>
	  </tr>		
	 </table> 
</td></tr>		
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_faclass") ?>>Asset Class</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_faclass") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_serialno") ?>>Serial No.</label></td>
						<td width="168">&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_serialno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_srcdoc") ?>>Source Document</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_srcdoc") ?>/>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_srcname") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mfrserialno") ?>>Mfr Serial No.</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_mfrserialno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_acquidate") ?>>Acquisition Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_acquidate") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property1") ?>>Property 1</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property1") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Start Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property2") ?>>Property 2</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property2") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>End Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property3") ?>>Property 3</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property3") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_depredate") ?>>Depreciation Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_depredate") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property4") ?>>Property 4</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property4") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_accumdepre") ?>>Accumulated Dep.</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_accumdepre") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property5") ?>>Property 5</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property5") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_salvagevalue") ?>>Salvage Value</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_salvagevalue") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property6") ?>>Property 6</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property6") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bookvalue") ?>>Book Value</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_bookvalue") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property7") ?>>Property 7</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property7") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remainlife") ?>>Life/Remaining</label></td>
						<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_life") ?>/>/<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_remainlife") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property8") ?>>Property 8</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property8") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property9") ?>>Property 9</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property9") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_projcode") ?>>Project</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_projcode") ?>/></td>
						<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_property10") ?>>Property 10</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_property10") ?>/></td>
					</tr>
				</table>
			</div>
		</div>
        <div class="tabbertab" title="Remarks">	
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA>
                </td></tr>
            </table>
        </div>
		<div class="tabbertab" title="Depreciation - Multiple Departments">	
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr><td width="168">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_deptweightperc") ?>>Department Total %</label></td>
					<td width="168">&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_deptweightperc") ?>/></td>
				</tr>
				<tr><td>&nbsp;</td>
					<td>&nbsp;</td>
					<td >&nbsp;</td>
					<td>&nbsp;</td>
				</tr>				
			</table>
			<?php $objGrids[4]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Registries">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Assignments">	
			<?php $objGrids[2]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Attachments">
			<div id="divtab1Pictures" style="overflow-y:auto; overflow-x:auto;" >
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					  <tr >
						<td width="300" valign="top"><?php $objGrids[3]->draw() ?></td>
						<td width="5" valign="top">&nbsp;</td>
						<td valign="top">
							<img id="PictureImg" src="" align="absmiddle" border=1 alt="">
						</td>
					  </tr>
				</table>
			</div>
		</div>        
		<div class="tabbertab" title="Depreciation Schedules">	
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr><td width="168">&nbsp;</td>
					<td>&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_depreacct") ?>>Depreciation Account</label></td>
					<td width="168">&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_depreacct") ?>/></td>
				</tr>
				<tr><td width="168">&nbsp;</td>
						<td>&nbsp;</td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_accumdepreacct") ?>>Accumulated Dep. Account</label></td>
						<td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_accumdepreacct") ?>/></td>
				</tr>				
				<tr><td>&nbsp;</td>
					<td>&nbsp;</td>
					<td >&nbsp;</td>
					<td>&nbsp;</td>
				</tr>				
			</table>
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,211); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addgridobject($objGrids[0],20,239) ?>		
<?php $page->resize->addgridobject($objGrids[1],20,277) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,217) ?>		
<?php $page->resize->addgridobject($objGrids[3],-1,217) ?>		
<?php $page->resize->addgridobject($objGrids[4],20,293) ?>		
<?php $page->resize->addinput("u_remarks",30,220); ?>

