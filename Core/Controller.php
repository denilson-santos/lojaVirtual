<?php 
namespace Core;

use Config\Config;
use Core\Language;

class Controller {
    protected $language;

    public function __construct() {
        // $config = new Config();
        $this->language = new Language();
    }

    public function loadView($viewName, $viewData = []) {
        extract($viewData);
        require "Views/".$viewName.".php";
    }

    public function loadTemplate($viewName, $viewData = []) {
        require "Views/template.php";
    }

    public function loadViewInTemplate($viewName, $viewData = []) {
        extract($viewData);
        require "Views/".$viewName.".php";
    }
}