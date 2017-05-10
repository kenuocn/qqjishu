<?php
// +----------------------------------------------------------------------
// | 附件管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class AttachmentController extends AdminBase { 
	/**
     * @cc 上传功能
     */
	public function upload(){
		//接受配置参数
		$filetype=I('get.type')?I('get.type'):'file';   //指定上传类型
		$fileNumLimit=I('get.num','1');   //同时上传的文件个数限制
		$auto=I('get.auto')?I('get.auto'):true;  //是否选择完后自动上传  1-自动上传  2-手动上传
		if($auto=="true"){
			$auto=true;	
		}else{
			$auto=false;	
		} 
		$uploadconfig =array(
			'swf' =>  __ROOT__. '/statics/js/webuploader/swf/Uploader.swf',//引用swf地址
			'server' => U('Attachment/uploadflie',array('action'=>$filetype)),//处理上传的地址
			'filelistPah' => U('Attachment/uploadflie',array('action'=>'list'.$filetype)),//获取文件列表的地址
			'delPath' => U('Attachment/del'),//删除文件的地址
			'chunked' => false,//是否对文件块大小进行检测
			'chunkSize' => 512 * 1024,//文件块大小限制
			'fileNumLimit' => $fileNumLimit,//同时上传的文件个数限制
			'fileSizeLimit' => 200 * 1024 * 1024,//总文件大小限制，单位是Byte(200M)
			'fileSingleSizeLimit' => 200 * 1024 * 1024,//单个文件大小限制，单位是Byte(2M)
			'fileVal' => 'up'.$filetype,//服务端接收文件的键值，相当于<input type="file" name="file" />中name="file"，默认file
			'type'=>$filetype,
			'auto'=>$auto,//是否选择完后自动上传
			'ROOT_PATH'=>SITE_PATH,
			'formData' => array(//传给服务端的额外数据 
				'session' => session_id()//用于浏览器的session丢失
			),
			'obj'=>I('get.obj'),
			'pick' => array(
				'id' => '#filePicker',
				'label' => '点击选择文件',
				'name' => 'file'
			)
		);
		$this->assign('uploadconfig',json_encode($uploadconfig));
		$this->display();	
	}
	/**
     * @cc 附件上传
     */ 
	public function uploadflie(){
		import('Org.Vin.FileStorage');
		$vin=new \FileStorage;
		$vin::connect(STORAGE_TYPE);
		$action =I('get.action');
		switch($action){
			case 'config':
		        $result = json_encode(C("ueditor"));
		        break;
			case 'scrawl'://涂鸦
				$cfg['maxSize']=C('ueditor.scrawlMaxSize');
				$cfg['name']=C('ueditor.scrawlFieldName');
				$cfg['savePath']=C('ueditor.scrawlSavePath'); 
		        $result = $this->_uploadBase64($cfg);
		        break;	
			case 'video'://音频
				$cfg['maxSize']=C('ueditor.videoMaxSize');
				$cfg['name']=C('ueditor.videoFieldName'); 
				$cfg['ext']=C('ueditor.videoAllowFiles'); 
				$cfg['savePath']=C('ueditor.videoSavePath'); 
				$result = $this->_upload($cfg);
				break;
			case 'file'://文件
				$cfg['maxSize']=C('ueditor.fileMaxSize');
				$cfg['name']=C('ueditor.fileFieldName'); 
				$cfg['ext']=C('ueditor.fileAllowFiles'); 
				$cfg['savePath']=C('ueditor.fileSavePath'); 
				$result = $this->_upload($cfg);
				break;
			case 'image'://图片
				$cfg['maxSize']=C('ueditor.imageMaxSize');
				$cfg['name']=C('ueditor.imageFieldName'); 
				$cfg['ext']=C('ueditor.imageAllowFiles'); 
				$cfg['savePath']=C('ueditor.imageSavePath'); 
				$result = $this->_upload($cfg);
				break;
			case 'catch'://远程图片抓取
				$cfg['maxSize']=C('ueditor.catcherMaxSize');
				$cfg['name']=C('ueditor.catcherFieldName'); 
				$cfg['ext']=C('ueditor.catcherAllowFiles'); 
				$cfg['savePath']=C('ueditor.catchSavePath'); 
				$result = $this->_saveRemote($cfg);
				break;
			case 'listfile'://文件列表
				$cfg['ext']=C('ueditor.fileManagerAllowFiles'); 
				$result = $this->_listFile($cfg);
				break;
			case 'listimage'://图片列表
				$cfg['ext']=C('ueditor.imageManagerAllowFiles'); 
				$result = $this->_listFile($cfg);
				break;
			default: //默认
				$result = json_encode(array(
		            'state'=> '请求错误'
		        ));
		        break;
		}
		if (isset($_GET["callback"])) {
			if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
				echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
			} else {
				echo json_encode(array(
		            'state'=> 'callback参数不合法'
		       ));
			}
		} else {
			echo $result;
		}
		
	}
	/**
     * @cc 上传方法
     */
	private function _upload($cfg){
		import('Org.Vin.FileStorage');
		$vin=new \FileStorage();
		//上传参数配置
		$config=array(
			'maxSize'    =>    $cfg['maxSize'],
			'exts'		 =>    explode(".",$cfg['ext']),
			'rootPath'   =>    '.'.C('rootPath'),
			'savePath'   =>    $cfg['savePath'].'/',
			'saveName'   =>   array('uniqid',''), 
			'autoSub'    =>   true,
			'subName'    =>   array('date','Y'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		$info=$upload->uploadOne($_FILES[$cfg['name']]);
		if(!$info) {
			$data = array(
				"state"=>$upload -> getError(),
			);
			$this->ajaxReturn($data);exit; // 上传错误提示错误信息
		}else{
			$fileurl='/d/'.$info['savepath'].$info['savename'];// 上传成功 获取上传文件信息
			//保存数据库
			$arr['uid']=session('userinfo.uid');
			$arr['file_name']=$info['savename'];
			$arr['file_extension']=$info['ext'];
			$arr['file_size']=$info['size'];
			$arr['file_path']=$fileurl;
			$arr['time']=time();
			$arr['md5']=md5(time()); 
			if($this->_save($arr)){
				$data = array(
					'state'=>"SUCCESS",
					'title'=>$info['savename'],
					'url'=>$vin::getPath(C('rootPath'),$info['savepath'].$info['savename']),
					'original'=>$info['name'],
					'type'=>'.' . $info['ext'],
					'size'=>$info['size'],
				);
			}else{
				$data = array(
					"state"=>"保存附件失败",
				);
			}
			$this->ajaxReturn($data);exit;    	
		}
	}
	/**
	 * 
	 * 上传涂鸦
	 */
	private function _uploadBase64($cfg){
		import('Org.Vin.FileStorage');
		$vin=new \FileStorage();
		$data = array();
		$base64Data = $_POST[$cfg['name']];
        $img = base64_decode($base64Data);
        if(strlen($img)>$cfg['maxSize']){
        	$data['states'] = '涂鸦太大，无法上传';
        	return json_encode($data);
        }
		$rootPath=C('rootPath');
        //替换随机字符串 
        $imgname = uniqid().'.png';
        $filename = $cfg['savePath'].'/'.$imgname;
        if($vin::put($rootPath,$filename,$img)){
			$arr['uid']=session('userinfo.uid');
			$arr['file_name']=$imgname;
			$arr['file_extension']='png';
			$arr['file_size']=strlen($img);
			$arr['file_path']=$vin::getPath($rootPath,$filename);
			$arr['time']=time();
			$arr['md5']=md5(time());
			if($this->_save($arr)){
				$data=array(
					'state'=>'SUCCESS',
					'url'=>$vin::getPath($rootPath,$filename),
					'title'=>$imgname,
					'original'=>'scrawl.png',
					'type'=>'.png',
					'size'=>strlen($img),
				);
			}
        }else{
        	$data=array(
        		'state'=>'生成涂鸦失败',
        	);
        }
        return json_encode($data);
	}
	/**
	 * 
	 * 抓取远程图片
	 */
	private function _saveRemote($cfg){
		import('Org.Vin.FileStorage');
		$vin=new \FileStorage();
		$list = array();
		if (isset($_POST[$fieldName])) {
		    $source = $_POST[$cfg['name']];
		} else {
		    $source = $_GET[$cfg['name']];
		}
		foreach ($source as $imgUrl) {
		    $upload = new \Think\Upload();
			$imgUrl = htmlspecialchars($imgUrl);
	        $imgUrl = str_replace("&amp;", "&", $imgUrl);
	
	        //http开头验证
	        if (strpos($imgUrl, "http") !== 0) {
	            $data = array('state'=>'不是http链接');
	            return json_encode($data);
	        }
	        //格式验证(扩展名验证和Content-Type验证)
	        $fileType = strtolower(strrchr($imgUrl, '.'));
	        if (!in_array($fileType, explode(",",$cfg['ext'])) || stristr($heads['Content-Type'], "image")) {
	            $data = array("state"=>"错误文件格式");
	            return json_encode($data);
	        }
	         //打开输出缓冲区并获取远程图片
	        ob_start();
	        $context = stream_context_create(
	            array('http' => array(
	                'follow_location' => false // don't follow redirects
	            ))
	        );
	        readfile($imgUrl, false, $context);
	        $img = ob_get_contents();
	        ob_end_clean();
	        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);
	        
			if(strlen($img)>$cfg['maxSize']){
	        	$data['states'] = '图片太大，无法获取';
	        	return json_encode($data);
	        }
	        $rootpath = C('rootPath');
	        $imgname = uniqid().'.png';
	        $filename = $cfg['savePath'].'/'.$imgname;
	        $oriName = $m ? $m[1]:""; 
        	if($vin::put($rootpath,$filename,$img)){
			    array_push($list, array(
			        "state" => 'SUCCESS',
			        "url" => $vin::getPath($rootpath,$filename),
			        "size" => strlen($img),
			        "title" => $imgname,
			        "original" => $oriName,
			        "source" => htmlspecialchars($imgUrl)
			    ));
        	}else{
        		array_push($list,array('state'=>'文件写入失败'));
        	}
		}
		/* 返回抓取数据 */
		return json_encode(array(
		    'state'=> count($list) ? 'SUCCESS':'ERROR',
		    'list'=> $list
		));
	}
	/**
	 * 
	 * 保存附件到数据库
	 */
	private function _save($data){
		return M('attachment')->add($data);
	}
	/**
	 * 列出文件夹下所有文件，如果是目录则向下   百度编辑器
	 */
	private function _listFile($cfg){ 
		$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : 1000;
		$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
		$end = $start + $size;
		$ext=implode('|',$this->format_exts($cfg['ext']));
		$root=explode('/',C('rootPath'));
		$path = $_SERVER[DOCUMENT_ROOT] . DIRECTORY_SEPARATOR . $root[1];
		$files = getfiles($path, $ext, $key);
		if (!count($files)) {
		    return json_encode(array(
		        "state" => "没有文件",
		        "list" => array(),
		        "start" => $start,
		        "total" => count($files)
		    ));
		}
		/* 获取指定范围的列表 */
		$len = count($files);
		for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
		    $list[] = $files[$i];
		}
		//倒序
		//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
		//    $list[] = $files[$i];
		//}
		
		/* 返回数据 */
		$result = json_encode(array(
		    "state" => "SUCCESS",
		    "list" => $list,
		    "start" => $start,
		    "total" => count($files)
		));
		
		echo $result;
	}
	/**
     * @cc 删除附件
     */ 
	public function del(){
		$result = 0;
        $url = $_SERVER[DOCUMENT_ROOT] . $_GET['url'];
        $name = I('get.name')?I('get.name'):false;
        if(file_exists($url)){
            if(unlink($url)){ 
				$result = 1;
				$map=array('file_name'=>$name);
				M('Attachment')->where($map)->delete();
			}
        }
        echo $result;
        exit;
	}
	private function format_exts($exts){
		$data=array();
		foreach ($exts as $key => $value) {
			$data[]=ltrim($value,'.');
		}
		return $data;
	}
}