<?php
namespace Models;

use Core\Model;

class Category extends Model {
    public function getListCategories() {
        $data = [];
        $stm = $this->db->query('SELECT * FROM categories ORDER BY super_category DESC');
        
        if ($stm->rowCount() > 0) {
            foreach ($stm->fetchAll(\PDO::FETCH_ASSOC) as $item) {
                $item['subs_category'] = [];
                $data[$item['id']] = $item;
            }

            while ($this->stillNeed($data)) {
                $this->organizeCategory($data);
            }
        }

        return $data;
    }

    public function getCategoryName($id) {
        $stm = $this->db->prepare('SELECT name FROM categories WHERE id = :id_categorie');
        $stm->bindValue(':id_categorie', $id);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $data = $stm->fetch(\PDO::FETCH_ASSOC);
            return $data['name'];
        }
    }

    //  pega a arvore de categorias
    public function getCategoryTree($id) {
        $data = [];

        $haveSuperCategory = true;

        if (!empty($id) ) {
            while($haveSuperCategory) {
                $stm = $this->db->prepare('SELECT * FROM categories WHERE id = :id_category');
                $stm->bindValue(':id_category', $id);
                $stm->execute();

                if ($stm->rowCount() > 0) {
                    $stm = $stm->fetch(\PDO::FETCH_ASSOC);
                    $data[] = $stm;
                    
                    if (!empty($stm['super_category'])) {
                        $id = $stm['super_category'];
                    } else {
                        $haveSuperCategory = false;
                    }
                }
            }
        } else {

        }
        $data = array_reverse($data);
        
        return $data;
    }

    // Verifica se um item precisa ser reorganizado
    private function stillNeed($data) {
        foreach ($data as $item) {
            if (!empty($item['super_category'])) {
                return true;
            }
        }

        return false;
    }

    // Organiza as categorias 
    // O "&" serve para ligar qualquer alteração feita no param "$data" a variavel principal
    private function organizeCategory(&$data) {
        foreach ($data as $id => $item) {
            // Por enquanto vou deixar assim, modificar no futuro. Define a sub_category correta a determinata super_category, $data é uma matriz onde o primeiro indice é a super categoria e o segundo indice está dentro de subs_category
            if (!empty($data[$item['super_category']])) {
                $data[$item['super_category']]['subs_category'][$item['id']] = $item;
                unset($data[$id]);
                break;
            }
        }
    }
}