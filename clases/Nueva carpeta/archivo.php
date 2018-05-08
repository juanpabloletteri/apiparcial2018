<?php
class archivo{
    public $nombre;
    public $fecha;
    public $id;

    public static function TraerArchivos(){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM archivos");
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    public static function IngresarArchivo($titulo){
        $rta = false;
        $fecha = date('Y:m:d');
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO archivos (titulo,fecha) VALUES(:titulo,:fecha)");
        $consulta->bindValue(':titulo',$titulo);
        $consulta->bindValue(':fecha',$fecha);
        if ($consulta->execute()){
            $rta = true;
        }
        
        return $rta;
    }
}
?>