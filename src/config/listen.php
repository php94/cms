<?php

use App\Php94\Admin\Model\Menu;
use App\Php94\Cms\Field\Boolean;
use App\Php94\Cms\Field\Checkbox;
use App\Php94\Cms\Field\Code;
use App\Php94\Cms\Field\Date;
use App\Php94\Cms\Field\Datetime;
use App\Php94\Cms\Field\Files;
use App\Php94\Cms\Field\Markdown;
use App\Php94\Cms\Field\Number;
use App\Php94\Cms\Field\Parents;
use App\Php94\Cms\Field\Pic;
use App\Php94\Cms\Field\Pics;
use App\Php94\Cms\Field\Select;
use App\Php94\Cms\Field\Text;
use App\Php94\Cms\Field\Textarea;
use App\Php94\Cms\Field\Time;
use App\Php94\Cms\Field\WYSIWYG;
use App\Php94\Cms\Model\BaseModel;
use App\Php94\Cms\Model\FieldProvider;
use App\Php94\Cms\Model\ModelProvider;
use App\Php94\Cms\Http\Content\Index as ContentIndex;
use App\Php94\Cms\Http\Model\Index as ModelIndex;

return [
    Menu::class => function (
        Menu $menu
    ) {
        $menu->addMenu('模型管理', ModelIndex::class);
        $menu->addMenu('内容管理', ContentIndex::class);
    },
    ModelProvider::class => function (
        ModelProvider $modelProvider
    ) {
        $modelProvider->register(BaseModel::class);
    },
    FieldProvider::class => function (
        FieldProvider $fieldProvider
    ) {
        $fieldProvider->register(Text::class);
        $fieldProvider->register(Textarea::class);
        $fieldProvider->register(Datetime::class);
        $fieldProvider->register(Date::class);
        $fieldProvider->register(Time::class);
        $fieldProvider->register(Number::class);
        $fieldProvider->register(Boolean::class);
        $fieldProvider->register(Code::class);
        $fieldProvider->register(WYSIWYG::class);
        $fieldProvider->register(Markdown::class);
        $fieldProvider->register(Pic::class);
        $fieldProvider->register(Pics::class);
        $fieldProvider->register(Files::class);
        $fieldProvider->register(Select::class);
        $fieldProvider->register(Checkbox::class);
        $fieldProvider->register(Parents::class);
    }
];
