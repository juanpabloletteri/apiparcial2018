<?php
class persona
{
	public $id;
 	public $nombre;
  	public $mail;
	public $sexo;
	public $password;

	/*function __construct($nombre=null,$mail=null,$sexo=null,$password=null,$id=null){
		$this->nombre = $nombre;
		$this->mail = $mail;
		$this->sexo = $sexo;
		$this->password = $password;
		$this->id = $id;
	}*/

	public function getNombre(){
		return $this->nombre;
	}
	public function getMail(){
		return $this->mail;
	}
	public function getSexo(){
		return $this->sexo;
	}
	public function getPassword(){
		return $this->password;
	}
/* inicio  especiales para slimFramework*/

	/*AGREGAR USUARIO */
	public function AgregarUsuario($nombre,$mail,$sexo,$password)
	{
		$rta = false;
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into persona (nombre,mail,sexo, password)values(:nombre,:mail,:sexo,:password)");
		$consulta->bindValue(':nombre',$nombre, PDO::PARAM_STR);
		$consulta->bindValue(':sexo', $mail, PDO::PARAM_STR);
		$consulta->bindValue(':mail', $sexo, PDO::PARAM_STR);
		$consulta->bindValue(':password', $password, PDO::PARAM_STR);
		if($consulta->execute()){
			$rta = true;
		}
		return $rta;
	}
	/*AGREGAR USUARIO */
 	public function TraerUno($request, $response, $args) {
     	$id=$args['id'];
    	$elCd=persona::TraerUnCd($id);
     	$newResponse = $response->withJson($elCd, 200);  
    	return $newResponse;
	}
	public function traerTodasLasPersonas($request,$response){
		$todasLasPersonas=persona::TodasLasPersonas();
		$newResponse = $response->withJson($todasLasPersonas,200);
		return $newResponse;
	}
     public function TraerTodos($request, $response, $args) {
      	$todosLosCds=persona::TraerTodoLosCds();
     	$newResponse = $response->withJson($todosLosCds, 200);  
    	return $newResponse;
    }
      public function CargarUno($request, $response, $args) {
     	$response->getBody()->write("<h1>Cargar uno nuevo</h1>");
      	return $response;
    }
      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$id=$ArrayDeParametros['id'];
     	$cd= new persona();
     	$cd->id=$id;
     	$cantidadDeBorrados=$cd->BorrarCd();

     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    if($cantidadDeBorrados>0)
	    	{
	    		 $objDelaRespuesta->resultado="algo borro!!!";
	    	}
	    	else
	    	{
	    		$objDelaRespuesta->resultado="no Borro nada!!!";
	    	}
	    $newResponse = $response->withJson($objDelaRespuesta, 200);  
      	return $newResponse;
    }
     public function ModificarUno($request, $response, $args) {
     	//$response->getBody()->write("<h1>Modificar  uno</h1>");
     	$ArrayDeParametros = $request->getParsedBody();
	    //var_dump($ArrayDeParametros);    	
	    $micd = new persona();
	    $micd->id=$ArrayDeParametros['id'];
	    $micd->nombre=$ArrayDeParametros['nombre'];
	    $micd->mail=$ArrayDeParametros['mail'];
	    $micd->sexo=$ArrayDeParametros['sexo'];
		$micd->password=$ArrayDeParametros['password'];

	   	$resultado =$micd->ModificarCdParametros();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
		return $response->withJson($objDelaRespuesta, 200);		
    }

/* final especiales para slimFramework*/
  	public function BorrarCd()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from cds 				
				WHERE id=:id");	
				$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }

	public static function BorrarCdPorsexo($sexo)
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from cds 				
				WHERE jahr=:sexo");	
				$consulta->bindValue(':sexo',$sexo, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();

	 }
	public function ModificarCd()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update cds	set
				nombre='$this->nombre',
				mail='$this->mail',
				sexo='$this->sexo',
				password='$this->password'
				WHERE id='$this->id'");
			return $consulta->execute();

	 }
	
  
	 public function InsertarElCd()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into persona (nombre,mail,sexo,password)values('$this->nombre','$this->mail','$this->sexo','$this->password')");
				$consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
				

	 }

	  public function ModificarCdParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update persona 
				set nombre=:nombre,
				mail=:mail,
				sexo=:sexo,
				password=:password
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_INT);
			$consulta->bindValue(':sexo', $this->sexo, PDO::PARAM_STR);
			$consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
			$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
			return $consulta->execute();
	 }

	 public function InsertarElCdParametros()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into persona (nombre,mail,sexo, password)values(:nombre,:mail,:sexo,:password)");
				$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_INT);
				$consulta->bindValue(':sexo', $this->sexo, PDO::PARAM_STR);
				$consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
				$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
				$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }
	 public function GuardarCD()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarCdParametros();
	 		}else {
	 			$this->InsertarElCdParametros();
	 		}

	 }


  	public static function TraerTodoLosCds()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre as nombre, mail as mail,sexo as sexo, password as password from persona");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "persona");		
	}
	public static function TodasLasPersonas()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre as nombre, mail as mail,sexo as sexo, password as password from persona");
		$consulta->execute();
		$consulta->fetchAll(PDO::FETCH_ASSOC, "persona");
		$consulta = json_encode($consulta);
		return $consulta;
	}

	public static function TraerUnCd($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id, nombre as nombre, mail as mail,sexo as sexo, password as password from persona where id = $id");
			$consulta->execute();
			$cdBuscado= $consulta->fetchObject('persona');
			return $cdBuscado;				

			
	}
/////////////////hasta aca modifique
	public static function TraerUnCdsexo($id,$sexo) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  titel as nombre, interpret as mail,jahr as sexo from cds  WHERE id=? AND jahr=?");
			$consulta->execute(array($id, $sexo));
			$cdBuscado= $consulta->fetchObject('cd');
      		return $cdBuscado;				

			
	}

	public static function TraerUnCdsexoParamNombre($id,$sexo) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  titel as nombre, interpret as mail,jahr as sexo from cds  WHERE id=:id AND jahr=:sexo");
			$consulta->bindValue(':id', $id, PDO::PARAM_INT);
			$consulta->bindValue(':sexo', $sexo, PDO::PARAM_STR);
			$consulta->execute();
			$cdBuscado= $consulta->fetchObject('cd');
      		return $cdBuscado;				

			
	}
	
	public static function TraerUnCdsexoParamNombreArray($id,$sexo) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  titel as nombre, interpret as mail,jahr as sexo from cds  WHERE id=:id AND jahr=:sexo");
			$consulta->execute(array(':id'=> $id,':sexo'=> $sexo));
			$consulta->execute();
			$cdBuscado= $consulta->fetchObject('cd');
      		return $cdBuscado;				

			
	}

	public function mostrarDatos()
	{
	  	return "Metodo mostar:".$this->nombre."  ".$this->mail."  ".$this->sexo;
	}
}