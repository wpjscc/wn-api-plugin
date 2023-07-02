<?php namespace Wpjscc\Api\Controllers;

use BackendMenu;
//use Backend\Classes\Controller;
use Wpjscc\Api\Classes\Controller;
use Wpjscc\Api\Classes\ApiController;

/**
 * Tests Backend Controller
 */
class TestsApi extends Controller
{
     //黑名单
    protected $guarded = [
        
    ];
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Wpjscc\Api\Behaviors\FormController::class,
        \Wpjscc\Api\Behaviors\ListController::class,
        // \Wpjscc\Api\Behaviors\RelationController::class,
    ];

    
    /**
    * 
    * 1 list 的外部 可执行方法为 index，index_onDelete
    * 
    * 2 form 的外部 可执行方法为 create,update,preview 
    * create_onSave,create_onRelationRender,
    * update_onSave,update_onDelete,update_onRelationRender
    * preview,preview_onSave,preview_onRelationRender
    * 
    *  不可访问 index ,create,update,preview
    *   protected $guarded = [
    *       'index','create','update','preview'
    *   ];
    *  不可访问 下方方法
    * create_onSave,create_onRelationRender,
    * update_onSave,update_onDelete,update_onRelationRender
    * preview,preview_onSave,preview_onRelationRender
    * 
    * 设置一个空方法
    */

    public function __construct()
    {
        parent::__construct();


    }
    


    //自定义处理器
    public function index_onTest()
    {
        $this->putUserPreference('abc', 'def');
        $this->putUserPreference('a', 'b');
        $this->putUserPreference('c', 'd');
        $this->clearUserPreference('abc');
        
        return $this->success([
            'ajax' => 'true',
            'a' => $this->getUserPreference('a'),
            'c' => $this->getUserPreference('c'),
            'abc' => $this->getUserPreference('abc'),
            'getUserPreferences' => $this->getUserPreferences()
        ]);
    }

    public function index()
    {
        return $this->asExtension('ListController')->index();
    }

    public function index_onDelete()
    {
        return $this->asExtension('ListController')->index_onDelete();
    }

    public function create($context = null)
    {
        return $this->asExtension('FormController')->create($context);
    }

    public function create_onSave($context = null)
    {
        return $this->asExtension('FormController')->create_onSave($context);
    }

    public function create_onRelationRender()
    {
        if (!$this->isClassExtendedWith(\Wpjscc\Api\Behaviors\RelationController::class)) {
            throw new \Wpjscc\Api\Exceptions\NotFoundHttpException('RelationController behavior not found on controller.');
        }
        return $this->asExtension('FormController')->create_onRelationRender();
    }

    public function update($recordId = null, $context = null)
    {
        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function update_onSave($recordId = null, $context = null)
    {
        return $this->asExtension('FormController')->update_onSave($recordId, $context);
    }

    public function update_onDelete($recordId = null)
    {
        return $this->asExtension('FormController')->update_onDelete($recordId);
    }

    public function update_onRelationRender($recordId = null)
    {
        if (!$this->isClassExtendedWith(\Wpjscc\Api\Behaviors\RelationController::class)) {
            throw new \Wpjscc\Api\Exceptions\NotFoundHttpException('RelationController behavior not found on controller.');
        }

        return $this->asExtension('FormController')->update_onRelationRender($recordId);
    }

    public function preview($recordId = null, $context = null)
    {
        return $this->asExtension('FormController')->preview($recordId, $context);
    }

    public function preview_onSave($recordId = null, $context = null)
    {
        return $this->asExtension('FormController')->preview_onSave($recordId, $context);
    }

    public function preview_onRelationRender($recordId = null)
    {
        if (!$this->isClassExtendedWith(\Wpjscc\Api\Behaviors\RelationController::class)) {
            throw new \Wpjscc\Api\Exceptions\NotFoundHttpException('RelationController behavior not found on controller.');
        }
        return $this->asExtension('FormController')->preview_onRelationRender($recordId);
    }

}
