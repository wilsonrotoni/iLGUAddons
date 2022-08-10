<tr><td>
        
        

</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				
                                    <tr>
					  <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td>
					  <td width="5">&nbsp;</td>
					  <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_regdate") ?>></label></td>
                                    </tr>
					<tr>
					  <td colspan="2">&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                            <tr><td width="298"><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Application No.</label></td>
					  <td></td>
						<td colspan="2"></td>
					</tr>
					<tr>
					  <td>
				      <input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
                              
					<tr><td width="298"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>Business Name</label></td>
					  <td>&nbsp;</td>
						<td width="284"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcno") ?>></label></td>
					  <td width="192">&nbsp;</td>
				  </tr>
					<tr>
					  <td colspan="2">
					    <input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/>
					  </label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr>
					  <td width="298"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>></label></td>
					  <td>&nbsp;</td>
					  <td></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr>
					  <td colspan="2"><label class="lblobjs"><b>Name of Tax Payer</b></label></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr>
					  <td colspan="2"></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr>
					  <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
                                          <td width="523"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
			      </tr>
					<tr>
					  <td colspan="2"><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					  <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					  <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
                      <td><select name="select" <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select>
                      </td>
			      </tr>
					
					
				</table>
       	  </div>
        </div>            
	            
	  
            
            <!--wilson-->
		
		<div class="tabbertab" title="Assessments">
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr><td ></td>
					<td>&nbsp;</td>
					<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_decisiondate") ?>>Decision Date</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_decisiondate") ?>/></td>
				</tr>
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
            </tr>
			</table>
			<?php $objGrids[0]->draw(true) ?>
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
         	<tr class="fillerRow5px">
            	<td></td><td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
				<tr>
                                      <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_asstotal") ?>>Total Assessment</label></td>
					<td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_asstotal") ?>/></td>
				</tr>
				
			</table>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,180) ?>
    
