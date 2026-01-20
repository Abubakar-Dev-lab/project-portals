<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TrashService;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function __construct(protected TrashService $trashService) {}

    public function index()
    {
        $trashed = $this->trashService->getTrashedItems();
        // We pass the two separate collections to the view
        return view('admin.trash.index', [
            'projects' => $trashed['projects'],
            'tasks'    => $trashed['tasks']
        ]);
    }

    public function restoreProject($id)
    {
        $this->trashService->restoreProject($id);
        return redirect()->route('admin.trash.index')->with('success', 'Project restored successfully.');
    }

    public function restoreTask($id)
    {
        $result = $this->trashService->restoreTask($id);

        if (!$result['status']) {
            return redirect()->route('admin.trash.index')->with('error', $result['message']);
        }

        return redirect()->route('admin.trash.index')->with('success', $result['message']);
    }

    public function wipeProject($id)
    {
        $this->trashService->wipeProject($id);
        return redirect()->route('admin.trash.index')->with('success', 'Project permanently deleted.');
    }

    public function wipeTask($id)
    {
        $this->trashService->wipeTask($id);
        return redirect()->route('admin.trash.index')->with('success', 'Task permanently deleted.');
    }
}
