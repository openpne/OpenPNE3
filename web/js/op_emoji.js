
var opEmoji=Class.create();opEmoji.prototype={id:null,useAu:false,useSb:false,palletList:{docomo:{id:"epDocomo",emoji:[{start:1,end:252}],shortname:"i"},au:{id:"epAu",emoji:[{start:1,end:518},{start:700,end:822}],shortname:"e"},softbank:{id:"epSb",emoji:[{start:1,end:485}],shortname:"s"}},initialize:function(id)
{this.id=id;},createEmojiPallet:function()
{document.write('<div id="'+this.id+'_epDocomo" style="display:none;"></div>');if(this.useAu){document.write('<div id="'+this.id+'_epAu" style="display:none"></div>');}
if(this.useSb){document.write('<div id="'+this.id+'_epSb" style="display:none"></div>');}},renderEmojiPallet:function(carrier)
{var pl=this.palletList[carrier];var emoji_set=pl.emoji;var textareaId=this.id;var palletId=this.id+'_'+pl.id;var div=$(palletId);Element.addClassName(palletId,"isLoadEmojiImage");for(var num in emoji_set){var emoji=emoji_set[num];var emoji_end=emoji.end;for(var i=emoji.start;i<=emoji_end;i++){var src=op_get_relative_uri_root()+"/images/emoji/"+pl.shortname+"/"+pl.shortname+i+".gif";var alt="["+pl.shortname+":"+i+"]";var img=document.createElement("img");img.setAttribute("src",src);img.setAttribute("alt",alt);img.onclick=function(){var emoji_code=this.getAttribute("alt");op_insert_str_to_selection($(textareaId),emoji_code);};div.appendChild(img);}}
Element.removeClassName(palletId,"processLoadEmojiImage");},togglePallet:function(pallet)
{var id=this.id+'_'+pallet;if($(id).style.display=="none"){if(!Element.hasClassName(id,'isLoadEmojiImage')){Element.addClassName(id,"processLoadEmojiImage");var carrier='';if(pallet=='epDocomo'){carrier='docomo';}
if(pallet=='epAu'){carrier='au';}
if(pallet=='epSb'){carrier='softbank';}
this.renderEmojiPallet(carrier);}
if(Element.hasClassName(id,'processLoadEmojiImage')){return this.togglePallet(pallet);}
this.closeAllEmojiPallet();Element.show(id);}else{Element.hide(id);}},closeAllEmojiPallet:function()
{if($(this.id+"_epDocomo")){Element.hide($(this.id+"_epDocomo"));}
if($(this.id+"_epAu")){Element.hide($(this.id+"_epAu"));}
if($(this.id+"epSb")){Element.hide($(this.id+"_epSb"));}}}
Object.extend(opEmoji,{instances:{},getInstance:function(id){if(!this.instances[id]){this.instances[id]=new opEmoji(id);}
return this.instances[id];}});