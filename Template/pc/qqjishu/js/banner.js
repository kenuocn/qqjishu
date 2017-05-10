document.addEventListener('DOMContentLoaded',function(){
	var banner = $dom('wrap');
	var count = banner.children.length;
	var imgWidth = banner.children[0].width;
	var width = imgWidth*count;
	var curr = 1;
	var pre = 0;
	var time = null;
	var prev = $dom('prev');
	var next = $dom('next');
	if(count>1){
		time = window.setInterval(function(){
			translate(1);
		},2000);
	}else{
		prev.style.display = "none";
		next.style.display = "none";
	}

	prev.onclick = function(){
		if(time) window.clearInterval(time);
		translate(-1);
	}
	// console.log(curr);
	next.onclick = function(){
		if(time) window.clearInterval(time);
		// translate函数产生闭包，外部无法访问该函数内部的curr
		translate(1);
	}
	function translate(direc) {
		if(direc>0){
			curr = curr<count?++curr:1;
		}else {
			curr = curr==1?count:--curr;
		}
		var width = (curr-1)*imgWidth;
		banner.style['-webkit-transform'] = 'translateX(-'+width+'px)';
		banner.style['transform'] = 'translateX(-'+width+'px)';
		// console.log(curr);
	}
});
