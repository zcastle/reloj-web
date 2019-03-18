<?PHP

namespace Lib;

class Db {

  private $db;
  private $logger;

  public function __construct($db, $logger){
    $this->db = $db;
    $this->logger = $logger;
  }

  public function getEmpleados(){
    return $this->db->table('empleado')->select("codigo", "nombre")->get();
  }

  public function getMarcaciones($codigo, $tienda, $del = "", $al = ""){
    $tabla = $this->db->table("marcacion AS M")->select("E.codigo", "E.nombre AS empleado", "M.fecha_hora", "T.nombre AS tienda")
                    ->join("empleado AS E", "E.codigo", "=", "M.codigo")
                    ->leftJoin("tienda AS T", "T.reloj_serie", "=", $this->db::raw("substr(M.reloj_serie, 1, length(M.reloj_serie) - 1)"));

    if($codigo > 0){
      $tabla->where("M.codigo", $codigo);
    }

    if($del == "" || $al == ""){
      $tabla->whereBetween('M.fecha_hora', array(date("Y-m-d 00:00:00"), date("Y-m-d 23:59:59")));
    }else{
      $tabla->whereBetween('M.fecha_hora', array("$del 00:00:00", "$al 23:59:59"));
    }

    if($tienda > 0){
        $tabla->where("T.id", $tienda);
    }
    
    if($codigo == 0){
      $tabla->orderBy("E.nombre");
    }

    $tabla->orderBy("M.fecha_hora");

    return $tabla->get();
  }

}

?>
