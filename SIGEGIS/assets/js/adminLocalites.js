$(document).ready(function() {
	$("#menu ul li:not(':first,:gt(7)')").hide();
	var $anneeDecoupage=$("#anneeDecoupage");
	 
	label={
			"idPays":"ID pays","nomPays":"Nom du pays","anneeDecoupage":"Découpage administratif",
				"idRegion":"ID région",
				"nomRegion":"Nom de la région",
				"idDepartement":"ID département",
				"nomDepartement":"Nom du département",
				"idCollectivite":"ID collectivité",
				"nomCollectivite":"Nom de la collectivité",
				"idCentre":"ID centre",
				"nomCentre":"Nom du centre"					
	};
		var localite={
				"pays":{
					1:"idPays",
					2:"nomPays",
					3:"anneeDecoupage"						
				},
				"region":{
					1:"idRegion",
					2:"nomRegion",
					3:"idPays"
				},
				"departement":{
					1:"idDepartement",
					2:"nomDepartement",
					3:"idRegion"
				},
				"collectivite":{
					1:"idCollectivite",
					2:"nomCollectivite",
					3:"idDepartement"
				},
				"centre":{
					1:"idCentre",
					2:"nomCentre",
					3:"idCollectivite"
				}
		};
		
		$localite=$.getUrlVar("typeLocalite");
	
	
	$("#list").jqGrid({
		autowidth:true,			
	    datatype: 'xml',
	    mtype: 'POST',
	    colNames:[label[localite[$localite][1]],label[localite[$localite][2]],label[localite[$localite][3]]],
	    colModel :[
		{name: localite[$localite][1], index: localite[$localite][1], editable:true},
		{name:localite[$localite][2], index:localite[$localite][2], editable:true, search:true, stype:'text', editrules:{required:true}},
		{name:localite[$localite][3], index:localite[$localite][3], editable:true, editrules:{required:true}}
	    ],
	    pager: '#pager',
	    rowNum:20,
	    rowList:[20,30,50,100,1000],
	    sortname: localite[$localite][1],
	    sortorder: 'asc',	    
	    viewrecords: true,
	    ondblClickRow: function(id) 	{
	    	$("#list").editGridRow(id,{closeAfterEdit:true});
		},
	    gridview: true
	}).navGrid("#pager",{edit:true,add:true,del:true,search:true},{closeAfterEdit:true},{closeAfterAdd:true});

	if($localite!="pays") element=$localite+"s";
	else element=$localite;
	
	$.ajax({            
		url: 'http://www.sigegis.ugb-edu.com/main_controller/getDecoupages',            			         			   
		dataType: 'json',      
		success: function(json) {
			$("#anneeDecoupage").empty();
			$.each(json, function(index, value) {         
				$("#anneeDecoupage").append('<option value="'+ index +'">'+ value +'</option>');							
			});	
			if ($.getUrlVar("annee")) $anneeDecoupage.val($.getUrlVar("annee"));
			else $("#anneeDecoupage>:last").attr("selected","selected");
			
			$("#list").setGridParam({url:"http://www.sigegis.ugb-edu.com/admin_controller/getGridLocalites?typeLocalite="+$.getUrlVar("typeLocalite")+"&annee="+$anneeDecoupage.val(), editurl:"http://www.sigegis.ugb-edu.com/admin_controller/localiteCRUD?typeLocalite="+$.getUrlVar("typeLocalite"), page:1}).trigger("reloadGrid");								
		}           
	});
	
	$("#anneeDecoupage").on("change", function(){
		if (!$.getUrlVar("annee")) $anneeDecoupage.val($.getUrlVar("annee"));
		$("#list").setGridParam({url:"http://www.sigegis.ugb-edu.com/admin_controller/getGridLocalites?typeLocalite="+$.getUrlVar("typeLocalite")+"&annee="+$anneeDecoupage.val(), editurl:"http://www.sigegis.ugb-edu.com/admin_controller/localiteCRUD?typeLocalite="+$.getUrlVar("typeLocalite"), page:1}).trigger("reloadGrid");
	});
	
	$("*[id*='button_']").on("click",function(){
		window.location="http://www.sigegis.ugb-edu.com/admin_controller/editLocalites?typeLocalite="+$(this).attr("id").substring(7)+"&annee="+$anneeDecoupage.val();
	});
	
	$(".ui-jqgrid-bdiv").removeAttr("style");
	$("#left-sidebar input, #left-sidebar button").attr("disabled","disabled");
});