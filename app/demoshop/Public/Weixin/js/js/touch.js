(function(O){
	
	
	var touch = function(fx,callback){
		fx = fx || "";
		fx = fx.split(" ");
		if(!O.isFunction(callback)) return this;
		var fnsName = "left right top bottom";
		this.each(function(){
			var _this = this;
			O.each("start move end".split(" "),function(k,value){
				O(_this)["touch"+value](function(){
					_this = this;
					O.each(fx,function(i,name){
						if(fnsName.indexOf(name) > -1){
							if(k < 2){
								touch[value](touch[name](callback,_this))
							}
						}else{
							O.error("您在使用touch方法时传入的参数 : "+ name + " 是一个不能识别的无效参数");
						}
					})
					if(k == 2){
						touch[value](callback,_this);
					}
					event.preventDefault();
				})
			})
		})
	},
	slide = {
		
		// 保存数据
		info : {
			startX : 0,
			startY : 0,
			endX : 0,
			endY : 0,
			moveX : 0,
			moveY : 0,
			left : false,
			right : false,
			top : false,
			bottom : false,
			end : false,
			start : false,
			move : false
		},
		
		// 触摸事件处理
		start : function(fn,dom){
			var touch = event.targetTouches[0],
				x = touch.pageX,
				y = touch.pageY;
			this.info.startX = x;
			this.info.startY = y;
			this.info.start = true;
			this.info.end = false;
			fn && fn();
		},
		move : function(fn,dom){
			var touch = event.targetTouches[0],
				x = touch.pageX,
				y = touch.pageY;
			this.info.moveX = this.info.endX = x;
			this.info.moveY = this.info.endY = y;
			this.info.move = true;
			this.setInfo(x,y);
			fn && fn();
		},
		end : function(fn,dom){
			var x = this.info.endX ,
				y = this.info.endY;
			this.setInfo(x,y);
			O.each(this.info,function(i){
				touch.info[i] = 0;
			});
			this.info.end = true;
			fn && fn.call(dom,this.info);
		},
		setInfo : function(x,y){
			if(x > this.info.startX){
				this.info.left = false;
				this.info.right = true;
			}else if(x < this.info.startX){
				this.info.left = true;
				this.info.right = false;
			}
			
			if(y > this.info.startY){
				this.info.top = false;
				this.info.bottom = true;
			}else if(y < this.info.startY){
				this.info.top = true;
				this.info.bottom = false;
			}
		}
	}
	
	O.each("left right top bottom".split(" "),function(i,name){
		slide[name] = function(){
			if(O.isFunction(arguments[0])){
				if(this.info[name]){
					arguments[0].call(arguments[1],this.info);
				}
			}
		}
	})
	
	O.each(slide,function(key , value){
		touch[key] = value;
	})
	
	delete slide;
	
	O.fn.extend("touch",touch);
	
})(eui)
