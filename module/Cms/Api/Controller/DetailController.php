<?php


namespace Module\Cms\Api\Controller;


use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Module\ModuleBaseController;
use Module\Cms\Type\CmsContentVerifyStatus;
use Module\Cms\Util\CmsCatUtil;
use Module\Cms\Util\CmsContentUtil;
use Module\Member\Auth\MemberUser;


class DetailController extends ModuleBaseController
{
    
    public function index()
    {
        $input = InputPackage::buildFromInput();
        $id = $input->getTrimString('id');
        if (is_numeric($id)) {
            $data = CmsContentUtil::get($id);
        } else {
            $data = CmsContentUtil::getByAlias($id);
        }
        $cat = CmsCatUtil::get($data['record']['catId']);
        $view = $cat['detailTemplate'];
        if (empty($view)) {
            $view = $data['model']['detailTemplate'];
        }
        $catChain = CmsCatUtil::chain($cat['id']);
        $catRoot = CmsCatUtil::root($cat['id']);
        $catRootChildren = CmsCatUtil::children($catRoot['id']);
        $viewData = [];
        $viewData['view'] = $view;
        $viewData['record'] = $data['record'];
        $viewData['cat'] = $cat;
        $viewData['catRoot'] = $catRoot;
        $viewData['catChain'] = $catChain;
        $viewData['catRootChildren'] = $catRootChildren;
        $viewData['model'] = $data['model'];
        if ($data['record']['verifyStatus'] != CmsContentVerifyStatus::VERIFY_PASS) {
            if (MemberUser::isNotMine($data['record']['memberUserId'])) {
                BizException::throws('记录未审核');
            }
        }
        return Response::generateSuccessData($viewData);
    }
}