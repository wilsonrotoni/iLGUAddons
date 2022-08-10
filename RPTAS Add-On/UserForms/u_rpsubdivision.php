<tr><td>
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                 
                                    <tr>
					  <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td>
					  <td width="208">&nbsp;</td>
                                          <td> </td>
                                          <td width="4">&nbsp;</td>
                                          <td width="21">&nbsp;</td>
                                          <td colspan="2"></td>
                                          <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_sqm") ?>>Area (sqm) </label></td>
                                    </tr>
                                    <tr>
					  <td colspan="2">&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
					  <td>&nbsp; </td>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
                                          <td width="94">&nbsp;</td>
                                          <td width="261" >&nbsp;</td>
                                          <td width="150">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_sqm") ?>/></td>
                 			 </tr>
                                    <tr>
                                      <td width="300"><label <?php $page->businessobject->items->userfields->draw->caption("u_pin") ?>>PIN No. / </label><label <?php $page->businessobject->items->userfields->draw->caption("u_tdno") ?>>TD No.</label></td>
                                        <td width="208">&nbsp;</td>
                                        <td width="103">&nbsp;</td>
                                      <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                         <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><label <?php $page->businessobject->items->userfields->draw->caption("u_assvalue") ?>>Assessed Value </label></td>
				    </tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="22" <?php $page->businessobject->items->userfields->draw->text("u_pin") ?>/>&nbsp;<input type="text" size="22" <?php $page->businessobject->items->userfields->draw->text("u_tdno") ?>/></td>
					  <td colspan="2">&nbsp;</td>
					  <td>&nbsp;</td>
                      <td>&nbsp;</td>
                       <td>&nbsp;</td>
                      <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_assvalue") ?>/></td>
				  </tr>
					<tr>
					  <td width="300"><label <?php $page->businessobject->items->userfields->draw->caption("u_ownername") ?>>Declared Owner</label></td>
					  <td>&nbsp;</td>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
					  <td colspan="2">&nbsp;</td>
					  <td>&nbsp;</td>
                                           <td><label <?php $page->businessobject->items->userfields->draw->caption("u_subdno") ?>>Subdivided into</label></td>
				  </tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_ownername") ?>/></td>
					  <td colspan="2">&nbsp;</select>&nbsp;</td>
                                          <td>&nbsp;</td>
                                          <td>&nbsp;</td>
                                          <td></td>
                                          <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_subdno") ?>/></td>
				  </tr>
					<tr class="fillerRow5px">
					  <td colspan="5"></td>
				  </tr>
             </table>    
                      		<?php $objGrids[0]->draw(true); ?>	
 
			
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],20,-1) ?>