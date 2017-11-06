var uploadFile = function(){
	var uploadFile = document.getElementsByClassName("w_uploadFile");
	var uploadFileImg = document.getElementsByClassName("w_uploadFileImg");
	var parent = document.getElementsByClassName("w_uploadImg")[0];
	document.body.onclick = function(){
		uploadFile[0].files[0];
		var eles = document.createElement("li");
		var imgs = document.createElement("img");
		imgs.setAttribute("src",uploadFile[0].files[0]);
		eles.appendChild(imgs);
		parent.insertBefore(uploadFile,eles);
	}
};

