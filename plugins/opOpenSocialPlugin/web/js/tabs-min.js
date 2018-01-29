if(typeof (Control)=="undefined"){var Control={}}Control.Tabs=Class.create();Object.extend(Control.Tabs,{instances:[],findByTabId:function(A){return Control.Tabs.instances.find(function(B){return B.links.find(function(C){return C.key==A})})}});Object.extend(Control.Tabs.prototype,{initialize:function(C,B){this.activeContainer=false;this.activeLink=false;this.containers=$H({});this.links=[];Control.Tabs.instances.push(this);this.options={beforeChange:Prototype.emptyFunction,afterChange:Prototype.emptyFunction,hover:false,linkSelector:"li a",setClassOnContainer:false,activeClassName:"active",defaultTab:"first",autoLinkExternal:true,targetRegExp:/#(.+)$/,showFunction:Element.show,hideFunction:Element.hide};Object.extend(this.options,B||{});(typeof (this.options.linkSelector=="string")?$(C).getElementsBySelector(this.options.linkSelector):this.options.linkSelector($(C))).findAll(function(D){return(/^#/).exec(D.href.replace(window.location.href.split("#")[0],""))}).each(function(D){this.addTab(D)}.bind(this));this.containers.values().each(this.options.hideFunction);if(this.options.defaultTab=="first"){this.setActiveTab(this.links.first())}else{if(this.options.defaultTab=="last"){this.setActiveTab(this.links.last())}else{this.setActiveTab(this.options.defaultTab)}}var A=this.options.targetRegExp.exec(window.location);if(A&&A[1]){A[1].split(",").each(function(D){this.links.each(function(F,E){if(E.key==F){this.setActiveTab(E);throw $break}}.bind(this,D))}.bind(this))}if(this.options.autoLinkExternal){$A(document.getElementsByTagName("a")).each(function(D){if(!this.links.include(D)){var E=D.href.replace(window.location.href.split("#")[0],"");if(E.substring(0,1)=="#"){if(this.containers.keys().include(E.substring(1))){$(D).observe("click",function(G,F){this.setActiveTab(F.substring(1))}.bindAsEventListener(this,E))}}}}.bind(this))}},addTab:function(A){this.links.push(A);A.key=A.getAttribute("href").replace(window.location.href.split("#")[0],"").split("/").last().replace(/#/,"");this.containers[A.key]=$(A.key);A[this.options.hover?"onmouseover":"onclick"]=function(B){if(window.event){Event.stop(window.event)}this.setActiveTab(B);return false}.bind(this,A)},setActiveTab:function(A){if(!A){return }if(typeof (A)=="string"){this.links.each(function(B){if(B.key==A){this.setActiveTab(B);throw $break}}.bind(this))}else{this.notify("beforeChange",this.activeContainer);if(this.activeContainer){this.options.hideFunction(this.activeContainer)}this.links.each(function(B){(this.options.setClassOnContainer?$(B.parentNode):B).removeClassName(this.options.activeClassName)}.bind(this));(this.options.setClassOnContainer?$(A.parentNode):A).addClassName(this.options.activeClassName);this.activeContainer=this.containers[A.key];this.activeLink=A;this.options.showFunction(this.containers[A.key]);this.notify("afterChange",this.containers[A.key])}},next:function(){this.links.each(function(B,A){if(this.activeLink==B&&this.links[A+1]){this.setActiveTab(this.links[A+1]);throw $break}}.bind(this));return false},previous:function(){this.links.each(function(B,A){if(this.activeLink==B&&this.links[A-1]){this.setActiveTab(this.links[A-1]);throw $break}}.bind(this));return false},first:function(){this.setActiveTab(this.links.first());return false},last:function(){this.setActiveTab(this.links.last());return false},notify:function(B){try{if(this.options[B]){return[this.options[B].apply(this.options[B],$A(arguments).slice(1))]}}catch(A){if(A!=$break){throw A}else{return false}}}});if(typeof (Object.Event)!="undefined"){Object.Event.extend(Control.Tabs)};