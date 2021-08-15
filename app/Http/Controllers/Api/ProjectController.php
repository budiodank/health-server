<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTool;
use App\Models\ProjectParam;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = false;
        $code = 200;
        $data = NULL;

        try {
            $user_id = $request->user()->user_id;

            $data = Project::where('user_id', $user_id)->get();

            if ($data == false) {
                $error = true;
                $code = 404;
                $message = "Data not found";
            } else {
                $error = false;
                $code = 200;
                $message = "Data found";
            }

            return response()->json([
                'error' => $error,
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }

    public function indexTool(Request $request, $project)
    {
        $error = false;
        $code = 200;
        $data = NULL;

        try {
            $data = ProjectTool::where('project_id', $project)
                                ->select('project_id', 'data', 'created_at', DB::raw("CONCAT(HOUR(created_at),':',MINUTE(created_at),':',SECOND(created_at)) as created_tm"))
                                ->orderBy('created_at','desc')
                                ->limit(10)
                                ->get();

            if ($data == false) {
                $error = true;
                $code = 404;
                $message = "Data not found";
            } else {
                $error = false;
                $code = 200;
                $message = "Data found";
            }

            return response()->json([
                'error' => $error,
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }

    public function indexParam(Request $request, $project)
    {
        $error = false;
        $code = 200;
        $data = NULL;

        try {
            $data = ProjectParam::where('project_id', $project)
                                ->select('project_id', 'name')
                                ->get();

            if ($data == false) {
                $error = true;
                $code = 404;
                $message = "Data not found";
            } else {
                $error = false;
                $code = 200;
                $message = "Data found";
            }

            return response()->json([
                'error' => $error,
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = false;
        $code = 200;
        $data = NULL;
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255'
            ]);

            $user_id = $request->user()->user_id;

            $codedetail = new User;

            $project = Project::create([
                'project_id' => $codedetail->getCode(10),
                'user_id' => $user_id,
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'active' => "Y",
            ]);


            if ($project == false) {
                $error = true;
                $code = 500;
                $message = "Project cannot be saved!";
            } else {
                $error = false;
                $code = 200;
                $message = "Project successfully created!";
                $data = $user_id;
            }

            return response()->json([
                'error' => $error,
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }

    public function storeTool(Request $request, $id)
    {
        $error = false;
        $code = 200;
        $data = NULL;
        try {
            $validatedData = $request->validate([
                'data' => 'required|JSON'
            ]);

            $project = ProjectTool::create([
                'project_id' => $id,
                'data' => $validatedData['data']
            ]);


            if ($project == false) {
                $error = true;
                $code = 500;
                $message = "Project Tool cannot be saved!";
            } else {
                $error = false;
                $code = 200;
                $message = "Project Tool successfully created!";
                $data = $id;
            }

            return response()->json([
                'error' => $error,
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showTool($id)
    {
        $error = false;
        $code = 200;
        $data = NULL;

        try {
            $data = ProjectTool::where('project_id', $project)
                                ->orderBy('created_at','desc')
                                ->first();

            if ($data == false) {
                $error = true;
                $code = 404;
                $message = "Data not found";
            } else {
                $error = false;
                $code = 200;
                $message = "Data found";
            }

            return response()->json([
                'error' => $error,
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error = false;
        $code = 200;
        $data = NULL;

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'active' => 'required'
            ]);

            $project = Project::find($id);

            if ($project == false) {
                $error = true;
                $code = 500;
                $message = "Data not found!";
            } else {
                $project->name = $validatedData['name'];
                $project->description = $validatedData['description'];
                $project->active = $validatedData['active'];


                if ($project->save()) {
                    $message = "Project successfully updated!";
                } else {
                    $error = true;
                    $code = 500;
                    $message = "Data can not saved!";
                }
            }

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
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
        $error = false;
        $code = 200;
        $data = NULL;

        try {
            $project = Project::find($id);

            if ($project == false) {
                $error = true;
                $code = 500;
                $message = "Data not found!";
            } else {
                if ($project->delete()) {
                    $message = "Project successfully deleted!";
                } else {
                    $error = true;
                    $code = 500;
                    $message = "Data can not deleted!";
                }
            }

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }
}
