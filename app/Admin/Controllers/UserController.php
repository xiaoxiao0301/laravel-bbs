<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class UserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new User(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name')->width('160px');
            $grid->column('email')->copyable();
            $grid->column('email_verified_at', '邮箱验证')
                ->display(function($emailVerifiedAt) {return $emailVerifiedAt == '' ? '否' : '是';})
                ->width('100px')
                ->help("用户是否已经验证过邮箱", 'purple', 'right')
                ->label(Admin::color()->orange());
//            $grid->column('password');
//            $grid->column('remember_token');
//            $grid->column('avatar')->display(function($avatar) {
//                return "<image src='". $avatar ."'width=52px height=52px";
//            });
            $grid->column('avatar')->image();
            $grid->column('introduction');
            $grid->column('notification_count');
            $grid->column('last_actived_at')->sortable()->width('160px');
            $grid->column('created_at')->width('160px');
            $grid->column('updated_at')->sortable()->width('160px');

            // 表格简单搜索框
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id'); // == 匹配筛选
                $filter->like('name'); // like 匹配
                $filter->like('email');
            });

            // 开启弹窗创建表单
            $grid->enableDialogCreate();
            // 设置弹窗宽高，默认 700px，670px
            $grid->setDialogFormDimensions("60%", "70%");

            // 开启字段选择器功能, 控制页面上显示的字段
            $grid->showColumnSelector();
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new User(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('email');
            $show->field('email_verified_at');
            $show->field('password');
            $show->field('remember_token');
            $show->field('avatar');
            $show->field('introduction');
            $show->field('notification_count');
            $show->field('last_actived_at');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new User(), function (Form $form) {

            // 新增页面和保存数据
            if ($form->isCreating()) {
                $form->text('name')->rules('required|min:4');
                $form->text('email')->rules('required|string|max:255|unique:users');
                $form->text('introduction');
                $form->password('password')->rules('required|min:6');
                $form->image('avatar')->rules('required') ->accept('jpg,png,gif,jpeg')
                    ->move('images/avatars')->uniqueName()->saveFullUrl();
            }

            // 编辑页面和保存编辑数据
            if ($form->isEditing()) {
                $form->display('id');
                $form->text('name')->rules('required|min:4');
                $form->text('email')->rules('required|email');
                $form->password('password')->rules('required|min:6');
                $form->text('introduction');
                $form->image('avatar')->rules('required') ->accept('jpg,png,gif,jpeg')
                    ->move('images/avatars')->uniqueName()->saveFullUrl();
                // 隐藏列表按钮
                $form->disableListButton(false);
                $form->disableViewButton(false);
                $form->disableDeleteButton(false);
                // 去掉整个工具栏内容
                $form->disableHeader(false);

                // 底部继续编辑按钮
                $form->disableEditingCheck();
                // 底部查看
                $form->disableViewCheck();
            }

            // 删除
            if ($form->isDeleting()) {

            }

        });
    }
}
