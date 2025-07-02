<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FileAndData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FileAndDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filesAndDatas = FileAndData::with(['categoryDocument', 'project'])->get();
        return response()->json($filesAndDatas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'file_source' => 'nullable|url',
            'category_documents_id' => 'nullable|exists:category_documents,id',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'access' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle file uploads
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        $filePath = $request->hasFile('file') ? $request->file('file')->store('files', 'public') : null;

        $fileAndData = FileAndData::create([
            'name' => $request->name,
            'file_source' => $request->file_source,
            'category_documents_id' => $request->category_documents_id,
            'project_id' => $request->project_id,
            'image' => $imagePath,
            'file' => $filePath,
            'access' => json_decode($request->access, true),
        ]);

        return response()->json($fileAndData, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fileAndData = FileAndData::with(['categoryDocument', 'project'])->find($id);

        if (!$fileAndData) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        return response()->json($fileAndData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fileAndData = FileAndData::find($id);

        if (!$fileAndData) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'file_source' => 'nullable|url',
            'category_documents_id' => 'nullable|exists:category_documents,id',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'access' => 'sometimes|required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle file updates
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($fileAndData->image);
            $fileAndData->image = $request->file('image')->store('images', 'public');
        }

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($fileAndData->file);
            $fileAndData->file = $request->file('file')->store('files', 'public');
        }

        $fileAndData->update([
            'name' => $request->input('name', $fileAndData->name),
            'file_source' => $request->input('file_source', $fileAndData->file_source),
            'category_documents_id' => $request->input('category_documents_id', $fileAndData->category_documents_id),
            'project_id' => $request->input('project_id', $fileAndData->project_id),
            'access' => $request->has('access') ? json_decode($request->access, true) : $fileAndData->access,
        ]);

        return response()->json($fileAndData);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fileAndData = FileAndData::find($id);

        if (!$fileAndData) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Delete associated files
        if ($fileAndData->image) {
            Storage::disk('public')->delete($fileAndData->image);
        }

        if ($fileAndData->file) {
            Storage::disk('public')->delete($fileAndData->file);
        }

        $fileAndData->delete();

        return response()->json(['message' => 'Resource deleted successfully']);
    }
}
