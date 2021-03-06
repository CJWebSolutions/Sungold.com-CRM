function changeStatus() {
	var f = document.orderForm;
	
	var itemcnt = parseInt(f.itemcnt.value);
	var chk = false;

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["orderno"+i].checked == true) {
			chk = true;
		}

		if((f.elements["old_orderstat"+i].value == "2" || f.elements["old_orderstat"+i].value == "3" || f.elements["old_orderstat"+i].value == "4") && f.elements["orderstat"+i].value == "1") {
			alert("CAN NOT CHANGE [CURRENT ORDER STATUS] TO [WAITING]");
			return;
		}

		if(f.elements["old_orderstat"+i].value == "5" && f.elements["orderstat"+i].value != "5") {
			alert("CAN NOT CHANGE [CANCELED] STATUS TO OTHER STATUS ");
			return;
		}
	}

	if(chk) {
		f.act_flag.value="update";
		f.method="POST";
		f.action="order_changestatus.php";
		f.submit();
	} else {
		alert("Please, Choose Order No. and Tick to Change!");
	}			
}

function checkStockForm() {
	frm = document.stockForm;
	if(frm.productid.value =="") {
		alert("Please, Select Product!");
		frm.productid.focus();
		return;
	}
	if(frm.qty.value == "") {
		alert("Please, Insert Quantity!");
		frm.qty.focus();
		return;
	}
	/*
	if(!validDigit(frm.qty)) {
		alert("Please, Insert only Digit on Quantity!");
		frm.qty.value="";
		frm.qty.focus();
		return;
	}
	*/
	frm.submit();

}


function cancelOrder(orderno) {
	window.open("order_cancel.php?orderno="+orderno, "cancelorder", "width=550, height=400, left=500, top=100, menubar=no, scrollbars=no, status=no, toolbars=no, location=no");
}

function checkUpdateAccountForm(thisform) {
	with (thisform) {		
		if (validate_radio(gender,"Please, Select your gender")==false) {return false}	
		if (validate_required(f_name,"First Name must be filled out!")==false) {f_name.focus();return false}
		if (validate_required(l_name,"First Name must be filled out!")==false) {f_name.focus();return false}
		if (validate_email(email, "Incorrect Email Type!")==false) {email.focus();return false}
		if (validate_required(addr, "Address must be filled out!")==false) {addr.focus();return false}
		if (validate_required(city, "City must be filled out!")==false) {city.focus();return false}
		if (validate_required(state, "State must be selected!")==false) {state.focus();return false}
		if (validate_required(postcode, "Postcode must be filled out!")==false) {postcode.focus();return false}
		if (validate_required(tel,"Telephone must be filled out!")==false) {tel.focus();return false}
		if(company.value != "" && abn.value == "") {
			alert("Please, Insert A.B.N.");
			abn.focus();
			return false;
		}
		if (validate_checked(terms,"Please Read & Tick terms and conditions!")==false) {terms.focus();return false}	
	}
	if(confirm("Press OK to confirm?")) {
		return true;
	} else {
		return false;
	}
}

function checkFreightForm(obj) {
	var f = obj;
	var chk = false;
	var itemcnt = parseInt(f.itemcnt.value);

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["name"+i].value != "" && f.elements["freight"+i].value != "" && f.elements["limit_amount"+i].value != "" && f.elements["description"+i].value != "") {
			chk = true;
		}	
	}

	if(chk) {
		f.action="freight_regist_ok.php";
		f.submit();
	} else {
		alert("Please, Insert Blank Space!");
		return false;
	}
}

function disCount(orderno) {
	window.open("order_discount.php?orderno="+orderno, "abc", "width=300, height=150, left=500, top=100, menubar=no, scrollbars=no, status=no, toolbars=no, location=no");
}

function orderDelete() {	
	var f = document.orderForm;
	
	var itemcnt = parseInt(f.itemcnt.value);
	var chk = false;

	for(var i=0; i<itemcnt;i++) {
		if(f.elements["orderno"+i].checked == true) {
			chk = true;
		}
	}
	if(chk) {
		if(confirm("Are you sure?")) {
			f.act_flag.value="delete";
			f.action="order_changestatus.php";
			f.submit();
		}
	} else {
		alert("Please, Tick the order no to delete!");
	}
}

function validDigit(obj) {
	//alert(obj.value);
	var str=obj.value;
	var reg=/[0123456789]\w/;
	var result=reg.test(str);     //매치되는 것이 있음(true)	
	alert(result);
	return result;
}

function searchNow() {
	var f = document.searchOrderForm;
	var sday = f.sday.value;
	var smonth = f.smonth.value;
	var syear = f.syear.value;

	var eday = f.eday.value;
	var emonth = f.emonth.value;
	var eyear = f.eyear.value;

	var start_date = syear + "-" + smonth + "-" + sday;
	var end_date = eyear + "-" + emonth + "-" + eday;

	//alert(start_date);
	//alert(end_date);
	//return;

	if(start_date > end_date) {
		alert("Can't search!");
		return;
	}

	f.method="post";
	f.action="order_list.php";
	f.submit();
}

function issueTaxinvoice(orderno) {
	window.open("issue_taxinvoice.php?orderno="+orderno, "taxinvoice", "");
}
                           

function validate_required(field,alerttxt) {
	with (field) {
		if (value==null||value=="") {alert(alerttxt);return false}
		else {return true}
	}
}

function validate_checked(field,alerttxt) {
	with (field) {
		if (checked == false) {alert(alerttxt);return false}
		else {return true}
	}
}

function validate_radio(field,alerttxt) {
	var st = 0;
	for(var i=0; i < field.length; i++) {
		if(field[i].checked == true)  
			st = 1;
	}
	if (st == 0) {alert(alerttxt);return false}
	else {return true}
}

function validate_email(field,alerttxt) {
	with (field) {
		apos=value.indexOf("@");
		dotpos=value.lastIndexOf(".");
		if (apos<1||dotpos-apos<2) {alert(alerttxt);return false}
		else {return true}
	}
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  