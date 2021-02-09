<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ControllerServicio extends Controller
{
    public function index()
    {
    	$servicio = Servicio::get();
    	return $servicio;
    }

    public function serviciosXuser($id)
    {
    	//devolvemos todos los servicios que tenga el cliente seleccionado
    	$alumnos = DB::select(
    		"SELECT servicios.* FROM clientes, servicios WHERE servicios.id = clientes.servicio_id AND clientes.id = ?",
		    [
		        $id
		    ]
		);
		return $alumnos;
       
    }

     public function store(Request $request)
    {
    	$credentials = $request->only('tipo','nombre', 'imagen', 'fecha_inicio', 'fecha_fin', 'observaciones');   

        $validator = Validator::make($credentials, [
        	'tipo' => 'required',
            'nombre' => 'required',
            'imagen' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'observaciones' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Los campos no son correctos',
                'error' => $validator->errors()
            ],422);
        }
        $exploded = explode(',', $request->imagen);
        $decoded = base64_decode($exploded[1]); //como la imagen vienen en base64 verificamos si tiene la extencion jpg o png
        if(str_contains($exploded[0], 'jpg')){
            $extension = 'jpg';
        }else{
            $extension = 'png';
        }
        $filename = Str::random().'.'.$extension;
        $path = public_path().'/'.$filename;
        file_put_contents($path, $decoded); //guardamos la imagen en la carpeta publica para tener facil acceso a las imagenes desde el front
        $servicio = new Servicio();
        $servicio->nombre = $request->nombre;
        $servicio->imagen = $filename; 
        $servicio->tipo_de_servicio = $request->tipo;
        $servicio->fecha_inicio = $request->fecha_inicio;
        $servicio->fecha_fin = $request->fecha_fin;       
        $servicio->observaciones = $request->observaciones;
        $servicio->save();
        return $servicio;
    }

     public function update(Request $request, $id)
    {
    	$credentials = $request->only('tipo','nombre', 'imagen', 'fecha_inicio', 'fecha_fin', 'observaciones');   

        $validator = Validator::make($credentials, [
        	'tipo' => 'required',
            'nombre' => 'required',
            'imagen' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'observaciones' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Los campos no son correctos',
                'error' => $validator->errors()
            ],422);
        }
        $exploded = explode(',', $request->imagen);
        $decoded = base64_decode($exploded[1]); //como la imagen vienen en base64 verificamos si tiene la extencion jpg o png
        if(str_contains($exploded[0], 'jpg')){
            $extension = 'jpg';
        }else{
            $extension = 'png';
        }
        $filename = Str::random().'.'.$extension;
        $path = public_path().'/'.$filename;
        file_put_contents($path, $decoded); //guardamos la imagen en la carpeta publica para tener facil acceso a las imagenes desde el front
        $servicio = Servicio::find($id);
        // unlink(public_path().'/'.$servicio->imagen);  para cuando este en produccion no hayan dos imagenes del mismo servicio 
        $servicio->nombre = $request->nombre;
        $servicio->imagen = $filename; 
        $servicio->tipo_de_servicio = $request->tipo;
        $servicio->fecha_inicio = $request->fecha_inicio;
        $servicio->fecha_fin = $request->fecha_fin;       
        $servicio->observaciones = $request->observaciones;
        $servicio->save();
        return $servicio;
    }

    public function destroy($id){
    	$servicio = Servicio::find($id);
    	// unlink(public_path().'/'.$servicio->imagen);  para cuando este en produccion no hayan dos imagenes del mismo servicio 
    	$servicio->delete();
    }

}
