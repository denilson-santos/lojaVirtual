<?php
namespace Controllers;

use Core\Controller;
use Models\Product;
use Models\Category;

class CategoryController extends Controller {  
    public function index() {
        header('Location: '.BASE_URL);
    }

    public function enter($id) {
        $data = [];

        $category = new Category();
        $product = new Product();

        $currentPage = 1;
        $offset = 0;
        $limit = 3;

        if (!empty($_GET['p'])) {
            $currentPage = $_GET['p'];
        }

        $offset = ($currentPage * $limit) - $limit;

        if (!empty($category->getCategoryName($id))) {
            $filters = ['category' => $id];

            $data = [
                'categoryName' => $category->getCategoryName($id),
                'categories' => $category->getListCategories(),
                'categoryFilter' => $category->getCategoryTree($id),
                'products' => $product->getListProducts($offset, $limit, $filters),
                'totalItens' => $product->getTotalProducts($filters),
                'numberPages' => ceil($product->getTotalProducts($filters) / $limit),
                'currentPage' => $currentPage,
                'categoryId' => $id
            ];

            $this->loadTemplate('category', $data);
        } else {
            header('Location: '.BASE_URL);
        }
    }

} 
