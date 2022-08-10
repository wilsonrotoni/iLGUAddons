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
	
function loadu_showtype($p_selected) {
		$status1= array('Days' => 'OB Days', 'PM' => 'OB Out', 'AM' => 'OB In');
		$_html = "";	
		$_selected = "";
		reset($status1);
		while (list($key, $val) = each($status1)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

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
        <tr><td width="168">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Official Business List">
        	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr><td width="128"><label <?php genCaptionHtml($schema["date1"]) ?> >From & To</label></td>
					<td >&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["date1"]) ?> />
                    	 &nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["date2"]) ?> /></td>
                    <td width="160"><label <?php genCaptionHtml($schema["ob_hrs"]) ?> >Total Hrs & Place of Travel</label></td>
					<td >&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["ob_hrs"]) ?> />
                    	 &nbsp;<input type="text" size="35" <?php genInputTextHtml($schema["ob_place"]) ?> /></td>
                </tr>
                <tr>
                	<td width="128"><label <?php genCaptionHtml($schema["ob_time1"]) ?> >Time From & To</label></td>
					<td >&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["ob_time1"]) ?> />
                    	 &nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["ob_time2"]) ?> />
                    <td width="160"><label <?php genCaptionHtml($schema["ob_type"]) ?> >Official Business Type</label></td>
					<td >&nbsp;<select <?php genSelectHtml($schema["ob_type"],array("loadu_showtype","",":")) ?> ></select>
                         &nbsp;&nbsp;<a class="button" href="" onClick="formSubmit('add_data');return false;">Added</a></td>
                </tr>
                <tr>
					<td colspan="4"><?php $objGrids[0]->draw(true) ?></td>
                </tr>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],20,300) ?>		

