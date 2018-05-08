<?php
class encuesta{
    public $id;
    public $nombre;
    public $f_inicio;
    public $f_fin;
    public $duracion;
    public $activo;

    public static function agregarEncuesta($curso,$nombre,$opcion1,$opcion2,$duracion){
         //cambie el estado de activa de 1 a 2 para que pueda activarla el profesor
        $rta = false;
        $duracion_total = '+'.$duracion.'hours';
        $fecha_inicio = date('Y-m-d H:m:s');
        $fecha_fin = date('Y-m-d H:m:s',strtotime($duracion_total));
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO `encuestas`
                                                        ( `id_curso`, `nombre_encuesta`, `opcion1`, `opcion2`, `fecha_inicio`, `fecha_fin`, `activa`) 
                                                        VALUES (:id_curso,:nombre,:op1,:op2,:finicio,:ffin,2)");
        $consulta->bindValue(':id_curso',$curso);
        $consulta->bindValue(':nombre',$nombre);
        $consulta->bindValue(':op1',$opcion1);
        $consulta->bindValue(':op2',$opcion2);
        $consulta->bindValue(':finicio',$fecha_inicio);
        $consulta->bindValue(':ffin',$fecha_fin);
        if ($consulta->execute()){
            $rta = true;
        }
        $alumnos = encuesta::TraerListaPorCurso($curso);
        encuesta::agregarDetalleEncuesta($alumnos);

        return $rta;
    }
    public static function ultimaEncuesta(){
        $ultimaEncuesta=false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id_encuesta FROM encuestas ORDER BY id_encuesta DESC LIMIT 1");
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $ultimaEncuesta = $datos[0]['id_encuesta'];
        return $ultimaEncuesta;
        
    }
    public static function DesactivarEncuesta(){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE encuestas SET activa VALUE :activa WHERE id_encuesta = :id_encuesta");
        if ($consulta->execute()){
            $rta = true;
        }

        return $rta;
    }
    public static function MostrarEncuestas(){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM encuestas");
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    /*public static function MostrarEncuestasPorProf($id_prof){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT encuestas.id_encuesta,encuestas.fecha_inicio,encuestas.nombre_encuesta,materias.nombre, comisiones.nombre
                                                            FROM curso,encuestas,usuarios,materias,comisiones
                                                            WHERE encuestas.id_curso = curso.id_curso 
                                                            AND	comisiones.id_comision = curso.id_comision
                                                            AND	materias.id = curso.id_materia
                                                            AND	usuarios.id = curso.id_profesor
                                                            AND	curso.id_profesor = :prof");
        $consulta->bindValue(':prof',$id_prof);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }*/
    public static function MostrarEncuestasPorProf($id_prof){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT *
                                                            FROM curso,encuestas,usuarios,materias,comisiones
                                                            WHERE encuestas.id_curso = curso.id_curso 
                                                            AND	comisiones.id_comision = curso.id_comision
                                                            AND	materias.id = curso.id_materia
                                                            AND	usuarios.id = curso.id_profesor");
        $consulta->bindValue(':prof',$id_prof);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    public static function MostrarEncuestaPorId($id_encuesta){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT encuestas.id_encuesta,encuestas.id_curso,encuestas.nombre_encuesta,encuestas.opcion1,encuestas.opcion2,encuestas.fecha_fin,encuestas.activa
                                                            FROM	encuestas
                                                            WHERE encuestas.id_encuesta = :id_encuesta");
        $consulta->bindValue(":id_encuesta",$id_encuesta);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    public static function MostrarDatosPorEncuestaId($id_encuesta){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT SUM(CASE WHEN quevoto = 1 THEN 1 ELSE 0 END) AS op1, SUM( CASE WHEN quevoto = 2 THEN 1 ELSE 0 END) AS op2, COUNT(*) AS TOTAL_VOTANTES
                                                            FROM detalle_encuesta
                                                            WHERE id_encuesta = :id_encuesta");
        $consulta->bindValue(':id_encuesta',$id_encuesta);                                                            
        if($consulta->execute() ){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    ////////////////////////////  CAMBIO DE CONSULTA
    /*public static function MostrarEncuestaDeAlumno($id_encuesta,$id_alumno){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT	*
                                                            FROM	detalle_encuesta
                                                            WHERE	detalle_encuesta.id_alumno = :id_alumno
                                                            AND		detalle_encuesta.id_encuesta = :id_encuesta");
        $consulta->bindValue(':id_alumno',$id_alumno);
        $consulta->bindValue(':id_encuesta',$id_encuesta);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }*/

    public static function MostrarEncuestaDeAlumno($id_alumno){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT	*
                                                            FROM	detalle_encuesta, encuestas
                                                            WHERE	detalle_encuesta.id_alumno = :id_alumno
                                                            AND     detalle_encuesta.id_encuesta = encuestas.id_encuesta");
        $consulta->bindValue(':id_alumno',$id_alumno);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
///////////////////////////////////////////////
    public static function cursosPorProf($id_prof){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT DISTINCT curso.id_curso,materias.nombre,comisiones.nombre
                                                            FROM	curso,materias,comisiones
                                                            WHERE	curso.id_materia = materias.id
                                                            AND		curso.id_comision = comisiones.id_comision
                                                            AND 	curso.id_profesor = :id_prof");
        $consulta->bindValue(':id_prof',$id_prof);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $rta = json_encode($rta);
        }
        return $rta;
    }
    public static function agregarDetalleEncuesta($alumnos){
        $ultimaEnc = encuesta::ultimaEncuesta();
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        foreach($alumnos as $alumno){
             $id_alumno = $alumno['id'];
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO detalle_encuesta (id_encuesta,id_alumno) VALUES(:ultimaEnc,:alumno)");
            $consulta->bindValue('alumno',$id_alumno);
            $consulta->bindValue(':ultimaEnc',$ultimaEnc);
            $consulta->execute();
        }
    }
    public static function TraerListaPorCurso($idCurso){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT usuarios.id,usuarios.nombre, usuarios.apellido
                                                        FROM usuarios, curso, detalle_curso
                                                        WHERE detalle_curso.id_alumno = usuarios.id AND curso.id_curso = detalle_curso.id_curso AND curso.id_curso = :id_curso");
        $consulta->bindValue(':id_curso',$idCurso);
        if ($consulta->execute()){
            $rta = $consulta->fetchAll(PDO::FETCH_ASSOC);
        }
        return $rta;
    }
    public static function UpdateVotoAlumno($id_encuesta,$id_alumno,$voto){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `detalle_encuesta` SET `voto`= 1,`quevoto`=:voto 
                                                        WHERE id_encuesta = :id_encuesta
                                                        AND id_alumno = :id_alumno");
        $consulta->bindValue(':id_encuesta',$id_encuesta);
        $consulta->bindValue(':id_alumno',$id_alumno);
        $consulta->bindValue(':voto',$voto);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function UpdateEstadoEncuesta($id_encuesta){
        $rta = false;
        $fecha_actual = date('Y-m-d H:m:s');
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE encuestas SET activa = 0
                                                        WHERE id_encuesta = :id_encuesta
                                                        AND fecha_fin < :fecha_actual");
        $consulta->bindValue(':id_encuesta',$id_encuesta);
        $consulta->bindValue(':fecha_actual',$fecha_actual);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function activarEncuestaProfesor($id_encuesta){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE encuestas SET activa = 1
                                                        WHERE id_encuesta = :id_encuesta");
        $consulta->bindValue(':id_encuesta',$id_encuesta);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function eliminarEncuesta($id_encuesta){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta ("DELETE FROM `encuestas` WHERE id_encuesta = :id_encuesta");
        $consulta->bindValue(':id_encuesta',$id_encuesta);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
    public static function modificarEncuesta($id_encuesta,$opcion1,$opcion2){
        $rta = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `encuestas` SET `opcion1`= :opcion1,`opcion2`=:opcion2 
                                                        WHERE id_encuesta = :id_encuesta");
        $consulta->bindValue(':id_encuesta',$id_encuesta);
        $consulta->bindValue(':opcion1',$opcion1);
        $consulta->bindValue(':opcion2',$opcion2);
        if ($consulta->execute()){
            $rta = true;
        }
        return $rta;
    }
}
?>