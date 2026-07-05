<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('number')->get();
        return Inertia::render('Admin/Table/Index', [
            'tables' => $tables
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|unique:tables,number',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ], [
            'number.unique' => 'Nomor meja sudah digunakan.',
        ]);

        Table::create($request->all());

        return redirect()->back()->with('success', 'Meja berhasil ditambahkan.');
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'number' => 'required|integer|unique:tables,number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ], [
            'number.unique' => 'Nomor meja sudah digunakan.',
        ]);

        $table->update($request->all());

        return redirect()->back()->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->back()->with('success', 'Meja berhasil dihapus.');
    }
}
