              ��            0              ��            P              ��           �              ��                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           m
                        ciudad
                     where
                        id_padre = ".$id;

    $rsDepeHijo=$db->conn->query($sqlDepeHijo);

    while(!$rsDepeHijo->EOF)
    {
            
            
            $sqlCountHijo = "select
                                count(id) as countid
                            from
                                ciudad
                            where
                                id_padre = ".$rsDepeHijo->fields["ID"];
            $rsCountHijo=$db->conn->query($sqlCountHijo);
            //$opcTipo = "'".$rsDepeHijo->fields["EXISTE_DEP"]."'";
            $cuantos= $rsCountHijo->fields["COUNTID"];
             if ($cuantos==0)
                $cuantos='';
             else
            $cuantos=' ('.$rsCountHijo->fields["COUNTID"].')';
             $opcTipo = 0;//ultimo nivel, no necesita guardar mas hijas
           
               $menu_depeHijo .= '<li id="phtml_'.$rsDepeHijo->fields["ID"].'" class="jstree-closed">';
            
            
            $menu_depeHijo .= '<input type="hidden" name="hidd_pd'.$rsDepeHijo->fields["ID"].'" id="hidd_pd'.$rsDepeHijo->fields["ID"].'" value="'.$rsCountHijo->fields["COUNTID"].'" size="4"/>';
            
            $existe=0;
        $existeAdmin = 0;
        

          //if ($cuantos=='')
         $menu_depeHijo.=graficarCheckDesabled($rsDepeHijo->fields["ID"],$rsDepeHijo->fields["NOMBRE"]);
        
            
           //$menu_depeHijo .= '<font size=1 color="black">'.$rsDepeHijo->fields["NOMBRE"].'</font>';
            //$menu_depeHijo .= "</a>";
            if($rsCountHijo->fields["COUNTID"]!='0')//si tiene hijas
            {
                $menu_depeHijo .= "<ul>";
                $menu_depeHijo .= obtenerDependencia($rsDepeHijo->fields["ID"], $db);
                $menu_depeHijo .= "</ul>";
            }
            $menu_depeHijo .= "</li>";
            $existe=0;
            $rsDepeHijo->MoveNext();
        
    }
    return $menu_depeHijo;
}

/*
//opcion: si ya esta seleccionado
//dependencia
function graficacheck($opcion,$depe_codi,$db){
    if (countPadre($depe_codi,$db)==1)//si tiene hijos
         $html='class="jstree-closed jstree-undetermined"';
    else{
        if ($opcion=='SI')//si la dependencia ya esta administrando el usuario
            $html='class="jstree-checked"';
        else//si no administra el usuario la dependencia
            $html='class="jstree-leaf jstree-unchecked"';
    }
    return $html;    
}
//devuelve cuantas dependencias existe en la dependencia padre
function countPadre($depe_codi,$db){
    $sql="select count(depe_codi) as contador from dependencia where depe_estado = 1 and depe_codi_padre = $depe_codi"; 
    //echo $sql;
    $rs=$db->conn->query($sql);
     if (!$rs->EOF)
             $contador = $rs->fields['CONTADOR'];
         if ($contador>1)
             return 1;
     return 0;
}
//retorna si el usuario ya esta administrando en esa dependencia
function obtenerCodigos($usr_codigo,$depe_codigo,$db){    
    $sql="select depe_codi_tmp from usuario_dependencia where usua_codi = $usr_codigo";
    //echo $sql;
    $rs=$db->conn->query($sql);
     if (!$rs->EOF){
             $depe_codi_tmp = $rs->fields["DEPE_CODI_TMP"];
            
             $depe_codigos = split(",",$depe_codi_tmp);
         
            for($i=0;$i<sizeof($depe_codigos);$i++){ 
                if ($i!=0){                              
                    if ($depe_codigos[$i]==$depe_codigo){
                        return 1;
                        break;
                    }                   
                }
         }
     }
     else return 0;
}
function obtenerCodigosPadres($usr_codigo,$depe_codigo,$db){    
   
     $depe_codi_tmp = areas_admin($usr_codigo,$db);
     //echo $depe_codi_tmp;
     $adm=0;
          if ($depe_codi_tmp!=''){
             $depe_codigos = split(",",$depe_codi_tmp);
         
            for($i=0;$i<sizeof($depe_codigos);$i++){ 
                if ($i!=0){                              
                    if ($depe_codigos[$i]==$depe_codigo){
                        $adm= 1;
                        break;
                    }                   
                }
         }
         return $adm;
     }
     else return '';
}

function areas_admin($usr_codigo,$db){
    $sql ="select * from usuario_dependencia u    
            where usua_codi = $usr_codigo";
   
           $rsDepePadre=$db->conn->query($sql);
           $depeCodiTmp = $rsDepePadre->fields['DEPE_CODI_TMP'];
           $depeOrdenTmp = substr($depeCodiTmp,1);
           if ($depeCodiTmp!=''){
               $sql_orden= "select depe_codi,depe_codi_padre from dependencia 
                        where depe_codi in ($depeOrdenTmp) and depe_estado = 1 
               and depe_codi <> depe_codi_padre";
               $sql_orden.=" group by depe_codi,depe_codi_padre,depe_nomb
                        order by depe_codi_padre";
               //echo $sql_orden;
               $rs=$db->conn->query($sql_orden);

               while(!$rs->EOF){
                   $padre = $rs->fields['DEPE_CODI_PADRE'];
                    if ($padre!=$padre2){
                    $depe_codi.= ",".$rs->fields['DEPE_CODI_PADRE'];
                    $sqlp="select depe_codi_padre as padre_padre from dependencia where depe_codi<> depe_codi_padre and depe_codi = ".$rs->fields['DEPE_CODI_PADRE'];
                    //echo $sqlp;
                     $rsP=$db->conn->query($sqlp);
                        if(!$rsP->EOF){
                            $depe_codi.=",".$rsP->fields['PADRE_PADRE'];
                        }
                    }
                    $depe_codi.= ",".$rs->fields['DEPE_CODI'];
                    $padre2 = $rs->fields['DEPE_CODI_PADRE'];
                    $rs->MoveNext();                 
                }
           }
           return $depe_codi;
}
function pertenece($inst_codi,$usua_codi,$db,$tipo){
    $sql="select depe_codi_tmp from usuario_dependencia 
    where usua_codi = $usua_codi and inst_codi = $inst_codi";
    //echo $sql;
    $rs=$db->conn->query($sql);
    
     if (!$rs->EOF){
         
             if ($tipo==1)
                 return 1;
             else
                 return $rs->fields['DEPE_CODI_TMP'];
             }
             return 0;
}*/
?>