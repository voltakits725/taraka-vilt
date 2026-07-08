<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        // Hanya nampilin karyawan, jangan nampilin pelanggan atau sesama owner
        $employees = User::whereIn('role', ['admin', 'barista'])->latest()->get();
        return Inertia::render('Admin/Employee/Index', [
            'employees' => $employees
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'barista'])],
            'salary' => 'nullable|integer|min:0',
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return back()->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, User $employee)
    {
        // Pastikan owner tidak mengedit user customer
        if (!in_array($employee->role, ['admin', 'barista'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(['admin', 'barista'])],
            'salary' => 'nullable|integer|min:0',
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'phone' => 'nullable|string|max:20',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        return back()->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(User $employee)
    {
        if (!in_array($employee->role, ['admin', 'barista'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $employee->delete();
        return back()->with('success', 'Karyawan berhasil dihapus.');
    }
}
