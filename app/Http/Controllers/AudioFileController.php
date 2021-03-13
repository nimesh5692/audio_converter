<?php

namespace App\Http\Controllers;

use App\AudioFile;
use Illuminate\Http\Request;

class AudioFileController extends Controller
{
    public function index(Request $request)
    {
        $response = '';
        $status = '';

        try {
            // $user = $request->user();

            $files = $request->user()->files;

            $response = array(
                'files' => $files
            );

            $status = '200';
        } catch (\Throwable $th) {
            $response = ['error' => 'Unable to fetch data'];
            $status = '500';
            throw $th;
        } finally {
            return response()->json($response, $status);
        }
    }
}
