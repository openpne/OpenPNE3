
function getCenterMuchScreen(element)
{var width=$(element).width();var height=$(element).height();var screenWidth=$(window).width();var screenHeight=$(window).height();var screenTop=$(window).scrollTop();var left=(screenWidth/2)-(width/2);var top=(screenHeight/2+screenTop)-(height/2);if(top<10)
{top=10;}
return{"left":left+"px","top":top+"px"};}
var opCookie={set:function(name,value,expires,path,domain,secure,httponly)
{var result='';if(value==undefined||(value instanceof String&&!value))
{var expires=new Date();expires.setTime((new Date()).getTime()-(12*30*24*60*60*1000));result=name+'=deleted; expires='+expires.toUTCString();}
else
{if(!expires||!(expires instanceof Date))
{var expires=new Date();console.debug(expires);expires.setTime((new Date()).getTime()+(60*60*1000));}
value=encodeURIComponent(value);result=name+"="+value+"; expires="+expires.toUTCString();}
if(path&&path.length)
{result=result+"; path="+path;}
if(domain&&domain.length)
{result=result+"; domain="+domain;}
if(secure)
{result=result+"; secure";}
if(httponly)
{result=result+"; secure";}
document.cookie=result;},get:function(name)
{var value=null;if(document.cookie&&document.cookie.length)
{var _cookie=document.cookie;var cookies=_cookie.split(';');for(var i=0;i<cookies.length;i++)
{var _cookie=$.trim(cookies[i]);if(_cookie.indexOf(name+'=')==0)
{value=$.trim(decodeURIComponent(_cookie.substr(name.length+1)));break;}}}
return value;}};function pne_url2a(url)
{var urlstr;if(url.length>57)
{var _url=url.replace("&amp;","&");if(_url.length>57)
{_url=_url.substr(0,57)+'...';urlstr=_url.replace("&","&amp;");}}
if(!urlstr)
{urlstr=url;}
document.write('<a href="'+url+'" target="_blank">'+urlstr+'</a>');}
function preventDoubleSubmission(form)
{var submitted=false;form.addEventListener('submit',function(ev){if(submitted){ev.preventDefault();return;}
submitted=true;var submitButtons=form.querySelectorAll('input[type="submit"],button[type="submit"]');for(var i=0;i<submitButtons.length;i++){submitButtons[i].disabled=true;}});}
var opLocalStorage={isEnabledVar:null,isEnabled:function()
{if(typeof this.isEnabledVar==='boolean')
{return this.isEnabledVar;}
try
{if(typeof window.localStorage==='undefined')
{return this.isEnabledVar=false;}
else if(window.localStrage)
{var testString='opTest';localStorage.setItem(testString,testString);localStorage.removeItem(testString);}}
catch(e)
{return this.isEnabledVar=false;}
return this.isEnabledVar=true;},set:function(name,value)
{if(!opLocalStorage.isEnabled())
{return false;}
localStorage.setItem(name,value);},get:function(name)
{if(!opLocalStorage.isEnabled())
{return false;}
return localStorage.getItem(name);}};var smtSwitch={key:'disable_smt',datePeriod:30,elem:null,initialize:function(){this.updateExpires(false);this.elem=document.getElementById('smt-switch');if(this.elem)
{this.elem.addEventListener('click',function(){smtSwitch.switchPc();});}
var $toSmt=document.getElementById('SmtSwitchLink');if($toSmt)
{$toSmt.addEventListener('click',function(){smtSwitch.switchSmt();},false);}},isSwitchPc:function(){return'1'===opCookie.get(this.key);},switchPc:function(){smtSwitch.updateExpires(true);location.reload();},switchSmt:function(){opCookie.set(this.key,null,this.getExpiresDate(),openpne.baseUrl);location.reload();},getExpiresDate:function(){var expiresDate=new Date();expiresDate.setTime(expiresDate.getTime()+this.datePeriod*24*60*60*1000);return expiresDate;},updateExpires:function(force){if(force||this.isSwitchPc()){opCookie.set(this.key,'1',this.getExpiresDate(),openpne.baseUrl);}}};