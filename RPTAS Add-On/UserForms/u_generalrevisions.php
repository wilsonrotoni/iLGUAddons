<tr><td>
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                            <td width="187" ><label <?php $page->businessobject->items->userfields->draw->caption("u_revisionyearfrom") ?>>GR Year From</label></td>
                            <td width="1142" align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_revisionyearfrom") ?>></select></td>
                            <td width="200" ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalareasqm") ?> >Total Area (sqm)</label></td>
                            <td width="200" align=left>&nbsp;<input type="text"  size="17" <?php $page->businessobject->items->userfields->draw->text("u_totalareasqm") ?> /></td>
                    </tr>
                    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_revisionyearto") ?>>GR Year To</label></td>
                            <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_revisionyearto") ?>></select></td>
                            <td ><label <?php $page->businessobject->items->draw->caption("u_totalmarketval") ?> >Total Market Value</label></td>
                            <td align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_totalmarketval") ?>/></td>
                    </tr>
                  <tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_oldbarangay") ?>>Old Barangay</label></td>
                            <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_oldbarangay") ?>></select></td>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalassval") ?>>Total Assessed Value</label></td>
                            <td align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_totalassval") ?>/></td>
                    </tr>
                    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_kind") ?>>Property Type</label></td>
                            <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_kind") ?>></select></td>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_effdate") ?>>Effectivity Date</label></td>
                            <td align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_effdate") ?>/></td>
                    </tr>
                    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rpucount") ?>>RPU Count</label></td>
                            <td  align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_rpucount") ?>/></td>
                            <td ></td>
                            <td align=left>&nbsp;</td>
                    </tr>
                    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_assessedby") ?>>Appraised/Assessed By</label></td>
                            <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_assessedby",null,null,null,null,"width:250px") ?>></select> &nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_assesseddate") ?>>Date</label>&nbsp;&nbsp;&nbsp;<input type="text"  size="12" <?php $page->businessobject->items->userfields->draw->text("u_assesseddate") ?> /></td>
                            <td ></td>
                            <td align=left>&nbsp;</td>
                    </tr>
                    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_recommendby") ?>>Recommending Approval</label></td>
                        <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_recommendby",null,null,null,null,"width:250px") ?>></select> &nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_recommenddate") ?>>Date</label>&nbsp;&nbsp;&nbsp;<input type="text"  size="12" <?php $page->businessobject->items->userfields->draw->text("u_recommenddate") ?> /></td>
                            <td ></td>
                            <td align=left>&nbsp;</td>
                    </tr>
                    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_approvedby") ?>>Approved By</label></td>
                           <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_approvedby",null,null,null,null,"width:250px") ?>></select> &nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_approveddate") ?>>Date</label>&nbsp;&nbsp;&nbsp;<input type="text"  size="12" <?php $page->businessobject->items->userfields->draw->text("u_approveddate") ?> /></td>
                            <td ></td>
                            <td align=left>&nbsp;</td>
                    </tr>
                    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_recordedby") ?>>Recording Person</label></td>
                           <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_recordedby",null,null,null,null,"width:250px") ?>></select> &nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_recordeddate") ?>>Date</label>&nbsp;&nbsp;&nbsp;<input type="text"  size="12" <?php $page->businessobject->items->userfields->draw->text("u_recordeddate") ?> /></td>
                            <td ></td>
                            <td align=left>&nbsp;</td>
                    </tr>
                    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_status") ?>>Status</label></td>
                            <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_status") ?>></select></td>
                            <td ></td>
                            <td align=left>&nbsp;</td>
                    </tr>
                    <tr><td ></td>
                            <td  align=left>&nbsp;</td>
                            <td ></td>
                            <td align=left>&nbsp;</td>
                    </tr>
                    <tr><td ></td>
                            <td  align=left>&nbsp;<a class="button" href="" onClick="SearchProperty();return false;">Search</a></td>
                            <td ></td>
                            <td align=left>&nbsp;</td>
                    </tr>
                    
	</table>   
</td></tr>		