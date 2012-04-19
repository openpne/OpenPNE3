(function($){$.widget("openpne.opEmoji",{options:{useAu:false,useSb:false},palletList:{docomo:{id:"epDocomo",emoji:[{start:1,end:252}],shortname:"i"},au:{id:"epAu",emoji:[{start:1,end:518},{start:700,end:822}],shortname:"e"},softbank:{id:"epSb",emoji:[{start:1,end:485}],shortname:"s"}},_create:function()
{this.element.before('<div id="'+this.element.attr('id')+'_epDocomo" style="display:none;"></div>');if(this.options.useAu){this.element.before('<div id="'+this.element.attr('id')+'_epAu" style="display:none"></div>');}
if(this.options.useSb){this.element.before('<div id="'+this.element.attr('id')+'_epSb" style="display:none"></div>');}},renderEmojiPallet:function(carrier)
{var pl=this.palletList[carrier];var emoji_set=pl.emoji;var textarea=$('#'+this.element.attr('id'));var pallet=$('#'+this.element.attr('id')+'_'+pl.id);pallet.addClass('isLoadEmojiImage');for(var num in emoji_set){var emoji=emoji_set[num];var emoji_end=emoji.end;for(var i=emoji.start;i<=emoji_end;i++){var src=op_get_relative_uri_root()+"/images/emoji/"+pl.shortname+"/"+pl.shortname+i+".gif";var alt="["+pl.shortname+":"+i+"]";var img=document.createElement("img");img.setAttribute("src",src);img.setAttribute("alt",alt);img.onclick=function(){var emoji_code=this.getAttribute("alt");op_insert_str_to_selection(textarea,emoji_code);};pallet.append(img);}}
pallet.removeClass('processLoadEmojiImage');},togglePallet:function(pallet_name)
{var pallet=$('#'+this.element.attr('id')+'_'+pallet_name);if(pallet.is(':hidden')){if(pallet.is(':not(.isLoadEmojiImage)')){pallet.addClass('processLoadEmojiImage');var carrier='';if(pallet_name==='epDocomo'){carrier='docomo';}
if(pallet_name==='epAu'){carrier='au';}
if(pallet_name==='epSb'){carrier='softbank';}
this.renderEmojiPallet(carrier);}
if(pallet.is('.processLoadEmojiImage')){return this.togglePallet(pallet_name);}
this.closeAllEmojiPallet();pallet.show();}else{pallet.hide();}},closeAllEmojiPallet:function()
{var id=this.element.attr('id');$('#'+id+'_epDocomo,'+'#'+id+'_epAu,'+'#'+id+'_epSb').hide();}});})(jQuery);
