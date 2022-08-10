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

function loadu_branch($p_args = array(),$p_selected) {
	global $objConnection;
	$_html = "";	
	$_selected = "";
	$obj = new recordset(NULL,$objConnection);
	$obj->queryopen("SELECT b.code,b.name FROM u_premploymentinfobiometrix a INNER JOIN u_prbranches b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_branches WHERE a.code = '".$_SESSION["userid"]."'");
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
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		</tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Cost Center</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter",array("loadu_pf",$page->getitemstring("u_profitcenter"),":")) ?>></select>
            	 &nbsp;<input type="hidden" <?php $page->businessobject->items->draw->text("name") ?> /></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_branches") ?>>Branches /Machine</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_branches",array("loadu_branch",$page->getitemstring("u_branches"),":")) ?>></select></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_biometrixid") ?>>Biometrix ID</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_biometrixid") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_empid") ?>>ID & Name</label></td>
            <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_empid") ?>/>
            	&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_empname") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Application Date</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/>
            	&nbsp;<input type="hidden" size="9" <?php $page->businessobject->items->userfields->draw->text("u_status") ?>/>
                <input type="hidden" size="5" <?php $page->businessobject->items->userfields->draw->text("u_tastatus") ?>/></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_tareason") ?>>Reason</label></td>
						<td rowspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_tareason") ?>rows="2" cols="50"><?php echo $httpVars['df_u_tareason'] ?></TEXTAREA></td>
		</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Time Adjustments">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
    <?php $page->resize->addgridobject($objGrids[0],20,350) ?>
</td></tr>		


