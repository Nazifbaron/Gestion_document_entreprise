<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\NouveauDocumentAjoute;
use App\Models\User;
use App\Notifications\DocumentEnAttenteValidation;
use App\Notifications\DocumentRejete;
use App\Notifications\DocumentPartage;

class DocumentController extends Controller
{
    use AuthorizesRequests;

 

    public function index(Request $request)
{
    
    // 🔕 Marquer comme lues les notifs de partage
    
        
    
    $user = auth()->user();

    $query = Document::query()->with(['folder', 'sharedWith','category']);

    // Filtrage par état d’archivage
    if ($request->filled('archived')) {
        $query->where('archived', $request->archived === '1');
    } else {
        $query->where('archived', false); // Par défaut, afficher seulement les documents non archivés
    }

    // 🔍 Filtres (titre, dossier)
    if ($request->filled('titre')) {
        $query->where('title', 'like', '%' . $request->titre . '%');
    }

    if ($request->filled('folder_id')) {
        $query->where('folder_id', $request->folder_id);
    }

    // 👥 Filtrage selon le rôle
    if ($user->hasRole('employe')) {
        $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhereHas('sharedWith', function ($sub) use ($user) {
                  $sub->where('users.id', $user->id);
              });
        });
    }

    // 🔢 Pagination finale
    $documents = $query->latest()->paginate(10);


    $folders = Folder::all();
    $users = User::where('id', '!=', $user->id)->get();

    return view('admin.documents.index', compact('documents', 'folders', 'users'));
}


    public function create()
    {
        $categories = Category::all();
            
        $folders = Folder::all();
        return view('admin.documents.create', compact('folders','categories'));
    }

    
    public function store(Request $request, Document $document)
    {
        

        $request->validate([
            'title' => 'required|string|min:4',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'folder_id' => 'required|exists:folders,id',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png,jpeg,odt',
            'category_id' => 'required|exists:categories,id'
        ]);

        $path = $request->file('file')->store('documents');
        $status = auth()->user()->hasRole(['admin', 'responsable']) ? 'validé' : 'en attente';


        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'folder_id' => $request->folder_id,
            'user_id' => auth()->id(),
            'file_path' => $path,
            'status' => $status,
            'category_id' => $request->category_id
        ]);

            $document->load('user'); // 🔥 Charge la relation user une seule fois
            // 🔔 Notifier les administrateurs et/ou responsables
            $admins = User::role(['admin', 'responsable'])->get(); // nécessite Spatie

            foreach ($admins as $admin) {
                $admin->notify(new NouveauDocumentAjoute($document));
                 }

                 if (!auth()->user()->hasRole(['admin', 'responsable'])) {
                    foreach ($admins as $admin) {
                        $admin->notify(new DocumentEnAttenteValidation($document));
                    }
                }
                
        return redirect()->route('admin.documents.index')->with('success', 'Document ajouté avec succès.');
    }

    
    public function edit(Document $document)
    {
        /*accès interdit au utilisateur au role d'employer
        if (auth()->user()->hasRole('employe') && $document->user_id !== auth()->id()) {
            abort(403, 'Accès interdit');
        }*/
        $this->authorize('update', $document);

        $categories = Category::all();
        $folders = Folder::all(); // pour la liste déroulante
        return view('admin.documents.edit', compact('document', 'folders','categories'));
    }



    public function update(Request $request, Document $document)
    {
        if (auth()->user()->hasRole('employe') && $document->user_id !== auth()->id()) {
            abort(403, 'Accès interdit');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'folder_id' => 'required|exists:folders,id',
            'file' => 'nullable|file|max:20480', // 20MB max
            'category_id' => 'required|exists:categories,id',
        ]);


            $document->title = $validated['title'];
            $document->type = $validated['type'];
            $document->folder_id = $validated['folder_id'];
            $document->category_id = $validated['category_id'];
                    // 👇 Sauvegarder l'ancienne version si un nouveau fichier est envoyé
        if ($request->hasFile('file')) {
            $oldPath = $document->file_path;

            // Enregistrer l'ancienne version dans versions/
            $versionNumber = $document->versions()->count() + 1;
            $versionPath = 'versions/' . basename($oldPath);
            Storage::copy($oldPath, $versionPath);

            $document->versions()->create([
                'file_path' => $versionPath,
                'version_number' => $versionNumber,
            ]);

                // Stocker le nouveau
                $path = $request->file('file')->store('documents');
            $document->file_path = $path;
        }

       
        $document->save();


    // 🔥 Partage aux utilisateurs sélectionnés
    $document->sharedWith()->sync($request->input('shared_users', []));
        return redirect()->route('admin.documents.index')->with('success', 'Document mis à jour avec version sauvegardée.');
    }

   
    public function destroy(Document $document)
    {
        /*if (auth()->user()->hasRole('employe') && $document->user_id !== auth()->id()) {
            abort(403, 'Accès interdit');
        }*/
        $this->authorize('delete', $document);
        activity()
            ->performedOn($document)
            ->causedBy(auth()->user())
            ->withProperties([
                'title' => $document->title,
                'type' => $document->type,
                'file_path' => $document->file_path,
                
            ])
            ->log("Suppression du document : {$document->title}");
        // Supprimer le fichier dans le dossier storage/app/public/documents
        if ($document->file_path && Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Document supprimé avec succès.');
    }


   /* public function preview(Document $document)
    {
        $fileUrl = asset('storage/' . $document->file_path);
    
        $ext = pathinfo($document->file_path, PATHINFO_EXTENSION);
    
        // Extensions supportées par Google Docs Viewer
        $extensionsSupportées = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'odt', 'xls', 'xlsx', 'txt'];
    
        if (in_array($ext, $extensionsSupportées)) {
            $viewerUrl = "https://docs.google.com/viewer?url=" . urlencode($fileUrl) . "&embedded=true";
        } else {
            $viewerUrl = $fileUrl;
        }
    
        return view('admin.documents.preview', [
            'document' => $document,
            'url' => $viewerUrl,
        ]);
    }
    */


    public function telecharger(Document $document)
    {
        /*if (auth()->user()->hasRole('employe') && $document->user_id !== auth()->id()) {
            abort(403, 'Accès interdit');
        }*/
      

        // Chemin complet vers le fichier
        $chemin = storage_path('app/public/' . $document->file_path);
       
        // Vérifie que le fichier existe
        if (!file_exists($chemin)) {
            abort(404, 'Fichier introuvable');
        }

        // Téléchargement avec le vrai nom du fichier
        return response()->download($chemin, $document->title . '.' . pathinfo($chemin, PATHINFO_EXTENSION));
    }
  
    public function statistiques(Document $document)
    {
        $this->authorize('view', $document);
        $totalDocuments = Document::count();
        $totalFolders = Folder::count();
        $totalCategory = Category::count();

        // Documents par dossier
        $documentsParDossier = Folder::withCount('documents')->get();

        // Documents par extension (pdf, odt, etc.)
        $extensions = Document::selectRaw("SUBSTRING_INDEX(file_path, '.', -1) as extension, COUNT(*) as total")
            ->groupBy('extension')
            ->get();

        return view('admin.documents.stats', compact(
            'totalDocuments',
            'totalFolders',
            'documentsParDossier',
            'extensions'
        ));
    }
    
   

public function exportPdf(Request $request)
{
    $query = Document::with(['folder', 'user']);

    if (auth()->user()->hasRole('employe')) {
        $query->where('user_id', auth()->id());
    }

    // Appliquer les mêmes filtres que la page index
    if ($request->filled('titre')) {
        $query->where('title', 'like', '%' . $request->titre . '%');
    }

    if ($request->filled('folder_id')) {
        $query->where('folder_id', $request->folder_id);
    }

    $documents = $query->latest()->get();

    $pdf = Pdf::loadView('admin.documents.pdf', compact('documents'));
    return $pdf->download('documents-filtrés.pdf');
}



public function statsMois()
{
    $documentsParMois = Document::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

    $labels = [];
    $data = [];

    foreach (range(1, 12) as $mois) {
        $labels[] = Carbon::create()->month($mois)->locale('fr')->translatedFormat('F'); // "Janvier", etc.
        $match = $documentsParMois->firstWhere('mois', $mois);
        $data[] = $match ? $match->total : 0;
    }

    return view('admin.documents.stats_mois', compact('labels', 'data'));
}

    public function historique()
    {
        $logs = \Spatie\Activitylog\Models\Activity::latest()->paginate(20);
        return view('admin.documents.historique', compact('logs'));
    }

    public function downloadVersion(DocumentVersion $version)
    {
        return Storage::download($version->file_path);
    }

    public function restoreVersion(DocumentVersion $version)
    {
        $document = $version->document;

        if ($document->file_path !== $version->file_path) {
            $currentPath = $document->file_path;
            $nextVersion = $document->versions()->count() + 1;

            Storage::copy($currentPath, 'versions/' . basename($currentPath));

            $document->versions()->create([
                'file_path' => 'versions/' . basename($currentPath),
                'version_number' => $nextVersion,
            ]);

            // Restaurer
            $document->update(['file_path' => $version->file_path]);
        }

        return back()->with('success', 'Version restaurée avec succès.');
    }

    public function deleteVersion(DocumentVersion $version)
    {
        // Supprimer le fichier stocké
        if (Storage::exists($version->file_path)) {
            Storage::delete($version->file_path);
        }

        // Supprimer l'enregistrement en base de données
        $version->delete();

        return back()->with('success', 'Version supprimée avec succès.');
    }

    // Affiche tous les documents en attente de validation
    public function validationIndex()
    {
        $documents = Document::where('status', 'en_attente')->with('user')->latest()->get();

        return view('admin.documents.validation', compact('documents'));
    }

    // Valider un document
    public function valider(Document $document)
    {
        $document->update(['status' => 'valide']);
        // Notifier l'employé ici si tu veux

        return redirect()->route('admin.documents.validation.index')->with('success', 'Document validé');
    }    

    public function rejeter(Document $document)
    {
        $user = $document->user; // on sauvegarde l’utilisateur avant suppression
        $titre = $document->title;

        $document->delete();

        $user->notify(new DocumentRejete($titre));
        
        return redirect()->route('admin.documents.validation.index')
            ->with('error', 'Document supprimé après rejet.');
        
    }

    public function share(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $document = Document::findOrFail($request->document_id);

        $document->sharedWith()->syncWithoutDetaching($request->user_ids);

        return redirect()->route('admin.documents.index')->with('success', 'Document partagé avec succès.');
    }

    public function partagerMultiple(Request $request)
    {

        $documentIds = $request->input('document_ids', []);
        $userIds = $request->input('user_ids', []);

        $documents = Document::whereIn('id', $documentIds)->get();
        $users = User::whereIn('id', $userIds)->get();

        foreach ($documents as $document) {
            // Synchronisation sans supprimer les anciens partages
            $document->sharedWith()->syncWithoutDetaching($userIds);

            // Notification aux utilisateurs
            foreach ($users as $user) {
                $user->notify(new DocumentPartage($document));
            }
        }

        return redirect()->back()->with('success', '📤 Documents partagés avec succès !');
    }

    public function archiver(Document $document)
    {
        $document->update(['archived' => true]);
        return back()->with('success', '📦 Document archivé avec succès.');
    }

    public function restaurer(Document $document)
    {
        $document->update(['archived' => false]);
        return redirect()->route('admin.documents.index')->with('success', '🔓 Document restauré avec succès.');
    }

    public function shareMultiple(){
        $documents = Document::query();

            if ($search = request('search')) {
                $documents->where('title', 'like', '%' . $search . '%');
            }
        $documents = $documents->latest()->paginate(15);
        $users = User::all();
        return view('admin.documents.multiple',compact('documents','users'));
    }
}
