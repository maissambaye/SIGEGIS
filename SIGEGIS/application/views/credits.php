<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<!-- 
   Description : Template SIGeGIS 
   Auteur : Maissa Mbaye
   Email : maissa.mbaye@ugb.edu.sn
   Version : 1.5.3
   Date de dernière modification : 28/11/2012 à 16:07
   Dépendances : 
   			JQuery 1.8+, 
   			JQuery UI 1.9.1+ (custom), 
   			Pluggin, Chosen (JQuery) 0.9
   			Google Web font : Yanone Kaffeesatz (needs to be online to see effect)

 -->
<head>
<?php echo $head;?>
<style type="text/css">
body{background: url('../../assets/images/logo_ugb.png') no-repeat 90% 1%;}

#content {
min-height: 700px;
}
</style>
</head>
<body>
<div id="container">
	<div id="header">	
	<!--  header PK-->	
		<img title="Système d'Information Géographique Electoral" src="<?php echo img_url("logo.png");?>" style="position : absolute; top : 20px; left : 20px; height : 170px; "/>
		
		<br/>
		<br/>
		<?php $styles="chzn-select";?>
		<?php $filtres=array("sources","elections","tours","pays","regions","departements","collectivites","centres");?>
		<?php $labels_filtres=array("sources"=>"Source","elections"=>"Année","tours"=>"Tour","centres"=>"Centre","collectivites"=>"Collectivité","departements"=>"Département","regions"=>"Région","pays"=>"Pays");?>	
		<?php echo $menu;?> 
	</div>
	

	<div id="content">
		<h2>UNIVERSITE GASTON BERGER DE SAINT-LOUIS DU SENEGAL</h2>
		<h3>SIGeGIS</h3><br />
		<table id="credits">
			<tr>
				<td>Manager :</td><td>Maïssa Mbaye</td>
			</tr>
			<tr>
				<td>Developper Team</td><td></td>
			</tr>
			<tr>
				<td>2012 :</td><td> Amadou SOW<br/> Abdou Khadre GUEYE<br/></td>
			</tr>
		</table>	
	</div>		
	
		<?php echo $options_menu;?>			
		<?php echo $footer;?>
</div> <!-- Fin de content  -->

<!--panel de choix des -->


<?php echo $scripts;?>
</body>
</html>