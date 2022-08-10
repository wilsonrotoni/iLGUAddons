<?php


    set_time_limit(0);

    $progid = "u_UploadGeneralRevisions";

    if(!empty($_POST)) extract($_POST);
    if(!empty($_GET)) extract($_GET);
    $httpVars = array_merge($_POST,$_GET);

    include_once("../common/classes/connection.php");
    include_once("../common/classes/recordset.php");
    include_once("../common/classes/grid.php");
    include_once("./classes/masterdataschema.php");
    include_once("./classes/masterdatalinesschema.php");
    include_once("./classes/documentschema_br.php");
    include_once("./classes/documentlinesschema_br.php");
    include_once("./classes/masterdatalinesschema_br.php");
    include_once("./utils/companies.php");
    include_once("./inc/formutils.php"); 

    $page->restoreSavedValues();

    $page->objectcode = "U_UPLOADGENERALREVISIONS";
    $page->paging->formid = "./UDP.php?&objectcode=U_UPLOADGENERALREVISIONS";
    $page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
    $page->objectname = "Migrate GEO RPTAS";
    $page->settimeout(0);

    $enumkindofproperty = array();
    $enumkindofproperty["L"]="Land";
    $enumkindofproperty["B"]="Building and Other Structure";
    $enumkindofproperty["M"]="Machinery";

    $schema["barangay"] = createSchemaUpper("barangay");
    $schema["kind"] = createSchemaUpper("kind");
    $schema["revisionyear"] = createSchemaUpper("revisionyear");
        
    $objGrid = new grid("T1",$httpVars);
        
    $objGrid->addcolumn("docstatus");
    $objGrid->addcolumn("u_kind");
    $objGrid->addcolumn("u_barangay");
    $objGrid->addcolumn("u_quantity");
    
    $objGrid->columntitle("docstatus","Document Status");
    $objGrid->columntitle("u_kind","Property Kind");
    $objGrid->columntitle("u_quantity","Total Quantity");
    $objGrid->columntitle("u_barangay","Barangay");

    $objGrid->columnwidth("indicator",1);
    $objGrid->columnwidth("docstatus",15);
    $objGrid->columnwidth("u_kind",15);
    $objGrid->columnwidth("u_quantity",10);
    $objGrid->columnwidth("u_barangay",30);

    $objGrid->columnalignment("u_quantity","right");
    
    $objGrid->automanagecolumnwidth = true;
    
    $filterExp = "";
    $filterExp = genSQLFilterString("U_REVISIONYEAR",$filterExp,$httpVars['df_revisionyear']);
    $filterExp = genSQLFilterString("U_BARANGAY",$filterExp,$httpVars['df_barangay'],null,null,false);
    if ($filterExp !="") $filterExp = " AND " . $filterExp;
        
    function loadenumkindofproperty($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumkindofproperty;
		reset($enumkindofproperty);
		while (list($key, $val) = each($enumkindofproperty)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
    }
    
    
    function onFormAction($action) {
        global $objConnection;
        global $page;
        $actionReturn = true;
        if(!empty($_POST)) extract($_POST);
        if(!empty($_GET)) extract($_GET);
        $httpVars = array_merge($_POST,$_GET);

        $objRsFaas1 = new recordset(null,$objConnection);
        $objRsFaas1a = new recordset(null,$objConnection);
        $objRsFaas1b = new recordset(null,$objConnection);
        $objRsFaas1c = new recordset(null,$objConnection);
        $objRsFaas1d = new recordset(null,$objConnection);
        $objRsFaas1e = new recordset(null,$objConnection);
        $objRsFaas1p = new recordset(null,$objConnection);
        
        $objRsFaas2 = new recordset(null,$objConnection);
        $objRsFaas2a = new recordset(null,$objConnection);
        $objRsFaas2b = new recordset(null,$objConnection);
        $objRsFaas2c = new recordset(null,$objConnection);
        $objRsFaas2d = new recordset(null,$objConnection);
        $objRsFaas2e = new recordset(null,$objConnection);
        $objRsFaas2p = new recordset(null,$objConnection);
        
        $objRsFaas3 = new recordset(null,$objConnection);
        $objRsFaas3a = new recordset(null,$objConnection);
        $objRsFaas3b = new recordset(null,$objConnection);
        $objRsFaas3c = new recordset(null,$objConnection);
        $objRsFaas3p = new recordset(null,$objConnection);

        $objConnection->beginwork();

        if($action=="uploadgeneralrevisions"){  
            
            $filterExp = "";
            $filterExp = genSQLFilterString("U_REVISIONYEAR",$filterExp,$httpVars['df_revisionyear']);
            $filterExp = genSQLFilterString("U_BARANGAY",$filterExp,$httpVars['df_barangay'],null,null,false);
            if ($filterExp !="") $filterExp = " AND " . $filterExp;
            
            if ($page->getitemstring("kind")=="L") {
            $obju_Faas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
            $obju_Faas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
            $obju_Faas1b = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
            $obju_Faas1c = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
            $obju_Faas1d = new documentlinesschema_br(null,$objConnection,"u_rpfaas1d");
            $obju_Faas1e = new documentlinesschema_br(null,$objConnection,"u_rpfaas1e");
            $obju_Faas1p = new documentlinesschema_br(null,$objConnection,"u_rpfaas1p");
            
            $objRsFaas1->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS1 WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' $filterExp ");
            while ($objRsFaas1->queryfetchrow("NAME")) {
                    $num_rows++;
                    $obju_Faas1->prepareadd();
                    $obju_Faas1->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
                    $obju_Faas1->docno = getNextNoByBranch("u_rpfaas1","",$objConnection);
                    $obju_Faas1->docseries = -1;
                    $obju_Faas1->docstatus = $objRsFaas1->fields["DOCSTATUS"];
                    $obju_Faas1->setudfvalue("u_revisiondocno",$objRsFaas1->fields["DOCNO"]);
                    $obju_Faas1->setudfvalue("u_cancelled",$objRsFaas1->fields["U_CANCELLED"]);
                    $obju_Faas1->setudfvalue("u_temporarytdn",$objRsFaas1->fields["U_TEMPORARYTDN"]);
                    $obju_Faas1->setudfvalue("u_temporary",$objRsFaas1->fields["U_TEMPORARY"]);
                    $obju_Faas1->setudfvalue("u_pin",$objRsFaas1->fields["U_PIN"]);
                    $obju_Faas1->setudfvalue("u_tdno",$objRsFaas1->fields["U_TDNO"]);
                    $obju_Faas1->setudfvalue("u_varpno",$objRsFaas1->fields["U_VARPNO"]);
                    $obju_Faas1->setudfvalue("u_tctno",$objRsFaas1->fields["U_TCTNO"]);
                    $obju_Faas1->setudfvalue("u_tctdate",$objRsFaas1->fields["U_TCTDATE"]);
                    $obju_Faas1->setudfvalue("u_class",$objRsFaas1->fields["U_CLASS"]);
                    $obju_Faas1->setudfvalue("u_actualuse",$objRsFaas1->fields["U_ACTUALUSE"]);

                    $obju_Faas1->setudfvalue("u_trxcode",$objRsFaas1->fields["U_TRXCODE"]);
                    $obju_Faas1->setudfvalue("u_phase",$objRsFaas1->fields["U_PHASE"]);
                    $obju_Faas1->setudfvalue("u_titleno",$objRsFaas1->fields["U_TITLENO"]);
                    $obju_Faas1->setudfvalue("u_lotno",$objRsFaas1->fields["U_LOTNO"]);
                    $obju_Faas1->setudfvalue("u_block",$objRsFaas1->fields["U_BLOCK"]);
                    $obju_Faas1->setudfvalue("u_surveyno",$objRsFaas1->fields["U_SURVEYNO"]);
                    $obju_Faas1->setudfvalue("u_cadlotno",$objRsFaas1->fields["U_CADLOTNO"]);

                   
                    $obju_Faas1->setudfvalue("u_ownercompanyname",$objRsFaas1->fields["U_OWNERCOMPANYNAME"]);
                    $obju_Faas1->setudfvalue("u_ownername",$objRsFaas1->fields["U_OWNERNAME"]);
                    $obju_Faas1->setudfvalue("u_ownerlastname",$objRsFaas1->fields["U_OWNERLASTNAME"]);
                    $obju_Faas1->setudfvalue("u_ownerfirstname",$objRsFaas1->fields["U_OWNERFIRSTNAME"]);
                    $obju_Faas1->setudfvalue("u_ownerfirstname",$objRsFaas1->fields["U_OWNERFIRSTNAME"]);
                    $obju_Faas1->setudfvalue("u_email",$objRsFaas1->fields["U_EMAIL"]);
                    $obju_Faas1->setudfvalue("u_ownerkind",$objRsFaas1->fields["U_OWNERKIND"]);
                    $obju_Faas1->setudfvalue("u_ownertin",$objRsFaas1->fields["U_OWNERTIN"]);
                    $obju_Faas1->setudfvalue("u_owneraddress",$objRsFaas1->fields["U_OWNERADDRESS"]);
                    $obju_Faas1->setudfvalue("u_ownertype",$objRsFaas1->fields["U_OWNERTYPE"]);

                    $obju_Faas1->setudfvalue("u_adminname",$objRsFaas1->fields["U_ADMINNAME"]);
                    $obju_Faas1->setudfvalue("u_adminfirstname",$objRsFaas1->fields["U_ADMINFIRSTNAME"]);
                    $obju_Faas1->setudfvalue("u_adminmiddlename",$objRsFaas1->fields["U_ADMINMIDDLENAME"]);
                    $obju_Faas1->setudfvalue("u_adminlastname",$objRsFaas1->fields["U_ADMINLASTNAME"]);
                    $obju_Faas1->setudfvalue("u_adminaddress",$objRsFaas1->fields["U_ADMINADDRESS"]);
                    $obju_Faas1->setudfvalue("u_admintelno",$objRsFaas1->fields["U_ADMINTELNO"]);
                    $obju_Faas1->setudfvalue("u_revisionyear",$objRsFaas1->fields["U_REVISIONYEAR"]);

                    $obju_Faas1->setudfvalue("u_street",$objRsFaas1->fields["U_STREET"]);
                    $obju_Faas1->setudfvalue("u_subdivision",$objRsFaas1->fields["U_SUBDIVISION"]);
                    $obju_Faas1->setudfvalue("u_location",$objRsFaas1->fields["U_LOCATION"]);
                    $obju_Faas1->setudfvalue("u_oldbarangay",$objRsFaas1->fields["U_OLDBARANGAY"]);
                    $obju_Faas1->setudfvalue("u_barangay",$objRsFaas1->fields["U_BARANGAY"]);
                    $obju_Faas1->setudfvalue("u_municipality",$objRsFaas1->fields["U_MUNICIPALITY"]);
                    $obju_Faas1->setudfvalue("u_city",$objRsFaas1->fields["U_CITY"]);
                    $obju_Faas1->setudfvalue("u_province",$objRsFaas1->fields["U_PROVINCE"]);
                    
                    $obju_Faas1->setudfvalue("u_north",$objRsFaas1->fields["U_NORTH"]);
                    $obju_Faas1->setudfvalue("u_south",$objRsFaas1->fields["U_SOUTH"]);
                    $obju_Faas1->setudfvalue("u_east",$objRsFaas1->fields["U_EAST"]);
                    $obju_Faas1->setudfvalue("u_west",$objRsFaas1->fields["U_WEST"]);

                    $obju_Faas1->setudfvalue("u_taxable",$objRsFaas1->fields["U_TAXABLE"]);
                    $obju_Faas1->setudfvalue("u_effdate",$objRsFaas1->fields["U_EFFDATE"]);
                    $obju_Faas1->setudfvalue("u_effyear",$objRsFaas1->fields["U_EFFYEAR"]);
                    $obju_Faas1->setudfvalue("u_effqtr",$objRsFaas1->fields["U_EFFQTR"]);
                    
                    $obju_Faas1->setudfvalue("u_expyear",$objRsFaas1->fields["U_EXPYEAR"]);
                    $obju_Faas1->setudfvalue("u_expqtr",$objRsFaas1->fields["U_EXPQTR"]);
                    $obju_Faas1->setudfvalue("u_expdate",$objRsFaas1->fields["U_EXPDATE"]);   
                    
                    $obju_Faas1->setudfvalue("u_bilyear",$objRsFaas1->fields["U_BILYEAR"]);
                    $obju_Faas1->setudfvalue("u_bilqtr",$objRsFaas1->fields["U_BILQTR"]);
                    $obju_Faas1->setudfvalue("u_lastpaymode",$objRsFaas1->fields["U_LASTPAYMODE"]);

                    $obju_Faas1->setudfvalue("u_recordedby",$objRsFaas1->fields["U_RECORDEDBY"]);
                    $obju_Faas1->setudfvalue("u_recordeddate",$objRsFaas1->fields["U_RECORDEDDATE"]);
                    $obju_Faas1->setudfvalue("u_assessedby",$objRsFaas1->fields["U_ASSESSEDBY"]);
                    $obju_Faas1->setudfvalue("u_assesseddate",$objRsFaas1->fields["U_ASSESSEDDATE"]);
                    $obju_Faas1->setudfvalue("u_recommendby",$objRsFaas1->fields["U_RECOMMENDBY"]);
                    $obju_Faas1->setudfvalue("u_recommenddate",$objRsFaas1->fields["U_RECOMMENDDATE"]);
                    $obju_Faas1->setudfvalue("u_approvedby",$objRsFaas1->fields["U_APPROVEDBY"]);
                    $obju_Faas1->setudfvalue("u_approveddate",$objRsFaas1->fields["U_APPROVEDDATE"]);
                    $obju_Faas1->setudfvalue("u_releaseddate",$objRsFaas1->fields["U_RELEASEDDATE"]);
                    $obju_Faas1->setudfvalue("u_memoranda",$objRsFaas1->fields["U_MEMORANDA"]);
                    $obju_Faas1->setudfvalue("u_annotation",$objRsFaas1->fields["U_ANNOTATION"]);
                    $obju_Faas1->setudfvalue("u_assvalue",$objRsFaas1->fields["U_ASSVALUE"]);
                    $obju_Faas1->setudfvalue("u_totalareasqm",$objRsFaas1->fields["U_TOTALAREASQM"]);
                    $obju_Faas1->setudfvalue("u_marketvalue",$objRsFaas1->fields["U_MARKETVALUE"]);

                    $obju_Faas1->setudfvalue("u_prevarpno",$objRsFaas1->fields["U_PREVARPNO"]);
                    $obju_Faas1->setudfvalue("u_prevarpno2",$objRsFaas1->fields["U_PREVARPNO2"]);
                    $obju_Faas1->setudfvalue("u_prevtdno",$objRsFaas1->fields["U_PREVTDNO"]);
                    $obju_Faas1->setudfvalue("u_prevpin",$objRsFaas1->fields["U_PREVPIN"]);
                    $obju_Faas1->setudfvalue("u_prevowner",$objRsFaas1->fields["U_PREVOWNER"]);
                    $obju_Faas1->setudfvalue("u_prevvalue",$objRsFaas1->fields["U_PREVVALUE"]);
                    $obju_Faas1->setudfvalue("u_preveffdate",$objRsFaas1->fields["U_PREVEFFDATE"]);
                    $obju_Faas1->setudfvalue("u_prevrecordeddate",$objRsFaas1->fields["U_PREVRECORDEDDATE"]);
                    $obju_Faas1->setudfvalue("u_prevrecordedby",$objRsFaas1->fields["U_PREVRECORDEDBY"]);

                    if ($actionReturn) {
                        $objRsFaas1p->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS1P WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and DOCID = '".$objRsFaas1->fields["DOCID"]."' ");
                        while ($objRsFaas1p->queryfetchrow("NAME")) {
                            $obju_Faas1p->prepareadd();
                            $obju_Faas1p->docid = $obju_Faas1->docid;
                            $obju_Faas1p->lineid = getNextIDByBranch("u_rpfaas1p",$objConnection);
                            $obju_Faas1p->setudfvalue("u_prevarpno",$objRsFaas1p->fields["U_PREVARPNO"]);
                            $obju_Faas1p->setudfvalue("u_prevarpno2",$objRsFaas1p->fields["U_PREVARPNO2"]);
                            $obju_Faas1p->setudfvalue("u_prevtdno",$objRsFaas1p->fields["U_PREVTDNO"]);
                            $obju_Faas1p->setudfvalue("u_prevpin",$objRsFaas1p->fields["U_PREVPIN"]);
                            $obju_Faas1p->setudfvalue("u_prevowner",$objRsFaas1p->fields["U_PREVOWNER"]);
                            $obju_Faas1p->setudfvalue("u_prevvalue",$objRsFaas1p->fields["U_PREVVALUE"]);
                            $obju_Faas1p->setudfvalue("u_preveffdate",$objRsFaas1p->fields["U_PREVEFFDATE"]);
                            $obju_Faas1p->setudfvalue("u_prevrecordeddate",$objRsFaas1p->fields["U_PREVRECORDEDDATE"]);
                            $obju_Faas1p->setudfvalue("u_prevrecordedby",$objRsFaas1p->fields["U_PREVRECORDEDBY"]);
                            $actionReturn = $obju_Faas1p->add();
                            if (!$actionReturn) break;
                        }
                    }

                    if ($actionReturn) {
                        $objRsFaas1a->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS1A WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and u_arpno = '".$objRsFaas1->fields["DOCNO"]."' ");
                        while ($objRsFaas1a->queryfetchrow("NAME")) {
                            $obju_Faas1a->prepareadd();
                            $obju_Faas1a->docid = getNextIDByBranch("u_rpfaas1a",$objConnection);
                            $obju_Faas1a->docno = getNextNoByBranch("u_rpfaas1a","",$objConnection);
                            $obju_Faas1a->setudfvalue("u_gryear",$objRsFaas1a->fields["U_GRYEAR"]);
                            $obju_Faas1a->setudfvalue("u_arpno",$obju_Faas1->docno);
                            $obju_Faas1a->setudfvalue("u_class",$objRsFaas1a->fields["U_CLASS"]);
                            $obju_Faas1a->setudfvalue("u_taxable",$objRsFaas1a->fields["U_TAXABLE"]);
                            $obju_Faas1a->setudfvalue("u_sqm",$objRsFaas1a->fields["U_SQM"]);
                            $obju_Faas1a->setudfvalue("u_basevalue",$objRsFaas1a->fields["U_BASEVALUE"]);
                            $obju_Faas1a->setudfvalue("u_adjvalue",$objRsFaas1a->fields["U_ADJVALUE"]);
                            $obju_Faas1a->setudfvalue("u_marketvalue",$objRsFaas1a->fields["U_MARKETVALUE"]);
                            $obju_Faas1a->setudfvalue("u_actualuse",$objRsFaas1a->fields["U_ACTUALUSE"]);
                            $obju_Faas1a->setudfvalue("u_asslvl",$objRsFaas1a->fields["U_ASSLVL"]);
                            $obju_Faas1a->setudfvalue("u_assvalue", $objRsFaas1a->fields["U_ASSVALUE"]);
                            
                            if ($actionReturn) {
                                $objRsFaas1b->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS1B WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas1a->fields["DOCID"]."' ");
                                while ($objRsFaas1b->queryfetchrow("NAME")) {
                                    $obju_Faas1b->prepareadd();
                                    $obju_Faas1b->docid = $obju_Faas1a->docid;
                                    $obju_Faas1b->lineid = getNextIDByBranch("u_rpfaas1b",$objConnection);
                                    $obju_Faas1b->setudfvalue("u_subclass",$objRsFaas1b->fields["U_SUBCLASS"]);
                                    $obju_Faas1b->setudfvalue("u_class",$objRsFaas1b->fields["U_CLASS"]);
                                    $obju_Faas1b->setudfvalue("u_description",$objRsFaas1b->fields["U_DESCRIPTION"]);
                                    $obju_Faas1b->setudfvalue("u_unitvaluehas",$objRsFaas1b->fields["U_UNITVALUEHAS"]);
                                    $obju_Faas1b->setudfvalue("u_unit",$objRsFaas1b->fields["U_UNIT"]);
                                    $obju_Faas1b->setudfvalue("u_sqmhas",$objRsFaas1b->fields["U_SQMHAS"]);
                                    $obju_Faas1b->setudfvalue("u_sqm",$objRsFaas1b->fields["U_SQM"]);
                                    $obju_Faas1b->setudfvalue("u_unitvalue",$objRsFaas1b->fields["U_UNITVALUE"]);
                                    $obju_Faas1b->setudfvalue("u_basevalue",$objRsFaas1b->fields["U_BASEVALUE"]);
                                    $actionReturn = $obju_Faas1b->add();
                                    if (!$actionReturn) break;
                                }
                            }
                            
                            if ($actionReturn) {
                                $objRsFaas1c->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS1C WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas1a->fields["DOCID"]."' ");
                                while ($objRsFaas1c->queryfetchrow("NAME")) {
                                    $obju_Faas1c->prepareadd();
                                    $obju_Faas1c->docid = $obju_Faas1a->docid;
                                    $obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
                                    $obju_Faas1c->setudfvalue("u_adjtype",$objRsFaas1c->fields["U_ADJTYPE"]);
                                    $obju_Faas1c->setudfvalue("u_adjfactor",$objRsFaas1c->fields["U_ADJFACTOR"]);
                                    $obju_Faas1c->setudfvalue("u_adjperc",$objRsFaas1c->fields["U_ADJPERC"]);
                                    $obju_Faas1c->setudfvalue("u_adjvalue",$objRsFaas1c->fields["U_ADJVALUE"]);
                                    $actionReturn = $obju_Faas1c->add();
                                    if (!$actionReturn) break;
                                }
                            }
                            
                            if ($actionReturn) {
                                $objRsFaas1d->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS1D WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas1a->fields["DOCID"]."' ");
                                while ($objRsFaas1d->queryfetchrow("NAME")) {
                                    $obju_Faas1d->prepareadd();
                                    $obju_Faas1d->docid = $obju_Faas1a->docid;
                                    $obju_Faas1d->lineid = getNextIDByBranch("u_rpfaas1d",$objConnection);
                                    $obju_Faas1d->setudfvalue("u_planttype",$objRsFaas1d->fields["U_PLANTTYPE"]);
                                    $obju_Faas1d->setudfvalue("u_class",$objRsFaas1d->fields["U_CLASS"]);
                                    $obju_Faas1d->setudfvalue("u_productive",$objRsFaas1d->fields["U_PRODUCTIVE"]);
                                    $obju_Faas1d->setudfvalue("u_nonproductive",$objRsFaas1d->fields["U_NONPRODUCTIVE"]);
                                    $obju_Faas1d->setudfvalue("u_totalcount",$objRsFaas1d->fields["U_TOTALCOUNT"]);
                                    $obju_Faas1d->setudfvalue("u_unitvalue",$objRsFaas1d->fields["U_UNITVALUE"]);
                                    $obju_Faas1d->setudfvalue("u_marketvalue",$objRsFaas1d->fields["U_MARKETVALUE"]);
                                    $actionReturn = $obju_Faas1d->add();
                                    if (!$actionReturn) break;
                                }
                            }
                            
                            if ($actionReturn) {
                                $objRsFaas1e->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS1E WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas1a->fields["DOCID"]."' ");
                                while ($objRsFaas1e->queryfetchrow("NAME")) {
                                    $obju_Faas1e->prepareadd();
                                    $obju_Faas1e->docid = $obju_Faas1a->docid;
                                    $obju_Faas1e->lineid = getNextIDByBranch("u_rpfaas1e",$objConnection);
                                    $obju_Faas1e->setudfvalue("u_strip",$objRsFaas1e->fields["U_STRIP"]);
                                    $obju_Faas1e->setudfvalue("u_sqm",$objRsFaas1e->fields["U_SQM"]);
                                    $obju_Faas1e->setudfvalue("u_adjperc",$objRsFaas1e->fields["U_ADJPERC"]);
                                    $obju_Faas1e->setudfvalue("u_unitvalue",$objRsFaas1e->fields["U_UNITVALUE"]);
                                    $obju_Faas1e->setudfvalue("u_adjunitvalue",$objRsFaas1e->fields["U_ADJUNITVALUE"]);
                                    $obju_Faas1e->setudfvalue("u_basevalue",$objRsFaas1e->fields["U_BASEVALUE"]);
                                    $actionReturn = $obju_Faas1e->add();
                                    if (!$actionReturn) break;
                                }
                            }
                            
                            $actionReturn = $obju_Faas1a->add();
                        }
                    }

                if ($actionReturn) $actionReturn = $obju_Faas1->add();
                if (!$actionReturn) break;

                    
                }
                
                if ($actionReturn) {
                        $objConnection->commit();
                } else {
                    $myfile = fopen("../Addons/GPS/RPTAS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
                    $txt = $_SESSION["errormessage"]."\n";
                    fwrite($myfile, $txt);
                    fclose($myfile);
                    $objConnection->rollback();
                    echo $_SESSION["errormessage"];
                }
                
            } else if ($page->getitemstring("kind")=="B") {

                $obju_Faas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
                $obju_Faas2a = new documentschema_br(null,$objConnection,"u_rpfaas2a");
                $obju_Faas2b = new documentlinesschema_br(null,$objConnection,"u_rpfaas2b");
                $obju_Faas2c = new documentlinesschema_br(null,$objConnection,"u_rpfaas2c");
                $obju_Faas2d = new documentlinesschema_br(null,$objConnection,"u_rpfaas2d");
                $obju_Faas2e = new documentlinesschema_br(null,$objConnection,"u_rpfaas2e");
                $obju_Faas2p = new documentlinesschema_br(null,$objConnection,"u_rpfaas2p");

                $objRsFaas2->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS2 WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."'  and DOCSTATUS in ('Approved')  $filterExp ");
                while ($objRsFaas2->queryfetchrow("NAME")) {
                    $num_rows++;
                    $obju_Faas2->prepareadd();
                    $obju_Faas2->docid = getNextIDByBranch("u_rpfaas2",$objConnection);
                    $obju_Faas2->docno = getNextNoByBranch("u_rpfaas2","",$objConnection);
                    $obju_Faas2->docseries = -1;
                    $obju_Faas2->docstatus = $objRsFaas2->fields["DOCSTATUS"];
                    $obju_Faas2->setudfvalue("u_revisiondocno",$objRsFaas2->fields["DOCNO"]);
                    $obju_Faas2->setudfvalue("u_cancelled",$objRsFaas2->fields["U_CANCELLED"]);
                    $obju_Faas2->setudfvalue("u_temporarytdn",$objRsFaas2->fields["U_TEMPORARYTDN"]);
                    $obju_Faas2->setudfvalue("u_temporary",$objRsFaas2->fields["U_TEMPORARY"]);
                    $obju_Faas2->setudfvalue("u_prefix",$objRsFaas2->fields["U_PREFIX"]);
                    $obju_Faas2->setudfvalue("u_suffix",$objRsFaas2->fields["U_SUFFIX"]);
                    $obju_Faas2->setudfvalue("u_pin",$objRsFaas2->fields["U_PIN"]);
                    $obju_Faas2->setudfvalue("u_tdno",$objRsFaas2->fields["U_TDNO"]);
                    $obju_Faas2->setudfvalue("u_varpno",$objRsFaas2->fields["U_VARPNO"]);
                    $obju_Faas2->setudfvalue("u_class",$objRsFaas2->fields["U_CLASS"]);
                    $obju_Faas2->setudfvalue("u_actualuse",$objRsFaas2->fields["U_ACTUALUSE"]);
                    
                    $obju_Faas2->setudfvalue("u_revisionyear",$objRsFaas2->fields["U_REVISIONYEAR"]);
                    $obju_Faas2->setudfvalue("u_trxcode",$objRsFaas2->fields["U_TRXCODE"]);
                    $obju_Faas2->setudfvalue("u_ownercompanyname",$objRsFaas2->fields["U_OWNERCOMPANYNAME"]);
                    $obju_Faas2->setudfvalue("u_ownername",$objRsFaas2->fields["U_OWNERNAME"]);
                    $obju_Faas2->setudfvalue("u_ownerlastname",$objRsFaas2->fields["U_OWNERLASTNAME"]);
                    $obju_Faas2->setudfvalue("u_ownerfirstname",$objRsFaas2->fields["U_OWNERFIRSTNAME"]);
                    $obju_Faas2->setudfvalue("u_ownerfirstname",$objRsFaas2->fields["U_OWNERFIRSTNAME"]);
                    $obju_Faas2->setudfvalue("u_email",$objRsFaas2->fields["U_EMAIL"]);
                    $obju_Faas2->setudfvalue("u_ownerkind",$objRsFaas2->fields["U_OWNERKIND"]);
                    $obju_Faas2->setudfvalue("u_ownertin",$objRsFaas2->fields["U_OWNERTIN"]);
                    $obju_Faas2->setudfvalue("u_ownertelno",$objRsFaas2->fields["U_OWNERTELNO"]);
                    $obju_Faas2->setudfvalue("u_owneraddress",$objRsFaas2->fields["U_OWNERADDRESS"]);
                    $obju_Faas2->setudfvalue("u_ownertype",$objRsFaas2->fields["U_OWNERTYPE"]);
                     
                    $obju_Faas2->setudfvalue("u_adminname",$objRsFaas2->fields["U_ADMINNAME"]);
                    $obju_Faas2->setudfvalue("u_adminfirstname",$objRsFaas2->fields["U_ADMINFIRSTNAME"]);
                    $obju_Faas2->setudfvalue("u_adminmiddlename",$objRsFaas2->fields["U_ADMINMIDDLENAME"]);
                    $obju_Faas2->setudfvalue("u_adminlastname",$objRsFaas2->fields["U_ADMINLASTNAME"]);
                    $obju_Faas2->setudfvalue("u_adminaddress",$objRsFaas2->fields["U_ADMINADDRESS"]);
                    $obju_Faas2->setudfvalue("u_admintelno",$objRsFaas2->fields["U_ADMINTELNO"]);
                    $obju_Faas2->setudfvalue("u_admintin",$objRsFaas2->fields["U_ADMINTIN"]);
                    
                    $obju_Faas2->setudfvalue("u_street",$objRsFaas2->fields["U_STREET"]);
                    $obju_Faas2->setudfvalue("u_subdivision",$objRsFaas2->fields["U_SUBDIVISION"]);
                    $obju_Faas2->setudfvalue("u_location",$objRsFaas2->fields["U_LOCATION"]);
                    $obju_Faas2->setudfvalue("u_oldbarangay",$objRsFaas2->fields["U_OLDBARANGAY"]);
                    $obju_Faas2->setudfvalue("u_barangay",$objRsFaas2->fields["U_BARANGAY"]);
                    $obju_Faas2->setudfvalue("u_municipality",$objRsFaas2->fields["U_MUNICIPALITY"]);
                    $obju_Faas2->setudfvalue("u_city",$objRsFaas2->fields["U_CITY"]);
                    $obju_Faas2->setudfvalue("u_province",$objRsFaas2->fields["U_PROVINCE"]);
                    
                    $obju_Faas2->setudfvalue("u_landowner",$objRsFaas2->fields["U_LANDOWNER"]);
                    $obju_Faas2->setudfvalue("u_surveyno",$objRsFaas2->fields["U_SURVEYNO"]);
                    $obju_Faas2->setudfvalue("u_tctno",$objRsFaas2->fields["U_TCTNO"]);
                    $obju_Faas2->setudfvalue("u_lotno",$objRsFaas2->fields["U_LOTNO"]);
                    $obju_Faas2->setudfvalue("u_block",$objRsFaas2->fields["U_BLOCK"]);
                    $obju_Faas2->setudfvalue("u_landtdno",$objRsFaas2->fields["U_LANDTDNO"]);
                    $obju_Faas2->setudfvalue("u_sqm",$objRsFaas2->fields["U_SQM"]);
                    
                    $obju_Faas2->setudfvalue("u_building",$objRsFaas2->fields["U_BUILDING"]);
                    $obju_Faas2->setudfvalue("u_structuretype",$objRsFaas2->fields["U_STRUCTURETYPE"]);
                    $obju_Faas2->setudfvalue("u_cct",$objRsFaas2->fields["U_CCT"]);
                    $obju_Faas2->setudfvalue("u_ccidate",$objRsFaas2->fields["U_CCIDATE"]);
                    $obju_Faas2->setudfvalue("u_coidate",$objRsFaas2->fields["U_COIDATE"]);
                    $obju_Faas2->setudfvalue("u_floorcount",$objRsFaas2->fields["U_FLOORCOUNT"]);
                    $obju_Faas2->setudfvalue("u_bldgname",$objRsFaas2->fields["U_BLDGNAME"]);
                    $obju_Faas2->setudfvalue("u_bpno",$objRsFaas2->fields["U_BPNO"]);
                    $obju_Faas2->setudfvalue("u_startyear",$objRsFaas2->fields["U_STARTYEAR"]);
                    $obju_Faas2->setudfvalue("u_endyear",$objRsFaas2->fields["U_ENDYEAR"]);
                    $obju_Faas2->setudfvalue("u_occyear",$objRsFaas2->fields["U_OCCYEAR"]);
                    $obju_Faas2->setudfvalue("u_renyear",$objRsFaas2->fields["U_RENYEAR"]);
                    $obju_Faas2->setudfvalue("u_bpdate",$objRsFaas2->fields["U_BPDATE"]);

                    $obju_Faas2->setudfvalue("u_taxable",$objRsFaas2->fields["U_TAXABLE"]);
                    $obju_Faas2->setudfvalue("u_effdate",$objRsFaas2->fields["U_EFFDATE"]);
                    $obju_Faas2->setudfvalue("u_effyear",$objRsFaas2->fields["U_EFFYEAR"]);
                    $obju_Faas2->setudfvalue("u_effqtr",$objRsFaas2->fields["U_EFFQTR"]);
                    
                    $obju_Faas2->setudfvalue("u_expyear",$objRsFaas2->fields["U_EXPYEAR"]);
                    $obju_Faas2->setudfvalue("u_expqtr",$objRsFaas2->fields["U_EXPQTR"]);
                    $obju_Faas2->setudfvalue("u_expdate",$objRsFaas2->fields["U_EXPDATE"]);   
                    
                    $obju_Faas2->setudfvalue("u_bilyear",$objRsFaas2->fields["U_BILYEAR"]);
                    $obju_Faas2->setudfvalue("u_bilqtr",$objRsFaas2->fields["U_BILQTR"]);
                    $obju_Faas2->setudfvalue("u_lastpaymode",$objRsFaas2->fields["U_LASTPAYMODE"]);

                    $obju_Faas2->setudfvalue("u_recordedby",$objRsFaas2->fields["U_RECORDEDBY"]);
                    $obju_Faas2->setudfvalue("u_recordeddate",$objRsFaas2->fields["U_RECORDEDDATE"]);
                    $obju_Faas2->setudfvalue("u_assessedby",$objRsFaas2->fields["U_ASSESSEDBY"]);
                    $obju_Faas2->setudfvalue("u_assesseddate",$objRsFaas2->fields["U_ASSESSEDDATE"]);
                    $obju_Faas2->setudfvalue("u_recommendby",$objRsFaas2->fields["U_RECOMMENDBY"]);
                    $obju_Faas2->setudfvalue("u_recommenddate",$objRsFaas2->fields["U_RECOMMENDDATE"]);
                    $obju_Faas2->setudfvalue("u_approvedby",$objRsFaas2->fields["U_APPROVEDBY"]);
                    $obju_Faas2->setudfvalue("u_approveddate",$objRsFaas2->fields["U_APPROVEDDATE"]);
                    $obju_Faas2->setudfvalue("u_releaseddate",$objRsFaas2->fields["U_RELEASEDDATE"]);
                    $obju_Faas2->setudfvalue("u_memoranda",$objRsFaas2->fields["U_MEMORANDA"]);
                    $obju_Faas2->setudfvalue("u_annotation",$objRsFaas2->fields["U_ANNOTATION"]);
                    $obju_Faas2->setudfvalue("u_assvalue",$objRsFaas2->fields["U_ASSVALUE"]);
                    $obju_Faas2->setudfvalue("u_totalareasqm",$objRsFaas2->fields["U_TOTALAREASQM"]);
                    $obju_Faas2->setudfvalue("u_marketvalue",$objRsFaas2->fields["U_MARKETVALUE"]);

                    $obju_Faas2->setudfvalue("u_prevarpno",$objRsFaas2->fields["U_PREVARPNO"]);
                    $obju_Faas2->setudfvalue("u_prevarpno2",$objRsFaas2->fields["U_PREVARPNO2"]);
                    $obju_Faas2->setudfvalue("u_prevtdno",$objRsFaas2->fields["U_PREVTDNO"]);
                    $obju_Faas2->setudfvalue("u_prevpin",$objRsFaas2->fields["U_PREVPIN"]);
                    $obju_Faas2->setudfvalue("u_prevowner",$objRsFaas2->fields["U_PREVOWNER"]);
                    $obju_Faas2->setudfvalue("u_prevvalue",$objRsFaas2->fields["U_PREVVALUE"]);
                    $obju_Faas2->setudfvalue("u_preveffdate",$objRsFaas2->fields["U_PREVEFFDATE"]);
                    $obju_Faas2->setudfvalue("u_prevrecordeddate",$objRsFaas2->fields["U_PREVRECORDEDDATE"]);
                    $obju_Faas2->setudfvalue("u_prevrecordedby",$objRsFaas2->fields["U_PREVRECORDEDBY"]);

                    if ($actionReturn) {
                        $objRsFaas2p->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS2P WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and DOCID = '".$objRsFaas2->fields["DOCID"]."' ");
                        while ($objRsFaas2p->queryfetchrow("NAME")) {
                            $obju_Faas2p->prepareadd();
                            $obju_Faas2p->docid = $obju_Faas2->docid;
                            $obju_Faas2p->lineid = getNextIDByBranch("u_rpfaas2p",$objConnection);
                            $obju_Faas2p->setudfvalue("u_prevarpno",$objRsFaas2p->fields["U_PREVARPNO"]);
                            $obju_Faas2p->setudfvalue("u_prevarpno2",$objRsFaas2p->fields["U_PREVARPNO2"]);
                            $obju_Faas2p->setudfvalue("u_prevtdno",$objRsFaas2p->fields["U_PREVTDNO"]);
                            $obju_Faas2p->setudfvalue("u_prevpin",$objRsFaas2p->fields["U_PREVPIN"]);
                            $obju_Faas2p->setudfvalue("u_prevowner",$objRsFaas2p->fields["U_PREVOWNER"]);
                            $obju_Faas2p->setudfvalue("u_prevvalue",$objRsFaas2p->fields["U_PREVVALUE"]);
                            $obju_Faas2p->setudfvalue("u_preveffdate",$objRsFaas2p->fields["U_PREVEFFDATE"]);
                            $obju_Faas2p->setudfvalue("u_prevrecordeddate",$objRsFaas2p->fields["U_PREVRECORDEDDATE"]);
                            $obju_Faas2p->setudfvalue("u_prevrecordedby",$objRsFaas2p->fields["U_PREVRECORDEDBY"]);
                            $actionReturn = $obju_Faas2p->add();
                            if (!$actionReturn) break;
                        }
                    }
                    
                    if ($actionReturn) {
                        $objRsFaas2d->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS2D WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas2->fields["DOCID"]."' ");
                        while ($objRsFaas2d->queryfetchrow("NAME")) {
                            $obju_Faas2d->prepareadd();
                            $obju_Faas2d->docid = $obju_Faas2->docid;
                            $obju_Faas2d->lineid = getNextIDByBranch("u_rpfaas2d",$objConnection);
                            $obju_Faas2d->setudfvalue("u_selected",$objRsFaas2d->fields["U_SELECTED"]);
                            $obju_Faas2d->setudfvalue("u_prop",$objRsFaas2d->fields["U_PROP"]);
                            $actionReturn = $obju_Faas2d->add();
                            if (!$actionReturn) break;
                        }
                    }
                    
                    if ($actionReturn) {
                        $objRsFaas2c->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS2C WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas2->fields["DOCID"]."' ");
                        while ($objRsFaas2c->queryfetchrow("NAME")) {
                            $obju_Faas2c->prepareadd();
                            $obju_Faas2c->docid = $obju_Faas2->docid;
                            $obju_Faas2c->lineid = getNextIDByBranch("u_rpfaas2c",$objConnection);
                            $obju_Faas2c->setudfvalue("u_actualuse",$objRsFaas2c->fields["U_ACTUALUSE"]);
                            $obju_Faas2c->setudfvalue("u_asslvl",$objRsFaas2c->fields["U_ASSLVL"]);
                            $obju_Faas2c->setudfvalue("u_assvalue",$objRsFaas2c->fields["U_ASSVALUE"]);
                            $obju_Faas2c->setudfvalue("u_marketvalue",$objRsFaas2c->fields["U_MARKETVALUE"]);
                            $obju_Faas2c->setudfvalue("u_taxable",$objRsFaas2c->fields["U_TAXABLE"]);
                            $actionReturn = $obju_Faas2c->add();
                            if (!$actionReturn) break;
                        }
                    }

                    if ($actionReturn) {
                        $objRsFaas2a->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS2A WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and  u_arpno = '".$objRsFaas2->fields["DOCNO"]."' ");
                        while ($objRsFaas2a->queryfetchrow("NAME")) {
                            $obju_Faas2a->prepareadd();
                            $obju_Faas2a->docid = getNextIDByBranch("u_rpfaas2a",$objConnection);
                            $obju_Faas2a->docno = getNextNoByBranch("u_rpfaas2a","",$objConnection);
                            $obju_Faas2a->setudfvalue("u_gryear",$objRsFaas2a->fields["U_GRYEAR"]);
                            $obju_Faas2a->setudfvalue("u_arpno",$obju_Faas2->docno);
                            $obju_Faas2a->setudfvalue("u_class",$objRsFaas2a->fields["U_CLASS"]);
                            $obju_Faas2a->setudfvalue("u_taxable",$objRsFaas2a->fields["U_TAXABLE"]);
                            $obju_Faas2a->setudfvalue("u_bldgno",$objRsFaas2a->fields["U_BLDGNO"]);
                            $obju_Faas2a->setudfvalue("u_kind",$objRsFaas2a->fields["U_KIND"]);
                            $obju_Faas2a->setudfvalue("u_adjperc",$objRsFaas2a->fields["U_ADJPERC"]);
                            $obju_Faas2a->setudfvalue("u_asslvlby",$objRsFaas2a->fields["U_ASSLVLBY"]);
                            $obju_Faas2a->setudfvalue("u_floor",$objRsFaas2a->fields["U_FLOOR"]);
                            $obju_Faas2a->setudfvalue("u_flooradjperc",$objRsFaas2a->fields["U_FLOORADJPERC"]);
                            $obju_Faas2a->setudfvalue("u_sqm",$objRsFaas2a->fields["U_SQM"]);
                            $obju_Faas2a->setudfvalue("u_structuretype",$objRsFaas2a->fields["U_STRUCTURETYPE"]);
                            $obju_Faas2a->setudfvalue("u_subclass",$objRsFaas2a->fields["U_SUBCLASS"]);
                            $obju_Faas2a->setudfvalue("u_basevalue",$objRsFaas2a->fields["U_BASEVALUE"]);
                            $obju_Faas2a->setudfvalue("u_completeperc",$objRsFaas2a->fields["U_COMPLETEPERC"]);
                            $obju_Faas2a->setudfvalue("u_deprevalue",$objRsFaas2a->fields["U_DEPREVALUE"]);
                            $obju_Faas2a->setudfvalue("u_floorbasevalue",$objRsFaas2a->fields["U_FLOORBASEVALUE"]);
                            $obju_Faas2a->setudfvalue("u_floordeprevalue",$objRsFaas2a->fields["U_FLOORDEPREVALUE"]);
                            $obju_Faas2a->setudfvalue("u_unitvalue",$objRsFaas2a->fields["U_UNITVALUE"]);
                            $obju_Faas2a->setudfvalue("u_adjvalue",$objRsFaas2a->fields["U_ADJVALUE"]);
                            $obju_Faas2a->setudfvalue("u_flooradjvalue",$objRsFaas2a->fields["U_FLOORADJVALUE"]);
                            $obju_Faas2a->setudfvalue("u_adjmarketvalue",$objRsFaas2a->fields["U_ADJMARKETVALUE"]);
                            $obju_Faas2a->setudfvalue("u_flooradjmarketvalue",$objRsFaas2a->fields["U_FLOORADJMARKETVALUE"]);
                            $obju_Faas2a->setudfvalue("u_marketvalue",$objRsFaas2a->fields["U_MARKETVALUE"]);
                            $obju_Faas2a->setudfvalue("u_actualuse",$objRsFaas2a->fields["U_ACTUALUSE"]);
                            $obju_Faas2a->setudfvalue("u_asslvl",$objRsFaas2a->fields["U_ASSLVL"]);
                            $obju_Faas2a->setudfvalue("u_assvalue", $objRsFaas2a->fields["U_ASSVALUE"]);
                            $obju_Faas2a->setudfvalue("u_age", $objRsFaas2a->fields["U_AGE"]);
                            $obju_Faas2a->setudfvalue("u_startyear", $objRsFaas2a->fields["U_STARTYEAR"]);
                            $obju_Faas2a->setudfvalue("u_bldgdescriptions", $objRsFaas2a->fields["U_BLDGDESCRIPTIONS"]);
                            $obju_Faas2a->setudfvalue("u_withdecimal", $objRsFaas2a->fields["U_WITHDECIMAL"]);
     
                            if ($actionReturn) {
                                $objRsFaas2b->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS2B WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas2a->fields["DOCID"]."' ");
                                while ($objRsFaas2b->queryfetchrow("NAME")) {
                                    $obju_Faas2b->prepareadd();
                                    $obju_Faas2b->docid = $obju_Faas2a->docid;
                                    $obju_Faas2b->lineid = getNextIDByBranch("u_rpfaas2b",$objConnection);
                                    $obju_Faas2b->setudfvalue("u_itemdesc",$objRsFaas2b->fields["U_ITEMDESC"]);
                                    $obju_Faas2b->setudfvalue("u_sqm",$objRsFaas2b->fields["U_SQM"]);
                                    $obju_Faas2b->setudfvalue("u_startyear",$objRsFaas2b->fields["U_STARTYEAR"]);
                                    $obju_Faas2b->setudfvalue("u_age",$objRsFaas2b->fields["U_AGE"]);
                                    $obju_Faas2b->setudfvalue("u_quantity",$objRsFaas2b->fields["U_QUANTITY"]);
                                    $obju_Faas2b->setudfvalue("u_unitvalue",$objRsFaas2b->fields["U_UNITVALUE"]);
                                    $obju_Faas2b->setudfvalue("u_completeperc",$objRsFaas2b->fields["U_COMPLETEPERC"]);
                                    $obju_Faas2b->setudfvalue("u_basevalue",$objRsFaas2b->fields["U_BASEVALUE"]);
                                    $obju_Faas2b->setudfvalue("u_adjperc",$objRsFaas2b->fields["U_ADJPERC"]);
                                    $obju_Faas2b->setudfvalue("u_deprevalue",$objRsFaas2b->fields["U_DEPREVALUE"]);
                                    $obju_Faas2b->setudfvalue("u_adjvalue",$objRsFaas2b->fields["U_ADJVALUE"]);
                                    $obju_Faas2b->setudfvalue("u_adjmarketvalue",$objRsFaas2b->fields["U_ADJMARKETVALUE"]);
                                    $actionReturn = $obju_Faas2b->add();
                                    if (!$actionReturn) break;
                                }
                            }
                            
                            if ($actionReturn) {
                                $objRsFaas2e->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS2E WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas2a->fields["DOCID"]."' ");
                                while ($objRsFaas2e->queryfetchrow("NAME")) {
                                    $obju_Faas2e->prepareadd();
                                    $obju_Faas2e->docid = $obju_Faas2a->docid;
                                    $obju_Faas2e->lineid = getNextIDByBranch("u_rpfaas2e",$objConnection);
                                    $obju_Faas2e->setudfvalue("u_selected",$objRsFaas2e->fields["U_SELECTED"]);
                                    $obju_Faas2e->setudfvalue("u_prop",$objRsFaas2e->fields["U_PROP"]);
                                    $actionReturn = $obju_Faas2e->add();
                                    if (!$actionReturn) break;
                                }
                            }
                            
                            $actionReturn = $obju_Faas2a->add();
                        }
                    }

                if ($actionReturn) $actionReturn = $obju_Faas2->add();
                if (!$actionReturn) break;

                    
                }
                
                if ($actionReturn) {
                        $objConnection->commit();
                } else {
                    $myfile = fopen("../Addons/GPS/RPTAS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
                    $txt = $_SESSION["errormessage"]."\n";
                    fwrite($myfile, $txt);
                    fclose($myfile);
                    $objConnection->rollback();
                    echo $_SESSION["errormessage"];
                }
                
            } else if ($page->getitemstring("kind")=="M") {

                $obju_Faas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
                $obju_Faas3a = new documentschema_br(null,$objConnection,"u_rpfaas3a");
                $obju_Faas3b = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");
                $obju_Faas3c = new documentlinesschema_br(null,$objConnection,"u_rpfaas3c");
                $obju_Faas3p = new documentlinesschema_br(null,$objConnection,"u_rpfaas3p");

                $objRsFaas3->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS3 WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' $filterExp ");
                while ($objRsFaas3->queryfetchrow("NAME")) {
                    $num_rows++;
                    $obju_Faas3->prepareadd();
                    $obju_Faas3->docid = getNextIDByBranch("u_rpfaas3",$objConnection);
                    $obju_Faas3->docno = getNextNoByBranch("u_rpfaas3","",$objConnection);
                    $obju_Faas3->docseries = -1;
                    $obju_Faas3->docstatus = $objRsFaas3->fields["DOCSTATUS"];
                    $obju_Faas3->setudfvalue("u_revisiondocno",$objRsFaas3->fields["DOCNO"]);
                    $obju_Faas3->setudfvalue("u_cancelled",$objRsFaas3->fields["U_CANCELLED"]);
                    $obju_Faas3->setudfvalue("u_temporarytdn",$objRsFaas3->fields["U_TEMPORARYTDN"]);
                    $obju_Faas3->setudfvalue("u_temporary",$objRsFaas3->fields["U_TEMPORARY"]);
                    $obju_Faas3->setudfvalue("u_pin",$objRsFaas3->fields["U_PIN"]);
                    $obju_Faas3->setudfvalue("u_prefix",$objRsFaas3->fields["U_PREFIX"]);
                    $obju_Faas3->setudfvalue("u_suffix",$objRsFaas3->fields["U_SUFFIX"]);
                    $obju_Faas3->setudfvalue("u_tdno",$objRsFaas3->fields["U_TDNO"]);
                    $obju_Faas3->setudfvalue("u_varpno",$objRsFaas3->fields["U_VARPNO"]);
                    
                    $obju_Faas3->setudfvalue("u_class",$objRsFaas3->fields["U_CLASS"]);
                    $obju_Faas3->setudfvalue("u_actualuse",$objRsFaas3->fields["U_ACTUALUSE"]);
                    
                    $obju_Faas3->setudfvalue("u_revisionyear",$objRsFaas3->fields["U_REVISIONYEAR"]);
                    $obju_Faas3->setudfvalue("u_trxcode",$objRsFaas3->fields["U_TRXCODE"]);
                    $obju_Faas3->setudfvalue("u_ownercompanyname",$objRsFaas3->fields["U_OWNERCOMPANYNAME"]);
                    $obju_Faas3->setudfvalue("u_ownername",$objRsFaas3->fields["U_OWNERNAME"]);
                    $obju_Faas3->setudfvalue("u_ownerlastname",$objRsFaas3->fields["U_OWNERLASTNAME"]);
                    $obju_Faas3->setudfvalue("u_ownerfirstname",$objRsFaas3->fields["U_OWNERFIRSTNAME"]);
                    $obju_Faas3->setudfvalue("u_ownerfirstname",$objRsFaas3->fields["U_OWNERFIRSTNAME"]);
                    $obju_Faas3->setudfvalue("u_email",$objRsFaas3->fields["U_EMAIL"]);
                    $obju_Faas3->setudfvalue("u_ownertin",$objRsFaas3->fields["U_OWNERTIN"]);
                    $obju_Faas3->setudfvalue("u_owneraddress",$objRsFaas3->fields["U_OWNERADDRESS"]);
                    
                    $obju_Faas3->setudfvalue("u_adminname",$objRsFaas3->fields["U_ADMINNAME"]);
                    $obju_Faas3->setudfvalue("u_adminfirstname",$objRsFaas3->fields["U_ADMINFIRSTNAME"]);
                    $obju_Faas3->setudfvalue("u_adminmiddlename",$objRsFaas3->fields["U_ADMINMIDDLENAME"]);
                    $obju_Faas3->setudfvalue("u_adminlastname",$objRsFaas3->fields["U_ADMINLASTNAME"]);
                    $obju_Faas3->setudfvalue("u_adminaddress",$objRsFaas3->fields["U_ADMINADDRESS"]);
                    $obju_Faas3->setudfvalue("u_admintelno",$objRsFaas3->fields["U_ADMINTELNO"]);
                    
                    $obju_Faas3->setudfvalue("u_street",$objRsFaas3->fields["U_STREET"]);
                    $obju_Faas3->setudfvalue("u_subdivision",$objRsFaas3->fields["U_SUBDIVISION"]);
                    $obju_Faas3->setudfvalue("u_location",$objRsFaas3->fields["U_LOCATION"]);
                    $obju_Faas3->setudfvalue("u_oldbarangay",$objRsFaas3->fields["U_OLDBARANGAY"]);
                    $obju_Faas3->setudfvalue("u_barangay",$objRsFaas3->fields["U_BARANGAY"]);
                    $obju_Faas3->setudfvalue("u_municipality",$objRsFaas3->fields["U_MUNICIPALITY"]);
                    $obju_Faas3->setudfvalue("u_city",$objRsFaas3->fields["U_CITY"]);
                    $obju_Faas3->setudfvalue("u_province",$objRsFaas3->fields["U_PROVINCE"]);
                    
                    $obju_Faas3->setudfvalue("u_landowner",$objRsFaas3->fields["U_LANDOWNER"]);
                    $obju_Faas3->setudfvalue("u_bldgowner",$objRsFaas3->fields["U_BLDGOWNER"]);
                    $obju_Faas3->setudfvalue("u_landpin",$objRsFaas3->fields["U_LANDPIN"]);
                    $obju_Faas3->setudfvalue("u_bldgpin",$objRsFaas3->fields["U_BLDGPIN"]);
                    $obju_Faas3->setudfvalue("u_landtdno",$objRsFaas3->fields["U_LANDTDNO"]);
                    $obju_Faas3->setudfvalue("u_bldgtdno",$objRsFaas3->fields["U_BLDGTDNO"]);

                    $obju_Faas3->setudfvalue("u_taxable",$objRsFaas3->fields["U_TAXABLE"]);
                    $obju_Faas3->setudfvalue("u_effdate",$objRsFaas3->fields["U_EFFDATE"]);
                    $obju_Faas3->setudfvalue("u_effyear",$objRsFaas3->fields["U_EFFYEAR"]);
                    $obju_Faas3->setudfvalue("u_effqtr",$objRsFaas3->fields["U_EFFQTR"]);
                    
                    $obju_Faas3->setudfvalue("u_expyear",$objRsFaas3->fields["U_EXPYEAR"]);
                    $obju_Faas3->setudfvalue("u_expqtr",$objRsFaas3->fields["U_EXPQTR"]);
                    $obju_Faas3->setudfvalue("u_expdate",$objRsFaas3->fields["U_EXPDATE"]);   
                    
                    $obju_Faas3->setudfvalue("u_bilyear",$objRsFaas3->fields["U_BILYEAR"]);
                    $obju_Faas3->setudfvalue("u_bilqtr",$objRsFaas3->fields["U_BILQTR"]);
                    $obju_Faas3->setudfvalue("u_lastpaymode",$objRsFaas3->fields["U_LASTPAYMODE"]);

                    $obju_Faas3->setudfvalue("u_recordedby",$objRsFaas3->fields["U_RECORDEDBY"]);
                    $obju_Faas3->setudfvalue("u_recordeddate",$objRsFaas3->fields["U_RECORDEDDATE"]);
                    $obju_Faas3->setudfvalue("u_assessedby",$objRsFaas3->fields["U_ASSESSEDBY"]);
                    $obju_Faas3->setudfvalue("u_assesseddate",$objRsFaas3->fields["U_ASSESSEDDATE"]);
                    $obju_Faas3->setudfvalue("u_recommendby",$objRsFaas3->fields["U_RECOMMENDBY"]);
                    $obju_Faas3->setudfvalue("u_recommenddate",$objRsFaas3->fields["U_RECOMMENDDATE"]);
                    $obju_Faas3->setudfvalue("u_approvedby",$objRsFaas3->fields["U_APPROVEDBY"]);
                    $obju_Faas3->setudfvalue("u_approveddate",$objRsFaas3->fields["U_APPROVEDDATE"]);
                    $obju_Faas3->setudfvalue("u_releaseddate",$objRsFaas3->fields["U_RELEASEDDATE"]);
                    $obju_Faas3->setudfvalue("u_memoranda",$objRsFaas3->fields["U_MEMORANDA"]);
                    $obju_Faas3->setudfvalue("u_annotation",$objRsFaas3->fields["U_ANNOTATION"]);
                    $obju_Faas3->setudfvalue("u_assvalue",$objRsFaas3->fields["U_ASSVALUE"]);
                    $obju_Faas3->setudfvalue("u_marketvalue",$objRsFaas3->fields["U_MARKETVALUE"]);

                    $obju_Faas3->setudfvalue("u_prevarpno",$objRsFaas3->fields["U_PREVARPNO"]);
                    $obju_Faas3->setudfvalue("u_prevarpno2",$objRsFaas3->fields["U_PREVARPNO2"]);
                    $obju_Faas3->setudfvalue("u_prevtdno",$objRsFaas3->fields["U_PREVTDNO"]);
                    $obju_Faas3->setudfvalue("u_prevpin",$objRsFaas3->fields["U_PREVPIN"]);
                    $obju_Faas3->setudfvalue("u_prevowner",$objRsFaas3->fields["U_PREVOWNER"]);
                    $obju_Faas3->setudfvalue("u_prevvalue",$objRsFaas3->fields["U_PREVVALUE"]);
                    $obju_Faas3->setudfvalue("u_preveffdate",$objRsFaas3->fields["U_PREVEFFDATE"]);
                    $obju_Faas3->setudfvalue("u_prevrecordeddate",$objRsFaas3->fields["U_PREVRECORDEDDATE"]);
                    $obju_Faas3->setudfvalue("u_prevrecordedby",$objRsFaas3->fields["U_PREVRECORDEDBY"]);

                    if ($actionReturn) {
                        $objRsFaas3p->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS3P WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and DOCID = '".$objRsFaas3->fields["DOCID"]."' ");
                        while ($objRsFaas3p->queryfetchrow("NAME")) {
                            $obju_Faas3p->prepareadd();
                            $obju_Faas3p->docid = $obju_Faas3->docid;
                            $obju_Faas3p->lineid = getNextIDByBranch("u_rpfaas3p",$objConnection);
                            $obju_Faas3p->setudfvalue("u_prevarpno",$objRsFaas3p->fields["U_PREVARPNO"]);
                            $obju_Faas3p->setudfvalue("u_prevarpno2",$objRsFaas3p->fields["U_PREVARPNO2"]);
                            $obju_Faas3p->setudfvalue("u_prevtdno",$objRsFaas3p->fields["U_PREVTDNO"]);
                            $obju_Faas3p->setudfvalue("u_prevpin",$objRsFaas3p->fields["U_PREVPIN"]);
                            $obju_Faas3p->setudfvalue("u_prevowner",$objRsFaas3p->fields["U_PREVOWNER"]);
                            $obju_Faas3p->setudfvalue("u_prevvalue",$objRsFaas3p->fields["U_PREVVALUE"]);
                            $obju_Faas3p->setudfvalue("u_preveffdate",$objRsFaas3p->fields["U_PREVEFFDATE"]);
                            $obju_Faas3p->setudfvalue("u_prevrecordeddate",$objRsFaas3p->fields["U_PREVRECORDEDDATE"]);
                            $obju_Faas3p->setudfvalue("u_prevrecordedby",$objRsFaas3p->fields["U_PREVRECORDEDBY"]);
                            $actionReturn = $obju_Faas3p->add();
                            if (!$actionReturn) break;
                        }
                    }
                    
                    if ($actionReturn) {
                        $objRsFaas3b->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS3B WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas3->fields["DOCID"]."' ");
                        while ($objRsFaas3b->queryfetchrow("NAME")) {
                            $obju_Faas3b->prepareadd();
                            $obju_Faas3b->docid = $obju_Faas3->docid;
                            $obju_Faas3b->lineid = getNextIDByBranch("u_rpfaas3b",$objConnection);
                            $obju_Faas3b->setudfvalue("u_machine",$objRsFaas3b->fields["U_MACHINE"]);
                            $obju_Faas3b->setudfvalue("u_actualuse",$objRsFaas3b->fields["U_ACTUALUSE"]);
                            $obju_Faas3b->setudfvalue("u_asslvl",$objRsFaas3b->fields["U_ASSLVL"]);
                            $obju_Faas3b->setudfvalue("u_assvalue",$objRsFaas3b->fields["U_ASSVALUE"]);
                            $obju_Faas3b->setudfvalue("u_marketvalue",$objRsFaas3b->fields["U_MARKETVALUE"]);
                            $obju_Faas3b->setudfvalue("u_taxable",$objRsFaas3b->fields["U_TAXABLE"]);

                            $actionReturn = $obju_Faas3b->add();
                            if (!$actionReturn) break;
                        }
                    }

                    if ($actionReturn) {
                        $objRsFaas3a->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS3A WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and u_arpno = '".$objRsFaas3->fields["DOCNO"]."' ");
                        while ($objRsFaas3a->queryfetchrow("NAME")) {
                            $obju_Faas3a->prepareadd();
                            $obju_Faas3a->docid = getNextIDByBranch("u_rpfaas3a",$objConnection);
                            $obju_Faas3a->docno = getNextNoByBranch("u_rpfaas3a","",$objConnection);
                            $obju_Faas3a->setudfvalue("u_gryear",$objRsFaas3a->fields["U_GRYEAR"]);
                            $obju_Faas3a->setudfvalue("u_arpno",$obju_Faas3->docno);
                            $obju_Faas3a->setudfvalue("u_taxable",$objRsFaas3a->fields["U_TAXABLE"]);
                            $obju_Faas3a->setudfvalue("u_actualuse",$objRsFaas3a->fields["U_ACTUALUSE"]);
                            
                            $obju_Faas3a->setudfvalue("u_acqdate",$objRsFaas3a->fields["U_ACQDATE"]);
                            $obju_Faas3a->setudfvalue("u_brand",$objRsFaas3a->fields["U_BRAND"]);
                            $obju_Faas3a->setudfvalue("u_capacity",$objRsFaas3a->fields["U_CAPACITY"]);
                            $obju_Faas3a->setudfvalue("u_condition",$objRsFaas3a->fields["U_CONDITION"]);
                            $obju_Faas3a->setudfvalue("u_machine",$objRsFaas3a->fields["U_MACHINE"]);
                            $obju_Faas3a->setudfvalue("u_model", $objRsFaas3a->fields["U_MODEL"]);
                            $obju_Faas3a->setudfvalue("u_rcn", $objRsFaas3a->fields["U_RCN"]);
                            $obju_Faas3a->setudfvalue("u_cnvfactor", $objRsFaas3a->fields["U_CNVFACTOR"]);
                            $obju_Faas3a->setudfvalue("u_deprevalue", $objRsFaas3a->fields["U_DEPREVALUE"]);
                            $obju_Faas3a->setudfvalue("u_orgcost", $objRsFaas3a->fields["U_ORGCOST"]);
                            $obju_Faas3a->setudfvalue("u_quantity", $objRsFaas3a->fields["U_QUANTITY"]);
                            $obju_Faas3a->setudfvalue("u_remvalue", $objRsFaas3a->fields["U_REMVALUE"]);
                            $obju_Faas3a->setudfvalue("u_depreperc", $objRsFaas3a->fields["U_DEPREPERC"]);
                            $obju_Faas3a->setudfvalue("u_depreratefr", $objRsFaas3a->fields["U_DEPRERATEFR"]);
                            $obju_Faas3a->setudfvalue("u_deprerateto", $objRsFaas3a->fields["U_DEPRERATETO"]);
                            $obju_Faas3a->setudfvalue("u_estlife", $objRsFaas3a->fields["U_ESTLIFE"]);
                            $obju_Faas3a->setudfvalue("u_inityear", $objRsFaas3a->fields["U_INITYEAR"]);
                            $obju_Faas3a->setudfvalue("u_insyear", $objRsFaas3a->fields["U_INSYEAR"]);
                            $obju_Faas3a->setudfvalue("u_remlife", $objRsFaas3a->fields["U_REMLIFE"]);
                            $obju_Faas3a->setudfvalue("u_useyear", $objRsFaas3a->fields["U_USEYEAR"]);
                            $obju_Faas3a->setudfvalue("u_withdecimal", $objRsFaas3a->fields["U_WITHDECIMAL"]);
                            
                            if ($actionReturn) {
                                $objRsFaas3c->queryopen("SELECT * FROM BACOORREVISION.U_RPFAAS3C WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' and docid = '".$objRsFaas3a->fields["DOCID"]."' ");
                                while ($objRsFaas3c->queryfetchrow("NAME")) {
                                    $obju_Faas3c->prepareadd();
                                    $obju_Faas3c->docid = $obju_Faas3a->docid;
                                    $obju_Faas3c->lineid = getNextIDByBranch("u_rpfaas3c",$objConnection);
                                    $obju_Faas3c->setudfvalue("u_year",$objRsFaas3c->fields["U_YEAR"]);
                                    $obju_Faas3c->setudfvalue("u_marketvalue",$objRsFaas3c->fields["U_MARKETVALUE"]);
                                    $obju_Faas3c->setudfvalue("u_deprevalue",$objRsFaas3c->fields["U_DEPREVALUE"]);
                                    $obju_Faas3c->setudfvalue("u_adjmarketvalue",$objRsFaas3c->fields["U_ADJMARKETVALUE"]);
                                    $obju_Faas3c->setudfvalue("u_asslvl",$objRsFaas3c->fields["U_ASSLVL"]);
                                    $obju_Faas3c->setudfvalue("u_assvalue",$objRsFaas3c->fields["U_ASSVALUE"]);
                                    
                                    $actionReturn = $obju_Faas3c->add();
                                    if (!$actionReturn) break;
                                }
                            }
                            $actionReturn = $obju_Faas3a->add();
                        }
                    }

                if ($actionReturn) $actionReturn = $obju_Faas3->add();
                if (!$actionReturn) break;

                    
                }
                
                if ($actionReturn) {
                        $objConnection->commit();
                } else {
                    $myfile = fopen("../Addons/GPS/RPTAS Add-On/UserPrograms/Error_log/error.txt", "a") or die ("Unable to open file!");
                    $txt = $_SESSION["errormessage"]."\n";
                    fwrite($myfile, $txt);
                    fclose($myfile);
                    $objConnection->rollback();
                    echo $_SESSION["errormessage"];
                }
                
            }
        }
    
        return $actionReturn;
    }
    
    function onAfterDefault() { 
            global $objConnection;
            global $page;
            //var_dump($schema["docstatus"]);
    }   
    
    $objrs = new recordset(null,$objConnection);
   
    if ($page->getitemstring("revisionyear")!="" ) {
        if ($page->getitemstring("kind")=="") {
                $objrs->queryopenext("SELECT DOCSTATUS,KIND,U_BARANGAY,COUNT(*) AS CNT FROM( SELECT DOCSTATUS,'Land' as KIND,U_BARANGAY FROM BACOORREVISION.U_RPFAAS1  WHERE COMPANY = '$company' AND BRANCH = '$branch' $filterExp  UNION ALL select DOCSTATUS,'Building' as KIND,U_BARANGAY FROM BACOORREVISION.U_RPFAAS2  WHERE COMPANY = '$company' AND BRANCH = '$branch' $filterExp UNION ALL select  DOCSTATUS,'Machinery' as KIND,U_BARANGAY FROM BACOORREVISION.U_RPFAAS3 WHERE COMPANY = '$company' AND BRANCH = '$branch' $filterExp ) AS A GROUP BY KIND,U_BARANGAY,DOCSTATUS", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        } elseif ($page->getitemstring("kind")=="L") {
                $objrs->queryopenext("SELECT DOCSTATUS,'Land' as KIND,U_BARANGAY,COUNT(*) AS CNT FROM BACOORREVISION.U_RPFAAS1  WHERE COMPANY = '$company' AND BRANCH = '$branch' $filterExp GROUP BY KIND,U_BARANGAY,DOCSTATUS", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        } elseif ($page->getitemstring("kind")=="B") {
                $objrs->queryopenext("SELECT DOCSTATUS,'Building' as KIND,U_BARANGAY,COUNT(*) AS CNT FROM BACOORREVISION.U_RPFAAS2  WHERE COMPANY = '$company' AND BRANCH = '$branch' $filterExp GROUP BY KIND,U_BARANGAY,DOCSTATUS", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        } elseif ($page->getitemstring("kind")=="M") {
                $objrs->queryopenext("SELECT DOCSTATUS,'Machinery' as KIND,U_BARANGAY,COUNT(*) AS CNT FROM BACOORREVISION.U_RPFAAS3  WHERE COMPANY = '$company' AND BRANCH = '$branch' $filterExp GROUP BY KIND,U_BARANGAY,DOCSTATUS", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
        }
    }
        $page->paging_recordcount($objrs->recordcount());
        while ($objrs->queryfetchrow("NAME")) {
            $objGrid->addrow();
            $objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
            $objGrid->setitem(null,"u_kind",$objrs->fields["KIND"]);
            $objGrid->setitem(null,"u_barangay",$objrs->fields["U_BARANGAY"]);
            $objGrid->setitem(null,"u_quantity",formatNumeric($objrs->fields["CNT"]));

//            if (!$page->paging_fetch()) break;
        }	
	
    resetTabindex();
//    setTabindex($schema["u_varpno"]);		
//    $page->resize->addgrid("T1",10,260,false);
    $page->toolbar->setaction("print",false);
    
    require("./inc/formactions.php");
 
?>


<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>

<script language="JavaScript">
   
	function onFormSubmit(action) {
            if(action=="uploadgeneralrevisions"){
                if (isInputEmpty("revisionyear")) return false;
                if (isInputEmpty("kind")) return false;
                if (isInputEmpty("barangay")) return false;
                if(confirm("Upload General Revisions Real Property Records?")) return true;
                else  return false;
            }
	}  
        function onFormSubmitted(action) {
            showAjaxProcess();
            return true;
	}
        
        function onElementChange(element,column,table,row) {
		switch (column) {
                    case "kind": 
                    case "barangay": 
                    case "revisionyear": 
                        formPageReset(); 
                        clearTable("T1");
                    break;	
		}
		return true;
	}
        function onReadOnlyAccess(elementId) {
		switch (elementId) {
                    case "df_page_vr_limit":
                    case "df_kind":
                    case "df_barangay":
                    case "df_revisionyear":
                    return false;
		}
		return true;
	}
        function onPageSaveValues(p_action) {
		var inputs = new Array();
                    inputs.push("kind");
                    inputs.push("barangay");
                    inputs.push("revisionyear");
		return inputs;
	}
        function formSearchNow() {
		acceptText();
                if (isInputEmpty("revisionyear")) return false;
		formSearch('','<?php echo $page->paging->formid; ?>');
	}
       
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("getprevarpno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Upload General Revisions</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>"> </td>
	</tr>
</table></td>
</tr>
<tr class="fillerRow10px"><td></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
<td>
      <div style="border:0px solid gray" >
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            
            <tr>    
                <td width="168"><label <?php genCaptionHtml($schema["kind"],"") ?>>Property Type</label></td>
		<td align=left><select <?php genSelectHtml($schema["kind"],array("loadenumkindofproperty","",":")) ?> ></select></td>
		<td >&nbsp;</td>
                <td >&nbsp;</td>
            </tr>
            <tr>    
                <td><label <?php genCaptionHtml($schema["barangay"],"") ?>>Barangay</label></td>
                <td align=left><select <?php genSelectHtml($schema["barangay"],array("loadudflinktable","u_barangays:name:name",":")) ?> ></select></td>
                <td >&nbsp;</td>
                <td >&nbsp;</td>
            </tr>
            <tr>    
                <td><label <?php genCaptionHtml($schema["revisionyear"],"") ?>>Revision Year</label></td>
                <td align=left><select <?php genSelectHtml($schema["revisionyear"],array("loadudflinktable","u_gryears:code:name",":")) ?> ></select></td>
                <td >&nbsp;</td>
                <td >&nbsp;</td>
            </tr>
            <tr class="fillerRow10px"><td></td></tr>
            <tr>
                <td >&nbsp;</td>
                <td  ><a class="button" href="" onClick="formSearchNow();return false;">Search</a> &nbsp;&nbsp;&nbsp;<a class="button" href="" onClick="formSubmit('uploadgeneralrevisions');return false;">Upload Data</a></td>
                <td ></td>
                <td align=left>&nbsp;</td>
            </tr>
            <tr> 
        </table>
    </div>
</td>
<td>
</td>
</tr></table>
</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>

<?php // $page->writeRecordLimit(); ?>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	
<?php require("./bofrms/ajaxprocess.php"); ?>	
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString() . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>