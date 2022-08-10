


<tr><td>

        <div class="tabber" id="tab1">
            <div class="tabbertab" title="General">
                <div id="divudf" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td width="175">&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_municipality") ?>>Municipality Name</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_municipality") ?>/></td>
                            <td width = "168">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City Name</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_province") ?>>Province Name</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_stockallocglacctno") ?>>Goods Receipt Not Invoice</label></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_stockallocglacctno") ?>/>&nbsp;&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_stockallocglacctname") ?>/></td>
                            <td width="168"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_costmethod") ?>>Costing Method</label></td>
                            <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_costmethod") ?>></select></td>
                            <td ></td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_linkprograms") ?>>Link Programs/Projects</label></td>
                            <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_linkprograms", 1) ?>/></td>
                            <td ></td>
                            <td >&nbsp;</td>
                        </tr>
                        
                    </table>
                </div>
            </div>
        </div>
    </td></tr>	

    <?php $page->resize->addtab("tab1", -1, 81); ?>
    <?php $page->resize->addtabpage("tab1", "udf") ?>
    <?php // $page->resize->addtabpage("tab1", "bpl") ?>
    <?php // $page->resize->addtabpage("tab1", "pmr") ?>
    <?php // $page->resize->addtabpage("tab1", "mtop") ?>
    <?php // $page->resize->addtabpage("tab1", "rpt") ?>

