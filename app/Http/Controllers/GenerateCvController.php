<?php

namespace App\Http\Controllers;

use App\Models\GenerateCv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenerateCvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Code to list all generated CVs
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        try{
            $request->validate([
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

            $user = Auth::user();

            $generatecv = GenerateCv::create([
                'user_id' => $user->user_id,
                'template_id' => $request->template_id,
                'fullname' => $request->fullname,
                'job_title' => $request->job_title,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'introduction' => $request->introduction,
                'project_name' => $request->project_name,
                'describe_project' => $request->describe_project,
                'education' => $request->education,
                'skills' => $request->skills,
                'hobbies' => $request->hobbies,
            ]);

            return response()->json([
                'message' => 'CV generated successfully',
                'generatecv' => $generatecv
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate CV',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        } 

        try {
            $id = $request->query('generatecv_id');
            if ($id !== null) {
                $generatecv = GenerateCv::where('generatecv_id', $id)
                    ->where('user_id', $user->user_id)
                    ->first();
                if (!$generatecv) {
                    return response()->json([
                        'message' => 'CV not found'
                    ], 404);
                }
                return response()->json([
                    'message' => 'CV retrieved successfully',
                    'generatecv' => $generatecv
                ], 200);
            }

            $generatecv = GenerateCv::where('user_id', $user->user_id)->get();
            return response()->json([
                'message' => 'CV retrieved successfully',
                'generatecv' => $generatecv
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve CV',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        } 
        try {
            $id = $request->query('generatecv_id');
            $generatecv = GenerateCv::where('generatecv_id', $id)
                ->where('user_id', $user->user_id)
                ->first();

            if (!$generatecv) {
                return response()->json([
                    'message' => 'CV not found'
                ], 404);
            }

            $request->validate([
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

            $data = $request->only('template_id', 'fullname', 'job_title', 'email', 'phone_number', 'introduction', 'project_name', 'describe_project', 'education', 'skills', 'hobbies');
            $generatecv->update($data);

            return response()->json([
                'message' => 'CV updated successfully',
                'generatecv' => $generatecv
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update CV',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        } 

        try {
            $id = $request->query('generatecv_id');
            $generateCv = GenerateCv::where('generatecv_id', $id)
                ->where('user_id', $user->user_id)
                ->first();

            if (!$generateCv) {
                return response()->json([
                    'message' => 'CV not found'
                ], 404);
            }

            $generateCv->delete();
            return response()->json([
                'message' => 'CV deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete CV',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
