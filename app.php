<?php	
	header('Content-Type: text/html; charset=utf-8');
	
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$url = "http://ip-api.com/json/".$ip."?lang=es";
	
	$page = file_get_contents($url);
	
	$contendo = json_decode($page,true);
	r
	if($contendo["status"]=="fail"){			
		if(empty($_GET)){
			
			#An error message and a search field are displayed 
			echo "<table align='center' bgcolor='#F2F2F2' cellpadding='20px'><th colspan='2'><h2>Error</h2>
			<form method='get'>Seu ip não é reconhecido, introduza uma etiqueta:<br>			
			<input type='text'name='tag'><br><input type='submit' value='Aceitar'>
			</form></th></table>";

		}else{
			buscarPorTag($_GET['tag']);
		}
	}else{

		if(empty($_GET)){

			$cidade = $contendo["city"];
			buscarPorTag($cidade);
		

		}else{
			
			buscarPorTag($_GET['tag']);	
		}
	}

	function buscarPorTag($tag){		
		$flickr = "https://api.flickr.com/services/feeds/photos_public.gne?tags=".$tag;

		$xml = file_get_contents($flickr);

		$entradas = new SimpleXMLElement($xml);

		$entradas->registerXPathNamespace("feed","http://www.w3.org/2005/Atom");

		echo "<h1 align='center'>Últimas 10 fotos com a etiqueta ".$tag."</h1><table>";	

		$table = "<th colspan='2'>Quer buscar por outra etiqueta?<br><pre>Para usar várias separar com vírgula(sem espaços)</pre><form method='get'>			
			<input type='text'name='tag'><br><input type='submit' value='Aceitar'>
			</form><th>";

		$links = $entradas->xpath("//feed:entry/feed:link[@rel='enclosure']/./@href");
		
        	$indice=0;
		foreach ($links as $imagem) {
			$array_links[$indice] = $imagem;
			$indice++;	
		}
		
		for ($i = 1; $i <= 10; $i++) {

	    		$table = $table."<tr><td><img src=".$array_links[$i]." width='500px'></td><td width='300px'><b>Título</b><br>".$entradas->entry[$i]->title."<br><b>Autor</b></br>".$entradas->entry[$i]->author->name."</td></tr>";
		}
	
		echo "<table align='center'bgcolor='#F2F2F2' cellpadding='20px'>".$table."</table>";
	}
?>