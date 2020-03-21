<?php
$connection = mysqli_connect('localhost', 'root', '', 'petscouter');
$sqlpet = "SELECT * FROM pet";
//PT-BR: Vai conectar com o banco de dados da aplicação
//EN-USA: Is will connecte with the database of application

$move = true;
//PT-BR: Váriavel para vérifica sé o pet está em movimento ou não
//EN-USA: Variable for verificate if the pet if be in moviment or not
//Obs: A váriavel vai receber um valor do rastreador do pet
//Obs: the variable is will request on value of tracker of pet
if($move == false)
{
	$rastreadordopet = 7;
	$posX = -8.357526;
	$posY = -36.70928;
	$sql = "INSERT INTO gerador (Id_rastrador, geo_locationX, get_locationY) VALUES ($rastreadordopet, $posX, $posY)";

	$connection->query($sql);
	//PT-BR: Essa função vai verificar cada vez que o pet parou para registar sua geolocalização no banco de dados da aplicação
	//EN-USA: This function is will verificate each time what the pet stop for register your geolocation on database of applicaiton
}

//PT-BR: A váriavel "req" vai verificar a solicitação do usuário de acesso a plataforma
//EN-USA: The variable "req" is will verificate the solicitation of user of access the platform
if(isset($_GET['req']) && $_GET['req'] == "localizar")
{
	$idraster = $_POST['idraster'];

	if((isset($idraster) && !empty($idraster)))
	{
		$sql = "SELECT * FROM rastreador WHERE Id_rastreador=$idraster";

		$result = $connection->query($sql);
		$getresults = $connection->query($sql);
		$newresult = $getresults->fetch_assoc();

		if($result->num_rows>0)
		{
			$idresults = $newresult['Id'];
			$sql = "SELECT * FROM rastreador r INNER JOIN gerador g ON g.Id_rastrador = r.Id WHERE g.Id_rastrador=$idresults";

			$getlocaiton = $connection->query($sql);


			$coordsX = array();
			$coordsY = array();
			$datatime = array();

			while($resultlocation = $getlocaiton->fetch_assoc())
			{
				array_push($datatime, $resultlocation['Data_time']);
				array_push($coordsX, $resultlocation['geo_locationX']);
				array_push($coordsY, $resultlocation['get_locationY']);
			}
		}else
		{
			echo "<script>
				alert('O Id do rastrador colocado não está ativado ou não existe.');
			</script>";
		}
	}else
	{
		echo "<script>
				alert('Por favor preencha todos os campos à baixo.');
			</script>";
	}
	//PT-BR: essa função o usuário vai informa o ID do pet e o nome do pet, a o enviar essa solicitação a função vai procurar no banco de dados, os dados envaido e vai retorna um array de todos os pontos de geolocalização do pet.
	//EN-USA: this funcion the user is will informate the ID of pet and the name, the send this solicitation the function go find on database, the data of send and is will return one array of all points of geolocaiton of pet.
}

