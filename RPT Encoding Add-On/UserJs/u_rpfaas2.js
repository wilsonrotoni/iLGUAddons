function u_forRecommendGPSRPTAS() {
    if (isInputEmpty("u_trxcode",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_pin",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownertin",null,null,"tab1",0)) return false;
	if (isInputEmpty("u_ownername",null,null,"tab1",0)) return false;
	if (isInputNegative("u_effqtr",null,null,"tab1",1)) return false;
	if (isInputNegative("u_effyear",null,null,"tab1",1)) return false;

	if (isInputEmpty("u_assessedby",null,null,"tab1",4)) return false;
	if (isInputEmpty("u_assesseddate",null,null,"tab1",4)) return false;
	//if (getInput("u_assessedby")=="") setInput("u_assessedby",getGlobal("username"));
        if (isInputEmpty("u_bookno",null,null,"tab1",4)) return false;
        setInput("u_recordedby",getGlobal("username"));
        setInput("u_recordeddate",getPrivate("curdate"));
	setInput("docstatus","Assessed");
	formSubmit('sc');
   
}