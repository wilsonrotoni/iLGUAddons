<?php

function loadu_pf($p_args = array(),$p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("SELECT b.code,b.name FROM u_premploymentdeployment a INNER JOIN u_prprofitcenter b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_branch WHERE a.code = '".$_SESSION["userid"]."'");
			if ($obj->recordcount() > 0) {
				while ($obj->queryfetchrow()) {
					$_selected = "";
					if ($p_selected == $obj->fields[0]) $_selected = "selected";
					$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
				}
			}	
			$obj->queryclose();
		echo @$_html;
	}

?>

<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Application No.</label></td>
			<td colspan="2">&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Cost Center</label></td>
            <td colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter",array("loadu_pf",$page->getitemstring("u_profitcenter"),":")) ?>></select></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_empname") ?>>ID & Name</label></td>
            <td colspan="2">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_empid") ?>/>
            	<input type="hidden" size="9" <?php $page->businessobject->items->userfields->draw->text("u_status") ?>/>
                <input type="hidden" <?php $page->businessobject->items->draw->text("name") ?> />
                <input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_empname") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>App Date</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
            <td rowspan="2">
            	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                	<tr>
                    	<td width="120"><label <?php $page->businessobject->items->userfields->draw->caption("u_assreason") ?>>Reason</label></td>
           				<td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_assreason") ?>rows="1" cols="50"><?php echo $httpVars['df_u_assreason'] ?></TEXTAREA></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tk_wd") ?>>Total OB Hrs</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_tk_wd") ?>/>
            <input type="hidden" <?php $page->businessobject->items->userfields->draw->text("u_tk_ot") ?>/>
            <input type="hidden" size="7" <?php $page->businessobject->items->userfields->draw->text("u_offstatus") ?>/>
            <input type="hidden" size="12" <?php $page->businessobject->items->userfields->draw->text("u_tkdate") ?>/></td>
        </tr>
	</table>
</td></tr>	
<tr><td>
    <div class="tabber" id="tab1">
		<div class="tabbertab" title="Official Business List">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
    <?php $page->resize->addgridobject($objGrids[0],20,280) ?>	
</td></tr>
		

