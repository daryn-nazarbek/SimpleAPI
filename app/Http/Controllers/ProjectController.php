<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\Project as ProjectResource;

use App\Project;

class ProjectController extends Controller
{
    public function index()
    {
        return new ProjectCollection(Project::all());
    }

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    public function create(ProjectRequest $request)
    {
        $project = Project::create($request->validated());

        return response()->json($project, 201);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return response()->json($project, 200);
    }

    public function delete(Project $project)
    {
        $project->delete();

        return response()->json(null, 204);
    }
}
