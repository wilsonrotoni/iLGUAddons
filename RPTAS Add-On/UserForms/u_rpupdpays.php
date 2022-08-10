<tr><td>
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
					  <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td>
					  <td width="249">&nbsp;</td>
					  <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
                  </tr>
					<tr>
					  <td colspan="2">&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
					  <td width="249">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
					  <td >&nbsp;</td>
				  </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_pin") ?>>PIN</label></td>
					  <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_tdno") ?>>TD No</label></td>
						<td width="249">&nbsp;</td>
                        <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td >&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_pin") ?>/></td>
					  <td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tdno") ?>/></td>
					  <td colspan="2">&nbsp;</td>
				  </tr>
                  <tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>TIN</label></td>
					   <td>&nbsp;</td>
					  <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_authorizedby") ?>>Authorized By</td>
                                        <td>&nbsp;</td>
				    </tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/></td>
					  <td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_authorizedby") ?>/></td>
				  </tr>
					<tr>
					  <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_declaredowner") ?>>Declared Owner</label></td>
					  <td>&nbsp;</td>
					  <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_kind") ?>>Kind of Property /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_location") ?>>Location</label></td>
				  </tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_declaredowner") ?>/></td>
					  <td colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_kind") ?>/></select>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_location") ?>/></td>
				  </tr>
					<tr class="fillerRow5px">
					  <td colspan="4"></td>
				  </tr>
             </table>    
<?php $objGrids[0]->draw(true); ?>	
 
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr class="fillerRow5px">
					  <td width="399" colspan="5"></td>
				  </tr>
				</table>
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],0,335) ?>