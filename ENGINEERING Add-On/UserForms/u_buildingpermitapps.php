<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr> 
					
				<td width="50"></td>
					<td width="200"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Application Date</label></td>
                    <td width="200"><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Application No</label></td>
					<td width="250"><label <?php $page->businessobject->items->userfields->draw->caption("u_apptype") ?>>Application Type</label></td>
                    <td width="250"><label <?php $page->businessobject->items->userfields->draw->caption("u_areano") ?>>Area No</label></td>
					<td width="100"><label <?php $page->businessobject->items->userfields->draw->caption("u_ownership") ?>>Form of Ownership</label></td>
					<td></td>
					
				
					
					 <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >Permit No.</label></td>
					
					
					</tr>
					
					<tr>
					<td></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
                        <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
					 <td width="250"><select <?php $page->businessobject->items->userfields->draw->select("u_apptype",null,null,null,null, "width:200px") ?>></select></td>
                             
                              <td width="250">&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_areano") ?>/></td>
							  
							    <td width="300">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_ownership") ?>/></td>
								<td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_cellsite",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_cellsite") ?>>Cell Site</label> &nbsp;</td>
                               <td >&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
							 
						
						
                       
					</tr>
                     <!--space-->
                   
	</table>
</td></tr>	
<tr><td>
		
