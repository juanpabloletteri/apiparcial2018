<?php
class usuario{

    private $_nombre;
    private $_apellido;
    private $_dni;
    private $_mail;
    private $_sexo;
    private $_apodoJugador;
    private $_password;

    //GETTERS Y SETTERS

    public function getNombre()
    {
        return $this->_nombre;
    }
    public function setNombre($nombre)
    {
        $this->_nombre = $nombre;
    }
    public function getApellido()
    {
        return $this->_apellido;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function getDni()
    {
        return $this->_dni;
    }
    public function setDni($dni)
    {
        $this->_dni = $dni;
    }
    public function getMail()
    {
        return $this->_mail;
    }
    public function setMail($mail)
    {
        $this->_mail = $mail;
    }
    public function getSexo()
    {
        return $this->_sexo;
    }
    public function setSexo($sexo)
    {
        $this->_sexo = $sexo;
    }
    public function getApodoJugador()
    {
        return $this->_apodoJugador;
    }
    public function setApodoJugador($apodoJugador)
    {
        $this->_apodoJugador = $apodoJugador;
    }
    public function getPassword()
    {
        return $this->_password;
    }
    public function setPassword($pw)
    {
        $this->_password = $pw;
    }
    //GETTERS Y SETTERS

    //*************************************************************ALTA DE USUARIOS*****************************************************************************************************

    public static function AgregarUsuario($nombre,$apellido,$dni,$mail,$sexo,$apodoJugador,$password)
    {
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into  
        usuarios (nombre,apellido,dni,mail,sexo,apodoJugador,password)
        values(:nombre,:apellido,:dni,:mail,:sexo,:apodoJugador,:password)");

        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue(':apellido', $apellido);
        $consulta->bindValue(':dni', $dni);
        $consulta->bindValue(':mail',$mail);
        $consulta->bindValue(':sexo',$sexo);
        $consulta->bindValue(':apodoJugador', $apodoJugador);
        $consulta->bindValue(':password', $password);

        if($consulta->execute()){
            $rta = true;
        }
        return $rta; 
    }
 
    //TRAER TODOS LOS USUARIOS
    public static function TraerTodosLosUsuarios()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios");
        $consulta->execute();
        $consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($consulta);
    }
   //TRAER USUARIO POR ID
   public static function TraerUsuarioPorId($id)
   {
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
       $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios WHERE id=:id");
       $consulta->bindValue(":id",$id);
       $consulta->execute();
       $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
       //$nombresusuario = json_encode($datos);
       //return $nombresusuario;
       return json_encode($datos);     
   }
    //TRAER MAILS
    public static function TraerMails()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT mail FROM usuarios");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        //$mails = json_encode($datos);
        //return $mails;
        return json_encode($datos);
    }
   //TRAER NOMBRES DE USUARIO
   public static function TraerApodoJugador()
   {
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
       $consulta = $objetoAccesoDato->RetornarConsulta("SELECT apodoJugador FROM usuarios");
       $consulta->execute();
       $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
       //$nombresusuario = json_encode($datos);
       //return $nombresusuario;
       return json_encode($datos);     
   }
   //TRAER MAILS Y NOMBRES DE USUARIO
   public static function TraerMailsyApodoJugador()
   {
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
       $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id, mail, apodoJugador FROM usuarios");
       $consulta->execute();
       $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
       //$nombresusuario = json_encode($datos);
       //return $nombresusuario;
       return json_encode($datos);     
   }

   //TRAER MAILS Y PASSWORDS DE USUARIO
   public static function TraerMailsyPass()
   {
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
       $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id, mail, password FROM usuarios");
       $consulta->execute();
       $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
       //$nombresusuario = json_encode($datos);
       //return $nombresusuario;
       return json_encode($datos);     
   }

   public static function SumarPuntos($id,$puntos1, $puntos2, $puntos3)
   {
       $rta = false;
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
       $consulta = $objetoAccesoDato->RetornarConsulta(
           "UPDATE `usuarios` 
           SET `puntos1`=:puntos1 ,`puntos2`=:puntos2, `puntos3`=:puntos3 WHERE id=:id");
       $consulta->bindValue(':id',$id);
       $consulta->bindValue(':puntos1',$puntos1);
       $consulta->bindValue(':puntos2',$puntos2);
       $consulta->bindValue(':puntos3',$puntos3);
       if($consulta->execute()){
           $rta = true;
       }
       return $rta;
   }












    //ALTA DE ALUMNO
    public static function AgregarAlumno($nombre,$apellido,$dni,$mail,$sexo)
    {
        $rta = false;
        $legajo = usuario::UltimoAlumno();
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into  usuarios (nombre,apellido,dni,mail,password,tipo,legajo,sexo)values(:nombre,:apellido,:dni,:mail,:password,:tipo,:legajo,:sexo)");
        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue(':apellido', $apellido);
        $consulta->bindValue(':dni', $dni);
        $consulta->bindValue(':mail',$mail);
        $consulta->bindValue(':password', $dni);
        $consulta->bindValue(':sexo', $sexo);
        $consulta->bindValue(':tipo', 4);
        $consulta->bindValue(':legajo', $legajo+1);
        if($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    //ALTA DE ADMINISTRATIVO
    public static function AgregarAdministrativo($nombre,$apellido,$dni,$mail,$sexo)
    {
        $rta = false;
        $legajo = usuario::UltimoAdministrativo();
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into  usuarios (nombre,apellido,dni,mail,password,tipo,legajo,sexo)values(:nombre,:apellido,:dni,:mail,:password,:tipo,:legajo,:sexo)");
        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue(':apellido', $apellido);
        $consulta->bindValue(':dni', $dni);
        $consulta->bindValue(':mail',$mail);
        $consulta->bindValue(':password', $dni);
        $consulta->bindValue(':sexo', $sexo);
        $consulta->bindValue(':tipo', 3);
        $consulta->bindValue(':legajo', $legajo+1);
        if($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    //ALTA DE ADMINISTRADOR
    public static function AgregarAdmin($nombre,$apellido,$dni,$mail,$sexo)
    {
        $rta = false;
        $legajo = usuario::UltimoAdmin();
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into  usuarios (nombre,apellido,dni,mail,password,tipo,legajo,sexo)values(:nombre,:apellido,:dni,:mail,:password,:tipo,:legajo,:sexo)");
        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue(':apellido', $apellido);
        $consulta->bindValue(':dni', $dni);
        $consulta->bindValue(':mail',$mail);
        $consulta->bindValue(':password', $dni);
        $consulta->bindValue(':sexo', $sexo);
        $consulta->bindValue(':tipo', 1);
        $consulta->bindValue(':legajo', $legajo+1);
        if($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function BajaUsuario($id_usuario){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta ("DELETE FROM `usuarios` WHERE id = :id_usuario");
        $consulta->bindValue(':id_usuario',$id_usuario);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    //***********************************************TRAER USUARIOS POR TIPO**********************************************************************************************************
    //***********************************************TRAER USUARIOS POR TIPO**********************************************************************************************************/
    //TRAER TODOS LOS USUARIOS
    /*public static function TraerTodos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios");
        $consulta->execute();
        $consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = json_encode($consulta);
        return $consulta;
    }*/
    //TRAER TODOS LOS ALUMNOS
    public static function TraerAlumnos()
    {       
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM `usuarios` WHERE tipo = 4");
        $consulta->execute();
        $consulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = json_encode($consulta);
        return $consulta;
    }
    //TRAER TODOS LOS ADMINS
    public static function TraerAdmins()
    {       
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM `usuarios` WHERE tipo = 1");
        $consulta->execute();
        $consulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = json_encode($consulta);
        return $consulta;
    }
    //TRAER TODOS LOS ADMINISTRATIVOS
    public static function TraerAdminsistrativos()
    {       
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM `usuarios` WHERE tipo = 3");
        $consulta->execute();
        $consulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = json_encode($consulta);
        return $consulta;
    }
    //TRAER TODOS LOS PROFESORES
    public static function TraerProfesores()
    {       
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM `usuarios` WHERE tipo = 2");
        $consulta->execute();
        $consulta=$consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = json_encode($consulta);
        return $consulta;
    }
    //TRAER UN USUARIO POR LEGAJO
    public static function TraerUnUsuario ($id)
    {
        $usuario = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios WHERE legajo=:leg");
        $consulta->bindValue(':leg',$id);
        if($consulta->execute()){
            $usuario = $consulta->fetchObject('usuario');
        }
        $usuario = json_encode($usuario);
        return $usuario;
    }
    //***********************************************************ULTIMOS LEGAJOS**********************************************************************************
    //***********************************************************ULTIMOS LEGAJOS**********************************************************************************
    //ULTIMO ALUMNO
    public static function UltimoAlumno()
    {
        $ultimoLegajo=false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT legajo FROM usuarios WHERE tipo = 4 ORDER BY id DESC LIMIT 1");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $ultimoLegajo = $datos[0]['legajo'];
        return $ultimoLegajo;
    }
    //ULTIMO ADMINISTRATIVO
    public static function UltimoAdministrativo()
    {
        $ultimoLegajo=false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT legajo FROM usuarios WHERE tipo = 3 ORDER BY id DESC LIMIT 1");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $ultimoLegajo = $datos[0]['legajo'];
        return $ultimoLegajo;
    }
    //ULTIMO PROFESOR
    public static function UltimoProfesor()
    {
        $ultimoLegajo=false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT legajo FROM usuarios WHERE tipo = 2 ORDER BY id DESC LIMIT 1");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $ultimoLegajo = $datos[0]['legajo'];
        return $ultimoLegajo;
    }
    //ULTIMO ADMIN
    public static function UltimoAdmin()
    {
        $ultimoLegajo=false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT legajo FROM usuarios WHERE tipo = 1 ORDER BY id DESC LIMIT 1");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $ultimoLegajo = $datos[0]['legajo'];
        return $ultimoLegajo;
    }

    //****************************************MODIFICAR USUARIO*************************************************************************
    //****************************************MODIFICAR USUARIO*************************************************************************
    public static function ActualizarPassword($id,$pw)
    {
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `usuarios` SET `password`=:pw WHERE id=:id");
        $consulta->bindValue(':id',$id);
        $consulta->bindValue(':pw',$pw);
        if($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function TraerFotoPorId($id){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT foto FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id',$id);
        $consulta->execute();
        $foto = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $urlFin = $foto[0]['foto'];
        return $urlFin;
    }
    public static function subirFoto($foto,$id){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `usuarios` SET `foto`=:foto WHERE id=:id");
        $consulta->bindValue(':foto',$foto);
        $consulta->bindValue(':id',$id);
        if($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function modificarUsuario($id,$nombre,$apellido,$mail){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `usuarios` SET `nombre`= :nombre,`apellido`=:apellido,`mail`= :mail 
                                                        WHERE id = :id");
        $consulta->bindValue(':id',$id);
        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue(':apellido',$apellido);
        $consulta->bindValue(':mail',$mail);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
}
?>