!function(e){var t={};function o(n){if(t[n])return t[n].exports;var a=t[n]={i:n,l:!1,exports:{}};return e[n].call(a.exports,a,a.exports,o),a.l=!0,a.exports}o.m=e,o.c=t,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)o.d(n,a,function(t){return e[t]}.bind(null,a));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=6)}([function(e,t){e.exports=jQuery},function(e,t,o){(function(o){var n,a,r;a=[],void 0===(r="function"==typeof(n=function(){"use strict";function t(e,t,o){var n=new XMLHttpRequest;n.open("GET",e),n.responseType="blob",n.onload=function(){c(n.response,t,o)},n.onerror=function(){console.error("could not download file")},n.send()}function n(e){var t=new XMLHttpRequest;t.open("HEAD",e,!1);try{t.send()}catch(e){}return 200<=t.status&&299>=t.status}function a(e){try{e.dispatchEvent(new MouseEvent("click"))}catch(o){var t=document.createEvent("MouseEvents");t.initMouseEvent("click",!0,!0,window,0,0,0,80,20,!1,!1,!1,!1,0,null),e.dispatchEvent(t)}}var r="object"==typeof window&&window.window===window?window:"object"==typeof self&&self.self===self?self:"object"==typeof o&&o.global===o?o:void 0,i=r.navigator&&/Macintosh/.test(navigator.userAgent)&&/AppleWebKit/.test(navigator.userAgent)&&!/Safari/.test(navigator.userAgent),c=r.saveAs||("object"!=typeof window||window!==r?function(){}:"download"in HTMLAnchorElement.prototype&&!i?function(e,o,i){var c=r.URL||r.webkitURL,l=document.createElement("a");o=o||e.name||"download",l.download=o,l.rel="noopener","string"==typeof e?(l.href=e,l.origin===location.origin?a(l):n(l.href)?t(e,o,i):a(l,l.target="_blank")):(l.href=c.createObjectURL(e),setTimeout((function(){c.revokeObjectURL(l.href)}),4e4),setTimeout((function(){a(l)}),0))}:"msSaveOrOpenBlob"in navigator?function(e,o,r){if(o=o||e.name||"download","string"!=typeof e)navigator.msSaveOrOpenBlob(function(e,t){return void 0===t?t={autoBom:!1}:"object"!=typeof t&&(console.warn("Deprecated: Expected third argument to be a object"),t={autoBom:!t}),t.autoBom&&/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(e.type)?new Blob(["\ufeff",e],{type:e.type}):e}(e,r),o);else if(n(e))t(e,o,r);else{var i=document.createElement("a");i.href=e,i.target="_blank",setTimeout((function(){a(i)}))}}:function(e,o,n,a){if((a=a||open("","_blank"))&&(a.document.title=a.document.body.innerText="downloading..."),"string"==typeof e)return t(e,o,n);var c="application/octet-stream"===e.type,l=/constructor/i.test(r.HTMLElement)||r.safari,u=/CriOS\/[\d]+/.test(navigator.userAgent);if((u||c&&l||i)&&"undefined"!=typeof FileReader){var s=new FileReader;s.onloadend=function(){var e=s.result;e=u?e:e.replace(/^data:[^;]*;/,"data:attachment/file;"),a?a.location.href=e:location=e,a=null},s.readAsDataURL(e)}else{var d=r.URL||r.webkitURL,f=d.createObjectURL(e);a?a.location=f:location.href=f,a=null,setTimeout((function(){d.revokeObjectURL(f)}),4e4)}});r.saveAs=c.saveAs=c,e.exports=c})?n.apply(t,a):n)||(e.exports=r)}).call(this,o(3))},function(e,t,o){},function(e,t){var o;o=function(){return this}();try{o=o||new Function("return this")()}catch(e){"object"==typeof window&&(o=window)}e.exports=o},,,function(e,t,o){"use strict";o.r(t);o(2);var n=o(0),a=o.n(n),r=o(1);function i(e){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function c(e,t){for(var o=0;o<t.length;o++){var n=t[o];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,l(n.key),n)}}function l(e){var t=function(e,t){if("object"!=i(e)||!e)return e;var o=e[Symbol.toPrimitive];if(void 0!==o){var n=o.call(e,t||"default");if("object"!=i(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==i(t)?t:t+""}new(function(){return e=function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.table=a()(".cloud-customer-area-table"),this.table.length<1||(this.tableLoading=this.table?a()(this.table).find(".cloud-customer-area-table-loading"):null,this.getUserFiles(),this.listenDownloadClick(),this.listenUpload(),window.cca_operations=window.cca_operations?window.cca_operations:[],a()(window).on("beforeunload",(function(){if(window.cca_operations&&window.cca_operations.length>0)return!0})))},(t=[{key:"listenUpload",value:function(){var e=this;a()(this.table).on("reload",(function(){e.tryReload=setInterval((function(){console.log("Tento"),window.cca_operations&&window.cca_operations.length<1&&(console.log("Ok"),a()(e.table).find("tbody tr:not(.cloud-customer-area-table-loading)").remove(),a()(e.tableLoading).show(),e.getUserFiles(),clearInterval(e.tryReload))}),1e3)}))}},{key:"getUserFiles",value:function(){var e=this;this.table&&a.a.ajax({type:"POST",dataType:"json",url:frontend_cloud_customer_area.ajaxurl,data:{action:"cca_get_files",token:frontend_cloud_customer_area.token},success:function(t){a()(e.tableLoading).hide(),a.a.each(t,(function(t,o){var n=o.icon&&" - "!==o.icon&&o.icon.length>0?'<span class="cloud-customer-area-table-icon" style="background-image: url('+o.icon+')"></span>':" - ",r=o.id&&o.id.length>0?'<button class="button button-primary" data-id="'+o.id+'" title="'+frontend_cloud_customer_area.download_label+'" aria-label="'+frontend_cloud_customer_area.download_label+'">'+frontend_cloud_customer_area.download_label+"</button>":" - ",i=o.size?o.size:" - ";a()(e.table).append("<tr><td>"+o.name+"</td><td>"+o.date+"</td><td>"+n+"</td><td>"+i+"</td><td>"+r+"</td></tr>")}))},error:function(e){console.log(e.statusText)}})}},{key:"listenDownloadClick",value:function(){a()("body").on("click",".cloud-customer-area-table button",(function(e){e.preventDefault();var t=a()(e.target),o=a()(t).data("id");if(o&&!(o.length<=0)){var n=function(){a()(t).prop("disabled",!1),a()(t).removeClass("loading"),window.cca_operations=window.cca_operations.filter((function(e){return e!=o+"_download"}))};a()(t).prop("disabled",!0),a()(t).addClass("loading"),window.cca_operations.push(o+"_download");for(var i,c=[],l=0,u=function(e){var i={token:frontend_cloud_customer_area.token,file:o,method:s.method,mime:s.mime,part:e},u=new XMLHttpRequest;u.open("POST",frontend_cloud_customer_area.ajaxurl+"?action=cca_download_file",!0),u.setRequestHeader("Content-Type","application/x-www-form-urlencoded;"),u.responseType="arraybuffer",u.onload=function(o){c[e-1]=o.currentTarget.response,function(){if(l++,a()(t).css("--cca-loading-width",100*l/s.parts+"%"),!(l<s.parts)){var e=new Blob(c,{type:s.mime,responseType:"arraybuffer"});Object(r.saveAs)(e,s.name),a()(t).css("--cca-loading-width",0),n()}}()},u.onprogress=function(e){500===e.currentTarget.status&&(console.log(e.currentTarget.statusText),n(),u.abort())},u.send(a.a.param(i))},s=(i=!1,a.a.ajax({type:"POST",dataType:"json",async:!1,url:frontend_cloud_customer_area.ajaxurl,data:{action:"cca_get_file_info",token:frontend_cloud_customer_area.token,file:o},success:function(e){i={name:e.name,mime:e.mime,parts:e.parts,method:e.method}},error:function(e){console.log(e.statusText)}}),i),d=1;d<=s.parts;d++)u(d)}}))}}])&&c(e.prototype,t),o&&c(e,o),Object.defineProperty(e,"prototype",{writable:!1}),e;var e,t,o}());o.p}]);