<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="Application Form">
			<div id="divudf" style="overflow:auto;">
			
			<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
			<td>
			
			<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
          	<tr>
			
		
					 <td colspan="2"><label class="lblobjs"><b>Owner/Applicant</b></label></td>
              </tr>  
			  
			    <tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
				<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
				</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>Tin No.</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcno1") ?>>CTC No.</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ctcno1") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dateissued1") ?>>Date Issued</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dateissued1") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_placeissued1") ?>>Place Issued</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_placeissued1") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mobileno") ?>>Mobile/Tel.No.</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_mobileno") ?>/></td>
			</tr>
			
        <tr><td>&nbsp;</td></tr>
			 <tr>
					 <td colspan="2"><label class="lblobjs"><b>Address</b></label></td>
              </tr> 
			  
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_houseno") ?>>House No.</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_houseno") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Street</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_barangay") ?>>Barangay</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_barangay") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_municipality") ?>>Municipality</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_municipality") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_zipcode") ?>>Zipcode</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_zipcode") ?>/></td>
			</tr>
		
			 <tr><td>&nbsp;</td></tr>
			 <tr>
					 <td colspan="2"><label class="lblobjs"><b>Lot Owner Details</b></label></td>
              </tr> 
			  
			  <tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lotowneraddress") ?>>Address</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lotowneraddress") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcno2") ?>>CTC No.</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ctcno2") ?>/></td>
			</tr>
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dateissued2") ?>>Date Issued</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dateissued2") ?>/></td>
			</tr>
			
			<tr>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_placeissued2") ?>>Place Issued</label></td>
					<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_placeissued2") ?>/></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
			</table>
			</td>
			
			<td>
			<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
		
		 <tr><td>&nbsp;</td></tr>
		<tr> <td colspan="2"><label class="lblobjs"><b>Location of Construction</b></label></td></tr>
		
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lotno") ?>>Lot No.</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lotno") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_blkno") ?>>Blk No.</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_blkno") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tctno") ?>>Tct No.</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tctno") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_taxdecno") ?>>Tax Declaration No.</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_taxdecno") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lstreet") ?>>Street</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lstreet") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lbarangay") ?>>Barangay</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lbarangay") ?>/></td>
		</tr>
		<tr>
		 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lmunicipality") ?>>Municipality</label></td>
		 <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lmunicipality") ?>/></td>
		</tr>
		
		 <tr><td>&nbsp;</td></tr>
		 <tr><td>&nbsp;</td></tr>
		<tr> <td colspan="2"><label class="lblobjs"><b>Design Professional, Plans & Specifications</b></label></td></tr>
		
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_architechname1") ?>>Architech Name</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_architechname1") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiaddress1") ?>>Address</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiaddress1") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiprcno1") ?>>PRC No.</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiprcno1") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archivalidity1") ?>>Validity</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archivalidity1") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiptrno1") ?>>PTR No.</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiptrno1") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archidateissued1") ?>>Date Issued</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archidateissued1") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiissuedat1") ?>>Issued At</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiissuedat1") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_architin1") ?>>TIN</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_architin1") ?>/></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		</table>
			</td>
			
		<td>
		<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
		<tr>
		<td colspan="2"><label class="lblobjs"><b>Full-Time Inspector & Supervisor of Constructive Works</b></label></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_architechname2") ?>>Architech Name</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_architechname2") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiaddress2") ?>>Address</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiaddress2") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiprcno2") ?>>PRC No.</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiprcno2") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archivalidity2") ?>>Validity</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archivalidity2") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiptrno2") ?>>PTR No.</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiptrno2") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archidateissued2") ?>>Date Issued</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archidateissued2") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiissuedat2") ?>>Issued At</label></td>
	    <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiissuedat2") ?>/></td>
		</tr>
		<tr>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_architin2") ?>>TIN</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_architin2") ?>/></td>
		</tr>
		
		 <tr><td>&nbsp;</td></tr>
		 <tr><td colspan="2"><label class="lblobjs"><b>Fill up here before releasing the Building Permit Certificate</b></label></td></tr>
		 
		 <tr>
		 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_buildingpermitno") ?>>Building Permit No.</label></td>
		 <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_buildingpermitno") ?>/></td>
		 </tr>
		 <tr>
		 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_buildingpermitdateissued") ?>>Date Issued</label></td>
		 <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_buildingpermitdateissued") ?>/></td>
		 </tr>
		 <tr>
		 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_fsecno") ?>>FSEC No.</label></td>
		 <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_fsecno") ?>/></td>
		 </tr>
		 <tr>
		 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_fsecnodateissued") ?>>Date Issued</label></td>
		 <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_fsecnodateissued") ?>/></td>
		 </tr>
		 <tr>
		  <tr><td>&nbsp;</td></tr>
		   <tr>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_proposeddateofconstruction") ?>>Proposed Date of Construction</label></td>
                <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_proposeddateofconstruction") ?>/></td>
            </tr>
			<tr>
			 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_expecteddatecompletion") ?>>Expected Date of Completion</label></td>
                <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_expecteddatecompletion") ?>/></td>
			</tr>
		
			<tr>
			<td width="168"><label  <?php $page->businessobject->items->userfields->draw->caption("u_totalarea") ?>> <b>Total Area</b></label></td>
		 <td rowspan="1" valign="top">&nbsp;<textarea rows=1 cols=20 style="font-weight:bold; font-size:18px;" <?php $page->businessobject->items->userfields->draw->text("u_totalarea") ?>/><?php echo $page->getitemstring("u_totalarea") ?></textarea></td>
			</tr>
				<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;</td></tr>
	
	
		

		</table>
		</td>
			
			</table>
	
	</div>

    </div>   
	<div class="tabbertab" title="Use or Character of Occupancy">
		
		<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr>
		
	<td>&nbsp;<?php $objGrids[0]->draw(true); ?> </td>
		<td>&nbsp;<?php $objGrids[1]->draw(true); ?> </td>
      
		</tr>
		<tr>
	</tr>
		<tr>
		
		</tr>
		</table>
		
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr>
		
		<td>&nbsp;<?php $objGrids[2]->draw(true); ?></td>
		
      
		</tr>
		<tr>
	</tr>
		<tr>
		
		</tr>
		</table>
		</div>
		
		<div class="tabbertab" title="Application Requirements">	
				<?php $objGrids[3]->draw(true); ?>  
		</div>
		<div class="tabbertab" title="Required Plans">	
			  <?php $objGrids[4]->draw(true); ?>  
		</div>
		
		
		<div class="tabbertab" title="Engineers">	
			 <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr>
                   
				<tr>
		<td>&nbsp;<?php $objGrids[8]->draw(true); ?></td>
		
		
		</tr>	
				
				
					</tr>
	
		</table>
		</div>
		
		
		
		<div class="tabbertab" title="Building Fees">
		<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
         <!--<td colspan="1" ><label class="lblobjs"><b>Building Assessment</b></label></td>-->
		<tr>
		<td>&nbsp;<?php $objGrids[5]->draw(true); ?>   </td>
		</tr>
		</table>
        
        
		<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
         <!--<td colspan="1" ><label class="lblobjs"><b>Building Assessment</b></label></td>-->
		<tr>
		<td>&nbsp;<?php $objGrids[6]->draw(true); ?></td>
		
		
		</tr>
		</table>
	
		
		
	</div>
	
		<div class="tabbertab" title="Building Assessment">
		<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
			<td>
			
			<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
          	<tr>
			<label class="lblobjs"><b>Accessory Fees</b></label>
			<td><?php $objGrids[10]->draw(true); ?></td>
			</tr>
			<tr>
			<td></td>
		
			</tr>
			<tr>
			<td><label class="lblobjs"><b>Line & Grade</b></label></td>
		
			</tr>
			
			<tr>
			
			<td><?php $objGrids[11]->draw(true); ?></td>
			</tr>
		</table>
		</td>
		
		
		<td>
			
			<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
          	
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			
			
			</tr>
		</table>
		</td>
		
		
		<td>
			
			<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
          	<tr>
			<label class="lblobjs"><b>Building Assessment</b></label>
			<td>&nbsp;<?php $objGrids[7]->draw(true); ?></td>
			
			
			</tr>
		</table>
		</td>
		
		</table>
		
	<br />
		<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
		<td width="1200"></td>
		 <td width=""><label <?php $page->businessobject->items->userfields->draw->caption("u_buildingfeestotal") ?>><b>Total Building Fees</b></label></td>
          <td align="left">&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_buildingfeestotal") ?>/></td>
													
			</table>										
	
		
		</div>
			
		<div class="tabbertab" title="Other Assessments">	
		 <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		 <br />
				 <tr>

				<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mechappno") ?>>Mechanical </label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_mechappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_mechappno", "", "OpenLnkBtnu_mechanicalpermitapps()") ?></td>
			   
			    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_electronicsappno") ?>>Electronics</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_electronicsappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_electronicsappno", "", "OpenLnkBtnu_electronicspermitapps()") ?></td>
			   
			   <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_excavationappno") ?>>Excavation</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_excavationappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_excavationappno", "", "OpenLnkBtnu_excavationpermitapps()") ?></td>
			   <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_elecappno") ?>>Electrical </label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_elecappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_elecappno", "", "OpenLnkBtnu_electricalpermitapps()") ?></td>
			   
            </tr>
			<tr>
			 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_archiappno") ?>>Architectural</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_archiappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_archiappno", "", "OpenLnkBtnu_architecturalpermitapps()") ?></td>
			   
			    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_occupancyappno") ?>>Occupancy</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_occupancyappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_occupancyappno", "", "OpenLnkBtnu_occupancypermitapps()") ?></td>
			   <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_zoningappno") ?>>Zoning</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_zoningappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_zoningappno", "", "OpenLnkBtnu_zoningpermitapps()") ?></td>
			   <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_civilstrucappno") ?>>Civil/Structural</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_civilstrucappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_civilstrucappno", "", "OpenLnkBtnu_civilstrucpermitapps()") ?></td>
				 <tr>
					
				<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_plumbingappno") ?>>Plumbing</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_plumbingappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_plumbingappno", "", "OpenLnkBtnu_plumbingpermitapps()") ?></td>
			   
			    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_sanitaryappno") ?>>Sanitary</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_sanitaryappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_sanitaryappno", "", "OpenLnkBtnu_sanitarypermitapps()") ?></td>
			   
			 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_fencingappno") ?>>Fencing</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_fencingappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_fencingappno", "", "OpenLnkBtnu_fencingpermitapps()") ?></td>
			   
			    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_demolitionappno") ?>>Demolition</label></td>
               <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_demolitionappno") ?>/>&nbsp;<?php genLinkedButtonHtml("u_demolitionappno", "", "OpenLnkBtnu_demolitionpermitapps()") ?></td>
			
			   
			
				
               
            </tr>
			   
			   
			   
			</tr>
			 
			</table>
			 <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr>
                   
				<tr>
		<td>&nbsp;<?php $objGrids[9]->draw(true); ?></td>
		
		
		</tr>	
				
				
	
		</table>
		 <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr><td>&nbsp;</td></tr>
		<tr>
				 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_reqappfeestotal") ?>><b>Total Application Fees</b></label></td>
                  <td >&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_reqappfeestotal") ?>/></td>
                                                   
												   
				
					</tr>
					
					</table>
					</tr>
					
		</div>
		
		<table class="tableFreeForm" width="90%" border="0" cellpadding="0" cellspacing="0"  >
		
		<td width="900">&nbsp;</td>
        
		<td width="168"><label  <?php $page->businessobject->items->userfields->draw->caption("u_asstotal") ?>> <b>Total Assessment</b></label></td>
		 <td rowspan="3" valign="top">&nbsp;<textarea rows=2 cols=50 style="font-weight:bold; font-size:18px;" <?php $page->businessobject->items->userfields->draw->text("u_asstotal") ?>/><?php echo $page->getitemstring("u_asstotal") ?></textarea></td>
               
				</table>
		
		</div>
		
		</div>
		
		</tr>
		</td>
	
	
						
		
		
    
    
</td></tr>	
	
<?php $page->resize->addtab("tab1",-1,81); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],950,450) ?>
<?php $page->resize->addgridobject($objGrids[1],750,500) ?>
<?php $page->resize->addgridobject($objGrids[2],50,500) ?>
<?php $page->resize->addgridobject($objGrids[3],50,200) ?>
<?php $page->resize->addgridobject($objGrids[4],50,200) ?>
<?php $page->resize->addgridobject($objGrids[5],50,500) ?>
 <?php $page->resize->addgridobject($objGrids[6],50,450) ?>
<?php $page->resize->addgridobject($objGrids[7],850,300) ?>	
<?php $page->resize->addgridobject($objGrids[8],20,300) ?>	
<?php $page->resize->addgridobject($objGrids[9],50,400) ?>	
<?php $page->resize->addgridobject($objGrids[10],900,500) ?>
<?php $page->resize->addgridobject($objGrids[11],900,500) ?>

