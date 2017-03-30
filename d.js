var initElement,initHtml,newHtml,initJs = '',initCss = '';
initJs += "<script language=javascript src='http://dickdum.com/iscroll.js'></script>";
initCss += "<style>";
initCss += "#pullDown, #pullUp {height:40px;line-height:40px;padding:5px 10px;font-weight:bold;font-size:14px;color:#888;}";
initCss += "#pullDown .pullDownIcon, #pullUp .pullUpIcon  {display:block; float:left;width:40px; height:40px;background:url('http://dickdum.com/iscroll.png') 0 0 no-repeat;-webkit-background-size:40px 80px; background-size:40px 80px;-webkit-transition-property:-webkit-transform;-webkit-transition-duration:250ms;}";
initCss += "#pullDown .pullDownIcon {-webkit-transform:rotate(0deg) translateZ(0);}";
initCss += "#pullUp .pullUpIcon  { -webkit-transform:rotate(-180deg) translateZ(0);}";
initCss += "#pullDown.flip .pullDownIcon {-webkit-transform:rotate(-180deg) translateZ(0);}";
initCss += "#pullUp.flip .pullUpIcon {-webkit-transform:rotate(0deg) translateZ(0);}";
initCss += "#pullDown.loading .pullDownIcon, #pullUp.loading .pullUpIcon {background-position:0 100%;-webkit-transform:rotate(0deg) translateZ(0);-webkit-transition-duration:0ms;-webkit-animation-name:loading;-webkit-animation-duration:2s;-webkit-animation-iteration-count:infinite;-webkit-animation-timing-function:linear;}";
initCss += "@-webkit-keyframes loading {from { -webkit-transform:rotate(0deg) translateZ(0); }to { -webkit-transform:rotate(360deg) translateZ(0); }}";
initCss += "</style>";
document.write(initJs+initCss);

var myScroll,
    pullDownEl,
    pullDownOffset,
    pullUpEl,
    pullUpOffset,
    pullDownAction,
    pullUpAction,
    isDown = isUp = false;
function initIscroll(eid,style,downFun,upFun) {
    style = style?style.toUpperCase():"";
    pullDownAction = function(){
        setTimeout(function () {	// <-- Simulate network congestion, remove setTimeout from production!
            oldH = myScroll.scrollerH + myScroll.wrapperH;
            if(downFun) {
                downFun();
            }else{
                alert("Nothing to do.");
            }
            myScroll.refresh();
            newH = myScroll.scroller.children[1].offsetHeight + 100;
            if(newH > oldH){
                newOffset = oldH - newH;
            }else {
                newOffset = pullDownOffset;
            }
            myScroll.scrollTo(0,newOffset,0);
        }, 1000);
    }
    pullUpAction = function(){
        setTimeout(function () {	// <-- Simulate network congestion, remove setTimeout from production!
            if(upFun) {
                upFun();
            }else{
                alert("Nothing to do.");
            }
            myScroll.refresh();
        }, 1000);
    }
    initElement = document.getElementById(eid);
    initHtml = initElement.innerHTML;
    newHtml = '<div id="scroller">';
    if(style == "DOWN" || style == "BOTH"){
        isDown = true;
        newHtml += '<div id="pullDown"><span class="pullDownIcon"></span><span class="pullDownLabel">Pull down to refresh...</span></div>';
    }
    newHtml += initHtml;
    if(style == "UP" || style == "BOTH"){
        isUp = true;
        newHtml += '<div id="pullUp"><span class="pullUpIcon"></span><span class="pullUpLabel">Pull up to refresh...</span></div>'
    }
    newHtml += '</div>';
    initElement.innerHTML = newHtml;
    if(isDown){
        pullDownEl = document.getElementById('pullDown');
        pullDownOffset = pullDownEl.offsetHeight;
    }
    if(isUp){
        pullUpEl = document.getElementById('pullUp');
        pullUpOffset = 10;
    }
    myScroll = new iScroll(eid, {
        topOffset: pullDownOffset,
        vScrollbar: false,
        onRefresh: function () {
            if (isDown && pullDownEl.className.match('loading')) {
                pullDownEl.className = '';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';
            }
            if (isUp && pullUpEl.className.match('loading')) {
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Pull up to load more...';
            }

            if(isUp) document.getElementById("pullUp").style.display="none";
            initElement['style']['overflow-x'] = 'auto';
            //document.getElementById("show").innerHTML="onScrollEnd: contentH["+this.scroller.children[1].offsetHeight+"],Y["+this.y+"],maxScrollY["+this.maxScrollY+"],minScrollY["+this.minScrollY+"],scrollerH["+this.scrollerH+"],wrapperH["+this.wrapperH+"]";
        },
        onScrollMove: function () {
            this.scrollerH = this.scroller.children[1].offsetHeight+100;
            this.maxScrollY = this.wrapperH - this.scrollerH + this.minScrollY;
            //document.getElementById("show").innerHTML="onScrollEnd: contentH["+this.scroller.children[1].offsetHeight+"],Y["+this.y+"],maxScrollY["+this.maxScrollY+"],minScrollY["+this.minScrollY+"],scrollerH["+this.scrollerH+"],wrapperH["+this.wrapperH+"]";
            if (this.y > 0 && isDown) {
                pullDownEl.className = 'flip';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Release to refresh...';
                this.minScrollY = 0;
            }
            if (this.y < 0 && pullDownEl.className.match('flip') && isDown) {
                pullDownEl.className = '';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';
                this.minScrollY = -pullDownOffset;
            }
            if (isUp && this.scrollerH < this.wrapperH && this.y < (this.minScrollY-pullUpOffset) || this.scrollerH > this.wrapperH && this.y < (this.maxScrollY - pullUpOffset) ) {
                document.getElementById("pullUp").style.display="";
                pullUpEl.className = 'flip';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Release to refresh...';
            }
            if (isUp && this.scrollerH < this.wrapperH && this.y > (this.minScrollY-pullUpOffset) && pullUpEl.className.match('flip') || this.scrollerH > this.wrapperH && this.y > (this.maxScrollY - pullUpOffset) && pullUpEl.className.match('flip')) {
                document.getElementById("pullUp").style.display="none";
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Pull up to load more...';
            }
        },
        onScrollEnd: function () {
            if (isDown && pullDownEl.className.match('flip')) {
                pullDownEl.className = 'loading';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Loading...';
                pullDownAction();	// Execute custom function (ajax call?)
            }
            if (isUp && pullUpEl.className.match('flip')) {
                pullUpEl.className = 'loading';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Loading...';
                pullUpAction();	// Execute custom function (ajax call?)
            }
            //document.getElementById("show").innerHTML="onScrollEnd: contentH["+this.scroller.children[1].offsetHeight+"],Y["+this.y+"],maxScrollY["+this.maxScrollY+"],minScrollY["+this.minScrollY+"],scrollerH["+this.scrollerH+"],wrapperH["+this.wrapperH+"]";
        }
    });
    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==27){ // 按 Esc
            iScroll.prototype.enabled = false;
        }
        if(e && e.keyCode==13){ // enter 键
            iScroll.prototype.enabled = true;
        }
    };
    myScroll.scrollTo(0,myScroll.maxScrollY,0);
}
