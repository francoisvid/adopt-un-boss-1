<?php

namespace BWB\Framework\mvc\controllers;

use BWB\Framework\mvc\Controller;
use BWB\Framework\mvc\dao\DAOContrat;
use BWB\Framework\mvc\dao\DAOEntreprise;
use BWB\Framework\mvc\dao\DAOMatch;
use BWB\Framework\mvc\dao\DAOOffre;
use BWB\Framework\mvc\dao\DAOTechno;
use BWB\Framework\mvc\SecurityMiddleware;

/**
 * Description of OffreController
 *
 * @author ilies
 */
class OffreController extends Controller {

    private $dao_offre;
    private $dao_match;
    private $dao_entreprise;
    private $dao_techno;
    private $dao_contrat;
    private $security_middleware;
    private $security_controller;

    function __construct() {
        parent::__construct();
        $this->dao_offre = new DAOOffre();
        $this->dao_match = new DAOMatch();
        $this->dao_entreprise = new DAOEntreprise();
        $this->dao_techno = new DAOTechno();
        $this->dao_contrat = new DAOContrat();

        $this->security_middleware = new SecurityMiddleware();
        $this->security_controller = new SecurityController();
    }

    public function get_offres() {

        $offres = $this->dao_offre->retrieve_all_validated();
        $technos = $this->dao_techno->getAll();
        $contrats = $this->dao_contrat->getAll();
        $this->render("offres", array(
            "offres" => $offres,
            "technos" => $technos,
            "contrats" => $contrats,
            "caca" => false
        ));
    }

    public function get_offres_tri($arg) {

        $check = $this->dao_offre->check_argument($arg);

        if ($check) {
            $offres = $this->dao_offre->get_offre_by_techno($arg);
        } else {
            $offres = $this->dao_offre->get_offre_by_contrat($arg);
        }
        $technos = $this->dao_techno->getAll();
        $contrats = $this->dao_contrat->getAll();
        $this->render("offres", array(
            "offres" => $offres,
            "technos" => $technos,
            "contrats" => $contrats
        ));
    }

    public function get_offre($id) {
        if ($this->dao_offre->check_offre_statut_by_id($id)):
            $this->affichage_offre($id);
        else:
            header("Location: http://adopt-un-boss.bwb/offres");
        endif;
    }

    protected function affichage_offre($id) {
        
        $offre = $this->dao_offre->retrieve_current_offre($id);
        $idEntreprise = $this->dao_offre->get_entreprise_id_from_offre_id($id);
        $entreprise = $this->dao_entreprise->getEntrepriseInfos($idEntreprise);
        $technos = $this->dao_techno->getAll();
        $secteur = $this->dao_entreprise->get_entreprise_secteur_from_entreprise_id($idEntreprise);
        $otherOffres = $this->dao_offre->retrieve_all_validated_from_entreprise_id($idEntreprise, $id);
        $usersLiking = $this->dao_offre->check_who_is_liking($id);
                if (isset($_COOKIE['tkn'])):
            $id_user = $this->get_id();
            $permission = $this->get_role();
            $bool = $this->dao_offre->check_if_already_liked($id_user, $id);
            $this->render("offre", array(
            "offre" => $offre,
            "usersLiking" => $usersLiking,
            "permission" => $permission,
            "entreprise" => $entreprise,
            "technos" => $technos,
            "secteur" => $secteur,
            "otherOffres" => $otherOffres,
            "id_user" => $id_user,
            "bool" => $bool));
        else:
            $this->render("offre", array(
            "offre" => $offre,
            "usersLiking" => $usersLiking,
            "entreprise" => $entreprise,
            "technos" => $technos,
            "secteur" => $secteur,
            "otherOffres" => $otherOffres,
            "bool" => false));
        endif;
    }

    public function get_id() {
        return $this->security_middleware->verifyToken($_COOKIE['tkn'])->id;
    }

    public function get_role() {
        return $this->security_middleware->verifyToken($_COOKIE['tkn'])->role;
    }

    public function like($id_offre) {
        $id_user = $this->get_id();
        $id_boite = $this->dao_offre->get_entreprise_id_from_offre_id($id_offre);
        $this->dao_offre->like_offre($id_user, $id_offre);
        var_dump($this->dao_match->check_if_match_entreprise($id_user, $id_boite));
        if($this->dao_match->check_if_match_entreprise($id_user, $id_boite)){
            $this->dao_match->new_match($id_boite, $id_user, $id_offre);
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function post_new_offre() {
        $id = $this->get_id();
        $technos = $this->inputPost()["techname"];
        var_dump($technos);


        $this->dao_offre->create_new_offre($id, $_POST['intitule'], $_POST['poste'], $_POST['lieu'], $_POST['salaire'], $_POST['detail'], $_POST['type_de_contrat']);

        foreach ($technos as $key => $value) {
            echo $key;
            $this->dao_offre->add_technos_to_offre($key);
        }
    }

}
