<?php

namespace BWB\Framework\mvc\dao;

use BWB\Framework\mvc\DAO;

class DAOMatch extends DAO {

    public function create($array) {
        
    }

    public function delete($id) {
        
    }

    public function getAll() {
        
    }

    public function getAllBy($filter) {
        
    }

    public function retrieve($id) {
        
    }

    public function update($array) {
        
    }

    public function get_match($id, $type, $table) {
        return $result = $this->getPdo()->query("SELECT " . $table . "_user_id, offre_id FROM matching WHERE " . $type . "_user_id=" . $id)->fetchAll();
    }

    public function check_if_match_candidat($candidat_id, $offre_id) {

        $sql = "SELECT * FROM candidat_liked_offre WHERE candidat_user_id = " . $candidat_id . " AND offre_id = " . $offre_id;
        $result = $this->getPdo()->query($sql)->fetch();
        var_dump($result);

        if ($result !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function check_if_match_entreprise($candidat_id, $entreprise_id) {

        $sql = "SELECT * FROM entreprise_liked_candidat WHERE candidat_user_id =".$candidat_id . " AND entreprise_user_id =".$entreprise_id;
        $result = $this->getPdo()->query($sql);
        $donnees = $result->fetch();
        if (!$donnees) {
            return false;
        } else {
            return true;
        }
    }

    public function new_match($entreprise_id, $candidat_id, $offre_id) {
        $sql = "INSERT INTO matching(candidat_user_id, entreprise_user_id, offre_id) "
                . "VALUES (" . $candidat_id . "," . $entreprise_id . "," . $offre_id . ")";
        var_dump($this->getPdo()->exec($sql));

        $sql2 = "INSERT INTO `candidat_and_entreprise_chat`(`candidat_user_id`, `entreprise_user_id`)"
                . " VALUES (" . $candidat_id . "," . $entreprise_id . ")";
        $this->getPdo()->exec($sql2);
    }

}
