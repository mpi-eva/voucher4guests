       function chkFormular () {
                regex=/^[0-9]/;
               if (!(regex.test(document.Formular.number.value))){
                       alert("Please enter a number!");
                       document.Formular.number.focus();
                       return false;
               }
               if (!(regex.test(document.Formular.validity.value))){
                       alert("Please enter a number!");
                       document.Formular.validity.focus();
                       return false;
               }

       }

       function chkRadio () {
               var button = document.getElementsByName("select_validity");
               if (button[0].checked){
                       document.getElementById('fromto').style.display='none';
                       document.getElementById('days').style.display='block';
               }
               if (button[1].checked){
                       document.getElementById('fromto').style.display='block';
                       document.getElementById('days').style.display='none';
               }
	

       }  
