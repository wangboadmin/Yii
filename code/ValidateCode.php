<?php

/**
 * 使用说明
 * 控制器
 *    public function actioncode() {
        $_vc = new ValidateCode();  //实例化一个对象
        $_vc->setattr(100, 30, 4);
        $_vc->doimg();
        yii::app()->session['code'] = $_vc->getCode(); //验证码保存到SESSION中
    }
 * 试图   
 * <img  title="点击刷新" src="/login/code"  align="absbottom" onclick="this.src='/login/code?'+Math.random();"></img>
 * 
 * //字体加载要填好路径
 * ok
 * **/


//验证码类
class ValidateCode {

    private $charset = 'ABCDEFGHKMNPRSTUVWXYZ23456789'; //随机因子
    private $code; //验证码
    public $codelen; //验证码长度
    public $width; //宽度
    public $height; //高度
    private $img; //图形资源句柄
    private $font; //指定的字体
    private $fontsize = 16; //指定字体大小
    private $fontcolor; //指定字体颜色

    //构造方法初始化

    public function __construct() {
        $this->font = './../assets/font/elephant.ttf'; //注意字体路径要写对，否则显示不了图片
       
    }
    
    //wei-edit  set attr vale
    public  function setattr($width,$hight,$len){
         !empty($width) ? $this->width = $width :$this->width =130;
        !empty($hight) ? $this->height = $hight : $this->height =50;
        !empty($len) ? $this->codelen = $len : $this->codelen= 4;
    }


    //生成随机码
    private function createCode() {
        $_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->code .= $this->charset[mt_rand(0, $_len)];
        }
    }

    //生成背景
    private function createBg() {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    //生成文字
    private function createFont() {
        $_x = $this->width / $this->codelen;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
        }
    }

    //生成线条、雪花
    private function createLine() {
        //线条
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    //输出
    private function outPut() {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    //对外生成
    public function doimg() {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    //获取验证码
    public function getCode() {
        return strtolower($this->code);
    }

}
