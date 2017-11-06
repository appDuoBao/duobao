(function(w){
	
	var citys = {};
	w.Citys = function(objs){
		objs = objs || {};
		ak = objs.key || "9110ed0e8835920ae91bc8e2837accfe";
		var scriptTag = document.createElement("script");
		scriptTag.type = "text/javascript";
		scriptTag.src = "http://api.map.baidu.com/getscript?v=2.0&ak="+ak;
		document.head.insertBefore(scriptTag,document.head.firstChild);
		scriptTag.onload = function(){
			// 回调函数
			citys.map = BMap || {};
			citys.getLocation = function(fn){
				if(navigator.geolocation){
					navigator.geolocation.getCurrentPosition(function(e){
						var x = e.coords.longitude,
							y = e.coords.latitude,
							point = new BMap.Point(x,y);
						citys.get = new citys.map.Geocoder();
						fn && fn(point);
					});
				}
			}
			objs.load && objs.load(citys);
		}
	}
	
	
	
})(window);
