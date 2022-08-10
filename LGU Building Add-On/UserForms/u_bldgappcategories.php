<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" ><label <?php $page->businessobject->items->draw->caption("name") ?> >Category Name</label></td>
                    <td align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
                <tr class="fillerRow5px">
					  <td ></td>
					  <td></td>
		  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Building Permit">	
                    <div id="divbldg" style="overflow:auto;">
                        <?php $objGrids[0]->draw(true) ?>	
                    </div>
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="fillerRow5px"><td></td><td></td></tr>
                        <tr><td width="140"><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgtotal") ?>>Building Total</label></td>
                            <td align="left">&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_bldgtotal") ?>/></td>

                        </tr>
                    </table>
		</div>
		<div class="tabbertab" title="Mechanical">	
                    <div id="divmech" style="overflow:auto;">
                        <?php $objGrids[1]->draw(true) ?>	
                    </div>
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="fillerRow5px"><td></td><td></td></tr>
                        <tr><td width="140"><label <?php $page->businessobject->items->userfields->draw->caption("u_mechtotal") ?>>Mechanical Total</label></td>
                            <td align="left">&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_mechtotal") ?>/></td>

                        </tr>
                    </table>
		</div>
		<div class="tabbertab" title="Plumbing">	
                    <div id="divplum" style="overflow:auto;">
                        <?php $objGrids[2]->draw(true) ?>	
                    </div>
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="fillerRow5px"><td></td><td></td></tr>
                        <tr><td width="140"><label <?php $page->businessobject->items->userfields->draw->caption("u_plumbingtotal") ?>>Plumbing Total</label></td>
                            <td align="left">&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_plumbingtotal") ?>/></td>

                        </tr>
                    </table>	  
		</div>
		<div class="tabbertab" title="Electrical">	
                    <div id="divelec" style="overflow:auto;">
                        <?php $objGrids[3]->draw(true) ?>	
                    </div> 
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="fillerRow5px"><td></td><td></td></tr>
                        <tr><td width="140"><label <?php $page->businessobject->items->userfields->draw->caption("u_electricaltotal") ?>>Electrical Total</label></td>
                            <td align="left">&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_electricaltotal") ?>/></td>

                        </tr>
                    </table> 
		</div>
		<div class="tabbertab" title="Signage">	
                    <div id="divsign" style="overflow:auto;">
                        <?php $objGrids[4]->draw(true) ?>	
                    </div>
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="fillerRow5px"><td></td><td></td></tr>
                        <tr><td width="140"><label <?php $page->businessobject->items->userfields->draw->caption("u_signagetotal") ?>>Signage Total</label></td>
                            <td align="left">&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_signagetotal") ?>/></td>

                        </tr>
                    </table>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,240); ?>
<?php $page->resize->addtabpage("tab1","bldg") ?>
<?php $page->resize->addtabpage("tab1","mech") ?>
<?php $page->resize->addtabpage("tab1","plum") ?>
<?php $page->resize->addtabpage("tab1","elec") ?>
<?php $page->resize->addtabpage("tab1","sign") ?>
<?php $page->resize->addgridobject($objGrids[0],20,300) ?>		
<?php $page->resize->addgridobject($objGrids[1],20,300) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,300) ?>		
<?php $page->resize->addgridobject($objGrids[3],20,300) ?>		
<?php $page->resize->addgridobject($objGrids[4],20,300) ?>		

