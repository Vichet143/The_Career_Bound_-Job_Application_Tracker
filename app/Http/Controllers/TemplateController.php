<?php

namespace App\Http\Controllers;
use App\Models\Template;
use Illuminate\Http\Request;
use Exception;

class TemplateController extends Controller
{
    public function createTemplate (Request $request)
    {
        try {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_file' => 'required|string|max:255',
            'image_url' => 'required|string|max:255',
        ]);

        $template = Template::create([
            'template_name' => $request->template_name,
            'description' => $request->description,
            'template_file' => $request->template_file,
            'image_url' => $request->image_url,
        ]);

        return response()->json([
            'message' => 'Template created successfully',
            'data' => $template
        ], 201);
        } catch (Exception $e) {
             return response()->json([
                'message' => 'Failed to create template',
                'error' => $e->getMessage() ?: 'An error occurred'
            ], 500);
        }
    }

    public function getTemplates(request $request)
    {
        try {
            $id = $request->query('id');
            if ($id !== null) {
                $template = Template::find($id);
                if (!$template) {
                    return response()->json(['message' => 'Template not found.'], 404);
                }
                return response()->json([
                    'message' => 'Template retrieved successfully',
                    'data' => $template
                ], 200);
            }
            $templates = Template::all();

            return response()->json([
                'message' => 'Templates retrieved successfully',
                'data' => $templates
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve templates',
                'error' => $e->getMessage() ?: 'An error occurred'
            ], 500);
        }
    }

    public function updateTemplate(Request $request)
    {
        try {
            $id = $request->query('id');
            $template = Template::find($id);
            if (!$template) {
                return response()->json(['message' => 'Template not found.'], 404);
            }

            $request->validate([
                'template_name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'template_file' => 'sometimes|required|string|max:255',
                'image_url' => 'sometimes|required|string|max:255',
            ]);

            $data = $request->only('template_name', 'description', 'template_file', 'image_url');

            $template->update($data);

            return response()->json([
                'message' => 'Template updated successfully',
                'data' => $template
            ], 200);
        } catch (Exception $e) {
             return response()->json([
                'message' => 'Failed to update template',
                'error' => $e->getMessage() ?: 'An error occurred'
            ], 500);
        }
    }

}
