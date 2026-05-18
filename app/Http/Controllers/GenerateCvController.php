<?php

namespace App\Http\Controllers;

use App\Models\GenerateCv;
use Illuminate\Http\Request;

class GenerateCvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $generatecv = GenerateCv::latest()->paginate(10);
        return response()->json([
            'success' => true,
            'message' => 'CVs retrieved successfully',
            'data' => $generatecv
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'user_id' => 'required|exists:users,user_id',
                'template_id' => 'required|exists:templates,template_id',
                'fullname' => 'required|string|max:255',
                'job_title' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255',
                'phone_number' => 'nullable|string|max:20',
                'introduction' => 'nullable|string',
                'project_name' => 'nullable|string|max:255',
                'describe_project' => 'nullable|string',
                'education' => 'nullable|string',
                'skills' => 'nullable|string',
                'hobbies' => 'nullable|string',
            ]);

            $generatecv = GenerateCv::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'CV generated successfully',
                'data' => $generatecv
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate CV',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GenerateCv $generatecv)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'CV retrieved successfully',
                'data' => $generatecv
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve CV',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GenerateCv $generatecv)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GenerateCv $generateCv)
    {
        //
    }
}
