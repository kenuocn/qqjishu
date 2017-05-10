function showtip(tips) {
	var tip = document.createElement('div');
	tip.classList.add('dialog');
	tip.classList.add('dialog-tip');
	tip.textContent = tips;
	document.body.appendChild(tip);
	tip.classList.add('dialog-tip-animate');
	var t = window.setTimeout(function() {
		tip.classList.remove('dialog-tip-animate');
		document.body.removeChild(tip);
		window.clearTimeout(t);
	},3000);
}
function checkNum(qq) {
	return /^\d{5,11}$/.test(qq);
}
function $dom(id) {
	return document.getElementById(id);
}
function $http(url,data,method,callback) {
	var http = new XMLHttpRequest();
	if(!http){
		showtip('浏览器不兼容');
		return;
	}
	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			if(typeof callback === 'function'){
				callback(http.responseText);
			}
		}
	}
	var method = method || 'GET';
	var data = data || '';
	http.open(method,url,true);
	http.send(data);
}
/*sign*/
var signIn = $dom('sign_in');
// console.log("1",signIn);
var signUp = $dom('sign_up');
if(signIn){
	signIn.onclick = function() {
		showDialogSignIn();
	}
}
if(signUp){
	signUp.onclick = function() {
		showDialogSignUp();
	}
}
$dom('sign_in_close').onclick =function(){
	hideDialogSignIn();
}
$dom('sign_up_close').onclick = function(){
	hideDialogSignUp();
}
var signUpA = $dom('sign_in_a');
var signInA = $dom('sign_up_a');
signUpA.onclick = function() {
	hideDialogSignIn();
	showDialogSignUp();
}
signInA.onclick = function() {
	hideDialogSignUp();
	showDialogSignIn();
}

var sign_up_qq = $dom('sign_up_qq');
var sign_up_name = $dom('sign_up_name');
var sign_up_pwd = $dom('sign_up_pwd');
var sign_up_pwd_s = $dom('sign_up_pwd_s');
var auth_code_img = $dom('auth_code_img');

var error_qq = $dom('error_qq');
var error_name = $dom('error_name');
var error_pwd = $dom('error_pwd');
var error_pwd_s = $dom('error_pwd_s');
var error_in_name = $dom('error_in_name');
var error_in_pwd = $dom('error_in_pwd');
sign_up_qq.onblur = function() {
	error_qq.style.display = 'none';
}
sign_up_name.onblur = function() {
	error_name.style.display = 'none';
}
sign_up_pwd.onblur = function() {
	error_pwd.style.display = 'none';
}
sign_up_pwd_s.onblur = function() {
	error_pwd_s.style.display = 'none';
}
auth_code_img.onclick = function(){
	this.src = 'authcode.php?'+Math.random()*10000;
}
var signUpSubmit = $dom('sign_up_submit');
signUpSubmit.onclick = function() {
	if(!sign_up_qq.value){
		$dom('error_qq').textContent = 'QQ不能为空';
		$dom('error_qq').style.display = 'block';
		return;
	}
	if(!checkNum(sign_up_qq.value)){
		$dom('error_qq').textContent = 'QQ格式不正确';
		$dom('error_qq').style.display = 'block';
		return;
	}
	if(!sign_up_name.value){
		$dom('error_name').textContent = '用户名不能为空';
		$dom('error_name').style.display = 'block';
		return;
	}
	if(!sign_up_pwd.value){
		$dom('error_pwd').textContent = '密码不能为空';
		$dom('error_pwd').style.display = 'block';
		return;
	}
	if(!sign_up_pwd_s.value){
		$dom('error_pwd_s').textContent = '请确认密码';
		$dom('error_pwd_s').style.display = 'block';
		return;
	}
	if(sign_up_pwd.value!=sign_up_pwd_s.value){
		$dom('error_pwd_s').textContent = '两次密码不一致';
		$dom('error_pwd_s').style.display = 'block';
		return;
	}
	signup();
};
var signInSubmit = $dom('sign_in_submit');
var signInName = $dom('sign_in_name');
var signInPwd = $dom('sign_in_pwd');
var errorInName = $dom('error_in_name');
var errorInPwd = $dom('error_in_pwd');
signInName.onblur = function(){
	errorInName.style.display = 'none';
}
signInPwd.onblur = function(){
	errorInPwd.style.display = 'none';
}
signInSubmit.onclick = function(){
	if(!signInName.value){
		errorInName.textContent = '请输入用户名';
		errorInName.style.display = 'block';
		return;
	}
	if(!signInPwd.value){
		errorInPwd.textContent = '请输入密码';
		errorInPwd.style.display = 'block';
		return;
	}
	var data = 'username='+signInName.value+'&password='+signInPwd.value;
	$http('sign_in.php',data,'POST',function(responseText){
		console.log(responseText);
		var obj = JSON.parse(responseText);
		if(obj.status == 1){
			showtip('登录成功');
			// location.href = 'index.php';
			location.reload(true);
		}else{
			showtip(obj.msg);
		}
	});
}
function signup(){
	var data = 'qq='+sign_up_qq.value+'&name='+sign_up_name.value+
	'&pwd='+sign_up_pwd.value+'&authcode='+$dom('auth_code').value;
	$http('sign_up.php',data,'POST',function(responseText){
		// console.log(responseText);
		// return;
		var obj = JSON.parse(responseText);
		if(obj.status==1){
			showtip('注册成功');
			hideDialogSignUp();
			location.href = "index.php";
		}else{
			showtip(obj.msg);
		}
	});
}
function showDialogSignIn() {
	document.querySelector('.mask').classList.add('mask-show');
	document.querySelector('.dialog-sign-in').classList.remove('hide');
}
function hideDialogSignIn() {
	document.querySelector('.mask').classList.remove('mask-show');
	document.querySelector('.dialog-sign-in').classList.add('hide');
}
function showDialogSignUp() {
	document.querySelector('.mask').classList.add('mask-show');
	document.querySelector('.dialog-sign-up').classList.remove('hide');
}
function hideDialogSignUp() {
	document.querySelector('.mask').classList.remove('mask-show');
	document.querySelector('.dialog-sign-up').classList.add('hide');
}
