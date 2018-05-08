<?php
class materia{
    
    public $nombre;
    public $aula;
    public $id_materia;

//******************************************GETTERS Y SETTERS */
    public function getNombre(){
        return $this->nombre;
    }
    public function getAula(){
        return $this->aula;
    }
    public function getMateria(){
        return $this->id_materia;
    }

    public static function TraerTodasLasMaterias(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM `materias` GROUP BY nombre");
        $consulta->execute();
        $consulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = json_encode($consulta);
        return $consulta;
    }
    public static function AltaDeMateria($nombre,$fecha_inicio,$aula){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO materias (nombre,aula) VALUES (:nombre,:fecha_inicio,:aula) ");
        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue('aula',$aula);
        if($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function ModificarMateria($id,$nombre,$aula){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE materias SET nombre=:nombre, aula=:aula WHERE id=:id");
        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue(':aula',$aula);
        $consulta->bindValue(':id',$id);
        if($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }

}
?>