// page events
//page.events.add.load('onPageLoadLGUPurchasing');
//page.events.add.resize('onPageResizeLGUPurchasing');
page.events.add.submit('onPageSubmitLGUPurchasingBayambang');
//page.events.add.cfl('onCFLLGUPurchasing');
//page.events.add.cflgetparams('onCFLGetParamsLGUPurchasing');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadLGUPurchasing');

// element events
//page.elements.events.add.focus('onElementFocusLGUPurchasing');
//page.elements.events.add.keydown('onElementKeyDownLGUPurchasing');
//page.elements.events.add.validate('onElementValidateLGUPurchasing');
//page.elements.events.add.validateparams('onElementGetValidateParamsLGUPurchasing');
//page.elements.events.add.changing('onElementChangingLGUPurchasing');
//page.elements.events.add.change('onElementChangeLGUPurchasing');
//page.elements.events.add.click('onElementClickLGUPurchasing');
//page.elements.events.add.cfl('onElementCFLLGUPurchasing');
//.page.elements.events.add.cflgetparams('onElementCFLGetParamsLGUPurchasing');

// table events
//page.tables.events.add.reset('onTableResetRowLGUPurchasing');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowLGUPurchasing');
//page.tables.events.add.afterInsert('onTableAfterInsertRowLGUPurchasing');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowLGUPurchasing');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowLGUPurchasing');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowLGUPurchasing');
//page.tables.events.add.delete('onTableDeleteRowLGUPurchasing');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowLGUPurchasing');
//page.tables.events.add.select('onTableSelectRowLGUPurchasing');

function onPageLoadLGUPurchasing() {

}

function onPageResizeLGUPurchasing(width, height) {
}

function onPageSubmitLGUPurchasingBayambang(action) {
    if (action == "a" || action == "sc") {
        if (getInput("docstatus") != "D") {
            if (isInputEmpty("u_drno")) return false;
            if (isInputEmpty("u_invoiceno")) return false;
        }

    }
    return true;
}

function onCFLLGUPurchasing(Id) {
    return true;
}

function onCFLGetParamsLGUPurchasing(Id, params) {
    return params;
}

function onTaskBarLoadLGUPurchasing() {
}

function onElementFocusLGUPurchasing(element, column, table, row) {
    return true;
}

function onElementKeyDownLGUPurchasing(element, event, column, table, row) {
}

function onElementValidateLGUPurchasing(element, column, table, row) {
    switch (table) {
    }
    return true;
}

function onElementClickLGUPurchasing(element, column, table, row) {
    switch (table) {

    }
    return true;
}

function onElementCFLLGUPurchasing(element) {
    return true;
}

function onElementCFLGetParamsLGUPurchasing(Id, params) {
    switch (Id) {

    }

    return params;
}

function onTableResetRowLGUPurchasing(table) {
}

function onTableBeforeInsertRowLGUPurchasing(table, row) {
    switch (table) {
    }
    return true;
}

function onTableAfterInsertRowLGUPurchasing(table, row) {
    switch (table) {

    }
}

function onTableBeforeUpdateRowLGUPurchasing(table, row) {
    switch (table) {
    }
    return true;
}

function onTableAfterUpdateRowLGUPurchasing(table, row) {
    switch (table) {

    }
}

function onTableBeforeDeleteRowLGUPurchasing(table, row) {

}

function onTableDeleteRowLGUPurchasing(table, row) {
    switch (table) {

    }
    return true;
}

function onTableBeforeSelectRowLGUPurchasing(table, row) {
    return true;
}

function onTableSelectRowLGUPurchasing(table, row) {
}

//function u_CheckCashAvailGPSRPTAS() {
//    if (confirm("Document will be tag as Cash Available. Continue?")) {
//        setInput("u_cashavailable", 1);
//        formSubmit();
//    }
//}
//function u_UnCheckCashAvailGPSRPTAS() {
//     if (confirm("Document will be tag as NO Cash Available. Continue?")) {
//        setInput("u_cashavailable", 0);
//        formSubmit();
//    }
//}
