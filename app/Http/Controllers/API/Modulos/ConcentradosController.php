<?php

namespace App\Http\Controllers\API\Modulos;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

use App\Http\Controllers\Controller;

use App\Http\Requests;

use DB;

use App\Models\Proyecto;
use App\Models\Concentrado;
use App\Models\Reporte;
use App\Models\Auditoria;
use App\Models\Checklist;

class ConcentradosController extends Controller
{

    public function datosCatalogo(){
        try{
            $data = [];
            $data = $this->getUserAccessData();

            return response()->json(['data'=>$data],HttpResponse::HTTP_OK);
        }catch(\Exception $e){
            return response()->json(['error'=>['message'=>$e->getMessage(),'line'=>$e->getLine()]], HttpResponse::HTTP_CONFLICT);
        }
    }
    
    public function obtenerConcentrado($id){
        try{
            $return_data = [];
            $concentrado = Concentrado::with('proyecto.direccion')->where('proyecto_id',$id)->first();

            if(!$concentrado){
                $return_data['concentrado'] = false;
                $proyecto = Proyecto::with('direccion')->find($id);
                $return_data['data'] = $proyecto;
            }else{
                $return_data['concentrado'] = true;

                $concentrado->load(['reportes'=>function($reportes){
                    $reportes->select('reportes.*', DB::raw('COUNT(checklists_reactivos.id) as total_checklist_reactivos'))
                            ->leftjoin('checklists','checklists.id','=','reportes.checklist_id')
                            ->leftjoin('checklists_titulos','checklists_titulos.checklist_id','=','checklists.id')
                            ->leftjoin('checklists_reactivos','checklists_reactivos.checklist_titulo_id','=','checklists_titulos.id')
                            ->groupBy('reportes.id');
                },'reportes.auditoria','reportes.respuestas'=>function($respuestas){
                    $respuestas->select('reportes_respuestas.reporte_id', DB::raw('COUNT(IF(reportes_respuestas.tiene_informacion,1,NULL)) as total_positivos'), DB::raw('COUNT(IF(reportes_respuestas.no_aplica,1,NULL)) as total_no_aplica'))
                                ->groupBy('reporte_id');
                }]);

                $return_data['data'] = $concentrado;
            }

            return response()->json($return_data,HttpResponse::HTTP_OK);
        }catch(\Exception $e){
            return response()->json(['error'=>['message'=>$e->getMessage(),'line'=>$e->getLine()]], HttpResponse::HTTP_CONFLICT);
        }
    }

    public function obtenerChecklist(){
        try{
            $auditorias = Auditoria::get();
            $checklist = Checklist::with('titulos.reactivos')->where('activo',1)->first();

            $return_data = [
                'auditorias' => $auditorias,
                'checklist' => $checklist
            ];
            
            return response()->json($return_data,HttpResponse::HTTP_OK);
        }catch(\Exception $e){
            return response()->json(['error'=>['message'=>$e->getMessage(),'line'=>$e->getLine()]], HttpResponse::HTTP_CONFLICT);
        }
    }

    public function guardarReporte(Request $request){
        try{
            $parametros = $request->all();

            $concentrado = Concentrado::where('proyecto_id',$parametros['proyecto_id'])->where('direccion_id',$parametros['direccion_id'])->first();
            if(!$concentrado){
                $parametros['ultimo_reporte'] = $parametros['fecha'];
                $concentrado = Concentrado::create($parametros);
            }

            $reporte = Reporte::where('concentrado_id',$concentrado->id)->where('auditoria_id',$parametros['auditoria_id'])->where('checklist_id',$parametros['checklist_id'])->first();
            if(!$reporte){
                $parametros['concentrado_id'] = $concentrado->id;
                $reporte = Reporte::create($parametros);
                $reporte->respuestas()->createMany($parametros['respuestas']);
            }else{
                $reporte->load('respuestas');
            }

            $return_data['data'] = $request->all();

            return response()->json($return_data,HttpResponse::HTTP_OK);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['error'=>['message'=>$e->getMessage(),'line'=>$e->getLine()]], HttpResponse::HTTP_CONFLICT);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getUserAccessData($loggedUser = null){
        if(!$loggedUser){
            $loggedUser = auth()->userOrFail();
        }
        
        //$loggedUser->load('grupos.unidadesMedicas','grupos.unidadMedicaPrincipal');
        
        //$lista_clues = [];
        /*foreach ($loggedUser->grupos as $grupo) {
            $lista_unidades = $grupo->unidadesMedicas->toArray();
            
            $lista_clues += $lista_clues + $lista_unidades;
        }*/
        //$accessData->lista_clues = $lista_clues;

        $accessData = (object)[];

        /*if (\Gate::allows('has-permission', \Permissions::ADMIN_PERSONAL_ACTIVO)){
            $accessData->is_admin = true;
        }else{
            $accessData->is_admin = false;
        }*/

        return $accessData;
    }
}
