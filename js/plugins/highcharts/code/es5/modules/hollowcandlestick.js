/*
 Highstock JS v10.3.3 (2023-01-20)

 Hollow Candlestick series type for Highcharts Stock

 (c) 2010-2021 Karol Kolodziej

 License: www.highcharts.com/license
*/
(function(b){"object"===typeof module&&module.exports?(b["default"]=b,module.exports=b):"function"===typeof define&&define.amd?define("highcharts/modules/hollowcandlestick",["highcharts","highcharts/modules/stock"],function(f){b(f);b.Highcharts=f;return b}):b("undefined"!==typeof Highcharts?Highcharts:void 0)})(function(b){function f(b,d,a,g){b.hasOwnProperty(d)||(b[d]=g.apply(null,a),"function"===typeof CustomEvent&&window.dispatchEvent(new CustomEvent("HighchartsModuleLoaded",{detail:{path:d,module:b[d]}})))}
b=b?b._modules:{};f(b,"Series/HollowCandlestick/HollowCandlestickPoint.js",[b["Core/Series/SeriesRegistry.js"]],function(b){var d=this&&this.__extends||function(){var b=function(a,c){b=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(b,c){b.__proto__=c}||function(b,c){for(var a in c)Object.prototype.hasOwnProperty.call(c,a)&&(b[a]=c[a])};return b(a,c)};return function(a,c){function d(){this.constructor=a}if("function"!==typeof c&&null!==c)throw new TypeError("Class extends value "+
String(c)+" is not a constructor or null");b(a,c);a.prototype=null===c?Object.create(c):(d.prototype=c.prototype,new d)}}();return function(b){function a(){var a=null!==b&&b.apply(this,arguments)||this;a.series=void 0;return a}d(a,b);a.prototype.getClassName=function(){var a=b.prototype.getClassName.apply(this),d=this.series.hollowCandlestickData[this.index];d.isBullish||"up"!==d.trendDirection||(a+="-bearish-up");return a};return a}(b.seriesTypes.candlestick.prototype.pointClass)});f(b,"Series/HollowCandlestick/HollowCandlestickSeries.js",
[b["Series/HollowCandlestick/HollowCandlestickPoint.js"],b["Core/Series/SeriesRegistry.js"],b["Core/Utilities.js"],b["Core/Axis/Axis.js"]],function(b,d,a,f){var c=this&&this.__extends||function(){var b=function(a,e){b=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(b,e){b.__proto__=e}||function(b,e){for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&(b[a]=e[a])};return b(a,e)};return function(a,e){function c(){this.constructor=a}if("function"!==typeof e&&null!==e)throw new TypeError("Class extends value "+
String(e)+" is not a constructor or null");b(a,e);a.prototype=null===e?Object.create(e):(c.prototype=e.prototype,new c)}}(),h=d.seriesTypes.candlestick,k=a.addEvent,g=a.merge;a=function(b){function a(){var a=null!==b&&b.apply(this,arguments)||this;a.data=void 0;a.hollowCandlestickData=[];a.options=void 0;a.points=void 0;return a}c(a,b);a.prototype.getPriceMovement=function(){var a=this.allGroupedData||this.yData,b=this.hollowCandlestickData;if(!b.length&&a&&a.length){b.push({isBullish:!0,trendDirection:"up"});
for(var c=1;c<a.length;c++)b.push(this.isBullish(a[c],a[c-1]))}};a.prototype.getLineColor=function(a){return"up"===a?this.options.upColor||"#06b535":this.options.color||"#f21313"};a.prototype.getPointFill=function(a){return a.isBullish?"transparent":"up"===a.trendDirection?this.options.upColor||"#06b535":this.options.color||"#f21313"};a.prototype.init=function(){b.prototype.init.apply(this,arguments);this.hollowCandlestickData=[]};a.prototype.isBullish=function(a,b){return{isBullish:a[0]<=a[3],trendDirection:a[3]<
b[3]?"down":"up"}};a.prototype.pointAttribs=function(a,c){var d=b.prototype.pointAttribs.call(this,a,c);a=this.hollowCandlestickData[a.index];d.fill=this.getPointFill(a)||d.fill;d.stroke=this.getLineColor(a.trendDirection)||d.stroke;c&&(c=this.options.states[c],d.fill=c.color||d.fill,d.stroke=c.lineColor||d.stroke,d["stroke-width"]=c.lineWidth||d["stroke-width"]);return d};a.defaultOptions=g(h.defaultOptions,{color:"#f21313",dataGrouping:{groupAll:!0,groupPixelWidth:10},lineColor:"#f21313",upColor:"#06b535",
upLineColor:"#06b535"});return a}(h);k(a,"updatedData",function(){this.hollowCandlestickData.length&&(this.hollowCandlestickData.length=0)});k(f,"postProcessData",function(){this.series.forEach(function(a){a.is("hollowcandlestick")&&a.getPriceMovement()})});a.prototype.pointClass=b;d.registerSeriesType("hollowcandlestick",a);"";return a});f(b,"masters/modules/hollowcandlestick.src.js",[],function(){})});
//# sourceMappingURL=hollowcandlestick.js.map