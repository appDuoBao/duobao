(function(w){
	
	var each = function(arr,fn){
		for(var i in arr){
			if(typeof fn == "function" && fn.call(arr[i] , i , arr[i]) === false) break;
		}
	},
	
	eui = function(selector,parent){
		return eui.prototype.init(selector,parent);
	},
	getElements = function(selector , parent){
		parent = parent || document;
		var _this = this , elements;
		if(eui.isElement(selector)){
			_this.elements = _this.elements || [];
			_this.elements.push(selector);
			_this.doms = _this.ele = [selector];
			_this.length = _this.elements.length;
			_this[_this.length-1] = selector;
		}else if(eui.isString(selector)){
			elements = parent.querySelectorAll(selector);
			_this.elements = _this.elements || [];
			each(elements,function(i,ele){
				if(eui.isElement(ele)){
					_this[i] = ele;
					_this.elements[i] = ele;
				}
			})
			_this.length = elements.length;
			this.doms = elements;
			
		}else if(eui.isFunction(selector)){
			
			_this.load(document,selector);
			
		}
		return _this;
	},
	addEventFunctions = {},
	
	fns = {
		version : "1.0.0",
		isFunction : function(fn){
			if(fn && typeof fn == "function" && fn.constructor === Function){
				return true;
			}
			return false;
		},
		isString : function(str){
			if(str && typeof str == "string"){
				return true;
			}
			return false;
		},
		isElement : function(Element){
			if(Element && typeof Element === "object" && Element.tagName){
				return true;
			}
			return false;
		},
		extend : function(name,fn){
			if(fn && this.isFunction(fn) && name && this.isString(name)){
				eui[name] = fns[name] = fn;
			}else if(typeof name == "object"){
				each(name , function(i){
					eui[i] = this;
				})
			}
		},
		addEvent : function(type,dom,fn){
			if(this.isString(type) && (dom == document || 
				this.isElement(dom)) && fn && this.isFunction(fn)){
				addEventFunctions[dom] = addEventFunctions[dom] || {};
				addEventFunctions[dom][type] = fn;
				dom.addEventListener(type,addEventFunctions[dom][type],false);
			}
		},
		removeEvent : function(type,dom){
			if(this.isString(type) && (dom == document || this.isElement(dom))){
				dom.removeEventListener(type,addEventFunctions[dom][type],false);
			}
		}
	};
	eui.fn = eui.prototype = {
		constructor : eui,
		init : function(selector,parent){
			
			parent = parent || document;
			
			getElements.call(this,selector,parent);
			
			return this;
		},
		each : function(){
			var fn = null , i ,arr;
			if(arguments[0].constructor === Array){
				i = arguments[0].length;
				arr = arguments[0];
				if(eui.isFunction(arguments[1])){
					fn = arguments[1];
				}else{
					fn = new Function();
				}
			}
			if(eui.isElement(arguments[0])){
				i = this.ele.length;
				arr = this.ele;
				if(eui.isFunction(arguments[1])){
					fn = arguments[1];
				}else{
					fn = new Function();
				}
			}
			if(eui.isFunction(arguments[0])){
				fn = arguments[0];
				i = this.doms.length;
				arr = this.doms;
			}
			while(i--){
				fn.call(arr[i],i,arr[i]);
			}
			return this;
		},
		find : function(selector){
			var eles = [] , _this = this;
			this.each(function(){
				if(eui.isString(selector)){
					var ele = this.querySelectorAll(selector);
					each(ele,function(i,ele){
						if(eui.isElement(ele)){
							eles.push(ele)
						}
					})
				}else if(eui.isElement(selector)){
					eles.push(selector);
				}
			});
			each(eles,function(i,ele){
				_this[i] = ele;
			});
			_this.length = eles.length;
			_this.elements = _this.doms = eles;
			return _this;
		},
		html : function(str){
			if(eui.isString(str)){
				this.each(function(){
					this.innerHTML = str;
				})
				return this;
			}else{
				var txt = "";
				this.each(function(){
					txt = this.innerHTML;
				})
				return txt;
			}
		},
		eq : function(i){
			if(i >= this.elements.length) return this;
			this.doms = [this.elements[i]];
			return this;
		},
		load : function(Element,fn){
			if(Element == document || eui.isElement(Element)){
				Element.addEventListener("DOMContentLoaded",fn,false);
			}
		},
		extend : function(name,fn){
			if(fn && eui.isFunction(fn) && name && eui.isString(name)){
				eui.prototype[name] = eui.fn[name] = fn;
			}else{
				if(typeof name == "object"){
					each(name , function(i){
						eui.prototype[i] = eui.fn[i] = this;
					})
				}
			}
			return this;
		},
		addEvent : function(type,fn){
			this.each(function(){
				if(eui.isFunction(fn)){
					eui.addEvent(type,this,fn);
				}
			})
			return this;
		},
		removeEvent : function(type){
			this.each(function(){
				if(eui.isString(type)){
					eui.removeEvent(type,this);
				}
			})
		}
	}
	each(fns,function(name,value){
		eui[name] = value;
	})
	
	each("click blur touchstart touchmove touchend focus".split(" "),function(i,name){
		eui.fn.extend(name,function(){
			var fn = new Function();
			if(eui.isFunction(arguments[0])){
				fn = arguments[0]
			}
			this.each(function(){
				eui.addEvent(name,this,fn);
			})
			return this;
		})
	})
	
	eui.fn.extend({
		css : function(name,val){
			if(eui.isString(name) && eui.isString(val)){
				this.each(function(){
					this.style[name] = val;
				})
				return this;
			}else if(eui.isString(name) && val === undefined){
				this.each(function(){
					val = eui.fn.getStyle(this,name);
				})
				return val;
			}else if(typeof name == "object"){
				each(name,function(name,val){
					eui.fn.each(function(){
						this.style[name] = val;
					})
				})
				return this;
			}
		},
		getStyle : function(dom,name){
			return window.getComputedStyle(dom,null)[name];
		},
		append : function(selector){
			var reg = /^<[a-z]+.*>.*<\/[a-z]+>$/;
			if(eui.isElement(selector)){
				this.each(function(){
					this.appendChild(selector)
				});
			}else if(eui.isString(selector) && reg.test(selector)){
				this.each(function(){
					this.innerHTML += selector;
				})
			}
			return this;
		},
		attr : function(name,value){
			if(name && eui.isString(name) && !value){
				var txt = "";
				this.each(function(){
					txt = this.getAttribute(name);
				})
				return txt;
			}else if(name && eui.isString(name) && value){
				this.each(function(){
					txt = this.setAttribute(name,value);
				})
			}
			return this;
		},
		addClass : function(str){
			if(str && eui.isString(str)){
				this.each(function(){
					if(eui.isElement(this)){
						this.classList.add(str);
					}
				})
			}
			return this;
		},
		removeClass : function(str){
			if(str && eui.isString(str)){
				this.each(function(){
					if(eui.isElement(this)){
						this.classList.remove(str);
					}
				})
			}
			return this;
		}
	})
	eui.extend({
		loadFile : function(path , type){
			if(!eui.isString(path) || !eui.isString(type)) return false;
			var types = /^(css|js|img)$/,
				m = type.match(types),
				eles = {
					"css" : "link .css",
					"js" : "script .js",
					"img" : "img"
				},
				tagInfo = eles[m[0]].split(" "),
				attrs = ["rel","src","href"];
			if(m){
				var dom = document.createElement(tagInfo[0]);
				if(m[0] == "css"){
					dom[attrs[0]] = "stylesheet";
					dom[attrs[2]] = path + tagInfo[1];
				}else{
					dom[attrs[1]] = path + tagInfo[1];
				}
				if(m[0] == "css" || m[0] == "js"){
					eui("head").append(dom);
				}
				if(m[0] == "img"){
					return dom;
				}
			}
			return this;
		},
		getPath : function(ele){
			if(!eui.isElement(ele)){
				this.error("您在使用getPath函数时传入的参数不是一个有效的dom节点");
				return this;
			}	
			var src = "src" , path;
			if(ele.tagName.toLocaleLowerCase() == "link"){
				src = "href";
			}
			path = ele[src].substring(0,ele[src].lastIndexOf("/")+1);
			return path;
		},
		each : each
	})
	each("error info log".split(" "),function(i,name){
		eui.extend(name,function(tag,flip){
			flip = flip || " : ";
			if(!this.isString(flip)) return this;
			if(tag && typeof tag == "object"){
				each(tag,function(key,value){
					console[name](key + flip + value);
				})
			}else if(tag && (this.isString(tag) || typeof tag == "number")){
				console[name](tag);
			}
			return this;
		})
	})
	
	
	
	
	
	
	
	eui.prototype.init.prototype = eui.prototype;
	window['eui'] = eui;
	window['$'] = window['$'] || eui;
})(window);
