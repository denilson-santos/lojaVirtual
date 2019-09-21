<?php
namespace Models;

use Core\Model;

class Product extends Model {
    
    public function getListProducts($offset = 0, $limit = 3, $filters = [], $random = false) {
        $data = [];
        $orderByRandom = ''; 

        if ($random) {
            $orderByRandom = 'ORDER BY RAND()';
        }

        if (!empty($filters['top_rated']) && !$random) {
            $orderByRandom = 'ORDER BY rating DESC';
        }

        $where = $this->buildWhere($filters, 'none');     

        $stm = $this->db->prepare(
            'SELECT product.*, brand.name as brand_name FROM product
            JOIN brand ON brand_id = id_brand WHERE '.implode(" AND ", $where).' '.$orderByRandom.' LIMIT :offset, :limit');
        // print_r($stm); exit;
        $this->bindWhere($filters, $stm, 'none');
        $stm->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stm->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $key => $item) {
                $data[$key]['images'] = $this->getImagesByProductId($item['id_product']);
            }
        }

        return $data;
    }

    public function getTotalProductsByBrands($filters = []) {
        $data = [];
        $where = $this->buildWhere($filters, 'brand');

        $stm = $this->db->prepare(
            'SELECT brand_id, COUNT(*) AS total_products_by_brand FROM product WHERE '.implode(" AND ", $where).' GROUP BY brand_id');

        $this->bindWhere($filters, $stm, 'brand');
        $stm->execute();
            
        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }  

        return $data;      
    } 

    public function getTotalRatingsByStars($filters = []) {
        $data = [];
        $where = $this->buildWhere($filters, 'rating');

        $stm = $this->db->prepare(
            'SELECT rating, COUNT(*) AS total_ratings_by_star FROM product WHERE '.implode(" AND ", $where).' GROUP BY rating');
            
        $this->bindWhere($filters, $stm, 'rating');
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }  

        return $data;   
    }

    public function getPromotionCount($filters = []) {
        $where = $this->buildWhere($filters, 'promotion');
        $stm = $this->db->prepare(
            'SELECT COUNT(*) AS total_promotion FROM product WHERE '.implode(" AND ", $where));
        $this->bindWhere($filters, $stm, 'promotion');
        // print_r($stm); exit;
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetch(\PDO::FETCH_ASSOC);
        }  

        return $data['total_promotion'];  
    }

    public function getImagesByProductId($id) {
        $data = [];

        $stm = $this->db->prepare(
            'SELECT url FROM product_image WHERE product_id = :id_product');
        $stm->bindValue(':id_product', $id);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }  

        return $data;      
    }

    public function getAvailableOptions($filters = []) {
        $options = [];
        $optionsInfo = [];
        $idsProducts = [];

        $where = $this->buildWhere($filters, 'none');

        $stm = $this->db->prepare(
            'SELECT id_product, options FROM product WHERE '.implode(" AND ", $where));

        $this->bindWhere($filters, $stm, 'none');
        $stm->execute();
        
        if ($stm->rowCount() > 0) {
            $products = $stm->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($products as $product) {
                $optionsByProduct = explode(',', $product['options']);
                $idsProducts[] = $product['id_product'];

                foreach ($optionsByProduct as $option) {
                    if (!in_array($option, $options)) {
                        $options[] = $option;
                    }
                }
            }
        }

        $optionsInfo = $this->getAvailableInfoByOptions($options, $idsProducts);

        return $optionsInfo;
    }

    public function getAvailableInfoByOptions($options, $idsProducts) {
        $optionsInfo = [];
        $option = new Option();
        $where = ['1=1'];

        // params do in
        if (!empty($options)) {
            $inOptions = $this->buildIN($options, 'option');
            $inIdProducts = $this->buildIN($idsProducts, 'idProduct');

            $where[] = 'option_id IN('.$inOptions.') AND product_id IN('.$inIdProducts.')';
        }

        $stm = $this->db->prepare('SELECT value, option_id, COUNT(*) AS amount_by_value FROM product_option WHERE '.implode(' AND ', $where).' GROUP BY value ORDER BY option_id');

        // values para cada param do in
        if (!empty($options)) {
            $inOptions = $this->bindIN($options, 'option', $stm);
            $inIdProducts = $this->bindIN($idsProducts, 'idProduct', $stm);
        }
        
        $stm->execute();

        if($stm->rowCount() > 0) {
            $values = $stm->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($options as $op) {
                foreach ($values as $value) {
                    if ($value['option_id'] == $op) {
                        $optionsInfo[$op]['name'] = $option->getOptionName($op);
                        $optionsInfo[$op]['values'][] = [
                            'option_id' => $value['option_id'],
                            'name' => $value['value'],
                            'amount_by_value' => $value['amount_by_value']
                        ];
                    }
                }
            }
        }
        
        return $optionsInfo;
    }

    public function getTotalProducts($filters = []) {
        $where = $this->buildWhere($filters, '');

        $stm = $this->db->prepare(
            'SELECT COUNT(*) AS total_product FROM product WHERE '.implode(' AND ', $where));

        $this->bindWhere($filters, $stm, '');
        $stm->execute();
        
        $data = $stm->fetch(\PDO::FETCH_ASSOC);

        return $data['total_product'];
 
    }

    public function getMaxPrice() {
        $data = 0;

        $stm = $this->db->prepare(
            'SELECT MAX(promo_price) AS max_promo_price, 
            (SELECT MAX(price) FROM product WHERE promo = 0 ) 
            AS max_price FROM product');

        $stm->execute();
        
        $data = $stm->fetch(\PDO::FETCH_ASSOC);
        
        return max($data);
    }

    // Monta a clausula 'where' a partir dos filtros
    private function buildWhere($filters, $filtersRemoved) {
        $where = ['1=1'];
        $inParamsBrand = '';

        if (!empty($filters['category']) && $filtersRemoved != 'category') {
            $where[] = 'category_id = :id_category';
        }

        if (!empty($filters['brand']) && $filtersRemoved != 'brand') {
            $inParamsBrand = $this->buildIN($filters['brand'], 'brand');
            $where[] = 'brand_id IN('.$inParamsBrand.')';
        }

        if (!empty($filters['rating']) && $filtersRemoved != 'rating') {
            foreach ($filters['rating'] as $rating) {
                if($rating == 0) { 
                    $whereRating[] = 'rating = :rating0';
                } else if($rating > 0 && $rating < 2) {
                    $whereRating[] = 'rating > :rating0 AND rating < :rating2';
                } else if($rating >= 2  && $rating < 3) {
                    $whereRating[] = 'rating >= :rating2 AND rating < :rating3';
                } else if($rating >= 3  && $rating < 4) {
                    $whereRating[] = 'rating >= :rating3 AND rating < :rating4';
                } else if($rating >= 4  && $rating < 5) {
                    $whereRating[] = 'rating >= :rating4 AND rating < :rating5';
                } else if($rating == 5) {
                    $whereRating[] = 'rating = :rating5';
                }
            }

            $where[] = 'rating IN(SELECT rating FROM product WHERE '.implode(' OR ', $whereRating).')';
        }

        if (!empty($filters['promotion']) || $filtersRemoved == 'promotion') {
            $where[] = 'promo = :promo1';  
        } 

        if (!empty($filters['featured'])) {
            $where[] = 'featured = :featured';  
        } 

        if (!empty($filters['option']) && $filtersRemoved != 'option') {
            $inParams = $this->buildIN($filters['option'], 'option');

            $where[] = 'id_product IN(SELECT product_id FROM product_option WHERE product_option.value IN ('.$inParams.'))';
        }

        if(!empty($filters['searchTerm'])) {
            $where[] = 'product.name LIKE :searchTerm';
        }

        if (isset($filters['rangePrice0']) || isset($filters['rangePrice1'])) {
            if (!empty($filters['promotion']) || $filtersRemoved == 'promotion') {
                $where[] = 'promo_price BETWEEN :range_price0 AND :range_price1';  
            } else {
                $where[] = 'price BETWEEN :range_price0 AND :range_price1 AND :promo0 OR '.implode(' AND ', $where).' AND promo_price BETWEEN :range_price0 AND :range_price1 AND :promo1';
            }
        }

        return $where;
    }

    //  montagem dos parametros para cada item dentro da clausula do in, pois o preprare não suporta um array por params no in, é necessário definir o mesmo numero de params que o array tem
    private function buildIN($array, $prefix) {
        foreach ($array as $key => $value) { 
            if ($key == 0) {
                $inParams = ':'.$prefix.$key;
            } else {
                $inParams .= ', :'.$prefix.$key;
            }
        }

        return $inParams;        
    }

    // Adiciona ou não um bind depedendo da existencia de um filtro que foi adicionado na clausula where
    private function bindWhere($filters, &$stm, $filtersRemoved) {       
        if (!empty($filters['category']) && $filtersRemoved != 'category') {
            $stm->bindValue(':id_category', $filters['category']);
        }

        if(!empty($filters['brand']) && $filtersRemoved != 'brand') {
            $this->bindIN($filters['brand'], 'brand', $stm);
        }

        if (!empty($filters['rating']) && $filtersRemoved != 'rating') {
            foreach ($filters['rating'] as $rating) {
                if($rating == 0) { 
                    $stm->bindValue(':rating0', 0);  
                } else if($rating > 0 && $rating < 2) {
                    $stm->bindValue(':rating0', 0);  
                    $stm->bindValue(':rating2', 2);  
                } else if($rating >= 2  && $rating < 3) {
                    $stm->bindValue(':rating2', 2);  
                    $stm->bindValue(':rating3', 3);  
                } else if($rating >= 3  && $rating < 4) {
                    $stm->bindValue(':rating3', 3);  
                    $stm->bindValue(':rating4', 4);  
                } else if($rating >= 4  && $rating < 5) {
                    $stm->bindValue(':rating4', 4);  
                    $stm->bindValue(':rating5', 5);  
                } else if($rating == 5) {
                    $stm->bindValue(':rating5', 5);  
                }
            }
        }

        if (!empty($filters['promotion']) || $filtersRemoved == 'promotion') {
            $stm->bindValue(':promo1', 1);  
        }

        if (!empty($filters['featured'])) {
            $stm->bindValue(':featured', 1);  
        } 

        if(!empty($filters['option']) && $filtersRemoved != 'option') {
            $this->bindIN($filters['option'], 'option', $stm);
        }

        if(!empty($filters['searchTerm'])) {
            $stm->bindValue(':searchTerm', '%'.$filters['searchTerm'].'%');  
        }

        if (isset($filters['rangePrice0']) || isset($filters['rangePrice1'])) {
            $stm->bindValue(':range_price0', $filters['rangePrice0']);            
            $stm->bindValue(':range_price1', $filters['rangePrice1']);         
            
            if (!empty($filters['promotion']) || $filtersRemoved == 'promotion') {
              //    
            } else {
                $stm->bindValue(':promo0', 0);                
                $stm->bindValue(':promo1', 1);                
            }
        }
    }

    // bind para cada item dentro da clausula in
    private function bindIN($array, $prefix, $stm) {
        if (is_array($prefix)) {
            foreach ($prefix as $k => $p) {
                foreach ($array as $key => $value) {
                    $inParam = ':'.$p.$key;
                    $stm->bindValue($inParam, $value);
                }               
            }
        } else {
            foreach ($array as $key => $value) {
                $inParam = ':'.$prefix.$key;
                $stm->bindValue($inParam, $value);
            }
        }
    }
}