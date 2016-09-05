// JavaScript Document
function enterIn(evt){
  	var evt=evt?evt:(window.event?window.event:null);//兼容IE和FF
 	 if (evt.keyCode==13){
		 login();
	}
}