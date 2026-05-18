<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        // Code to list all applications
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'application_date' => 'nullable|date',
                'application_note' => 'nullable|string',
                'template_name' => 'required|string|max:255',
            ]);

            $user = Auth::user();

            $application = Application::create([
                'user_id' => $user->user_id,
                'company_name' => $request->company_name,
                'job_title' => $request->job_title,
                'application_date' => $request->application_date,
                'application_note' => $request->application_note,
                'template_name' => $request->template_name,
            ]);
            return response()->json(['message' => 'Application created successfully', 'application' => $application], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create application', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        try {

            $id = $request->query('application_id');
            if ($id !== null) {
                $application = Application::where('application_id', $id)
                    ->where('user_id', $user->user_id)
                    ->first();
                if (!$application) {
                    return response()->json(['message' => 'Application not found'], 404);
                }
                return response()->json(['message' => 'Application retrieved successfully', 'application' => $application], 200);
            }

            $applications = Application::where('user_id', $user->user_id)->get();
            return response()->json(['message' => 'Applications retrieved successfully', 'applications' => $applications], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve applications', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            $id = $request->query('application_id');
            $application = Application::where('application_id', $id)
                ->where('user_id', $user->user_id)
                ->first();

            if (!$application) {
                return response()->json(['message' => 'Application not found'], 404);
            }

            $request->validate([
                'company_name' => 'sometimes|required|string|max:255',
                'job_title' => 'sometimes|required|string|max:255',
                'application_date' => 'nullable|date',
                'application_note' => 'nullable|string',
                'template_name' => 'sometimes|required|string|max:255',
            ]);

            $data = $request->only('company_name', 'job_title', 'application_date', 'application_note', 'template_name');
            $application->update($data);

            return response()->json(['message' => 'Application updated successfully', 'application' => $application], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update application', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            $id = $request->query('application_id');
            $application = Application::where('application_id', $id)
                ->where('user_id', $user->user_id)
                ->first();

            if (!$application) {
                return response()->json(['message' => 'Application not found'], 404);
            }

            $application->delete();
            return response()->json(['message' => 'Application deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete application', 'message' => $e->getMessage()], 500);
        }
    }
}
