<?php
namespace Models;

use Core\Model;

class Brand extends Model {
    public function getListBrands() {
        $data = [];

        $stm = $this->db->query('SELECT * FROM brands');

        if($stm->rowCount() > 0) {
            $data = $stm->fetchAll(\PDO::FETCH_ASSOC);
            return $data;
        }
        
        return $data;
    }

    public function getBrandNameById($id) {
        $data = [];

        $stm = $this->db->prepare('SELECT name FROM brands WHERE id = :brand_id');
        $stm->bindValue(':brand_id', $id);
        $stm->execute();

        if($stm->rowCount() > 0) {
            $data = $stm->fetch(\PDO::FETCH_ASSOC);
            return $data;
        }
        
        return $data;
    }
}