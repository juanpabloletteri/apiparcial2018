<?php
class asistencia{
    public $id;
    public $id_curso;
    public $fecha;

    public static function ValidarAsistencia($idCurso){
        $fecha = date('Y:m:d');
        $rta = 0;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id_curso FROM asistencias WHERE id_curso = :id_curso AND fecha = :fecha");
        $consulta->bindValue(':fecha',$fecha);
        $consulta->bindValue(':id_curso',$idCurso);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $esValido = $datos[0]['id_curso'];
        if ($esValido == null){
            $rta = 1;
        }
        return $rta;
    }
    public static function AgregarAsistencia($idCurso){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $fecha = date('Y:m:d');
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO asistencias (id_curso,fecha) VALUES (:id_curso,:fecha)");
        $consulta->bindValue(':id_curso',$idCurso);
        $consulta->bindValue(':fecha',$fecha);
        if($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function UltimaAsistencia(){
        $ultimaAsistencia=false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id_asistencia FROM asistencias ORDER BY id_asistencia DESC LIMIT 1");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $ultimaAsistencia = $datos[0]['id_asistencia'];
        return $ultimaAsistencia;
    }
    public static function TraerListaPorCurso($idCurso){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("   SELECT 	u.id AS id,u.legajo AS legajo, u.nombre AS nombre, u.apellido AS apellido
                                                            FROM 	usuarios AS u,curso AS c,detalle_curso AS d
                                                            WHERE 	c.id_curso = d.id_curso AND d.id_alumno = u.id AND c.id_curso = :idCurso");
        $consulta->bindValue(':idCurso',$idCurso);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;       
    }
    public static function AgregarDetalleAsistencia($idAlumno){
        $rta = false;
        $lastAsist = asistencia::UltimaAsistencia();
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta ("INSERT INTO `detalle_asistencia`(`id_asistencia`, `id_alumno`, `presente`) VALUES (:asistencia,:alumno,-1)");
        $consulta->bindValue(':asistencia',$lastAsist);
        $consulta->bindValue(':alumno',$idAlumno);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function UpdateDetalleAsistencia($idAlumno){
        $rta = false;
        $lastAsist = asistencia::UltimaAsistencia();
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta ("UPDATE `detalle_asistencia` SET `presente`= presente * -1 WHERE id_alumno = :alumno AND id_asistencia = :asistencia");
        $consulta->bindValue(':asistencia',$lastAsist);
        $consulta->bindValue(':alumno',$idAlumno);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function ListaHistoriaDeAsistencias(){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT asistencias.id_asistencia,curso.id_curso, asistencias.fecha, materias.nombre, comisiones.nombre AS comision
                                                            FROM curso,asistencias,materias, comisiones
                                                            WHERE curso.id_curso = asistencias.id_curso
                                                            AND materias.id = curso.id_materia
                                                            AND comisiones.id_comision = curso.id_comision");
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }

        return $rta;
    }
    public static function ListaDeAlumnosPorIdAsistencia($idAsist){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT usuarios.id,usuarios.legajo,usuarios.nombre,usuarios.apellido,detalle_asistencia.presente
                                                            FROM asistencias,detalle_asistencia,usuarios
                                                            WHERE asistencias.id_asistencia = detalle_asistencia.id_asistencia 
                                                            AND	usuarios.id = detalle_asistencia.id_alumno
                                                            AND asistencias.id_asistencia = :idAsist");
        $consulta->bindValue(":idAsist",$idAsist);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    public static function UpdateFotoAsistencia($foto){
        $rta = false;
        $lastAsist = asistencia::UltimaAsistencia();
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE asistencias SET foto=:foto WHERE id_asistencia = :idAsist");
        $consulta->bindValue(':foto',$foto);
        $consulta->bindValue(':idAsist',$lastAsist);
        
        //var_dump($consulta->execute());
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function UrlFotoAsistencia($idAsist){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT foto FROM asistencias WHERE id_asistencia = :idAsist");
        $consulta->bindValue(':idAsist',$idAsist);
        if ($consulta->execute()){
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = $datos[0]['foto'];
            
        }
        return $rta;
    }
    /*public static function RevisarAsistencias(){
        $rta = falsE;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT ");
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode ($rta);
        }

        return $rta;
    }*/
    public static function FaltasPorAlumnoPorCurso($idCurso){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta ("SELECT id_alumno , COUNT(*)
                                                            FROM asistencias AS a,curso , detalle_asistencia AS da
                                                            WHERE a.id_curso = curso.id_curso
                                                            AND da.id_asistencia = a.id_asistencia
                                                            AND curso.id_curso = :curso
                                                            AND da.presente=-1
                                                            GROUP BY id_alumno");
        $consulta->bindValue(':curso',$idCurso);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }

        return $rta;                                                            
    }
    public static function EliminarAsistencia(){
        $rta = false;
        $rta1 = false;
        $lastAsist = asistencia::UltimaAsistencia();
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta ("DELETE FROM detalle_asistencia
                                                            WHERE id_asistencia = :asistencia");
        $consulta->bindValue(':asistencia',$lastAsist);
        if ($consulta->execute()){
            $rta1 = true;
        }
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta ("DELETE FROM asistencias
                                                            WHERE id_asistencia = :asistencia");
        $consulta->bindValue(':asistencia',$lastAsist);
        if ($consulta->execute()){
            $rta2 = true;
        }
        if ($rta1 == true && $rta2== true){
            $rta = true;
        }
        return $rta;
    }
    public static function EstadisticaAsistenciaGlobal(){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT usuarios.nombre,usuarios.apellido
                                                            , sum(CASE WHEN detalle_asistencia.presente=-1 THEN 1 else 0 END) faltas 
                                                            , sum(CASE WHEN detalle_asistencia.presente=1 THEN 1 else 0 END) presentes 
                                                            FROM asistencias,detalle_asistencia,curso,usuarios
                                                            WHERE asistencias.id_asistencia = detalle_asistencia.id_asistencia
                                                            AND asistencias.id_curso = curso.id_curso
                                                            AND usuarios.id = detalle_asistencia.id_alumno
                                                            GROUP BY usuarios.nombre,usuarios.apellido");
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    public static function EstadisticaAsistenciaPorCurso($idCurso){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT usuarios.nombre,usuarios.apellido
                                                            , sum(CASE WHEN detalle_asistencia.presente=-1 THEN 1 else 0 END) faltas 
                                                            , sum(CASE WHEN detalle_asistencia.presente=1 THEN 1 else 0 END) presentes 
                                                            FROM asistencias,detalle_asistencia,curso,usuarios
                                                            WHERE asistencias.id_asistencia = detalle_asistencia.id_asistencia
                                                            AND asistencias.id_curso = curso.id_curso
                                                            AND curso.id_curso = :idCurso
                                                            AND usuarios.id = detalle_asistencia.id_alumno
                                                            GROUP BY usuarios.nombre,usuarios.apellido");
        $consulta->bindValue(':idCurso',$idCurso);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    public static function EstadisticaAsistenciaPorAlumno($idAlumno){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::DameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT usuarios.nombre,usuarios.apellido
                                                        , sum(CASE WHEN detalle_asistencia.presente=-1 THEN 1 else 0 END) faltas 
                                                        , sum(CASE WHEN detalle_asistencia.presente=1 THEN 1 else 0 END) presentes 
                                                        FROM asistencias,detalle_asistencia,curso,usuarios
                                                        WHERE asistencias.id_asistencia = detalle_asistencia.id_asistencia
                                                        AND asistencias.id_curso = curso.id_curso
                                                        AND detalle_asistencia.id_alumno = :idAlumno
                                                        AND usuarios.id = detalle_asistencia.id_alumno
                                                        GROUP BY usuarios.nombre,usuarios.apellido");
        $consulta->bindValue(':idAlumno',$idAlumno);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
}
?>