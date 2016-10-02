function displayUploadOptions(cb){
	var checkedStatus = cb.checked;
	var divContent = document.getElementById('uploadEncryptionOptions');
	if(checkedStatus == true){
		divContent.style.display = 'block';
	}
	else{
		divContent.style.display = 'none';
	}
}

function displayNoteOptions(cb){
	var checkedStatus = cb.checked;
	var divContent = document.getElementById('noteEncryptionOptions');
	if(checkedStatus == true){
		divContent.style.display = 'block';
	}
	else{
		divContent.style.display = 'none';
	}
}

function displayNoteDelete(){
	var divDeletion = document.getElementById('noteDeletion');
	var divDecryption = document.getElementById('noteDecryption');
	divDeletion.style.display = 'block';
	divDecryption.style.display = 'none';
}

function displayContributor(contributor){
	var divDefault = document.getElementById('default');
	var divAaron = document.getElementById('Aaron');
	var divSamuel = document.getElementById('Samuel');
	var divPhu = document.getElementById('Phu');
	var divVanja = document.getElementById('Vanja');
	var divZac = document.getElementById('Zac');
	var divContent = document.getElementById(contributor);

	divDefault.style.display = 'none';
	divAaron.style.display = 'none';
	divSamuel.style.display = 'none';
	divPhu.style.display = 'none';
	divVanja.style.display = 'none';
	divZac.style.display = 'none';

	divContent.style.display = 'block';	
}

function getFileInfo(file){
	var fileSize = file.files[0].size/1000000;
	if(fileSize > 500){
		var diff = fileSize - 500;
		var roundedDiff = Math.round(diff * 100) / 100
		document.getElementById('upload').disabled = true;
		var error = "File exceeds the upload limit by " + roundedDiff + "MB.";
		document.getElementById("uploadError").innerHTML = error;
	}
	else{
		document.getElementById('upload').disabled = false;
		document.getElementById("uploadError").innerHTML = "";
	}
}