if(isset($_GET['req']) && $_GET['req'] == "register")
{
	$namepet = $_POST['namepet'];
	$typepet = $_POST['typepet'];
	$agepet = $_POST['agepet'];
	$sexpet = $_POST['sexpet'];

	if((isset($namepet) && !empty($namepet)) && (isset($typepet) && !empty($typepet)) && (isset($agepet) && !empty($agepet)) && (isset($sexpet) && !empty($sexpet)))
		{
			$sql = "INSERT INTO pet (Name, Type, Age, Sex) VALUES ('$namepet', '$typepet', '$agepet', '$sexpet')";

			$connection->query($sql);

			echo "<script>
					alert('Novo pet registrado com sucesso');
				</script>";
		}else
		{
			echo "<script>
					alert('Por favor preencha todos os campos à baixo.');
				</script>";
		}
	//PT-BR: Essa função vai registar os dados do pet informados pelo usuário.
	//EN-USA: This is function go register the date of pet informated of user.
}
if(isset($_GET['req']) && $_GET['req'] == 'rastradorcad')
{
	$coderast = $_POST['coderast'];
	$petselect = $_POST['petselect'];
	if((isset($coderast) && !empty($coderast)) && (isset($petselect) && !empty($petselect)))
	{
		$sql = "INSERT INTO rastreador (Id_rastreador, Id_pet) VALUES ($coderast, $petselect)";

		$connection->query($sql);

		echo "<script>
				alert('O rastreador do seu pet agora está ativado');
			</script>";
	}else
	{
		echo "<script>
				alert('Por favor preencha todos os campos à baixo.');
			</script>";
	}
	//PT-BR: Essa função vai ativar o ID do rastreador do pet informados pelo usuário.
	//EN-USA: This function go active the ID of tracker pet informated of user.
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Pet Rastreation</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZQbf7lGRSjC6rb0hTx2KBkRssvfY4MjI&callback=inicializar"></script>
	    <script>
	 		function inicializar()
	 		{
	 			if('geolocation' in navigator)
	 			{
	 				//PT-BR: Aqui o navegador vai pegar a geolocalização do usuário.
	 				//EN-USA: Here the browser go get the geolocation of user.
	 				navigator.geolocation.getCurrentPosition(function(position){

	 					var lat = position.coords.latitude;
	 					var lng = position.coords.longitude;

	 					var coordenadas = {lat, lng};

				 		var parametros = {
				 			zoom: 18,
				          	center: new google.maps.LatLng(coordenadas),
				          	mapTypeId: google.maps.MapTypeId.SATELLITE,
				          	disableDefaultUI: true,
				 		};

				        var mapa = new google.maps.Map(document.getElementById('map'), parametros);

				        var marker = new google.maps.Marker({
					          position: coordenadas,
					          map: mapa
					        });

				        var coords = [];

				        coords.unshift(new google.maps.LatLng(lat, lng));
				        //PT-BR: Aqui é onde usuário está localizado
				        //EN-USA: Here is where user to be located
				        
				        <?php
				        for($i=0; $i < count($datatime); $i++)
				        {
				        ?>
					        for(var i=0; i < <?php echo $i+1; ?>; i++)
					        {
					        	coords.unshift(new google.maps.LatLng(<?php echo $coordsX[$i]; ?>, <?php echo $coordsY[$i]; ?>));
					        }	
					    <?php
					    }
					    ?>
					    //PT-BR: a patir da localização do usuário, vai ser adicionado um array de infomações pegada do array de geolocalização do pet para criar o guia de onde ele está.
					    //EN-USA: from of user location, an array of information will be addesd get of data geolocation array, where to be created the guia for saw where the pat stayed.
					    
				        var tour = new google.maps.Polyline({
				        	path: coords,
				        	strokeColor: '#ff0000',
				        	strokeOpacity: 0.6,
				        	strokeWeight: 2,
				        });

				        tour.setMap(mapa);

	 				}, function(error){
	 					console.log(error);
	 				});
	 			}else{
	 				alert("Não foi possível pega sua localização");
	 				//PT-BR: caso o usuário nege sua geolocalização, então não vai ser possível rastrear.
	 				//EN-USA: case the user don't show your geolocation, so don't go possible tracking.
	 			}
	      }
	    </script>
  </head>
<body onload="inicializar()">
    <?php
    if(isset($_GET['type']) && $_GET['type'] == "cadpet")
    {
    ?>
    	<div class="jumbotron">
	    	<form action="pet_rastreation.php?req=register" method="POST">
			  <div class="form-group">
			    <label for="exampleInputText">Nome do Pet</label>
			    <input type="text" name="namepet" class="form-control" id="exampleInputText" placeholder="Digite o nome do seu pet">
			    <label for="exampleInputText">Tipo de Pet</label>
			    <input type="text" name="typepet" class="form-control" id="exampleInputText" placeholder="Digite o nome do seu pet">
			    <label for="exampleInputText">Idade do Pet</label>
			    <input type="text" name="agepet" class="form-control" id="exampleInputText" placeholder="Digite o nome do seu pet">
			    <label for="exampleInputText">Sexo do Pet</label>
			    <input type="text" name="sexpet" class="form-control" id="exampleInputText" placeholder="Digite o nome do seu pet">
			  </div>
			  <button type="submit" class="btn btn-primary">Registrar</button>
			</form></br></br>
			<a class="btn btn-primary" href="pet_rastreation.php">Voltar</a>
	    </div>
    <?php
    }else if(isset($_GET['type']) && $_GET['type'] == "rastre")
    {
    ?>
    	<div class="jumbotron">
	    	<form action="pet_rastreation.php?req=rastradorcad" method="POST">
			  <div class="form-group">
			    <label for="exampleInputText">Código do Rastrador</label>
			    <input type="number" name="coderast" class="form-control" id="exampleInputText" placeholder="Digite o código do rastrador">
			    <label for="exampleFormControlSelect1">Selecione Seu Pet</label>
			    <select name="petselect" class="form-control" id="exampleFormControlSelect1">
			      <?php
			      	$sqlpetselect = $connection->query($sqlpet);
			      	while($pets = $sqlpetselect->fetch_assoc())
			      	{
			      ?>
			      <option value="<?php echo $pets['Id']; ?>"><?php echo $pets['Name']; ?></option>
			      <?php
			  		}
			      ?>
			    </select>
			  </div>
			  <button type="submit" class="btn btn-primary">Registrar</button>
			</form></br></br>
			<a class="btn btn-primary" href="pet_rastreation.php">Voltar</a>
	    </div>
    <?php
    }else
    {
    ?>
    	<div id="map" style="width: 100%; height: 480px;"></div>
	    <div class="jumbotron">
	    	<form action="pet_rastreation.php?req=localizar" method="POST">
			  <div class="form-group">
			  <div class="form-group">
			    <label for="exampleInputText">Id do rastreador</label>
			    <input type="number" name="idraster" class="form-control" id="exampleInputText" placeholder="Diigite o id do rastreador">
			  </div>
			  <button type="submit" class="btn btn-primary">Localizar</button>
			</form></br></br>
			<a class="btn btn-primary" href="pet_rastreation.php?type=cadpet">Registrar Pet</a>
			<a class="btn btn-primary" href="pet_rastreation.php?type=rastre">Registrar Rastreador</a>
	    </div>
	    <table class="table">
		  <thead>
		    <tr>
		      <th scope="col">Data e Hora</th>
		      <th scope="col">Latitude</th>
		      <th scope="col">Longitude</th>
		    </tr>
		  </thead>
		  <tbody>
		  <?php
		  if(isset($datatime))
		  {
		  	for($i = 0; $i < count($datatime); $i++)
		  		{
		  ?>
		    <tr>
		      <td><?php echo $datatime[$i]; ?></td>
		      <td><?php echo $coordsX[$i]; ?></td>
		      <td><?php echo $coordsY[$i]; ?></td>
		    </tr>
		   <?php
				}
			}
		   ?>
		  </tbody>
		</table>
    <?php
    }
    ?>
</body>
</html>