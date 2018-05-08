<?php
/**
* 
*/
class Lugares
{
	#ATRIBUTOS------------------------------------------------------------------------------------------------------------
	private $_idPiso;
	private $_ocupado;
	private $_discapacitado;
	private $_idLugar;

	#CONSTRUCTOR------------------------------------------------------------------------------------------------------------
	function __construct($piso,$lugar,$ocupado,$discapacitado)
	{
		$this->_idPiso = $piso;
		$this->_idLugar = $lugar;
		$this->_ocupado = $ocupado;
		$this->_discapacitado = $discapacitado;		
	}

	#GETTERS Y SETTERS------------------------------------------------------------------------------------------------------
	public function GetPiso()
	{
		return $this->_idPiso;
	}
	public function GetLugar()
	{
		return $this->_idLugar;
	}
	public function GetOcupado()
	{
		return $this->_ocupado;
	}
	public function GetDiscapacitado()
	{
		return $this->_discapacitado;
	}

	#METODOS---------------------------------------------------------------------------------------------------------------------------
	#LIBERAR LUGAR DE LA BASE----------------------------------------------------------------------------------------------------------
	public static function LiberarLugar($id)
	{
		$rta = false;
		$pdo = new PDO("mysql:host = localhost; dbname=u196283608_est","u196283608_gui","Colombres1");
		$db = $pdo->prepare("UPDATE lugares SET ocupado=:ocupado WHERE id_lugar=:lugar");
		$db->bindValue(':ocupado',false);
		$db->bindValue(':lugar',$id);
		if($db->execute())
		{
			$rta = true;
		}
		return $rta;
	}
	#OCUPAR LUGAR EN LA BASE LUGARES--------------------------------------------------------------------------------------------------- 
	public static function OcuparLugar($id)
	{
		$rta = false;
		$pdo = new PDO("mysql:host = localhost; dbname=u196283608_est","u196283608_gui","Colombres1");
		$db = $pdo->prepare("UPDATE lugares SET ocupado=:ocupado WHERE id_lugar=:lugar");
		$db->bindValue(':ocupado',true);
		$db->bindValue(':lugar',$id);
		if ($db->execute())
		{
			$rta = true;
		}
		return $rta;
	}
	#LUGARES LIBRES POR PISO-----------------------------------------------------------------------------------------------------------
	public static function LugaresLibres($piso)
	{
		$datos = "";
		$pdo = new PDO("mysql:host = localhost; dbname=u196283608_est","u196283608_gui","Colombres1");
		$db = $pdo->prepare("SELECT * FROM lugares WHERE id_piso = :piso");
		$db->bindValue(':piso',$piso);
		$db->execute();
		while($linea = $db->fetch(PDO::FETCH_ASSOC))
		{
			if ($linea["ocupado"] != true)
			{
				$datos.="<option>".$linea["id_lugar"]."</option>";
			}
		}
		echo $datos;
	}

	#GRILLA LUGARES LIBRES-------------------------------------------------------------------------------------------------------------
	public static function GrillaLugares($piso)
	{
		$inicio ="<table border='2'>";
		$datos ="";
		$fin ="</table>";
		$pdo = new PDO("mysql:host = localhost; dbname=u196283608_est","u196283608_gui","Colombres1");
		$db = $pdo->prepare("SELECT * FROM lugares WHERE id_piso = :piso");
		$db->bindValue(':piso',$piso);
		$db->execute();
		$cont = 0;
		while($linea = $db->fetch(PDO::FETCH_ASSOC))
		{
			if($cont == 0 || $cont %10 == 0)
			{
				$datos.='<tr>';	
			}				
			if ($linea["ocupado"] != true )
			{
				$datos.='<td class="col-md-1 bg-success">'.$linea["id_lugar"].'</td>';
			}else
			{
				$datos.='<td class="col-md-1 bg-danger">'.$linea["id_lugar"].'</td>';
			}
			$cont++;
			if($cont == 10)
			{
				$datos.='</tr>';
				$cont = 0;	
			}
		}
		echo $inicio.$datos.$fin;

	}
	#PRIMER USO DE LUGARES--------------------------------------------------------------------------------------------------------------
	public static function PrimerUso()
	{
		$rta = false;
		$pdo = new PDO("mysql:host = localhost; dbname=u196283608_est","u196283608_gui","Colombres1");
		$db = $pdo->prepare("INSERT INTO lugares (id_piso,id_lugar,ocupado,discapacitado)VALUES(:piso,:lugar,:ocupado,:discapacitado)");
		$piso1 = 100;
		$piso2 = 200;
		$piso3 = 300;
		for ($i = 1 ; $i <= 30 ; $i++)
		{
			if($i < 4)
			{
				$db->bindValue(':discapacitado',true);
			}else
			{
				$db->bindValue(':discapacitado',false);
			}
			$num = $piso1+$i;
			$db->bindValue(':piso',1);
			$db->bindParam(':lugar',$num);
			$db->bindValue(':ocupado',false);
			$rta=$db->execute();
		}
		for ($i = 1 ; $i <= 30 ; $i++)
		{
			if($i < 4)
			{
				$db->bindValue(':discapacitado',true);
			}else
			{
				$db->bindValue(':discapacitado',false);
			}
			$num = $piso2+$i;
			$db->bindValue(':piso',2);
			$db->bindValue(':lugar',$num);
			$db->bindValue(':ocupado',false);
			$rta=$db->execute();
		}
		for ($i = 1 ; $i <= 30 ; $i++)
		{
			if($i < 4)
			{
				$db->bindValue(':discapacitado',true);
			}else
			{
				$db->bindValue(':discapacitado',false);
			}
			$num = $piso3+$i;
			$db->bindValue(':piso',3);
			$db->bindValue(':lugar',$num);
			$db->bindValue(':ocupado',false);
			$rta=$db->execute();
		}
		return $rta;
	}
}
?>