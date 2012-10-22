<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Filtering_model extends CI_Model{
	private $tables=array("presidentielle"=>"resultatspresidentielles","legislative"=>"resultatslegislatives","municipale"=>"resultatsmunicipales","regionale"=>"resultatsregionales","rurale"=>"resultatsrurales");
	private $tablesParticipation=array("presidentielle"=>"participationpresidentielles","legislative"=>"participationlegislatives","municipale"=>"participationmunicipales","regionale"=>"participationregionales","rurale"=>"participationrurales");

	function getCandidatsAnnee(){
		if(!empty($_GET["typeElection"])) $typeElection=$_GET["typeElection"];
		else return;
		
		$annees = NULL;
		$annees=$_GET["annees"];	// "2007,2012"

		if($annees != NULL){
			$arrayAnnees=explode(",",$annees);
		}

		if ( !empty($_GET['param']) AND !empty($_GET["annees"]) AND !empty($_GET["niveau"])) {
			
			$requete="SELECT rp.idCandidature, CONCAT(prenom, ' ', nom) as nomCandidat
			FROM {$this->tables[$typeElection]} rp
			LEFT JOIN candidature ON rp.idCandidature = candidature.idCandidature
			LEFT JOIN source ON rp.idSource = source.idSource
			LEFT JOIN election ON rp.idElection = election.idElection
			LEFT JOIN centre ON rp.idCentre = centre.idCentre";

			if ($_GET["niveau"]=="dep" OR $_GET["niveau"]=="reg" OR $_GET["niveau"]=="pays")
				$requete.=" LEFT JOIN collectivite ON centre.idCollectivite = collectivite.idCollectivite
				LEFT JOIN departement ON collectivite.idDepartement = departement.idDepartement";
			if ($_GET["niveau"]=="reg" OR $_GET["niveau"]=="pays")
				$requete.=" LEFT JOIN region ON departement.idRegion = region.idRegion";
			if ($_GET["niveau"]=="pays")
				$requete.=" LEFT JOIN pays ON region.idPays = pays.idPays";
			
			$parametres=$_GET['param'];
			$params=explode(",",$parametres);
			$v=0;
			
			if ($_GET["niveau"]=="cen") $parametres3="centre.idCentre";
			elseif ($_GET["niveau"]=="dep") $parametres3="departement.idDepartement";
			elseif ($_GET["niveau"]=="reg") $parametres3="region.idRegion";
			elseif ($_GET["niveau"]=="pays") $parametres3="pays.idPays";
				
			$colonnesBDD=array("rp.idSource","election.tour",$parametres3);

			for($i=0;$i<sizeof($params);$i++) {
				if($v++)$requete.=" AND $colonnesBDD[$i]='".$params[$i]."'";
				else $requete.=" WHERE $colonnesBDD[$i]='".$params[$i]."'";
			}
				
			for($i=0;$i<sizeof($arrayAnnees);$i++) {
				if ($i==0) $requete.=" AND (YEAR(election.dateElection) ='".$arrayAnnees[$i]."'";
				else $requete.=" OR YEAR(election.dateElection) ='".$arrayAnnees[$i]."'";
			}

			$requete.=") GROUP BY rp.idCandidature HAVING count(distinct rp.idElection)=".sizeOf($arrayAnnees);

			$query=$this->db->query($requete);

			$candidatures = array();

			if($query->result()){
				foreach ($query->result() as $candidature) {
					$candidatures[$candidature->idCandidature] = $candidature->nomCandidat;
				}
				echo json_encode($candidatures);
			}
			else // AUCUN RESULTAT (CANDIDAT)
			{
				$candidatures[''] = "Aucun";
				echo json_encode($candidatures);
			}
		} return FALSE;
	}

	function getCandidatsLocalite(){
		$localites = NULL;
		$localites=$_GET["localites"];	// "R1,R2,R3"
			
		if($localites != NULL){
			$arrayLocalites=explode(",",$localites);
		}

		if ( !empty($_GET['param']) AND !empty($_GET["localites"]) AND !empty($_GET["niveau"])) {

			$parametres=$_GET['param'];
			$params=explode(",",$parametres);
			$v=0;
						
			$typeElection=$params[3]; // 4eme parametre
			
			$requete="SELECT rp.idCandidature, CONCAT(prenom, ' ', nom) as nomCandidat
			FROM {$this->tables[$typeElection]} rp
			LEFT JOIN candidature ON rp.idCandidature = candidature.idCandidature
			LEFT JOIN source ON rp.idSource = source.idSource
			LEFT JOIN election ON rp.idElection = election.idElection
			LEFT JOIN centre ON rp.idCentre = centre.idCentre";

			if ($_GET["niveau"]=="dep" OR $_GET["niveau"]=="reg" OR $_GET["niveau"]=="pays")
				$requete.=" LEFT JOIN collectivite ON centre.idCollectivite = collectivite.idCollectivite
				LEFT JOIN departement ON collectivite.idDepartement = departement.idDepartement";
			if ($_GET["niveau"]=="reg" OR $_GET["niveau"]=="pays")
				$requete.=" LEFT JOIN region ON departement.idRegion = region.idRegion";
			if ($_GET["niveau"]=="pays")
				$requete.=" LEFT JOIN pays ON region.idPays = pays.idPays";			

			$colonnesBDD=array("rp.idSource","election.tour","YEAR(election.dateElection)","election.typeElection");

			for($i=0;$i<sizeof($params);$i++) {
				if($v++)$requete.=" AND $colonnesBDD[$i]='".$params[$i]."'";
				else $requete.=" WHERE $colonnesBDD[$i]='".$params[$i]."'";
			}
				
			if ($_GET["niveau"]=="cen") $parametres3="centre.idCentre";
			elseif ($_GET["niveau"]=="dep") $parametres3="departement.idDepartement";
			elseif ($_GET["niveau"]=="reg") $parametres3="region.idRegion";
			elseif ($_GET["niveau"]=="pays") $parametres3="pays.idPays";
				
			for($i=0;$i<sizeof($arrayLocalites);$i++) {
				if(!$i) $requete.=" AND ($parametres3 ='".$arrayLocalites[$i]."'";
				else $requete.="OR $parametres3 ='".$arrayLocalites[$i]."'";
			}

			$requete.=") GROUP BY rp.idCandidature";

			$query=$this->db->query($requete);

			$candidatures = array();

			if($query->result()){
				foreach ($query->result() as $candidature) {
					$candidatures[$candidature->idCandidature] = $candidature->nomCandidat;
				}
				echo json_encode($candidatures);
			}
			else // AUCUN RESULTAT (CANDIDAT)
			{
				$candidatures[''] = "Aucun";
				echo json_encode($candidatures);
			}
		} return FALSE;
	}
/*
	function getCandidatsArray(){
		$nomCandidat = NULL;

		if (!empty($_GET["term"])){

			$nomCandidat=$_GET["term"];
		}
		$this->db->select('idCandidature, nomCandidat, typeCandidat');

		if($nomCandidat != NULL){
			$this->db->like('nomCandidat',$nomCandidat);
		}


		$query = $this->db->order_by('nomCandidat')->get('candidature');

		$candidatures = array();

		if($query->result()){
			$chaine = array();
			foreach ($query->result() as $candidature)
				$chaine [$candidature->idCandidature]= $candidature->nomCandidat;
			echo '[{ "id": "8", "label": "laby", "value": "labx" },{ "id": "2", "label": "lab2y", "value": "lab2x" }]';
		}
		else{
			return FALSE;
		}

	}*/

	function getDatesElections(){
		$requete="SELECT DISTINCT YEAR(dateElection) as annee FROM election";
		if(!empty($_GET["typeElection"]))
			$requete.=" WHERE typeElection='".$_GET["typeElection"]."'";
		if(!empty($_GET["anneeDecoupage"]))
			$requete.=" AND anneeDecoupage=".$_GET["anneeDecoupage"];

		$requete.=" ORDER BY dateElection asc";
		$query=$this->db->query($requete);

		$elections = array();

		if($query->result()){
			foreach ($query->result() as $anneeElection) {
				$elections[$anneeElection->annee] = $anneeElection->annee;
			}
			echo json_encode($elections);
		}
		else{
			return FALSE;
		}
	}
	
	function getDatesElectionsAnalyse(){
		$requete="SELECT YEAR(dateElection) as annee FROM election";
		if(!empty($_GET["typeElection"]))
			$requete.=" WHERE typeElection='".$_GET["typeElection"]."'";
		if(!empty($_GET["anneeDecoupage"]))
			$requete.=" AND anneeDecoupage=".$_GET["anneeDecoupage"];
	
		$requete.=" ORDER BY dateElection asc";
		$query=$this->db->query($requete);
	
		$elections = array();
	
		if($query->result()){
			foreach ($query->result() as $anneeElection) {
				$elections[$anneeElection->annee] = $anneeElection->annee;
			}
			echo json_encode($elections);
		}
		else{
			return FALSE;
		}
	}

	function getCentres(){

		$idCollectivite = NULL;
		$anneeDecoupage=NULL;
		if (!empty($_GET["idCollectivite"])) $idCollectivite=$_GET["idCollectivite"];

		if (!empty($_GET["anneeDecoupage"])) $anneeDecoupage=$_GET["anneeDecoupage"];

		$this->db->select('idCentre, nomCentre');
		$this->db->join('collectivite', 'centre.idCollectivite = collectivite.idCollectivite', 'left');
		$this->db->join('departement', 'collectivite.idDepartement = departement.idDepartement', 'left');
		$this->db->join('region', 'departement.idRegion = region.idRegion', 'left');
		$this->db->join('pays', 'region.idPays = pays.idPays', 'left');

		if($idCollectivite != NULL)	$this->db->where('collectivite.idCollectivite',$idCollectivite);

		if($anneeDecoupage) $this->db->where('anneeDecoupage', $anneeDecoupage);
		$query = $this->db->order_by('nomCentre')->get('centre');
		$centres = array();
		if($query->result()){
			foreach ($query->result() as $centre) {
				$centres[$centre->idCentre] = $centre->nomCentre;
			}
			echo json_encode($centres);
		}
		else
		{
			return FALSE;
		}
	}

	function getCollectivites(){
			
		$idDepartement = NULL;
		$anneeDecoupage=NULL;
		if (!empty($_GET["idDepartement"])) $idDepartement=$_GET["idDepartement"];

		if (!empty($_GET["anneeDecoupage"])) $anneeDecoupage=$_GET["anneeDecoupage"];

		$this->db->select('idCollectivite, nomCollectivite');
		$this->db->join('departement', 'collectivite.idDepartement = departement.idDepartement', 'left');
		$this->db->join('region', 'departement.idRegion = region.idRegion', 'left');
		$this->db->join('pays', 'region.idPays = pays.idPays', 'left');

		if($idDepartement != NULL) $this->db->where('departement.idDepartement', $idDepartement);

		if($anneeDecoupage) $this->db->where('anneeDecoupage', $anneeDecoupage);

		$query = $this->db->order_by('nomCollectivite')->get('collectivite');

		$collectivites = array();

		if($query->result()){
			foreach ($query->result() as $collectivite) {
				$collectivites[$collectivite->idCollectivite] = $collectivite->nomCollectivite;
			}
			echo json_encode($collectivites);
		}
		else
		{
			return FALSE;
		}
	}

	function getDepartements(){
		$idRegion = NULL;
		$anneeDecoupage=NULL;
		if (!empty($_GET["idRegion"])) $idRegion=$_GET["idRegion"];

		if (!empty($_GET["anneeDecoupage"])) $anneeDecoupage=$_GET["anneeDecoupage"];

		$this->db->select('idDepartement, nomDepartement');
		$this->db->join('region', 'departement.idRegion = region.idRegion', 'left');
		$this->db->join('pays', 'region.idPays = pays.idPays', 'left');

		if($idRegion != NULL) $this->db->where('region.idRegion', $idRegion);

		if($anneeDecoupage) $this->db->where('anneeDecoupage', $anneeDecoupage);

		$query = $this->db->order_by('nomDepartement')->get('departement'); // Table 'departement'

		$departements = array();

		if($query->result()){
			foreach ($query->result() as $departement)
			{
				if($departement->idDepartement != "D0")
					$departements[$departement->idDepartement] = $departement->nomDepartement;
			}
			echo json_encode($departements);
		}
		else
		{
			return FALSE;
		}
	}

	function getRegions(){
		$pays = NULL;
		$anneeDecoupage=NULL;
		if (!empty($_GET["idPays"])) $pays=$_GET["idPays"];

		if (!empty($_GET["anneeDecoupage"])) $anneeDecoupage=$_GET["anneeDecoupage"];

		$this->db->select('idRegion, nomRegion');
		$this->db->join('pays', 'region.idPays = pays.idPays', 'left');

		if($pays) $this->db->where('pays.idPays', $pays);

		if($anneeDecoupage) $this->db->where('anneeDecoupage', $anneeDecoupage);

		$query = $this->db->order_by('nomRegion')->get('region');

		$regions = array();

		if($query->result()){
			foreach ($query->result() as $region)
			{
				if($region->idRegion != "R0")
					$regions[$region->idRegion] = $region->nomRegion;
			}
			echo json_encode($regions);
		}
		else
		{
			return FALSE;
		}
	}

	function getPays(){

		$anneeDecoupage=NULL;	$annee=NULL;

		if (!empty($_GET["anneeDecoupage"])) {
			$anneeDecoupage=$_GET["anneeDecoupage"];
		}
		else if (!empty($_GET["paramAnnee"])) {
			$paramAnnee=$_GET["paramAnnee"];
			$queryAnneesDecoupage=$this->db->query("SELECT distinct anneeDecoupage FROM election ORDER BY anneeDecoupage")->result();
			foreach ($queryAnneesDecoupage as $b)
				$anneesDecoupage[]=$b->anneeDecoupage."<br>";
			foreach ($anneesDecoupage as $decoupage){
				if ($paramAnnee>=$decoupage) $anneeDecoupage=$decoupage;
				else break;
			}
		}

		$this->db->select('idPays, nomPays');

		if($anneeDecoupage) $this->db->where('anneeDecoupage', $anneeDecoupage);		

		$query = $this->db->get('pays');
		
		$pays = array();

		if($query->result()){
			foreach ($query->result() as $lepays)
			{
				if($lepays->idPays != "0")
					$pays[$lepays->idPays] = $lepays->nomPays;
			}
			echo json_encode($pays);
		}
		else
		{
			return FALSE;
		}
	}

	function getSources(){
		$this->db->select('idSource, nomSource');

		$query = $this->db->get('source'); // Table 'regions'

		$sources = array();

		if($query->result()){
			foreach ($query->result() as $source) {
				$sources[$source->idSource] = $source->nomSource;
			}
			echo json_encode($sources);
		}else{
			return FALSE;
		}
	}

	function getTours(){ // idElection => Tour
		$this->db->select('idElection,dateElection,tour');

		if(!empty($_GET["dateElection"])) {
				
			$dateElection=$_GET["dateElection"];

			$this->db->where("YEAR(dateElection)",$dateElection);
		}
		$query = $this->db->get('election'); // Table 'regions'

		$tours = array();

		if($query->result()){
			foreach ($query->result() as $tour) {
				if ($tour->tour=="premier_tour") $tours[$tour->tour] = "Premier tour";
				elseif ($tour->tour=="second_tour") $tours[$tour->tour] = "Second tour";
			}
			echo json_encode($tours);
		}else{
			return FALSE;
		}
	}

	function getDecoupages(){ // idElection => Tour
		$requete="SELECT distinct anneeDecoupage FROM pays ORDER BY anneeDecoupage";
		$query=$this->db->query($requete);
		$decoupages = array();
		if($query->result()){
			foreach ($query->result() as $decoupage) {
				if($decoupage->anneeDecoupage) $decoupages[$decoupage->anneeDecoupage] = "Découpage de ".$decoupage->anneeDecoupage;
			}
			echo json_encode($decoupages);
		}else{
			return FALSE;
		}
	}

}