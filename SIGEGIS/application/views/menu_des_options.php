<div id="pannelside">
		<div  style="background : url('<?php echo img_url("p_haut.png");?>') no-repeat; background-position-x: 0px; background-position-y: 0px; width: 285px; padding-left : 15px; padding-top:25px;padding-bottom:26px; margin-left : 5px; margin-bottom:-1px;" >
			<h1> Choisissez vos filtres</h1>	
		</div>
		<div style=" background : url('<?php echo img_url("p_milieu.png");?>') repeat-y; background-position-x: 0px; background-position-y: -5px; width: 285px;  color : #FFFFFF;margin-left : 5px; margin-bottom:0px;margin-top:0px;" >
					<div class="zone_des_options" style="width:243px;padding-left:10px;padding-bottom : 10px;padding-bottom : 5px;">
							<form action="">
								<fieldset id="types_elections">
									<legend>Type d'élection à représenter</legend>
									<input id="presidentielle" type="radio" name="radio" checked="checked" />
									<label for="presidentielle">Election présidentielle</label><br /> 
									<input id="legislative" type="radio" name="radio" />
									<label for="legislative">Election législative</label><br /> 
									<input id="locale" type="radio" name="radio" />
									<label for="locale">Election locale</label><br />
								</fieldset>

								<fieldset id="types_affichage">
									<legend>Mode de représentation</legend>
									<input type="checkbox" id="map" name="map" />
									<label for="map">Carte</label><br />
									<input type="checkbox" id="bar" checked="checked" name="bar" />
									<label for="bar">Diagramme en colonnes</label><br /> 
									<input type="checkbox" id="pie" name="pie" />
									<label for="pie">Diagramme à secteurs</label><br /> 							
									<input type="checkbox" id="line" name="line" />
									<label for="line">Courbes</label><br />
									<input type="checkbox" id="grid" name="grid" />
									<label for="grid">Tableau</label>
								</fieldset>
							</form>
					</div>
		</div>
		<!-- Partie basse de du menu -->

		<div id="partie_bas_menu" style="background : url('<?php echo img_url("p_bas.png");?>') no-repeat; background-position-x: 0px;  width: 285px; height: 60px;margin-left : 5px;"></div>

		<!-- Bouton menu -->
		<div id="openclose" style=" position:absolute; top : 6px; left : 250px;">
				<a href="#" title="Cacher/Afficher le panneau des filtres"><img src="<?php echo img_url("close.png");?>"/></a>
		</div>

</div> <!--fin pannel slide -->