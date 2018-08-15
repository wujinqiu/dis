<?php
namespace app\index\controller;
use app\index\model\User;
use think\Controller;
use think\captcha\Captcha;

class Index extends Controller
{
    /**
     * 注册页
     * @return mixed|void
     */
    public function register()
    {
        if(request()->isPost()){
            $postData=input('post.');
            if(!captcha_check($postData['verifycode'])){
                return $this->error('验证码校验失败!');
            }
            if(!$this->checkPassword($postData)){
                return $this->error('密码校验失败!');
            }
            $user=new User();
            $user->name=$postData['username'];
            $user->email=$postData['email'];
            $user->password=md5(md5($postData['password']));
            $user->created_at=intval(microtime(true));
            $user->save();

            return $this->success('恭喜！注册成功！');
        }
        echo $this->fetch();
    }


    public function login(){
        if(request()->isPost()){
            $login=input('post.login');
            $password=input('post.password');
            $cond=[];
            $cond['name|email']=$login;
            $cond['password']=md5(md5($password));
            $user=User::get($cond);
            if($user){
                session('user',$user);
                return $this->success('恭喜，登陆成功！');
            }
            return $this->error('抱歉，登录失败~');
        }
        $this->assign('user',session('user'));
        echo $this->fetch();
    }

    public function logout(){
        session('user',null);
//        echo $this->fetch('login',['user'=>session('user')]);
        $this->redirect('index/index/login');
    }

    private function checkPassword($data){
        if(!$data['password']){
            return false;
        }
        if($data['password']!=$data['password_confirmation']){
            return false;
        }
        return true;
    }

    /**
     * 验证码
     * @return \think\Response
     */
    public function verify()
    {
        //验证码配置
        $config =    [
            // 验证码字符集合
            'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
            // 验证码字体大小(px)
            'fontSize' => 16,
            // 是否画混淆曲线
            'useCurve' => true,
            'useNoise' => false,
            // 验证码图片高度
            'imageH'   => 35,
            // 验证码图片宽度
            'imageW'   => 150,
            // 验证码位数
            'length'   => 4,
            // 验证成功后是否重置
            'reset'    => true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }
}
