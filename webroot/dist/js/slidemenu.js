!function(s){"use strict";function n(s){return new RegExp("(^|\\s+)"+s+"(\\s+|$)")}var a,e,t;function c(s,n){(a(s,n)?t:e)(s,n)}"classList"in document.documentElement?(a=function(s,n){return s.classList.contains(n)},e=function(s,n){s.classList.add(n)},t=function(s,n){s.classList.remove(n)}):(a=function(s,a){return n(a).test(s.className)},e=function(s,n){a(s,n)||(s.className=s.className+" "+n)},t=function(s,a){s.className=s.className.replace(n(a)," ")}),s.classie={hasClass:a,addClass:e,removeClass:t,toggleClass:c,has:a,add:e,remove:t,toggle:c}}(window);