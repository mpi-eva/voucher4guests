function popup (url) {
		fenster = window.open(url, "Popupfenster", "width=350px,height=460,resizable=yes");
		fenster.focus();
		return false;
}

function AutomatischesWeiterspringen(AktuellesFeld, NaechstesFeld){
		if(AktuellesFeld.getAttribute("maxlength") == AktuellesFeld.value.length){
        		document.getElementsByName(NaechstesFeld)[0].focus();
    		}
}

function chkFormular () {			
		var warning1 = "Please enter the voucher completely!";
		if (document.Formular.IX1.value.length<5) {
				alert(warning1);
				document.Formular.IX1.focus();
				return false;
		}
		if (document.Formular.IX2.value.length<5) {
				alert(warning1);
				document.Formular.IX2.focus();
				return false;
		}	
		if (document.Formular.IX3.value.length<5) {
				alert(warning1);
				document.Formular.IX3.focus();
				return false;
		}
		if (document.Formular.IX4.value.length<5) {
				alert(warning1);
				document.Formular.IX4.focus();
				return false;
				}
		if (!document.Formular.policy.checked){
				alert("Please accept the User Agreement!");
				document.Formular.policy.focus();
				return false;
		}
}