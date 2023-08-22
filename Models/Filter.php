<?php
namespace Models;

use Core\Model;

class Filter extends Model {
    public function getFilters($filters) {
        $brand = new Brand();
        $product = new Product();
    
        $data = [
            'brands' => $brand->getListBrands(),
            'productsByBrands' => $product->getTotalProductsByBrands($filters),
            // 'totalProductsByBrands' => $product->getTotalProducts($filters),
            'ratings' => $product->getTotalRatingsByStars($filters),
            'ratingsByStars' => [
                0 => 0, // 0 Estrelas
                1 => 0, // 1 Estrela
                2 => 0, // 2 Estrelas
                3 => 0, // 3 Estrelas
                4 => 0, // 4 Estrelas
                5 => 0  // 5 Estrelas
            ],
            // 'totalRatingsByStars' => count($product->getListRatingsByStars($filters)),
            'totalProductsInPromotion' => $product->getPromotionCount($filters),
            'rangePrice0' => 0,
            'rangePrice1' => 0,
            'maxFilterPrice' => $product->getMaxPrice(),
            'options' => $product->getAvailableOptions($filters),
            'searchTerms' => (!empty($filters['searchTerm'])? $filters['searchTerm'] : '')
        ];

        // basicamente ele faz um loop em cada marca e adiciona uma propriedade ao array data no item 'brands', chamada de 'count', que recebe como valor o 'total_products_by_brand', calculando a quantidade de produtos por marca)
        foreach ($data['brands'] as $key => $brand) {
            $data['brands'][$key]['count'] = 0; // para evitar erros quando uma marca n tiver produtos

            foreach ($data['productsByBrands'] as $productsByBrand) {
                if($productsByBrand['brand_id'] == $brand['id']) {
                    $data['brands'][$key]['count'] = $productsByBrand['total_products_by_brand'];
                }
            }

            // if ($data['brands'][$key]['count'] == 0) {
            //     unset($data['brands'][$key]);
            // }
        }

        // Criando um filtro para as avaliações
        foreach ($data['ratingsByStars'] as $key => $item) {
            $currentStarRange = $key + 1;
            $totalRatingsByStar = 0;

            foreach ($data['ratings'] as $rating) {

                if($rating['rating'] == 0 && $key == 0) {
                    $totalRatingsByStar = $rating['total_ratings_by_star'];

                } else if($rating['rating'] > 0 && $rating['rating'] < 2 && $key == 1) {
                    $totalRatingsByStar += $rating['total_ratings_by_star'];
                    
                } else if($rating['rating'] >= $key && $rating['rating'] < $currentStarRange && $key > 1) {
                    $totalRatingsByStar += $rating['total_ratings_by_star'];                    
                }
                
                $data['ratingsByStars'][$key] = $totalRatingsByStar;
            }            
        }
        
        if(isset($filters['rangePrice0'])) {
            $data['rangePrice0'] = $filters['rangePrice0'];
        }

        if(isset($filters['rangePrice1'])) {
            $data['rangePrice1'] = $filters['rangePrice1'];
        }

        if (empty($data['rangePrice1'])) {
            $data['rangePrice1'] = $data['maxFilterPrice'];
        }

        return $data;
    }
} 