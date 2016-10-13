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
	var divContent = document.getElementById(contributor);

	divDefault.style.display = 'none';
	divAaron.style.display = 'none';
	divSamuel.style.display = 'none';
	divPhu.style.display = 'none';
	divVanja.style.display = 'none';

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

function changeColor(item){
	if(item.id == 1){
		item.style.color = "#FF0000";
	}
	else if(item.id == 2){
		item.style.color = "#FF7F00";
	}
	else if(item.id == 3){
		item.style.color = "#FFFF00";
	}
	else if(item.id == 4){
		item.style.color = "#00FF00";
	}
	else if(item.id == 5){
		item.style.color = "#0000FF";
	}
	else if(item.id == 6){
		item.style.color = "#4B0082";
	}
	else if(item.id == 7){
		item.style.color = "#9400D3";
	}
	else if(item.id == 8){
		item.style.color = "#FF7F00";
	}
	else if(item.id == 9){
		item.style.color = "#FFFF00";
	}
	else if(item.id == 10){
		item.style.color = "#00FF00";
	}
	else if(item.id == 11){
		item.style.color = "#0000FF";
	}
	else if(item.id == 12){
		item.style.color = "#4B0082";
	}
	else if(item.id == 13){
		item.style.color = "#9400D3";
	}
}

function resetColor(item){
	item.style.color = "";
}

function unlockVisual(item){
	item.className = "fa fa-unlock-alt";
}

function unlockVisualReset(item){
	item.className = "fa fa-lock";
}

function logoutHover(){
	for(let i=1; i<15; i++){
		setTimeout( function timer(){
			if(i > 1){
				var resetItem = document.getElementById(i - 1);
				resetColor(resetItem);
			}
			if(i < 14){
				var item = document.getElementById(i);
				changeColor(item);
			}
		}, i*25 );
	}
}
