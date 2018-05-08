<?php
class item{

    private $_id;
    private $_nombre;
    private $_cantidad;
    private $_precio;
    private $_descripcion;

    //AGREGAR ITEMS
    public static function AgregarItem($nombre,$cantidad,$precio,$descripcion)
    {
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into  
        items (nombre,cantidad,precio,descripcion)
        values(:nombre,:cantidad,:precio,:descripcion)");

        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue(':cantidad', $cantidad);
        $consulta->bindValue(':precio', $precio);
        $consulta->bindValue(':descripcion',$descripcion);

        if($consulta->execute()){
            $rta = true;
        }
        return $rta; 
    }
 
    //TRAER TODOS LOS ITEMS
    public static function TraerTodosLosItems()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM items");
        $consulta->execute();
        $consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($consulta);
    }
   //TRAER ITEM POR ID
   public static function TraerItemPorId($id)
   {
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
       $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM items WHERE id=:id");
       $consulta->bindValue(":id",$id);
       $consulta->execute();
       $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
       //$nombresusuario = json_encode($datos);
       //return $nombresusuario;
       return json_encode($datos);     
   }

}
?>