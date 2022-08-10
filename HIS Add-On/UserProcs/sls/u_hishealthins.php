<?php

	include_once("../common/classes/recordset.php");

	function loadu_hishealthins($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '0' order by NAME");
		$_html .= '<optgroup label="National Health Insurance">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryclose();

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '2' order by NAME");
		$_html .= '<optgroup label="Discount">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '1' order by NAME");
		$_html .= '<optgroup label="HMO">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '3' order by NAME");
		$_html .= '<optgroup label="LGU">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '4' order by NAME");
		$_html .= '<optgroup label="Company">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '5' order by NAME");
		$_html .= '<optgroup label="Collector">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '6' order by NAME");
		$_html .= '<optgroup label="Others">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		echo @$_html;
	}
	
	function loadu_hishealthins2($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '0' order by NAME");
		$_html .= '<optgroup label="National Health Insurance">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '1' order by NAME");
		$_html .= '<optgroup label="HMO">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '3' order by NAME");
		$_html .= '<optgroup label="LGU">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '4' order by NAME");
		$_html .= '<optgroup label="Company">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '6' order by NAME");
		$_html .= '<optgroup label="Others">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '7' order by NAME");
		$_html .= '<optgroup label="Expense">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryclose();

		echo @$_html;
	}	
	
	function loadu_hishealthins3($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '0' order by NAME");
		$_html .= '<optgroup label="National Health Insurance">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '2' order by NAME");
		$_html .= '<optgroup label="Discount">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '1' order by NAME");
		$_html .= '<optgroup label="HMO">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '4' order by NAME");
		$_html .= '<optgroup label="Company">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryclose();

		echo @$_html;
	}

	function loadu_hishealthins4($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '1' order by NAME");
		$_html .= '<optgroup label="HMO">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '3' order by NAME");
		$_html .= '<optgroup label="LGU">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '4' order by NAME");
		$_html .= '<optgroup label="Company">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '6' order by NAME");
		$_html .= '<optgroup label="Others">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '7' order by NAME");
		$_html .= '<optgroup label="Expense">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryclose();

		echo @$_html;
	}	
	
	function loadu_hishealthins5($p_selected) {
		global $objConnection;
		global $page;

		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '0' order by NAME");
		$_html .= '<optgroup label="National Health Insurance">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryclose();

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '1' order by NAME");
		$_html .= '<optgroup label="HMO">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '3' order by NAME");
		$_html .= '<optgroup label="LGU">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '4' order by NAME");
		$_html .= '<optgroup label="Company">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		echo @$_html;
	}
	
	function loadu_hishealthins6($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '0' order by NAME");
		$_html .= '<optgroup label="National Health Insurance">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryclose();

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '2' order by NAME");
		$_html .= '<optgroup label="Discount">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '1' order by NAME");
		$_html .= '<optgroup label="HMO">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '3' order by NAME");
		$_html .= '<optgroup label="LGU">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '4' order by NAME");
		$_html .= '<optgroup label="Company">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '5' order by NAME");
		$_html .= '<optgroup label="Collector">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '6' order by NAME");
		$_html .= '<optgroup label="Others">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '7' order by NAME");
		$_html .= '<optgroup label="Expense">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		echo @$_html;
	}
	
	function loadu_hishealthins7($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '0' order by NAME");
		$_html .= '<optgroup label="National Health Insurance">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";
		$obj->queryclose();

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '1' order by NAME");
		$_html .= '<optgroup label="HMO">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '3' order by NAME");
		$_html .= '<optgroup label="LGU">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '4' order by NAME");
		$_html .= '<optgroup label="Company">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '5' order by NAME");
		$_html .= '<optgroup label="Collector">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '6' order by NAME");
		$_html .= '<optgroup label="Others">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		echo @$_html;
	}
		
	function loadu_hishealthins8($p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";

		$obj = new recordset(NULL,$objConnection);
		
		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '2' order by NAME");
		$_html .= '<optgroup label="Discount">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '1' order by NAME");
		$_html .= '<optgroup label="HMO">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '3' order by NAME");
		$_html .= '<optgroup label="LGU">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '4' order by NAME");
		$_html .= '<optgroup label="Company">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '5' order by NAME");
		$_html .= '<optgroup label="Collector">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '6' order by NAME");
		$_html .= '<optgroup label="Others">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		$obj->queryopen("select CODE, NAME from U_HISHEALTHINS where U_HMO = '7' order by NAME");
		$_html .= '<optgroup label="Expense">';
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$_html .= "</optgroup>";

		echo @$_html;
	}
		
	function slgetdisplayu_hishealthins($p_selected) {
		global $objConnection;
		$data = $p_selected;
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select NAME from U_HISHEALTHINS where CODE = '$p_selected'");
		if ($obj->recordcount() > 0) {
			if ($obj->queryfetchrow()) {
				$data = $obj->fields[0];
			} else {
				$data = $p_selected;
			}
		}
		$obj->queryclose();
		return $data;
	}

	
?>