$(function(){
	resize();
})
function resize (){
	var w = $("html").width(),
		size = w / 16;
	$("html").css("font-size",size + "px");
}




window.alert = function(title,len,fn){
	var dom = document.createElement("div");
	document.body.appendChild(dom);
	var alertBg = document.createElement("div");
	alertBg.className = "alert-bg";
	alertBg.style.zIndex = "1000000000000";
	dom.appendChild(alertBg);
	dom.style.zIndex = "1000000000000";
	var alertC = document.createElement("div");
	alertC.className = "UI-alert";
	dom.appendChild(alertC);
	alertC.style.zIndex = "1000000000000";
	var content = document.createElement("div");
	content.className = "alert-content";
	content.innerHTML = title !== undefined ? title : "";
	var btns = document.createElement("div");
	btns.className = "alert-button";
	alertC.appendChild(content);
	alertC.appendChild(btns);
	if(len && len == 2){
		btns.innerHTML = "<div><button>取消</button></div><div><button class='ok'>确定</button></div>";
	}else{
		btns.innerHTML = "<div><button class='ok'>确定</button></div>";
	}
	var buts = btns.getElementsByTagName("button");
	for(var i = 0; i < buts.length; i++){
		buts[i].index = i;
		buts[i].onclick = function(){
			var index = this.index;
			document.body.removeChild(dom);
			if(fn && typeof fn == "function"){
				fn.call(this,index);
			}
		}
	}
}
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