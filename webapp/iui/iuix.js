(function(){var _1=20;var _2=0;var _3=30000;var _4=null;var _5=null;var _6=null;var _7=0;var _8=0;var _9=location.hash;var _a="#_";var _b=[];var _c=0;var _d;var _e=false;var _f="portrait";var _10="landscape";window.iui={logging:false,busy:false,animOn:true,ajaxErrHandler:null,httpHeaders:{"X-Requested-With":"XMLHttpRequest"},showPage:function(_11,_12){if(_11){if(_11==_5){log("page = currentPage = "+_11.id);iui.busy=false;return;}if(_6){_6.removeAttribute("selected");sendEvent("blur",_6);_6=null;}if(hasClass(_11,"dialog")){iui.busy=false;sendEvent("focus",_11);showDialog(_11);}else{sendEvent("load",_11);var _13=_5;sendEvent("blur",_5);_5=_11;sendEvent("focus",_11);if(_13){setTimeout(slidePages,0,_13,_11,_12);}else{updatePage(_11,_13);}}}},showPageById:function(_14){var _15=$(_14);if(_15){if(!iui.busy){iui.busy=true;var _16=_b.indexOf(_14);var _17=_16!=-1;if(_17){_b.splice(_16);}iui.showPage(_15,_17);}}},goBack:function(){if(!iui.busy){iui.busy=true;_b.pop();var _18=_b.pop();var _19=$(_18);iui.showPage(_19,true);}},replacePage:function(_1a){var _1b=$(_1a);if(_1b){if(!iui.busy){iui.busy=true;var _1c=_b.indexOf(_1a);var _1d=_1c!=-1;if(_1d){log("error: can't replace page with ancestor");}_b.pop();iui.showPage(_1b,false);}}},showPageByHrefExt:function(_1e,_1f,_20,_21,cb){if(!iui.busy){iui.busy=true;iui.showPageByHref(_1e,_1f,_20,_21,cb);}},showPageByHref:function(_23,_24,_25,_26,cb){function spbhCB(xhr){log("xhr.readyState = "+xhr.readyState);if(xhr.readyState==4){if((xhr.status==200||xhr.status==0)&&!xhr.aborted){var _29=document.createElement("div");_29.innerHTML=xhr.responseText;sendEvent("beforeinsert",document.body,{fragment:_29});if(_26){replaceElementWithFrag(_26,_29);iui.busy=false;}else{iui.insertPages(_29);}}else{iui.busy=false;if(iui.ajaxErrHandler){iui.ajaxErrHandler("Error contacting server, please try again later");}}if(cb){setTimeout(cb,1000,true);}}}iui.ajax(_23,_24,_25,spbhCB);},ajax:function(url,_2b,_2c,cb){var xhr=new XMLHttpRequest();_2c=_2c?_2c.toUpperCase():"GET";if(_2b&&_2c=="GET"){url=url+"?"+iui.param(_2b);}xhr.open(_2c,url,true);if(cb){xhr.onreadystatechange=function(){cb(xhr);};}var _2f=null;if(_2b&&_2c!="GET"){xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");_2f=iui.param(_2b);}for(var _30 in iui.httpHeaders){xhr.setRequestHeader(_30,iui.httpHeaders[_30]);}xhr.send(_2f);xhr.requestTimer=setTimeout(ajaxTimeout,_3);return xhr;function ajaxTimeout(){try{xhr.abort();xhr.aborted=true;}catch(err){log(err);}}},param:function(o){var s=[];for(var key in o){s[s.length]=encodeURIComponent(key)+"="+encodeURIComponent(o[key]);}return s.join("&").replace(/%20/g,"+");},insertPages:function(_34){var _35=_34.childNodes;var _36;for(var i=0;i<_35.length;++i){var _38=_35[i];if(_38.nodeType==1){if(!_38.id){_38.id="__"+(++_c)+"__";}var _39=$(_38.id);var _3a;if(_39){_39.parentNode.replaceChild(_38,_39);_3a=$(_38.id);}else{_3a=document.body.appendChild(_38);}sendEvent("afterinsert",document.body,{insertedNode:_3a});if(_38.getAttribute("selected")=="true"||!_36){_36=_38;}--i;}}sendEvent("afterinsertend",document.body,{fragment:_34});if(_36){iui.showPage(_36);}},getSelectedPage:function(){for(var _3b=document.body.firstChild;_3b;_3b=_3b.nextSibling){if(_3b.nodeType==1&&_3b.getAttribute("selected")=="true"){return _3b;}}},getAllViews:function(){return document.querySelectorAll("body > *:not(.toolbar)");},isNativeUrl:function(_3c){for(var i=0;i<iui.nativeUrlPatterns.length;i++){if(_3c.match(iui.nativeUrlPatterns[i])){return true;}}return false;},nativeUrlPatterns:[new RegExp("^http://maps.google.com/maps?"),new RegExp("^mailto:"),new RegExp("^tel:"),new RegExp("^http://www.youtube.com/watch\\?v="),new RegExp("^http://www.youtube.com/v/"),new RegExp("^javascript:"),],hasClass:function(_3e,_3f){var re=new RegExp("(^|\\s)"+_3f+"($|\\s)");return re.exec(_3e.getAttribute("class"))!=null;},addClass:function(_41,_42){if(!iui.hasClass(_41,_42)){_41.className+=" "+_42;}},removeClass:function(_43,_44){if(iui.hasClass(_43,_44)){var reg=new RegExp("(\\s|^)"+_44+"(\\s|$)");_43.className=_43.className.replace(reg," ");}}};addEventListener("load",function(_46){var _47=iui.getSelectedPage();var _48=getPageFromLoc();if(_47){_4=_47;iui.showPage(_47);}if(_48&&(_48!=_47)){iui.showPage(_48);}setTimeout(preloadImages,0);if(typeof window.onorientationchange=="object"){window.onorientationchange=orientChangeHandler;_e=true;setTimeout(orientChangeHandler,0);}setTimeout(checkOrientAndLocation,0);_d=setInterval(checkOrientAndLocation,300);},false);addEventListener("unload",function(_49){return;},false);addEventListener("click",function(_4a){var _4b=findParent(_4a.target,"a");if(_4b){function unselect(){_4b.removeAttribute("selected");}if(_4b.href&&_4b.hash&&_4b.hash!="#"&&!_4b.target){followAnchor(_4b);}else{if(_4b==$("backButton")){iui.goBack();}else{if(_4b.getAttribute("type")=="submit"){var _4c=findParent(_4b,"form");if(_4c.target=="_self"){_4c.submit();return;}submitForm(_4c);}else{if(_4b.getAttribute("type")=="cancel"){cancelDialog(findParent(_4b,"form"));}else{if(_4b.target=="_replace"){followAjax(_4b,_4b);}else{if(iui.isNativeUrl(_4b.href)){return;}else{if(_4b.target=="_webapp"){location.href=_4b.href;}else{if(!_4b.target&&_4b.href){followAjax(_4b,null);}else{return;}}}}}}}}_4a.preventDefault();}},true);addEventListener("click",function(_4d){var div=findParent(_4d.target,"div");if(div&&hasClass(div,"toggle")){div.setAttribute("toggled",div.getAttribute("toggled")!="true");_4d.preventDefault();}},true);function followAnchor(_4f){function unselect(){_4f.removeAttribute("selected");}if(!iui.busy){iui.busy=true;_4f.setAttribute("selected","true");iui.showPage($(_4f.hash.substr(1)));setTimeout(unselect,500);}}function followAjax(_50,_51){function unselect(){_50.removeAttribute("selected");}if(!iui.busy){iui.busy=true;_50.setAttribute("selected","progress");iui.showPageByHref(_50.href,null,"GET",_51,unselect);}}function sendEvent(_52,_53,_54){if(_53){var _55=document.createEvent("UIEvent");_55.initEvent(_52,false,false);if(_54){for(i in _54){_55[i]=_54[i];}}_53.dispatchEvent(_55);}}function getPageFromLoc(){var _56;var _57=location.hash.match(/#_([^\?_]+)/);if(_57){_56=_57[1];}if(_56){_56=$(_56);}return _56;}function orientChangeHandler(){var _58=window.orientation;switch(_58){case 0:setOrientation(_f);break;case 90:case -90:setOrientation(_10);break;}}function checkOrientAndLocation(){if(!_e){if((window.innerWidth!=_7)||(window.innerHeight!=_8)){_7=window.innerWidth;_8=window.innerHeight;var _59=(_7<_8)?_f:_10;setOrientation(_59);}}if(location.hash!=_9){var _5a=location.hash.substr(_a.length);if((_5a=="")&&_4){_5a=_4.id;}iui.showPageById(_5a);}}function setOrientation(_5b){document.body.setAttribute("orient",_5b);if(_5b==_f){iui.removeClass(document.body,_10);iui.addClass(document.body,_f);}else{if(_5b==_10){iui.removeClass(document.body,_f);iui.addClass(document.body,_10);}else{iui.removeClass(document.body,_f);iui.removeClass(document.body,_10);}}setTimeout(scrollTo,100,0,1);}function showDialog(_5c){_6=_5c;_5c.setAttribute("selected","true");if(hasClass(_5c,"dialog")){showForm(_5c);}}function showForm(_5d){_5d.onsubmit=function(_5e){_5e.preventDefault();submitForm(_5d);};_5d.onclick=function(_5f){if(_5f.target==_5d&&hasClass(_5d,"dialog")){cancelDialog(_5d);}};}function cancelDialog(_60){_60.removeAttribute("selected");}function updatePage(_61,_62){if(!_61.id){_61.id="__"+(++_c)+"__";}_9=_a+_61.id;if(!_62){location.replace(_9);}else{location.assign(_9);}_b.push(_61.id);var _63=$("pageTitle");if(_61.title){_63.innerHTML=_61.title;}var _64=_61.getAttribute("ttlclass");_63.className=_64?_64:"";if(_61.localName.toLowerCase()=="form"&&!_61.target){showForm(_61);}var _65=$("backButton");if(_65){var _66=$(_b[_b.length-2]);if(_66&&!_61.getAttribute("hideBackButton")){_65.style.display="inline";_65.innerHTML=_66.title?_66.title:"Back";var _67=_66.getAttribute("bbclass");_65.className=(_67)?"button "+_67:"button";}else{_65.style.display="none";}}iui.busy=false;}function slidePages(_68,_69,_6a){var _6b=(_6a?_68:_69).getAttribute("axis");clearInterval(_d);sendEvent("beforetransition",_68,{out:true});sendEvent("beforetransition",_69,{out:false});if(canDoSlideAnim()&&_6b!="y"){slide2(_68,_69,_6a,slideDone);}else{slide1(_68,_69,_6a,_6b,slideDone);}function slideDone(){if(!hasClass(_69,"dialog")){_68.removeAttribute("selected");}_d=setInterval(checkOrientAndLocation,300);setTimeout(updatePage,0,_69,_68);_68.removeEventListener("webkitTransitionEnd",slideDone,false);sendEvent("aftertransition",_68,{out:true});sendEvent("aftertransition",_69,{out:false});if(_6a){sendEvent("unload",_68);}}}function canDoSlideAnim(){return (iui.animOn)&&(typeof WebKitCSSMatrix=="object");}function slide1(_6c,_6d,_6e,_6f,cb){if(_6f=="y"){(_6e?_6c:_6d).style.top="100%";}else{_6d.style.left="100%";}scrollTo(0,1);_6d.setAttribute("selected","true");var _71=100;slide();var _72=setInterval(slide,_2);function slide(){_71-=_1;if(_71<=0){_71=0;clearInterval(_72);cb();}if(_6f=="y"){_6e?_6c.style.top=(100-_71)+"%":_6d.style.top=_71+"%";}else{_6c.style.left=(_6e?(100-_71):(_71-100))+"%";_6d.style.left=(_6e?-_71:_71)+"%";}}}function slide2(_73,_74,_75,cb){_74.style.webkitTransitionDuration="0ms";var _77="translateX("+(_75?"-":"")+window.innerWidth+"px)";var _78="translateX("+(_75?"100%":"-100%")+")";_74.style.webkitTransform=_77;_74.setAttribute("selected","true");_74.style.webkitTransitionDuration="";function startTrans(){_73.style.webkitTransform=_78;_74.style.webkitTransform="translateX(0%)";}_73.addEventListener("webkitTransitionEnd",cb,false);setTimeout(startTrans,0);}function preloadImages(){var _79=document.createElement("div");_79.id="preloader";document.body.appendChild(_79);}function submitForm(_7a){if(!iui.busy){iui.busy=true;iui.addClass(_7a,"progress");iui.showPageByHref(_7a.action,encodeForm(_7a),_7a.method||"GET",null,clear);}function clear(){iui.removeClass(_7a,"progress");}}function encodeForm(_7b){function encode(_7c){for(var i=0;i<_7c.length;++i){if(_7c[i].name){args[_7c[i].name]=_7c[i].value;}}}var _7e={};encode(_7b.getElementsByTagName("input"));encode(_7b.getElementsByTagName("textarea"));encode(_7b.getElementsByTagName("select"));encode(_7b.getElementsByTagName("button"));return _7e;}function findParent(_7f,_80){while(_7f&&(_7f.nodeType!=1||_7f.localName.toLowerCase()!=_80)){_7f=_7f.parentNode;}return _7f;}function hasClass(_81,_82){return iui.hasClass(_81,_82);}function replaceElementWithFrag(_83,_84){var _85=_83.parentNode;var _86=_83;while(_85.parentNode!=document.body){_85=_85.parentNode;_86=_86.parentNode;}_85.removeChild(_86);var _87;while(_84.firstChild){_87=_85.appendChild(_84.firstChild);sendEvent("afterinsert",document.body,{insertedNode:_87});}sendEvent("afterinsertend",document.body,{fragment:_84});}function $(id){return document.getElementById(id);}function log(){if((window.console!=undefined)&&iui.logging){console.log.apply(console,arguments);}}})();