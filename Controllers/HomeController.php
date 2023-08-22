<?php
namespace Controllers;

use Core\Controller;
use Models\Product;
use Models\Category;
use Models\Filter;

class HomeController extends Controller {  
    public function index() {
        $product = new Product();
        $category = new Category();
        $filter = new Filter();
        
        $data = [];
        $filtersSelected = [];
        $currentPage = 1;
        $offset = 0;
        $limit = 9;

        if (isset($_GET['filters']) && is_array($_GET['filters'])) {
            $filtersSelected = $_GET['filters'];
        }

        if (isset($_GET['p'])) {
            $currentPage = $_GET['p'];
        }
            
        $offset = ($currentPage * $limit) - $limit;

        $data = [
            'products' => $product->getListProducts($offset, $limit, $filtersSelected),
            'totalItens' => $product->getTotalProducts($filtersSelected),
            'numberPages' => ceil($product->getTotalProducts($filtersSelected) / $limit),
            'currentPage' => $currentPage,
            'categories' => $category->getListCategories(),
            'filters' => $filter->getFilters($filtersSelected),
            'filtersSelected' => $filtersSelected,
            'sidebarWidgetsFeatured' => $product->getListProducts(0, 5, ['featured' => 1], true),
            'footerWidgetsFeatured' => $product->getListProducts(0, 3, ['featured' => 1], true),
            'widgetsPromotion' => $product->getListProducts(0, 3, ['promo' => 1], true),
            'widgetsTopRated' => $product->getListProducts(0, 3, ['top_rated' => 1]),

        ];

        $this->loadTemplate('home', $data);
    }

} 
