<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Topic;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class TopicController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Topic(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title');
            $grid->column('body');
            $grid->column('user_id');
            $grid->column('category_id');
            $grid->column('reply_count');
            $grid->column('view_count');
            $grid->column('last_reply_user_id');
            $grid->column('order');
            $grid->column('excerpt');
            $grid->column('slug');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
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
        return Show::make($id, new Topic(), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $show->field('body');
            $show->field('user_id');
            $show->field('category_id');
            $show->field('reply_count');
            $show->field('view_count');
            $show->field('last_reply_user_id');
            $show->field('order');
            $show->field('excerpt');
            $show->field('slug');
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
        return Form::make(new Topic(), function (Form $form) {
            $form->display('id');
            $form->text('title');
            $form->text('body');
            $form->text('user_id');
            $form->text('category_id');
            $form->text('reply_count');
            $form->text('view_count');
            $form->text('last_reply_user_id');
            $form->text('order');
            $form->text('excerpt');
            $form->text('slug');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
