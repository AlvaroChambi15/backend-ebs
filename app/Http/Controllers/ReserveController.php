<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Reserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReserveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /* * Guardando una soicitud de pacientes. */

    public function storeSolicitud(Request $request)
    {
        // return $request;

        $request->validate([
            "nombres" => "required|min:2|max:60",
            "apellidos" => "required|min:2|max:60",
            "email" => "max:80",
            "celular" => "required|digits:8"
        ]);

        DB::begintransaction();
        try {

            /* CONVIRTIENDO EL ESPACIO "tratamiento" en un array */
            $tratArray = (array) $request->tratamiento;
            /* OBTEMOS EL PRIMER ELEMENTO DEL ARRAY tratamiento */
            $fisrtNameTrat = reset($tratArray);
            /* CONVIRTIENDO EL PRIMER ELEMENTO DE "tratameinto" en un JSON VALIDO */
            $nameTrat = json_encode($fisrtNameTrat);

            // ================================================

            $solicitud = new Reserve;

            $solicitud->nombres = $request->nombres;
            $solicitud->apellidos = $request->apellidos;
            $solicitud->edad = $request->edad;
            $solicitud->email = $request->email;
            $solicitud->celular = $request->celular;
            $solicitud->contacto_llamada = $request->contactTelefono;
            $solicitud->contacto_whatsapp = $request->contactWsp;
            $solicitud->contacto_correo = $request->contactEmail;

            $solicitud->especialidad = $nameTrat;
            $solicitud->fecha_soli = $request->fechaSoli;
            $solicitud->hora_soli = $request->horaSoli;

            $solicitud->save();

            //Creando su Horario

            $horario = new Horario;

            $horario->fecha = $request->fechaSoli;
            $horario->hora = $request->horaSoli;
            $horario->reserve_id = $solicitud->id;
            $horario->estado = "SOLICITUD";

            $horario->save();

            DB::commit();
            return response()->json(["mensaje" => "solicitud-almacenada"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["mensaje" => "Algo ocurrio al enviar la solicitud", "error" => $e], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reserve  $reserve
     * @return \Illuminate\Http\Response
     */
    public function show(Reserve $reserve)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reserve  $reserve
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reserve $reserve)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reserve  $reserve
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reserve $reserve)
    {
        //
    }
}