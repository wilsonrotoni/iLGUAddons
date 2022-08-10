<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				
               <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
               		
					<tr><td colspan="2"><label class="lblobjs"><b>Name of Tax Payer</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>*Last Name</label></td>
						<td width="238"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>*First Name</label></td>
						<td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
						<td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
					  <td>&nbsp;</td>
			      </tr>
                  <tr><td colspan="2"><label class="lblobjs"><b>*Business Name</b></label></td>
						<td>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr>
                                            <td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
                                            <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
					</tr>
                   <tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Business Address</b></label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bunittype","Commercial") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bunittype") ?>>Commercial</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bunittype","Residential") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bunittype") ?>>Residential</label></td>
						<td colspan="3"></td>
						
					</tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Bldg No.</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgname") ?>>Bldg Name</label></td>
						<td></td>
						<td></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgname") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Unit No.</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_bstreet") ?>>Street</label></td>
						<td width="170"></td>
						<td></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bunitno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bstreet") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Subdivision</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>*Brgy.</label></td>
						<td width="170"></td>
						<td ></td>
						<td >&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bvillage") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy") ?>></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bcity") ?>>City/Municipality</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_bprovince") ?>>Province</label></td>
						<td width="170"></td>
						<td></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bcity") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bprovince") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_btelno") ?>>Tel No.</label></td>
						<td>&nbsp;</td>
						<td width="170"></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_btelno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bemail") ?>>Email Address</label></td>
						<td>&nbsp;</td>
						<td width="170"></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_bemail") ?>/></td>
					  <td colspan="3">&nbsp;</td>
				  </tr>
					
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					
				</table> 
               
			</div>
		</div>
		
      <div class="tabbertab" title="Zoning Assessments">
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr><td colspan="4"><label <?php $page->businessobject->items->userfields->draw->caption("u_planapptype") ?>>Application Type</label> &nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_planapptype","NEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_planapptype") ?>>New</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_planapptype","RENEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_planapptype") ?>>Renewal</label>
                                            </td>
					<td>&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_planappdate") ?>>Application Date</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_planappdate") ?>/></td>
				</tr>
				<tr><td colspan="4"></td>
					<td>&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_iszoninginspect") ?>>For Inspection</label></td>
					<td width="168">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_iszoninginspect","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_iszoninginspect") ?>>Yes</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_iszoninginspect","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_iszoninginspect") ?>>No</label></td>
				</tr>
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
            </tr>
			</table>	
			<?php $objGrids[1]->draw(true); ?>
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
				<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_planningremarks") ?>>Planning Remarks</label></td>
					<td rowspan="3" valign="top">&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_planningremarks") ?>/><?php echo $page->getitemstring("u_planningremarks")?></textarea></td>
					<td width="168">&nbsp;</td>
					<td width="168">&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_planasstotal") ?>>Total Assessment</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_planasstotal") ?>/></td>
				</tr>
				<tr>
				  <td >&nbsp;</td>
				  <td width="168"></td>
                                    <td width="168">&nbsp;></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr>
				  <td>&nbsp;</td>
				  <td></td>
				  <td></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
			</table>	
	  </div>
            <div class="tabbertab" title="Building Assessments">
                <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_engiapptype") ?>>Application Type</label> </td>
					<td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_engiapptype","NEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_engiapptype") ?>>New</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_engiapptype","RENEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_engiapptype") ?>>Renewal</label></td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_engiappdate") ?>>Application Date</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_engiappdate") ?>/></td>
				</tr>
                                <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_isbldginspect") ?>>For Inspection</label></td>
					<td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_isbldginspect","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_isbldginspect") ?>>Yes</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_isbldginspect","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_isbldginspect") ?>>No</label></td>
					<td width="168"></td>
					<td width="168">&nbsp;</td>
				</tr>
                                
                </table>
                
               <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td width="800" rowspan="8">
                                <div class="tabber" id="tab1">
                                        <div class="tabbertab" title="Building Permit">
                                            <?php $objGrids[3]->draw(true); ?>
                                        </div>
                                        <div class="tabbertab" title="Mechanical">
                                            <?php $objGrids[4]->draw(true); ?>
                                        </div>
                                        <div class="tabbertab" title="Plumbing">
                                            <?php $objGrids[5]->draw(true); ?>
                                        </div>
                                        <div class="tabbertab" title="Electrical">
                                            <?php $objGrids[6]->draw(true); ?>
                                        </div>
                                        <div class="tabbertab" title="Signage">
                                            <?php $objGrids[7]->draw(true); ?>
                                        </div>
                                </div>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                               <?php $objGrids[0]->draw(true); ?>
                            </td>
                        </tr>
                        
                    </table>
               
				
			
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                            <tr class="fillerRow5px">
                                <td></td><td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
				<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_engiremarks") ?>>Building Remarks</label></td>
					<td rowspan="3" valign="top">&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_engiremarks") ?>/><?php echo $page->getitemstring("u_engiremarks")?></textarea></td>
					<td width="168">&nbsp;</td>
					<td width="168">&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_engiasstotal") ?>>Total Assessment</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_engiasstotal") ?>/></td>
				</tr>
				<tr>
				  <td >&nbsp;</td>
				  <td width="168"></td>
					<td width="168">&nbsp;></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr>
				  <td>&nbsp;</td>
				  <td></td>
				  <td></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
			</table>	
	  </div>
      <div class="tabbertab" title="Health Assessments">
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rhuapptype") ?>>Application Type</label> </td>
                                    <td>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_rhuapptype","NEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_rhuapptype") ?>>New</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_rhuapptype","RENEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_rhuapptype") ?>>Renewal</label> </td>
                                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rhuappdate") ?>>Application Date</label></td>
                                    <td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_rhuappdate") ?>/></td>
				</tr>
				<tr>
                                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_sanitarychar") ?>>Category</label></td>
                                    <td>&nbsp; <select <?php $page->businessobject->items->userfields->draw->select("u_sanitarychar",null,null,null,null,"width:400px") ?>></select></td>
                                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ishealthinspect") ?>>For Inspection</label></td>
                                    <td width="168">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_ishealthinspect","1") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_ishealthinspect") ?>>Yes</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_ishealthinspect","0") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_ishealthinspect") ?>>No</label></td>
				</tr>
                                    <td > &nbsp;</td>
                                    <td>&nbsp; <select <?php $page->businessobject->items->userfields->draw->select("u_sanitarykind", array("loadu_sanitarykinds", $page->getitemstring("u_sanitarychar"),":")) ?>></select></td>
                                    <td width="168"></td>
                                    <td width="168">&nbsp;</td>
				</tr>
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
            </tr>
			</table>	
			<?php $objGrids[2]->draw(true); ?>
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
				<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_rhuremarks") ?>>Health Remarks</label></td>
					<td rowspan="3" valign="top">&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_rhuremarks") ?>/><?php echo $page->getitemstring("u_rhuremarks")?></textarea></td>
					<td width="168">&nbsp;</td>
					<td width="168">&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_rhuasstotal") ?>>Total Assessment</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_rhuasstotal") ?>/></td>
				</tr>
				<tr>
				  <td >&nbsp;</td>
				  <td width="168"></td>
					<td width="168">&nbsp;></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr>
				  <td>&nbsp;</td>
				  <td></td>
				  <td></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
			</table>	
	  </div>
		
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],-1,324) ?>	
<?php $page->resize->addgridobject($objGrids[1],20,284) ?>
<?php $page->resize->addgridobject($objGrids[2],20,324) ?>
<?php // $page->resize->addgridobject($objGrids[3],,384) ?>	

