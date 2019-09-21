<?php 
namespace Controllers;

use Core\Controller;
use Models\Category;
use Models\Filter;
use Models\Product;

class SearchController extends Controller {
    public function index() {
        $product = new Product();
        $category = new Category();
        $filter = new Filter();
        
        $data = [];
        $filters = [];
        $currentPage = 1;
        $offset = 0;
        $limit = 6;

        if (!empty($_GET['term'])) {
            $searchTerm = $_GET['term'];
            $categorySearch = $_GET['category'];

            if (!empty($_GET['filters']) && is_array($_GET['filters'])) {
                $filters = $_GET['filters'];
            }
            
            $filters['searchTerm'] = $searchTerm;
            $filters['category'] = $categorySearch;

            if (!empty($_GET['p'])) {
                $currentPage = $_GET['p'];
            }
                
            $offset = ($currentPage * $limit) - $limit;

            $data = [
                'products' => $product->getListProducts($offset, $limit, $filters),
                'totalItens' => $product->getTotalProducts($filters),
                'numberPages' => ceil($product->getTotalProducts($filters) / $limit),
                'currentPage' => $currentPage,
                'categories' => $category->getListCategories(),
                'filters' => $filter->getFilters($filters),
                'filtersSelected' => $filters,
                'searchTerm' => $searchTerm,
                'category' => (!empty($categorySearch) ? $categorySearch : ''),
                'categoryFilter' => (!empty($categorySearch) ? $category->getCategoryTree($categorySearch) : '')
            ];

            $this->loadTemplate('search', $data);
        } else {
            header('location: '.BASE_URL);
        }
    }
}
