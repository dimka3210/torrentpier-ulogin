<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Router\RouteMatch;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		return new ViewModel();
    }
	
	public function topicAction()
    {
		return new ViewModel();
    }
	
	public function forumsAction()
    {
        return new ViewModel();
    }
	
	public function categoryAction()
    {
        return new ViewModel();
    }
	
	public function usersAction()
    {
        return new ViewModel();
    }	
	
	public function settingsAction()
    {
        return new ViewModel();
    }
	
	public function dlAction()
    {
        return new ViewModel();
    }
	
	public function trackerAction()
    {
        return new ViewModel();
    }
	
	
	
}
