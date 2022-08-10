<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
                    <td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Item Code</label></td>
                    <td align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
                    <td width="168"><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isconsumption",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isconsumption") ?>>Consumption</label></td>
                    <td width="168" rowspan="7"  align="center"><?php if ($photopath != "") { ?><img id="PhotoImg" height=120 src="<?php echo $photopath; ?>" width=120 align="absmiddle" border=1 onDblClick="uploadPhoto()"><?php } ?></td>
                    
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Item Description</label></td>
                    <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
                    <td ><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isfixedasset",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isfixedasset") ?>>Fixed Asset</label></td>
                    <td >&nbsp;</td>
                   
		</tr>
                <tr>
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_itemgroup") ?>>Item Group</label></td>
                    <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_itemgroup") ?>></select></td>
                    <td><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isstock",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isstock") ?>>Stock</label></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_itemsubgroup") ?>>Item Sub Group</label></td>
                    <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_itemsubgroup") ?>></select></td>
                    <td ></td>
                    <td >&nbsp;</td>
                    
                </tr>
                <tr>
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_costmethod") ?>>Costing Method</label></td>
                    <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_costmethod") ?>></select></td>
                    <td ></td>
                    <td >&nbsp;</td>
                </tr>
                <tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_uom") ?>>Uom</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_uom") ?>></select></td>
                    <td ></td>
                    <td >&nbsp;</td>
                   
		</tr>
                <tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_unitprice") ?>>Unit Price</label></td>
                    <td >&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_unitprice") ?> /></td>
                    <td ></td>
                    <td >&nbsp;</td>
                   
		</tr>
                <tr>
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_faclass") ?>>Fixed Asset Class</label></td>
                    <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_faclass") ?>></select></td>
                    <td ></td>
                    <td >&nbsp;</td>
                </tr>
                <tr>
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_orderedqty") ?>>Total Ordered</label></td>
                    <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_orderedqty") ?> /></td>
                    <td ></td>
                    <td >&nbsp;</td>
		</tr>
	</table>
        <div class="tabber" id="tab1">
            <div class="tabbertab" title="Inventory">
                <div id="divudf" style="overflow:auto;">
                     <?php $objGridA->draw(true); ?>	
                </div>
            </div>
            <div class="tabbertab" title="Registries">
                <div id="divudf" style="overflow:auto;">
                     <?php $objGridB->draw(true); ?>	
                </div>
            </div>
            <div class="tabbertab" title="Picture">
                <div id="divpic" style="overflow:auto;">
                    <?php if ($photopath != "") { ?><img id="PhotoImg" src="<?php echo $photopath; ?>" align="absmiddle"  onDblClick="uploadPhoto()"><?php } ?>
                </div>
            </div>
            <div class="tabbertab" title="Remarks">
                <div id="divudf" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA>
                        </td></tr>
                    </table>  
                </div>
            </div>
        </div>
</td></tr>	
<tr><td>
	
</td></tr>		
<?php $page->resize->addtab("tab1",-1,300); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addtabpage("tab1","pic") ?>
<?php // $page->resize->addgridobject($objGrids[0],20,200) ?>
<?php $page->resize->addgridobject($objGridA,10,420) ?>	
<?php $page->resize->addgridobject($objGridB,10,300) ?>	

    <?php $page->resize->addinput("u_remarks",30,300); ?>

