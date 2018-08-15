<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Tag as TagModel;

class Topic extends Controller
{
   public function newTopic(){
       $user=session('user');
       $category=[
           'imooc'=>[
               1=>'站务与公告',
               2=>'反馈',
               3=>'使用指南',
           ],
           'Mobile'=>[
               8=>'Android',
           ],
           'LifeStyle'=>[
               10=>'意欲蔓延'
           ],
           'Technology'=>[
               5=>'程序员',
               6=>'分享与创造',
           ]
       ];
       $tags=TagModel::all();
       $tags=isset($tags)?$tags:[];

       $this->assign('user',$user);
       $this->assign('category',$category);
       $this->assign('tags',$tags);
       echo $this->fetch();
   }
}
