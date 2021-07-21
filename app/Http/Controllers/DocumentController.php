<?php

namespace App\Http\Controllers;

use App\Mail\Message;
use App\Models\Document;
use App\Models\User;
use App\Traits\RequestTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller
{

    use RequestTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $documentsCreated = [];
        $documentsAssigned = [];

        if($user->role_id === 3 ){
            $documentsAssigned = Document::where('id_client_assigned', $user->id)->with('users', 'userAssigned', 'clientAssigned')->get();
        }else{
            $documentsCreated = Document::where('id_user_creator', $user->id)->with('users', 'userAssigned', 'clientAssigned')->get();
            $documentsAssigned = Document::where('id_user_assigned', $user->id)->where('id_user_creator','<>',$user->id)->with('users', 'userAssigned', 'clientAssigned')->get();
        }

        return Inertia::render('Document/HistoricSelector', [
            'documentsCreated' => $documentsCreated,
            'documentsAssigned' => $documentsAssigned,
        ]);
    }

    public function getDocumentsByClient(User $user)
    {
        $documents = Document::where('id_client_assigned', $user->id)->with('users')->get();

        return response()->json([
            'documents' => $documents
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $collaborators = User::where('role_id', 2)->where('id', '<>', $user->id)->get();
        $clients = User::where('role_id', 3)->get();

        return Inertia::render('Document/Create', [
            'collaborators' => $collaborators,
            'clients' => $clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            "date" => 'required|date|after_or_equal:' . date('Y-m-d'),
            "codigo" => 'string|required',
            "file" => 'file|required',
            "selectedClient" => 'required|numeric|exists:users,id',
            "reviewers" => 'required|array|min:1',
            "reviewers.*" => 'numeric|exists:users,id'
        ],);

        $path = $this->saveFile($request->file('file'));
        $creator = auth()->user();

        $document = Document::create(
            [
                'path' => $path,
                'id_user_assigned' => $fields['reviewers'][0],
                'id_user_creator' => $creator->id,
                'id_client_assigned' => $fields['selectedClient'],
                'codigo' => $fields['codigo'],
                'request_date' => date('Y-m-d', strtotime($fields['date'])),
            ]
        );

        foreach ($fields['reviewers'] as $key => $reviewer) {

            $user = User::find($reviewer);

            if ($key === 0) //enviar mail al primer revisor
            {
                Mail::to($user->email)->queue(new Message($user, $document));
            }

            $document->users()->attach($user);
        }

        $this->flashSuccess("El documento ha sido ingresado correctamente");

        return redirect()->route('document.welcome');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {

        return Inertia::render('Document/Review', [
            'document' => $document
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function finalizeContract(Document $document)
    {
        $document->users;

        return Inertia::render('Contract/Create', [
            'document' => $document
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function showWithObservations(Document $document)
    {
        return Inertia::render('Document/ReviewObs', [
            'document' => $document
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function updateFromReviewer(Request $request, Document $document)
    {
        $fields = $request->validate([
            "obs" => 'boolean|required', //existe observacion?
            "file" => 'nullable|file|required_if:obs,true',
        ]);

        if ($fields["obs"]) {

            //reemplazando file
            $newPath = $this->saveFile($request->file('file'));
            $this->deleteFile($document->path);
            $document->path = $newPath;

            $creator = $document->userCreator;

            Mail::to($creator->email)->queue(new Message($creator, $document));

            //devolviendo a creador
            $document->id_user_assigned = $document->id_user_creator;
        } else {

            $keyOfUserAssigned  = 0; //setearemos la posición del usuario asignado actual en la lista de revisores

            foreach ($document->users as $key => $user) {

                if ($document->id_user_assigned == $user->id) {
                    $keyOfUserAssigned = $key;
                    $user->documents()->updateExistingPivot($document->id, ["check" => true]);
                }
            }

            //validando si es el ultimo en la lista si no lo devolvemos al reviewer para que lo finalize
            if ($document->users->count() === ($keyOfUserAssigned + 1)) {
                $document->id_user_assigned = $document->id_user_creator;
            } else {
                $newReviewer = $document->users[$keyOfUserAssigned + 1];
                $document->id_user_assigned = $newReviewer->id;

                Mail::to($newReviewer->email)->queue(new Message($newReviewer, $document));
            }
        }

        $document->save();

        $this->flashSuccess("El documento ha sido actualizado correctamente");

        return redirect()->route('document.welcome');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function updateFromCreatorFinalize(Request $request, Document $document)
    {
        $fields = $request->validate([
            "file" => 'file|required',
            "objective" => 'string|required',
            "resolution" => 'string|required',
            "observations" => 'string|required',
        ]);

        $newPath = $this->saveFile($request->file('file'));
        $this->deleteFile($document->path);
        $document->path = $newPath;
        $document->state = "finalizado";
        $document->objective = $fields["objective"];
        $document->resolution = $fields["resolution"];
        $document->observations = $fields["observations"];
    
        $document->save();

        $this->flashSuccess("El documento ha sido finalizado correctamente");

        return redirect()->route('document.welcome');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function updateFromCreator(Request $request, Document $document)
    {
        $request->validate([
            "file" => 'nullable|file|required_if:obs,true',
        ]);

        $newPath = $this->saveFile($request->file('file'));
        $this->deleteFile($document->path);
        $document->path = $newPath;

        $keyOfLastUserCheck = -1; //setearemos la posición del usuario que devolvio el documento

        foreach ($document->users as $key => $user) {

            if ($user->pivot->check === 0) {
                $keyOfLastUserCheck = $key;
                break;
            }
        }

        $lastUserCheck = $document->users[$keyOfLastUserCheck];

        //devolviendo al ultimo revisor sin check
        $document->id_user_assigned = $lastUserCheck->id;

        Mail::to($lastUserCheck->email)->queue(new Message($lastUserCheck, $document));

        $document->save();

        $this->flashSuccess("El documento ha sido actualizado correctamente");

        return redirect()->route('document.welcome');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }

    
    protected function saveFile($file)
    {

    }

    protected function deleteFile($oldPath)
    {
        Storage::delete("public/{$oldPath}");
    }
}
