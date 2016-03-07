	    var req = null;
	    getXMLHttp();
	    var req_current = false;
	
	    function getXMLHttp() {
	      try{
	        req = new XMLHttpRequest();
	      }
	      catch (e){
	       try{
	          req = new ActiveXObject("Msxml2.XMLHTTP");
	        }
	        catch (e){
	          try{
	            req = new ActiveXObject("Microsoft.XMLHTTP");
	          }
	          catch (failed){
	            req = null;
	          }
	        }
	      }
	    }
function Deletethis(eintrag, server) {
         var ed = eintrag.id;
         if(req_current) req.abort();
         req_current = true;
 //      alert("test"+ed);
         Check = confirm ("Wollen sie wirklich den Eitrag mit VID="+ed+" deaktivieren?");
         if (Check==true){

           document.getElementById(ed).src = 'images/delete1.png';

           req.open("GET", "https://"+server+"/admin/includes/voucher_del.php?vid="+ed, true);


           req.onreadystatechange = function() {
             if(req.readyState == 4) {
               if(req.status == 200) {
                 if(req.responseText == 1) {
                 }else{
                   if(req.responseText == 0) {
                     alert("Dieser Eintrag wurde bereits deaktiviert!");
                   }else{ 
                     alert("Fehler!");
                   }
                 }
               }
                     req_current = false;
             }else {
               return false;
             }
           }
           req.send(null);                                        
           setTimeout("location.reload()", 1000);                        
         }                        
}

