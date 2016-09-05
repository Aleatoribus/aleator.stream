function displayOptions(cb){
	var checkedStatus = cb.checked;
	var divContent = document.getElementById('encryptionOptions');
	if(checkedStatus == true){
		divContent.style.display = 'block';
	}
	else{
		divContent.style.display = 'none';
	}
}
